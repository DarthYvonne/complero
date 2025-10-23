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
    public function index()
    {
        $resources = Resource::where('creator_id', auth()->id())
            ->with('creator', 'files')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('creator.resources.index', compact('resources'));
    }

    public function create()
    {
        $mailingLists = MailingList::where('creator_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

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

        // Ensure the mailing list belongs to this creator if specified
        if ($validated['mailing_list_id'] ?? null) {
            $mailingList = MailingList::where('id', $validated['mailing_list_id'])
                ->where('creator_id', auth()->id())
                ->firstOrFail();
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
        if ($resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne ressource');
        }

        $resource->load('tabs', 'files', 'creator');
        return view('creator.resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        if ($resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne ressource');
        }

        $resource->load('tabs');
        $mailingLists = MailingList::where('creator_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('creator.resources.edit', compact('resource', 'mailingLists'));
    }

    public function update(Request $request, Resource $resource)
    {
        if ($resource->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne ressource');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mailing_list_id' => ['nullable', 'exists:mailing_lists,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'files.*' => ['nullable', 'file', 'max:10240'],
            'remove_image' => ['boolean'],
        ]);

        // Ensure the mailing list belongs to this creator if specified
        if ($validated['mailing_list_id'] ?? null) {
            $mailingList = MailingList::where('id', $validated['mailing_list_id'])
                ->where('creator_id', auth()->id())
                ->firstOrFail();
        }

        $resource->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'mailing_list_id' => $request->input('mailing_list_id') ?: null,
            'price' => $validated['price'] ?? 0,
            'is_free' => $request->has('is_free'),
            'is_published' => $request->has('is_published'),
        ]);

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
        if ($resource->creator_id !== auth()->id()) {
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
        if ($resource->creator_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Fil slettet succesfuldt');
    }

    public function storeTab(Request $request, Resource $resource)
    {
        if ($resource->creator_id !== auth()->id()) {
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
