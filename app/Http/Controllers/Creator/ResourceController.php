<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\MailingList;
use App\Models\Resource;
use App\Models\ResourceFile;
use App\Models\ResourceTab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with('creator', 'files')->orderBy('created_at', 'desc');

        // Only scope to own content if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        } else {
            // Admin can filter by creator
            if ($request->filled('creator_id')) {
                $query->where('creator_id', $request->creator_id);
            }
        }

        $resources = $query->get();

        // Get all creators for filter dropdown (only for admins)
        $creators = auth()->user()->role === 'admin'
            ? \App\Models\User::whereIn('role', ['creator', 'admin'])->orderBy('name')->get()
            : collect();

        return view('creator.resources.index', compact('resources', 'creators'));
    }

    public function create()
    {
        $query = MailingList::where('is_active', true)->orderBy('name');

        // Only show own mailing lists if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $mailingLists = $query->get();

        return view('creator.resources.create', compact('mailingLists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mailing_list_id' => ['nullable', 'exists:mailing_lists,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'files.*' => ['nullable', 'file', 'max:10240'],
        ]);

        // Ensure the mailing list belongs to this creator if specified (unless admin)
        if ($validated['mailing_list_id'] ?? null) {
            $query = MailingList::where('id', $validated['mailing_list_id']);

            if (auth()->user()->role !== 'admin') {
                $query->where('creator_id', auth()->id());
            }

            $mailingList = $query->firstOrFail();
        }

        $resource = Resource::create([
            'creator_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'mailing_list_id' => $request->input('mailing_list_id') ?: null,
            'price' => $validated['price'] ?? 0,
            'is_free' => $request->has('is_free'),
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('resources', 'public');
            $resource->update(['image_url' => $path]);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('resource-files', 'public');

                $resource->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()
            ->route('creator.resources.show', $resource)
            ->with('success', 'Ressource oprettet succesfuldt');
    }

    public function show(Resource $resource)
    {
        if (auth()->user()->role !== 'admin' && $resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne ressource');
        }

        $resource->load('tabs', 'files', 'creator');
        return view('creator.resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        if (auth()->user()->role !== 'admin' && $resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne ressource');
        }

        $resource->load('tabs');
        $query = MailingList::where('is_active', true)->orderBy('name');

        // Only show own mailing lists if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $mailingLists = $query->get();

        return view('creator.resources.edit', compact('resource', 'mailingLists'));
    }

    public function update(Request $request, Resource $resource)
    {
        if (auth()->user()->role !== 'admin' && $resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne ressource');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mailing_list_ids' => ['nullable', 'array'],
            'mailing_list_ids.*' => ['exists:mailing_lists,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'files.*' => ['nullable', 'file', 'max:10240'],
            'remove_image' => ['boolean'],
        ]);

        // Ensure the mailing lists belong to this creator if specified (unless admin)
        if (!empty($validated['mailing_list_ids'])) {
            $query = MailingList::whereIn('id', $validated['mailing_list_ids']);

            if (auth()->user()->role !== 'admin') {
                $query->where('creator_id', auth()->id());
            }

            // Verify all mailing lists are valid
            $validMailingLists = $query->pluck('id')->toArray();
            if (count($validMailingLists) !== count($validated['mailing_list_ids'])) {
                abort(403, 'En eller flere mailing lister tilhører ikke dig');
            }
        }

        $resource->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'] ?? 0,
            'is_free' => $request->has('is_free'),
            'is_published' => $request->has('is_published'),
        ]);

        // Sync mailing lists
        if (!empty($validated['mailing_list_ids'])) {
            $resource->mailingLists()->sync($validated['mailing_list_ids']);
        } else {
            // No selection, clear all
            $resource->mailingLists()->sync([]);
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($resource->image_url) {
                Storage::disk('public')->delete($resource->image_url);
            }
            $resource->update(['image_url' => null]);
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            if ($resource->image_url) {
                Storage::disk('public')->delete($resource->image_url);
            }

            $image = $request->file('image');
            $path = $image->store('resources', 'public');
            $resource->update(['image_url' => $path]);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('resource-files', 'public');

                $resource->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()
            ->route('creator.resources.show', $resource)
            ->with('success', 'Ressource opdateret succesfuldt');
    }

    public function destroy(Resource $resource)
    {
        if (auth()->user()->role !== 'admin' && $resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at slette denne ressource');
        }

        if ($resource->image_url) {
            Storage::disk('public')->delete($resource->image_url);
        }

        foreach ($resource->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $resource->delete();

        return redirect()
            ->route('creator.resources.index')
            ->with('success', 'Ressource slettet succesfuldt');
    }

    public function deleteFile(Resource $resource, ResourceFile $file)
    {
        if (auth()->user()->role !== 'admin' && $resource->creator_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Fil slettet succesfuldt');
    }

    public function storeTab(Request $request, Resource $resource)
    {
        if (auth()->user()->role !== 'admin' && $resource->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $validated['resource_id'] = $resource->id;
        $validated['order'] = $resource->tabs()->max('order') + 1;

        ResourceTab::create($validated);

        return redirect()->route('creator.resources.edit', $resource)
            ->with('success', 'Tab tilføjet succesfuldt');
    }

    public function deleteTab(Resource $resource, ResourceTab $tab)
    {
        if ($resource->creator_id !== auth()->id() || $tab->resource_id !== $resource->id) {
            abort(403);
        }

        $tab->delete();

        return redirect()->route('creator.resources.edit', $resource)
            ->with('success', 'Tab slettet succesfuldt');
    }
}
