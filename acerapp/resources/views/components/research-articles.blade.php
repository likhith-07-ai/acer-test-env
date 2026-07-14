@if($articles->count() > 0)
    <section class="py-6">
        <div class="cmsContainer">
            @if(isset($title) && $title)
                <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] mb-8 text-quaternary {{ $titleClass ?? '' }}">
                    {{ $title }}
                </h2>
            @endif

            @if(isset($description) && $description)
                <p class="text-lg text-gray-600 mb-12 {{ $titleClass ?? '' }}">
                    {{ $description }}
                </p>
            @endif

            <div class="outer flex flex-wrap lg:flex-nowrap gap-8 lg:pt-16 lg:mt-12">
                @foreach($articles as $article)
                    <article
                        class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full sm:w-[calc(50%-2rem)]  h-full  hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                        @if($article->featured_image)
                            <div class="rounded-2xl mb-8 overflow-hidden">
                                <a href="{{ route('public.research.show', $article->slug) }}" class="block">
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                                        class="w-full aspect-square object-cover">
                                </a>
                            </div>
                        @endif

                        @if($article->category)
                            <span class="font-medium mb-3 block">
                                {{ $article->category->name }}
                            </span>
                        @endif

                        <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                            {{ $article->title }}
                        </h3>

                        @if($article->excerpt)
                            <p>
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        <a href="{{ route('public.research.show', $article->slug) }}"
                            class="flex flex-row items-center gap-2 px-6 py-3 rounded-xl text-white text-sm md:text-base font-medium transition-all duration-300 bg-primary hover:brightness-110 hover:shadow-lg w-full mt-8 flex items-center justify-center gap-2">
                            Read Full Report
                            <i class="acericon-up-arrow"></i>
                        </a>
                    </article>
                @endforeach
            </div>

            @if(isset($showViewAll) && $showViewAll && $articles->count() >= $limit)
                <div class="mt-8 text-center">
                    <a href="{{ route('public.research.index') }}"
                        class="inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-6 rounded-lg shadow-sm transition-colors duration-200">
                        View All Research Articles
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>
@endif