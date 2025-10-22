<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of available resources (member view).
     */
    public function index()
    {
        // Show all published resources for members
        $resources = Resource::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resources.index', compact('resources'));
    }

    /**
     * Display the specified resource (member view).
     */
    public function show(Resource $resource)
    {
        // Only show published resources to members
        if (!$resource->is_published) {
            abort(404);
        }

        $resource->load('tabs', 'files', 'creator');

        return view('resources.show', compact('resource'));
    }
}
