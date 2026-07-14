<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PressRelease;

class PressReleaseController extends Controller
{
    public function index()
    {
        $pressReleases = PressRelease::latest()->paginate(10);
        return view('public.media-press', compact('pressReleases'));
    }

    public function show($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        return view('public.press-release-detail', compact('pressRelease'));
    }
}
