<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ResearchArticle;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $articles = ResearchArticle::public()->latest('published_at')->get();

        return response()->view('public.sitemap', [
            'articles' => $articles
        ])->header('Content-Type', 'text/xml');
    }
}
