<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResearchTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.view')) {
            abort(403, 'You do not have permission to view research tags.');
        }

        $tags = ResearchTag::orderBy('name')->paginate(config('pagination.admin_per_page'));
        return view('admin.research-tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.create')) {
            abort(403, 'You do not have permission to create research tags.');
        }

        return view('admin.research-tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.create')) {
            abort(403, 'You do not have permission to create research tags.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:research_tags,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ResearchTag::create([
            'name' => $request->name,
            'slug' => ResearchTag::generateUniqueSlug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.research-tags.index')
            ->with('success', __('messages.success.tag.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchTag $researchTag)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.view')) {
            abort(403, 'You do not have permission to view research tags.');
        }

        $researchTag->load('articles');
        return view('admin.research-tags.show', compact('researchTag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResearchTag $researchTag)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.edit')) {
            abort(403, 'You do not have permission to edit research tags.');
        }

        return view('admin.research-tags.edit', compact('researchTag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResearchTag $researchTag)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.edit')) {
            abort(403, 'You do not have permission to edit research tags.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:research_tags,name,' . $researchTag->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        // Update slug if name changed
        if ($researchTag->name !== $request->name) {
            $data['slug'] = ResearchTag::generateUniqueSlug($request->name, $researchTag->id);
        }

        $researchTag->update($data);

        return redirect()->route('admin.research-tags.index')
            ->with('success', __('messages.success.tag.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchTag $researchTag)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-tags.delete')) {
            abort(403, 'You do not have permission to delete research tags.');
        }

        // Detach from all articles
        $researchTag->articles()->detach();
        
        $researchTag->delete();

        return redirect()->route('admin.research-tags.index')
            ->with('success', __('messages.success.tag.deleted'));
    }
}
