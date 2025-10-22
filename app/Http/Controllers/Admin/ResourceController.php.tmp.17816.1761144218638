<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailingList;
use App\Models\Resource;
use App\Models\ResourceFile;
use App\Models\ResourceTab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index()
    {
        $resources = Resource::with('creator', 'files')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mailingLists = MailingList::where('is_active', true)->orderBy('name')->get();
        return view('admin.resources.create', compact('mailingLists'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'files.*' => ['nullable', 'file', 'max:10240'], // 10MB max per file
        ]);

        $resource = Resource::create([
            'creator_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'mailing_list_id' => $request->input('mailing_list_id') ?: null,
            'price' => $validated['price'] ?? 0,
            'is_free' => $request->has('is_free'),
            'is_published' => $request->has('is_published'),
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('resources', 'public');
            $resource->update(['image_url' => $path]);
        }

        // Handle file attachments
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
            ->route('admin.resources.show', $resource)
            ->with('success', 'Ressource oprettet succesfuldt');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        $resource->load('tabs', 'files', 'creator');
        return view('admin.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        $resource->load('tabs');
        $mailingLists = MailingList::where('is_active', true)->orderBy('name')->get();
        return view('admin.resources.edit', compact('resource', 'mailingLists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
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

        $resource->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'mailing_list_id' => $request->input('mailing_list_id') ?: null,
            'price' => $validated['price'] ?? 0,
            'is_free' => $request->has('is_free'),
            'is_published' => $request->has('is_published'),
        ]);

        // Handle image upload (replace existing)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($resource->image_url) {
                Storage::disk('public')->delete($resource->image_url);
            }

            $image = $request->file('image');
            $path = $image->store('resources', 'public');
            $resource->update(['image_url' => $path]);
        }

        // Handle new file attachments
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
            ->route('admin.resources.show', $resource)
            ->with('success', 'Ressource opdateret succesfuldt');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        // Delete image if exists
        if ($resource->image_url) {
            Storage::disk('public')->delete($resource->image_url);
        }

        // Delete all resource files
        foreach ($resource->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $resource->delete();

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Ressource slettet succesfuldt');
    }

    /**
     * Delete a specific file attachment.
     */
    public function deleteFile(Resource $resource, ResourceFile $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Fil slettet succesfuldt');
    }

    /**
     * Store a new tab for the resource.
     */
    public function storeTab(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $validated['resource_id'] = $resource->id;
        $validated['order'] = $resource->tabs()->max('order') + 1;

        ResourceTab::create($validated);

        return redirect()->route('admin.resources.edit', $resource)
            ->with('success', 'Tab tilføjet succesfuldt');
    }

    /**
     * Delete a tab from the resource.
     */
    public function deleteTab(Resource $resource, ResourceTab $tab)
    {
        if ($tab->resource_id !== $resource->id) {
            abort(404);
        }

        $tab->delete();

        return redirect()->route('admin.resources.edit', $resource)
            ->with('success', 'Tab slettet succesfuldt');
    }
}
