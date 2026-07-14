<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    /**
     * Display a listing of public policies
     */
    public function index(Request $request)
    {
        // Only show published and non-restricted policies
        $query = Policy::public();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $policies = $query->latest()->paginate(config('pagination.public_per_page'));

        return view('public.policies.index', compact('policies'));
    }

    /**
     * Display the specified policy
     */
    public function show(Policy $policy)
    {
        // Ensure policy is public
        if (!$policy->isPublic()) {
            abort(403, 'Access denied. This policy is not available.');
        }

        return view('public.policies.show', compact('policy'));
    }

    /**
     * Download policy PDF file
     */
    public function download(Policy $policy)
    {
        // Ensure policy is public
        if (!$policy->isPublic()) {
            abort(403, 'Access denied. This policy is restricted.');
        }

        if (!$policy->file_path) {
            abort(404, 'PDF file not found.');
        }

        return Storage::disk('public')->download($policy->file_path);
    }
}
