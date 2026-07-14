<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class RatingsController extends Controller
{
    public function index()
    {
        return view('public.ratings.index');
    }

    public function criteria()
    {
        return view('public.ratings.criteria');
    }

    public function process()
    {
        return view('public.ratings.process');
    }
}
