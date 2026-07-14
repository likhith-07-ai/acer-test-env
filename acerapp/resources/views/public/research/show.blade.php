@extends('layouts.public')

@section('title', $article->title . ' - ACER Research')

@section('content')
    <div class="py-8">
        <div class="cmsContainer max-w-4xl">
            <!-- Back Button -->
            <a href="{{ route('public.research.index') }}"
                class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Research Articles
            </a>

            <!-- Article Header -->
            <article class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($article->featured_image)
                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                        class="w-full h-auto">
                @endif

                <div class="p-8">
                    @if($article->category)
                        <span
                            class="inline-block px-3 py-1 text-xs font-semibold text-primary-600 bg-primary-50 rounded-full mb-4">
                            {{ $article->category->name }}
                        </span>
                    @endif

                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        {{ $article->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
                        @if($article->author)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $article->author->name }}
                            </div>
                        @endif
                        @if($article->published_at)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $article->published_at->format('F d, Y') }}
                            </div>
                        @endif
                        @if($article->views_count > 0)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ $article->views_count }} views
                            </div>
                        @endif
                    </div>

                    @if($article->tags->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($article->tags as $tag)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($article->excerpt)
                        <div class="text-lg text-gray-700 font-medium mb-6 border-l-4 border-primary-500 pl-4">
                            {{ $article->excerpt }}
                        </div>
                    @endif

                    <!-- Article Content (TinyMCE HTML) -->
                    <div class="prose max-w-none text-gray-700">
                        {!! $article->content !!}
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedArticles as $relatedArticle)
                            <article
                                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                                @if($relatedArticle->featured_image)
                                    <a href="{{ route('public.research.show', $relatedArticle->slug) }}">
                                        <img src="{{ asset('storage/' . $relatedArticle->featured_image) }}"
                                            alt="{{ $relatedArticle->title }}" class="w-full h-32 object-cover">
                                    </a>
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('public.research.show', $relatedArticle->slug) }}"
                                            class="hover:text-primary-600 transition-colors">
                                            {{ $relatedArticle->title }}
                                        </a>
                                    </h3>
                                    @if($relatedArticle->published_at)
                                        <time class="text-sm text-gray-500">
                                            {{ $relatedArticle->published_at->format('M d, Y') }}
                                        </time>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection