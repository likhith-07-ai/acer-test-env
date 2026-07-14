@props(['policies'])

@php
    use Illuminate\Support\Str;

    // Helper function to parse Editor.js JSON content OR TinyMCE HTML content for display
    function parsePolicyContent($content)
    {
        $description = '';
        $subPoints = [];
        $process = '';
        $note = '';

        try {
            // Try to parse as JSON first (old Editor.js format)
            $data = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['blocks'])) {
                // Old Editor.js JSON format
                foreach ($data['blocks'] as $block) {
                    $type = $block['type'] ?? '';
                    $blockData = $block['data'] ?? [];

                    if ($type === 'paragraph' && empty($description)) {
                        $description = Str::limit(strip_tags(htmlspecialchars_decode($blockData['text'] ?? '')), 150);
                    } elseif ($type === 'list' && empty($subPoints)) {
                        $subPoints = array_map(function ($item) {
                            return strip_tags(htmlspecialchars_decode($item));
                        }, array_slice($blockData['items'] ?? [], 0, 4)); // Limit to 4 sub-points
                    } elseif ($type === 'header' && isset($blockData['text']) && stripos($blockData['text'], 'process') !== false) {
                        // Find the next paragraph as process
                        $nextBlockIsProcess = false;
                        foreach ($data['blocks'] as $innerBlock) {
                            if ($innerBlock === $block) {
                                $nextBlockIsProcess = true;
                                continue;
                            }
                            if ($nextBlockIsProcess && ($innerBlock['type'] ?? '') === 'paragraph') {
                                $process = Str::limit(strip_tags(htmlspecialchars_decode($innerBlock['data']['text'] ?? '')), 150);
                                break;
                            }
                        }
                    } elseif ($type === 'quote' && empty($note)) {
                        $note = Str::limit(strip_tags(htmlspecialchars_decode($blockData['text'] ?? '')), 150);
                    }
                }
            } else {
                // New TinyMCE HTML format
                $dom = new DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                // Extract first paragraph as description
                $paragraphs = $dom->getElementsByTagName('p');
                if ($paragraphs->length > 0 && empty($description)) {
                    $description = Str::limit(strip_tags($paragraphs->item(0)->textContent), 150);
                }

                // Extract list items as sub-points
                $lists = $dom->getElementsByTagName('ul');
                if ($lists->length === 0) {
                    $lists = $dom->getElementsByTagName('ol');
                }
                if ($lists->length > 0 && empty($subPoints)) {
                    $listItems = $lists->item(0)->getElementsByTagName('li');
                    for ($i = 0; $i < min(4, $listItems->length); $i++) {
                        $subPoints[] = strip_tags($listItems->item($i)->textContent);
                    }
                }

                // Extract blockquote as note
                $blockquotes = $dom->getElementsByTagName('blockquote');
                if ($blockquotes->length > 0 && empty($note)) {
                    $note = Str::limit(strip_tags($blockquotes->item(0)->textContent), 150);
                }
            }
        } catch (\Exception $e) {
            // Fallback for invalid JSON or HTML
            if (!empty($content)) {
                $description = Str::limit(strip_tags(htmlspecialchars_decode($content)), 150);
            }
        }

        return compact('description', 'subPoints', 'process', 'note');
    }
@endphp

<div class="py-6 bg-white dark:bg-gray-900">
    <div class="cmsContainer">
        <!-- Section Title -->
        <div class="text-center mb-[1.5rem] md:mb-[2.25rem] lg:mb-[3rem]">
            <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary">
                Policy Hub
            </h2>
        </div>

        <!-- Cards Wrapper -->
        <div class="flex flex-wrap justify-center gap-6 lg:gap-8 lg:gap-y-12">
            @forelse($policies as $policy)

                <div class="flex flex-col gap-6 md:gap-8 group relative bg-white rounded-2xl p-4 lg:p-[1.5rem] transform transition-all duration-300 hover:-translate-y-2 hover:shadow-lg border border-quaternary-100 hover:border-primary-300
                                                w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1.333rem)]">
                    <div>
                        <!-- Icon -->
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-primary rounded-[12px] flex items-center justify-center mb-4 transition-colors duration-300">
                            @if($policy->icon)
                                <i class="{{ $policy->icon }} text-xl sm:text-2xl text-white"></i>
                            @else
                                <i class="acericon-doc text-xl sm:text-2xl text-white"></i>
                            @endif
                        </div>

                        <!-- Title -->
                        <h3 class="text-[1.125rem] md:text-[1.5rem] font-bold text-quaternary mb-3 font-sans">{{ $policy->title }}
                        </h3>

                        <!-- Full Content (TinyMCE HTML) -->
                        @if($policy->content)
                            <div class="text-gray-700 dark:text-gray-300 prose prose-sm editorContent max-w-none">
                                {!! $policy->content !!}
                            </div>
                        @endif

                        <!-- Tagline / Quote -->
                        @if($policy->tagline)
                            <p class="text-gray-600 dark:text-gray-400 mt-4 italic text-sm border-l-4 border-primary pl-3">
                                {{ $policy->tagline }}</p>
                        @endif
                    </div>



                    <!-- Button -->
                    <div class="mt-auto">
                        @if($policy->file_path)
                            <a href="{{ route('public.pdf.viewer', ['type' => 'policy', 'id' => $policy->id]) }}"
                                target="_blank"
                                class="flex flex-row items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-sm md:text-base font-medium transition-all duration-300 bg-primary hover:brightness-110 hover:shadow-lg">
                                Download PDF
                                <i class="acericon-download"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200 w-full">
                    <p class="text-gray-600">No policies available.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.editorContent ul {
    list-style-type: disc;
    padding-left: 20px;    
    margin-bottom: 12px;
}

.editorContent ul li {
    margin-bottom: 4px;
}

.editorContent ul li:last-child {
    margin-bottom: 0;
}

.editorContent h4 {
    font-weight: 600;     
    margin-bottom: 8px;
    color: #202020;  
}

.editorContent p {
    color: #374151;   
    margin-top: 8px; 
}
</style>