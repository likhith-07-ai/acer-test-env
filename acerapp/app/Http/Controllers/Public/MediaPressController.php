<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaPressController extends Controller
{
    public function index(Request $request)
    {
        // Block direct access without a search query
        if (!$request->has('search') || empty($request->input('search'))) {
            return redirect('/');
        }

        $searchQuery = $request->input('search');

        $pressReleases = \App\Models\PressRelease::where('company_name', 'like', '%' . $searchQuery . '%')
            ->orWhere('headline', 'like', '%' . $searchQuery . '%')
            ->latest()
            ->paginate(10);

        return view('public.media-press', compact('pressReleases', 'searchQuery'));
    }

    public function apiSearch(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = \App\Models\PressRelease::select('id', 'company_name', 'headline', 'date')
            ->where('company_name', 'like', '%' . $query . '%')
            ->orWhere('headline', 'like', '%' . $query . '%')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($pr) {
                return [
                    'id' => $pr->id,
                    'company_name' => $pr->company_name,
                    'headline' => $pr->headline,
                    'date' => $pr->date ? $pr->date->format('M d, Y') : null,
                    'url' => route('public.media-press', ['search' => $pr->company_name])
                ];
            });

        return response()->json($results);
    }
}
