<x-admin-layout>
    <div class="h-full -mx-8 -my-6 w-[calc(100%+4rem)]"
        x-data="pressReleaseWizard('{{ $pressRelease->format ?? 'raw' }}')">

        <div
            class="bg-white flex flex-col md:flex-row w-full h-[calc(100vh-150px)] overflow-hidden border-t border-gray-200">

            <!-- Sidebar Navigation -->
            <div x-show="formatType === 'raw' && showSidebar"
                class="w-[280px] shrink-0 bg-gray-50 border-r border-gray-200 p-4 overflow-y-auto sticky top-0 h-[calc(100vh-150px)] z-20 transition-all duration-300">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Press Release Sections</h3>
                <nav class="space-y-1.5">
                    <template x-for="(step, index) in steps" :key="index">
                        <button type="button" @click="scrollToStep(index); $event.preventDefault();" :class="{
                                'bg-white shadow-sm ring-1 ring-primary-500/20 text-primary-700 font-semibold border-l-4 border-l-primary-500': currentStep === index, 
                                'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900 border-l-4 border-l-transparent': currentStep !== index
                            }"
                            class="w-full text-left px-4 py-3 text-sm rounded-r-xl transition-all flex justify-between items-center group">

                            <span class="flex items-center gap-3">
                                <!-- Status Icon -->
                                <span
                                    :class="{'text-primary-500': currentStep === index, 'text-gray-400': currentStep !== index}">
                                    <svg x-show="step.completed && currentStep !== index" class="w-4 h-4 text-green-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span x-show="!step.completed || currentStep === index"
                                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                        :class="{'border-primary-500 bg-primary-500': currentStep === index, 'border-gray-300': currentStep !== index}">
                                        <span x-show="currentStep === index"
                                            class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                    </span>
                                </span>
                                <!-- Title -->
                                <span x-text="step.title"></span>
                            </span>
                        </button>
                    </template>
                </nav>
            </div>

            <!-- Content Area -->
            <div id="scrollable-content" class="flex-1 min-w-0 bg-white relative overflow-y-auto h-full">
                <!-- Header moved inside -->
                <div
                    class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] mb-8 border-b border-gray-200 p-6 px-8 flex justify-between items-center w-full">
                    <div class="flex items-center gap-4">
                        <button type="button" x-show="formatType === 'raw'" @click="showSidebar = !showSidebar"
                            class="transition-colors text-secondary-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 256 256" x-show="!showSidebar">
                                <path
                                    d="M228,128a12,12,0,0,1-12,12H120a12,12,0,0,1,0-24h96A12,12,0,0,1,228,128ZM120,76h96a12,12,0,0,0,0-24H120a12,12,0,0,0,0,24Zm96,104H40a12,12,0,0,0,0,24H216a12,12,0,0,0,0-24ZM31.51,144.49a12,12,0,0,0,17,0l40-40a12,12,0,0,0,0-17l-40-40a12,12,0,0,0-17,17L63,96,31.51,127.51A12,12,0,0,0,31.51,144.49Z">
                                </path>
                            </svg>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 256 256" x-show="showSidebar"
                                style="display: none;">
                                <path
                                    d="M228,128a12,12,0,0,1-12,12H120a12,12,0,0,1,0-24h96A12,12,0,0,1,228,128ZM120,76h96a12,12,0,0,0,0-24H120a12,12,0,0,0,0,24Zm96,104H40a12,12,0,0,0,0,24H216a12,12,0,0,0,0-24ZM72,148a12,12,0,0,0,8.49-20.49L49,96,80.49,64.48a12,12,0,0,0-17-17l-40,40a12,12,0,0,0,0,17l40,40A12,12,0,0,0,72,148Z">
                                </path>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl text-gray-900 font-bold">Edit Press Release:
                                {{ $pressRelease->company_name }}
                            </h1>
                            <p class="text-gray-500 text-sm mt-1">Follow the steps below to populate the press release
                                document.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span x-show="formatType === 'pdf'"
                            class="bg-gray-100 text-gray-700 font-bold px-3 py-1.5 rounded-lg text-sm border border-gray-200"><i
                                class="ri-file-pdf-line mr-1"></i> PDF Format</span>
                        <span x-show="formatType === 'raw'"
                            class="bg-primary-50 text-primary-700 font-bold px-3 py-1.5 rounded-lg text-sm border border-primary-200"><i
                                class="ri-file-text-line mr-1"></i> Raw Format</span>
                    </div>
                </div>
                <form id="press-release-form" class="px-8 pb-20" :class="{'max-w-4xl mx-auto': formatType === 'pdf'}"
                    action="{{ route('admin.press-releases.update', $pressRelease) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="format" :value="formatType">

                    <!-- STEP 1: Header Information -->
                    <div id="step-0" class="mb-16 scroll-mt-32">
                        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">1. Header</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Press Releases Date<span
                                        class="text-red-800">*</span></label>
                                <input type="date" name="date"
                                    value="{{ old('date', $pressRelease->date ? $pressRelease->date->format('Y-m-d') : '') }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                                <input type="text" name="city" value="{{ old('city', $pressRelease->city) }}"
                                    placeholder="e.g. Mumbai"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Company Name <span
                                        class="text-red-800">*</span></label>
                                <input type="text" name="company_name"
                                    value="{{ old('company_name', $pressRelease->company_name) }}"
                                    placeholder="e.g. Tata Projects Limited"
                                    class="block w-full border-gray-300 border-2 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-lg py-3"
                                    required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Headline Summary <span
                                        class="text-red-800">*</span></label>
                                <textarea name="headline" rows="2"
                                    placeholder="e.g. 'ACER AA / Stable' assigned to Non Convertible Debentures"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-base"
                                    required>{{ old('headline', $pressRelease->headline) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Press Release PDF <span
                                        class="text-xs text-gray-400 font-normal ml-2">(Optional)</span></label>
                                @if($pressRelease->pdf_file)
                                    <div class="mb-2 flex items-center gap-3">
                                        <span class="text-sm text-green-600 font-medium flex items-center gap-1">
                                            <i class="ri-checkbox-circle-fill"></i> Current PDF Uploaded
                                        </span>
                                        <a href="{{ Storage::url($pressRelease->pdf_file) }}" target="_blank"
                                            class="text-sm text-primary-600 hover:text-primary-800 underline">View PDF</a>
                                    </div>
                                @endif
                                <input type="file" name="pdf_file" accept=".pdf"
                                    class="block w-full border-gray-300 border-2 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 py-2 px-3 bg-gray-50/50">
                                <p class="text-xs text-gray-500 mt-1">Upload a new PDF to replace the current one. Leave
                                    blank to keep the existing PDF.</p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Rating Action Table -->
                    <div id="step-1" class="mb-16 scroll-mt-32">
                        <h2 class="text-2xl font-bold mb-2 text-gray-800">2. Rating Action</h2>
                        <p class="text-gray-500 mb-8 border-b pb-4">Add instruments dynamically.</p>
                        <input type="hidden" name="rating_action_table" :value="JSON.stringify(ratingActions)">

                        <div class="space-y-6">
                            <template x-for="(action, index) in ratingActions" :key="index">
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm relative">
                                    <div class="flex justify-between items-center mb-4 border-b pb-3">
                                        <h4 class="font-bold text-gray-700 text-lg">Row <span x-text="index + 1"></span>
                                        </h4>
                                        <button @click.prevent="removeRatingAction(index)" type="button"
                                            class="text-gray-500 hover:text-red-600 font-semibold text-sm">
                                            <i class="ri-close-line mr-1"></i> Delete
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase mb-1.5">INSTRUMENT/
                                                FACILITY**</label>
                                            <input type="text" x-model="action.instrument_name"
                                                placeholder="e.g. Rs.500 Crore Non Convertible Debentures"
                                                class="block w-full text-sm border-gray-300 rounded-lg">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">AMOUNT
                                                (INR CRORE)</label>
                                            <input type="number" step="0.01" x-model="action.amount_inr"
                                                placeholder="e.g. 500"
                                                class="block w-full text-sm border-gray-300 rounded-lg">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase mb-1.5">CURRENT
                                                RATINGS</label>
                                            <input type="text" x-model="action.current_rating"
                                                placeholder="e.g. ACER AA/Stable"
                                                class="block w-full text-sm border-gray-300 rounded-lg">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">RATING
                                                ACTION</label>
                                            <select x-model="action.rating_action"
                                                class="block w-full text-sm border-gray-300 rounded-lg">
                                                <option value="">Select...</option>
                                                <option value="Assigned">Assigned</option>
                                                <option value="Reaffirmed">Reaffirmed</option>
                                                <option value="Upgraded">Upgraded</option>
                                                <option value="Downgraded">Downgraded</option>
                                                <option value="Watch Positive">Watch Positive</option>
                                                <option value="Watch Negative">Watch Negative</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase mb-1.5">REGULATOR^</label>
                                            <input type="text" x-model="action.regulator"
                                                placeholder="e.g. SEBI, RBI, NHB"
                                                class="block w-full text-sm border-gray-300 rounded-lg">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <button @click.prevent="addRatingAction()" type="button"
                                class="w-full border-2 border-primary-500 text-primary-600 font-bold py-3 rounded-xl hover:bg-primary-50 transition">+
                                Add Row</button>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unsupported Rating
                                    (Optional) <span class="text-xs text-gray-400 font-normal ml-2">Text Input - e.g.
                                        ACER AAA</span></label>
                                <input type="text" name="unsupported_rating" placeholder="e.g. ACER AAA"
                                    class="block w-full text-sm border-gray-300 rounded-lg"
                                    value="{{ old('unsupported_rating', $pressRelease->unsupported_rating) }}">
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Analytical Approach -->
                    <div x-show="formatType === 'raw'">
                        <div id="step-2" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">3. Analytical Approach</h2>
                            <textarea name="analytical_approach" rows="4"
                                class="block w-full border-gray-300 rounded-lg tinymce-editor">{{ old('analytical_approach', $pressRelease->analytical_approach) }}</textarea>
                        </div>

                        <!-- STEP 4: Brief Summary -->
                        <div id="step-3" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">4. Brief Summary</h2>
                            <textarea name="brief_summary" rows="10"
                                class="block w-full border-gray-300 rounded-lg tinymce-editor">{{ old('brief_summary', $pressRelease->brief_summary) }}</textarea>
                        </div>

                        <!-- STEP 5: Strengths -->
                        <div id="step-4" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">5. Key Rating Drivers -
                                Strengths</h2>
                            <input type="hidden" name="strengths" :value="JSON.stringify(strengthsList)">
                            <div class="space-y-6">
                                <template x-for="(item, index) in strengthsList" :key="index">
                                    <div class="bg-gray-50/50 border border-gray-200 rounded-xl p-5 relative">
                                        <div
                                            class="flex justify-between items-center mb-4 border-b border-gray-200 pb-3">
                                            <h4 class="font-bold text-gray-800 text-lg">Strength <span
                                                    x-text="index + 1"></span></h4>
                                            <div class="flex space-x-3 items-center">
                                                <button x-show="index > 0"
                                                    @click.prevent="let tmp = strengthsList[index]; strengthsList[index] = strengthsList[index-1]; strengthsList[index-1] = tmp"
                                                    type="button"
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-semibold"><i
                                                        class="ri-arrow-up-circle-line mr-1"></i> Move Up</button>
                                                <button x-show="index < strengthsList.length - 1"
                                                    @click.prevent="let tmp = strengthsList[index]; strengthsList[index] = strengthsList[index+1]; strengthsList[index+1] = tmp"
                                                    type="button"
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-semibold"><i
                                                        class="ri-arrow-down-circle-line mr-1"></i> Move Down</button>
                                                <button @click.prevent="strengthsList.splice(index, 1)" type="button"
                                                    class="text-gray-500 hover:text-red-600 font-semibold text-sm ml-2 border-l border-gray-300 pl-3">
                                                    <i class="ri-close-line mr-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Heading</label>
                                        <input type="text" x-model="item.title"
                                            class="mb-4 block w-full text-sm border-gray-300 rounded-lg">

                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Body</label>
                                        <div x-data="{
                                        initEditor() {
                                            let el = this.$refs.editor;
                                            el.id = 'editor_strength_' + Date.now() + Math.random().toString(36).substring(2, 9);
                                            let editorInstance;
                                            tinymce.init({
                                                target: el,
                                                height: 250,
                                                menubar: false,
                                                plugins: ['lists', 'link', 'code', 'table'],
                                                toolbar: 'undo redo | bold italic | bullist numlist | removeformat',
                                                setup: (editor) => {
                                                    editorInstance = editor;
                                                    editor.on('init', () => { if (item.body) editor.setContent(item.body); });
                                                    editor.on('change', () => { item.body = editor.getContent(); });
                                                }
                                            });
                                            this.$watch('item.body', (val) => {
                                                if (editorInstance && editorInstance.getContent() !== val) {
                                                    editorInstance.setContent(val || '');
                                                }
                                            });
                                        }
                                    }" x-init="initEditor()">
                                            <textarea x-ref="editor"
                                                class="block w-full border-gray-300 rounded-lg"></textarea>
                                        </div>
                                    </div>
                                </template>
                                <button @click.prevent="strengthsList.push({title:'', body:''})" type="button"
                                    class="w-full border-2 border-dashed border-gray-300 bg-gray-50 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-100 transition">+
                                    Add Strength</button>
                            </div>
                        </div>

                        <!-- STEP 6: Weaknesses -->
                        <div id="step-5" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">6. Key Rating Drivers -
                                Weaknesses</h2>
                            <input type="hidden" name="weaknesses" :value="JSON.stringify(weaknessesList)">
                            <div class="space-y-6">
                                <template x-for="(item, index) in weaknessesList" :key="index">
                                    <div class="bg-gray-50/50 border border-gray-200 rounded-xl p-5 relative">
                                        <div
                                            class="flex justify-between items-center mb-4 border-b border-gray-200 pb-3">
                                            <h4 class="font-bold text-gray-800 text-lg">Weakness <span
                                                    x-text="index + 1"></span></h4>
                                            <div class="flex space-x-3 items-center">
                                                <button x-show="index > 0"
                                                    @click.prevent="let tmp = weaknessesList[index]; weaknessesList[index] = weaknessesList[index-1]; weaknessesList[index-1] = tmp"
                                                    type="button"
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-semibold"><i
                                                        class="ri-arrow-up-circle-line mr-1"></i> Move Up</button>
                                                <button x-show="index < weaknessesList.length - 1"
                                                    @click.prevent="let tmp = weaknessesList[index]; weaknessesList[index] = weaknessesList[index+1]; weaknessesList[index+1] = tmp"
                                                    type="button"
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-semibold"><i
                                                        class="ri-arrow-down-circle-line mr-1"></i> Move Down</button>
                                                <button @click.prevent="weaknessesList.splice(index, 1)" type="button"
                                                    class="text-gray-500 hover:text-red-600 font-semibold text-sm ml-2 border-l border-gray-300 pl-3">
                                                    <i class="ri-close-line mr-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Heading</label>
                                        <input type="text" x-model="item.title"
                                            class="mb-4 block w-full text-sm border-gray-300 rounded-lg">

                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Body</label>
                                        <div x-data="{
                                        initEditor() {
                                            let el = this.$refs.editor;
                                            el.id = 'editor_weakness_' + Date.now() + Math.random().toString(36).substring(2, 9);
                                            let editorInstance;
                                            tinymce.init({
                                                target: el,
                                                height: 250,
                                                menubar: false,
                                                plugins: ['lists', 'link', 'code', 'table'],
                                                toolbar: 'undo redo | bold italic | bullist numlist | removeformat',
                                                setup: (editor) => {
                                                    editorInstance = editor;
                                                    editor.on('init', () => { if (item.body) editor.setContent(item.body); });
                                                    editor.on('change', () => { item.body = editor.getContent(); });
                                                }
                                            });
                                            this.$watch('item.body', (val) => {
                                                if (editorInstance && editorInstance.getContent() !== val) {
                                                    editorInstance.setContent(val || '');
                                                }
                                            });
                                        }
                                    }" x-init="initEditor()">
                                            <textarea x-ref="editor"
                                                class="block w-full border-gray-300 rounded-lg"></textarea>
                                        </div>
                                    </div>
                                </template>
                                <button @click.prevent="weaknessesList.push({title:'', body:''})" type="button"
                                    class="w-full border-2 border-dashed border-gray-300 bg-gray-50 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-100 transition">+
                                    Add Weakness</button>
                            </div>
                        </div>

                        <!-- STEP 7: Liquidity -->
                        <div id="step-6" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">7. Liquidity</h2>
                            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div><label class="block text-sm font-semibold mb-2">Liquidity Indicator</label><select
                                        name="liquidity" class="w-full rounded-lg border-gray-300"
                                        x-init="$el.value = '{{ old('liquidity', $pressRelease->liquidity) }}'">
                                        <option>Adequate</option>
                                        <option>Strong</option>
                                        <option>Poor</option>
                                    </select></div>
                                <div class="md:col-span-2"><label class="block text-sm font-semibold mb-2">Liquidity
                                        Body
                                        Summary</label><textarea name="liquidity_body" rows="3"
                                        class="w-full rounded-lg border-gray-300 tinymce-editor">{{ old('liquidity_body', $pressRelease->liquidity_body) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 8: Sensitivities -->
                        <div id="step-7" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">8. Rating Sensitivities</h2>
                            <input type="hidden" name="positive_sensitivities"
                                :value="JSON.stringify(positiveSensitivities)">
                            <input type="hidden" name="negative_sensitivities"
                                :value="JSON.stringify(negativeSensitivities)">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Positive -->
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                                    <h3 class="font-bold text-gray-800 mb-4">Positive Factors</h3>
                                    <template x-for="(item, index) in positiveSensitivities" :key="index">
                                        <div class="flex items-center gap-2 mb-3">
                                            <input type="text" x-model="item.text" placeholder="Enter positive factor"
                                                class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button @click.prevent="positiveSensitivities.splice(index,1)" type="button"
                                                class="shrink-0 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2.5 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors">
                                                Remove
                                            </button>
                                        </div>
                                    </template>
                                    <button @click.prevent="positiveSensitivities.push({text:''})" type="button"
                                        class="text-sm font-bold text-primary-600 mt-2">+ Add Positive</button>
                                </div>
                                <!-- Negative -->
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                                    <h3 class="font-bold text-gray-800 mb-4">Negative Factors</h3>
                                    <template x-for="(item, index) in negativeSensitivities" :key="index">
                                        <div class="flex items-center gap-2 mb-3">
                                            <input type="text" x-model="item.text" placeholder="Enter negative factor"
                                                class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button @click.prevent="negativeSensitivities.splice(index,1)" type="button"
                                                class="shrink-0 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2.5 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors">
                                                Remove
                                            </button>
                                        </div>
                                    </template>
                                    <button @click.prevent="negativeSensitivities.push({text:''})" type="button"
                                        class="text-sm font-bold text-primary-600 mt-2">+ Add Negative</button>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 9: About Company -->
                        <div id="step-8" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">9. About the company</h2>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold mb-2">Company Overview</label>
                                <textarea name="about_company_body" rows="6"
                                    class="w-full rounded-lg border-gray-300 tinymce-editor">{{ old('about_company_body', $pressRelease->about_company_body) }}</textarea>
                            </div>
                            <h3 class="font-bold text-gray-700 mb-4 mt-8">Company Segments Table (Optional)</h3>
                            <input type="hidden" name="company_segments_table" :value="JSON.stringify(segmentsTable)">
                            <div class="space-y-4">
                                <template x-for="(seg, index) in segmentsTable" :key="index">
                                    <div class="flex gap-4 items-center mb-3">
                                        <input type="text" x-model="seg.group" placeholder="Segment Group"
                                            class="w-1/3 rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <input type="text" x-model="seg.description" placeholder="Description"
                                            class="w-2/3 rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <button @click.prevent="segmentsTable.splice(index, 1)" type="button"
                                            class="shrink-0 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2.5 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors">
                                            Remove
                                        </button>
                                    </div>
                                </template>
                                <button @click.prevent="segmentsTable.push({group:'', description:''})" type="button"
                                    class="text-sm font-bold text-primary-600">+ Add Segment</button>
                            </div>
                        </div>

                        <!-- STEP 10: Financial Indicators -->
                        <div id="step-9" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">10. Key Financial Indicators
                            </h2>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold mb-2">Financials Basis</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center"><input type="radio" name="financials_basis"
                                            value="Standalone" class="text-primary-600" checked> <span
                                            class="ml-2">Standalone</span></label>
                                    <label class="inline-flex items-center"><input type="radio" name="financials_basis"
                                            value="Consolidated" class="text-primary-600"> <span
                                            class="ml-2">Consolidated</span></label>
                                    <label class="inline-flex items-center"><input type="radio" name="financials_basis"
                                            value="Both" class="text-primary-600"> <span
                                            class="ml-2">Both</span></label>
                                </div>
                            </div>
                            <input type="hidden" name="fy_columns" :value="JSON.stringify(fyColumns)">

                            <div class="overflow-x-auto pb-4">
                                <div class="flex gap-4 min-w-max">
                                    <!-- Static Row Labels -->
                                    <div class="w-48 flex-shrink-0 space-y-4 pt-10">
                                        <div class="h-10 flex items-center text-xs font-bold text-gray-500">Operating
                                            Revenue (Cr)</div>
                                        <div class="h-10 flex items-center text-xs font-bold text-gray-500">EBITDA (Cr)
                                        </div>
                                        <div class="h-10 flex items-center text-xs font-bold text-gray-500">EBITDA
                                            Margin
                                            (%)</div>
                                        <div class="h-10 flex items-center text-xs font-bold text-gray-500">Interest
                                            Coverage (x)</div>
                                        <div class="h-10 flex items-center text-xs font-bold text-gray-500">Net Leverage
                                            (x)
                                        </div>
                                        <div class="h-10 flex items-center text-xs font-bold text-gray-500">PAT Margin
                                            (%)
                                        </div>
                                    </div>

                                    <!-- Dynamic FY Columns -->
                                    <template x-for="(col, index) in fyColumns" :key="index">
                                        <div class="w-40 flex-shrink-0 space-y-4 bg-gray-50 rounded-xl p-2 relative">

                                            <input type="text" x-model="col.label" placeholder="FY 2025"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm font-bold text-center bg-white">
                                            <input type="number" step="0.01" x-model="col.revenue"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm text-center">
                                            <input type="number" step="0.01" x-model="col.ebitda"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm text-center">
                                            <input type="number" step="0.01" x-model="col.ebitda_margin"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm text-center">
                                            <input type="number" step="0.01" x-model="col.coverage"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm text-center">
                                            <input type="number" step="0.01" x-model="col.leverage"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm text-center">
                                            <input type="number" step="0.01" x-model="col.pat_margin"
                                                class="h-10 w-full rounded-md border-gray-300 text-sm text-center">
                                            <button @click.prevent="fyColumns.splice(index, 1)" type="button"
                                                class="w-full bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors mt-2">
                                                Remove Year
                                            </button>
                                        </div>
                                    </template>

                                    <div
                                        class="w-40 flex-shrink-0 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-xl p-2">
                                        <button
                                            @click.prevent="fyColumns.push({label:'', revenue:'', ebitda:'', ebitda_margin:'', coverage:'', leverage:'', pat_margin:''})"
                                            class="text-primary-600 font-bold text-sm w-full h-full min-h-[50px]">+ Add
                                            Year</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-semibold mb-2">Financials Source (Appears as
                                    footnote)</label>
                                <input type="text" name="financials_source" placeholder="e.g. Issuer Name, ACER"
                                    class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    value="{{ old('financials_source', $pressRelease->financials_source) }}">
                            </div>
                        </div>

                        <!-- STEP 11: Status & Other Info -->
                        <div id="step-10" class="mb-16 scroll-mt-32">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">11. Status & other
                                information
                            </h2>
                            <div class="mb-6"><label class="block text-sm font-semibold mb-2">Non Cooperation Status (If
                                    any)</label><textarea name="non_cooperation_status" rows="3"
                                    class="w-full rounded-lg border-gray-300 tinymce-editor"
                                    placeholder="Not Applicable">{{ old('non_cooperation_status', $pressRelease->non_cooperation_status) }}</textarea>
                            </div>
                            <div class="mb-6"><label class="block text-sm font-semibold mb-2">Other
                                    Information</label><textarea name="other_information" rows="3"
                                    class="w-full rounded-lg border-gray-300 tinymce-editor"
                                    placeholder="Not Applicable">{{ old('other_information', $pressRelease->other_information) }}</textarea>
                            </div>
                        </div>

                        <!-- STEP 12: Annexure 1 Rating History -->
                        <div id="step-11" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">12. Annexure1 - Rating History</h2>
                            <input type="hidden" name="annexure_1_rating_history"
                                :value="JSON.stringify(ratingHistory.map(r => ({...r, year1_date: ratingHistoryY1Date, year2_date: ratingHistoryY2Date, year3_date: ratingHistoryY3Date})))">

                            <!-- Global Historical Rating Dates -->
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl mb-6">
                                <h4 class="font-bold text-gray-800 mb-3">Global Rating History Dates</h4>
                                <p class="text-xs text-gray-500 mb-4">Set the dates for the past 3 years once. These
                                    dates
                                    will apply to all historical ratings below.</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Year 1 Date</label>
                                        <input type="date" name="rating_history_y1_date" x-model="ratingHistoryY1Date"
                                            class="w-full text-sm rounded border-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Year 2 Date</label>
                                        <input type="date" name="rating_history_y2_date" x-model="ratingHistoryY2Date"
                                            class="w-full text-sm rounded border-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Year 3 Date</label>
                                        <input type="date" name="rating_history_y3_date" x-model="ratingHistoryY3Date"
                                            class="w-full text-sm rounded border-gray-300">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(row, i) in ratingHistory" :key="i">
                                    <div class="bg-gray-50 border p-4 rounded-xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-bold text-gray-800">Entry <span x-text="i+1"></span></h4>
                                            <button @click.prevent="ratingHistory.splice(i,1)" type="button"
                                                class="shrink-0 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors">
                                                Remove Entry
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div class="col-span-2">
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Instrument</label>
                                                <input type="text" x-model="row.instrument" placeholder="Instrument"
                                                    class="w-full text-sm rounded-lg border-gray-300">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Type</label>
                                                <select x-model="row.type"
                                                    class="w-full text-sm rounded-lg border-gray-300">
                                                    <option>Long-term</option>
                                                    <option>Short-term</option>
                                                    <option>Long-term/Short-term</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Limits
                                                    (Cr)</label>
                                                <input type="number" step="0.01" x-model="row.limits"
                                                    placeholder="Limits (Cr)"
                                                    class="w-full text-sm rounded-lg border-gray-300">
                                            </div>
                                            <div class="col-span-2 md:col-span-4">
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Current
                                                    Rating</label>
                                                <input type="text" x-model="row.current_rating"
                                                    placeholder="Current Rating"
                                                    class="w-full text-sm rounded-lg border-gray-300">
                                            </div>

                                            <!-- Historical Years Ratings Only -->
                                            <div class="col-span-2 md:col-span-4 mt-4 border-t pt-4">
                                                <h5 class="font-bold text-sm mb-3">Historical Ratings (Last 3 Years)
                                                </h5>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <!-- Year 1 -->
                                                    <div class="p-3 border rounded bg-white">
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Year 1
                                                            Rating (Rationale)</label>
                                                        <input type="text" x-model="row.year1_rating"
                                                            placeholder="Rating/Outlook/Watch"
                                                            class="w-full text-sm rounded border-gray-300">
                                                    </div>
                                                    <!-- Year 2 -->
                                                    <div class="p-3 border rounded bg-white">
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Year 2
                                                            Rating (Rationale)</label>
                                                        <input type="text" x-model="row.year2_rating"
                                                            placeholder="Rating/Outlook/Watch"
                                                            class="w-full text-sm rounded border-gray-300">
                                                    </div>
                                                    <!-- Year 3 -->
                                                    <div class="p-3 border rounded bg-white">
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Year 3
                                                            Rating (Rationale)</label>
                                                        <input type="text" x-model="row.year3_rating"
                                                            placeholder="Rating/Outlook/Watch"
                                                            class="w-full text-sm rounded border-gray-300">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button
                                    @click.prevent="ratingHistory.push({instrument:'', type:'Long-term', limits:'', current_rating:'', year1_rating:'', year2_rating:'', year3_rating:''})"
                                    class="border-2 py-3 border-dashed px-4 rounded-xl w-full border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors font-bold">+
                                    Add Rating History Row</button>
                            </div>
                        </div>

                        <!-- STEP 13: Complexity -->
                        <div id="step-12" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">13. Annexure1.1 - Complexity Level</h2>
                            <input type="hidden" name="annexure_1_1_complexity"
                                :value="JSON.stringify(complexityTable)">
                            <div class="space-y-4">
                                <template x-for="(row, i) in complexityTable" :key="i">
                                    <div class="flex gap-4"><input type="text" x-model="row.instrument"
                                            placeholder="Instrument" class="w-1/2 rounded-lg border-gray-300"><select
                                            x-model="row.level" class="w-1/2 rounded-lg border-gray-300">
                                            <option>Simple</option>
                                            <option>Complex</option>
                                            <option>Highly Complex</option>
                                        </select>
                                        <button @click.prevent="complexityTable.splice(i,1)" type="button"
                                            class="shrink-0 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-red-200 transition-colors">
                                            Remove Option
                                        </button>
                                    </div>
                                </template>
                                <button @click.prevent="complexityTable.push({instrument:'', level:'Simple'})"
                                    class="text-primary-600 font-bold">+ Add Row</button>
                            </div>
                        </div>

                        <!-- STEP 14: Instrument Details -->
                        <div id="step-13" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">14. Annexure2 - Instrument / Facility Details</h2>
                            <input type="hidden" name="annexure_2_instruments"
                                :value="JSON.stringify(instrumentDetails)">
                            <div class="space-y-4">
                                <template x-for="(row, i) in instrumentDetails" :key="i">
                                    <div class="bg-gray-50 border p-4 rounded-xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-bold text-gray-800">Instrument <span x-text="i+1"></span>
                                            </h4>
                                            <button @click.prevent="instrumentDetails.splice(i,1)" type="button"
                                                class="shrink-0 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-red-200 transition-colors">
                                                Remove Instrument
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div class="col-span-2"><input type="text" x-model="row.name"
                                                    placeholder="Name of Facility"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                            <div><input type="text" x-model="row.isin" placeholder="ISIN"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                            <div><input type="number" step="0.01" x-model="row.size"
                                                    placeholder="Size (Cr)"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                            <div><input type="date" x-model="row.issuance_date" title="Date of Issuance"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                            <div><input type="text" x-model="row.coupon" placeholder="Coupon Rate"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                            <div><input type="date" x-model="row.maturity" title="Maturity Date"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                            <div><select x-model="row.listing"
                                                    class="w-full text-sm rounded-lg border-gray-300">
                                                    <option>Listed</option>
                                                    <option>Unlisted</option>
                                                    <option>Proposed to be Listed</option>
                                                </select></div>
                                            <div><input type="text" x-model="row.rating" placeholder="Rating/Outlook"
                                                    class="w-full text-sm rounded-lg border-gray-300"></div>
                                        </div>
                                    </div>
                                </template>
                                <button
                                    @click.prevent="instrumentDetails.push({name:'', isin:'', size:'', issuance_date:'', coupon:'', maturity:'', listing:'Unlisted'})"
                                    class="border-2 py-3 border-dashed rounded w-full border-gray-300 text-gray-600 font-bold">+
                                    Add Instrument Row</button>
                            </div>
                        </div>

                        <!-- STEP 15: Lenders & Banks -->
                        <div id="step-14" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">15. Annexure3 - Facility Wise Lender Details</h2>
                            <input type="hidden" name="annexure_3_lenders" :value="JSON.stringify(lenderDetails)">
                            <div class="space-y-6">
                                <template x-for="(lender, i) in lenderDetails" :key="i">
                                    <div class="border border-gray-200 bg-gray-50/50 p-5 rounded-xl">
                                        <div class="flex justify-between mb-4">
                                            <input type="text" x-model="lender.name"
                                                placeholder="Lender Name (e.g. HDFC)"
                                                class="w-2/3 border-gray-300 rounded font-bold">
                                            <button @click.prevent="lenderDetails.splice(i,1)"
                                                class="text-red-800 font-bold text-sm">Remove Lender</button>
                                        </div>
                                        <!-- Facility Iteration inside Lender -->
                                        <div class="space-y-3 pl-4 border-l-2 border-gray-200">
                                            <template x-for="(f, fi) in lender.facilities" :key="fi">
                                                <div class="flex gap-3 items-center">
                                                    <input type="text" x-model="f.facility" placeholder="Facility Name"
                                                        class="w-2/5 text-sm rounded border-gray-300">
                                                    <input type="number" step="0.01" x-model="f.amount"
                                                        placeholder="Amt (Cr)"
                                                        class="w-1/5 text-sm rounded border-gray-300">
                                                    <input type="text" x-model="f.rating" placeholder="Rating"
                                                        class="w-1/4 text-sm rounded border-gray-300">
                                                    <button @click.prevent="lender.facilities.splice(fi,1)"
                                                        type="button"
                                                        class="shrink-0 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors">
                                                        Remove Option
                                                    </button>
                                                </div>
                                            </template>
                                            <button
                                                @click.prevent="lender.facilities.push({facility:'', amount:'', rating:''})"
                                                class="text-primary-600 text-sm font-bold">+ Add Facility to
                                                Lender</button>
                                        </div>
                                    </div>
                                </template>
                                <button
                                    @click.prevent="lenderDetails.push({name:'', facilities:[{facility:'', amount:'', rating:''}]})"
                                    class="border-2 py-3 border-dashed border-gray-300 bg-gray-50 text-gray-700 w-full font-bold rounded-xl">+
                                    Add New Lender Bank Group</button>
                            </div>
                        </div>

                        <!-- STEP 16: Covenant Details -->
                        <div id="step-15" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">16. Annexure4 - Covenant Details</h2>
                            <textarea name="ann4_covenants" rows="3"
                                class="w-full rounded-lg border-gray-300 tinymce-editor"
                                placeholder="Covenant details...">{{ old('ann4_covenants', $pressRelease->ann4_covenants) }}</textarea>
                        </div>

                        <!-- STEP 17: Fsr List -->
                        <div id="step-16" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">17. Annexure5 - Fsr List</h2>
                            <textarea name="ann5_fsr_list" rows="3"
                                class="w-full rounded-lg border-gray-300 tinymce-editor"
                                placeholder="FSR list details...">{{ old('ann5_fsr_list', $pressRelease->ann5_fsr_list) }}</textarea>
                        </div>

                        <!-- STEP 18: Entities Consolidated -->
                        <div id="step-17" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">18. Annexure6 - Entities Consolidated</h2>
                            <input type="hidden" name="ann6_entities_consolidated"
                                :value="JSON.stringify(entitiesConsolidated)">
                            <div class="space-y-4">
                                <template x-for="(entity, i) in entitiesConsolidated" :key="i">
                                    <div class="flex gap-4 items-center">
                                        <input type="text" x-model="entity.name" placeholder="Entity Name"
                                            class="w-1/3 text-sm rounded-lg border-gray-300">
                                        <select x-model="entity.extent"
                                            class="w-1/3 text-sm rounded-lg border-gray-300">
                                            <option>Full</option>
                                            <option>Partial (Equity Method)</option>
                                            <option>Not Consolidated</option>
                                        </select>
                                        <input type="text" x-model="entity.rationale"
                                            placeholder="Rationale for Consolidation"
                                            class="w-1/3 text-sm rounded-lg border-gray-300">
                                        <button @click.prevent="entitiesConsolidated.splice(i,1)" type="button"
                                            class="shrink-0 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-red-200 transition-colors">
                                            Remove Option
                                        </button>
                                    </div>
                                </template>
                                <button @click.prevent="entitiesConsolidated.push({name:'', rationale:''})"
                                    class="text-primary-600 font-bold">+ Add Entity</button>
                            </div>
                        </div>

                        <!-- STEP 19: Applicable Rating Criteria -->
                        <div id="step-18" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">19. Applicable Rating Criteria</h2>
                            <input type="hidden" name="applicable_criteria" :value="JSON.stringify(ratingCriteria)">
                            <div class="space-y-4">
                                <template x-for="(criteria, i) in ratingCriteria" :key="i">
                                    <div class="flex gap-4 items-center">
                                        <input type="text" x-model="criteria.name" placeholder="e.g. Basics of Ratings"
                                            class="w-1/2 rounded-lg border-gray-300 text-sm">
                                        <input type="url" x-model="criteria.url"
                                            placeholder="Link to criteria PDF on ACER website"
                                            class="w-1/2 rounded-lg border-gray-300 text-sm">
                                        <button @click.prevent="ratingCriteria.splice(i,1)" type="button"
                                            class="shrink-0 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-red-200 transition-colors">
                                            Remove Option
                                        </button>
                                    </div>
                                </template>
                                <button @click.prevent="ratingCriteria.push({name:'', url:''})" type="button"
                                    class="text-primary-600 font-bold">+ Add Criteria Link</button>
                            </div>

                        </div>

                        <!-- STEP 20: Analytical Contacts -->
                        <div id="step-19" class="mb-16 scroll-mt-32">
                            <h2 class="text-xl font-bold mb-4">20. Analytical Contacts</h2>
                            <input type="hidden" name="analytical_contacts" :value="JSON.stringify(contacts)">
                            <div class="space-y-4">
                                <template x-for="(contact, i) in contacts" :key="i">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <input type="text" x-model="contact.name" placeholder="Name"
                                            class="w-full text-sm rounded border-gray-300">
                                        <input type="text" x-model="contact.designation" placeholder="Designation"
                                            class="w-full text-sm rounded border-gray-300">
                                        <input type="text" x-model="contact.email" placeholder="Email"
                                            class="w-full text-sm rounded border-gray-300">
                                        <div class="flex gap-2">
                                            <input type="text" x-model="contact.phone" placeholder="Phone"
                                                class="w-full text-sm rounded border-gray-300">
                                            <button @click.prevent="contacts.splice(i,1)" type="button"
                                                class="shrink-0 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 px-3 py-2 rounded-lg font-semibold text-xs border border-gray-200 hover:border-red-200 transition-colors">
                                                Remove Option
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <button @click.prevent="contacts.push({name:'', designation:'', email:'', phone:''})"
                                    class="text-primary-600 font-bold">+ Add Contact Person</button>
                            </div>
                        </div>
                    </div><!-- End formatType raw conditionally hidden steps! -->

                    <!-- Navigation Controls -->
                    <div :class="{'md:left-[288px]': formatType === 'raw', 'md:left-[288px] justify-end': formatType === 'pdf'}"
                        class="fixed bottom-0 right-0 bg-white border-t border-gray-200 p-4 px-8 flex items-center justify-between z-40 transition-all w-[calc(100%-288px)]">
                        <div x-show="formatType === 'raw'">
                            <button type="button" @click="prevStep" :disabled="currentStep === 0"
                                :class="{'opacity-50': currentStep===0}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors hidden">Previous</button>
                        </div>
                        <div x-show="formatType === 'pdf'"></div>

                        <span x-show="formatType === 'raw'" class="text-sm font-bold text-gray-500">Step <span
                                x-text="currentStep + 1"></span> of
                            <span x-text="steps.length"></span></span>

                        <div class="flex gap-4 items-center">
                            <a href="{{ route('admin.press-releases.index') }}"
                                class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">Cancel
                                & Back</a>
                            <div class="flex gap-4">
                                <button type="button" x-show="formatType === 'raw'"
                                    class="bg-gray-50 text-gray-700 font-bold py-2 px-6 border border-gray-200 hover:bg-gray-100 rounded-lg transition-colors hidden">Preview</button>
                                <button type="submit"
                                    class="bg-primary-600 text-white font-bold py-2.5 px-10 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/20">Update
                                    Press Release</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pressReleaseWizard', (initialFormat = null) => ({
                formatType: initialFormat,
                showSidebar: false,
                setFormat(type) {
                    this.formatType = type;
                },
                ratingHistory: [],
                ratingHistoryY1Date: '',
                ratingHistoryY2Date: '',
                ratingHistoryY3Date: '',
                init() {
                    let scrollTimeout;
                    document.getElementById('scrollable-content').addEventListener('scroll', () => {
                        if (scrollTimeout) clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(() => {
                            let current = 0;
                            for (let i = 0; i < this.steps.length; i++) {
                                let el = document.getElementById('step-' + i);
                                if (el && el.getBoundingClientRect().top <= 300) {
                                    current = i;
                                }
                            }
                            if (this.currentStep !== current) {
                                this.currentStep = current;
                                this.steps.forEach((s, idx) => s.completed = idx <= current);
                            }
                        }, 50);
                    });

                    let pr = @json($pressRelease);
                    if (pr.rating_action_table && pr.rating_action_table.length > 0) {
                        this.ratingActions = pr.rating_action_table.map(a => ({
                            instrument_name: a.instrument_name || a.instrument || '',
                            amount_inr: a.amount_inr || a.amount || '',
                            current_rating: a.current_rating || a.rating || '',
                            rating_action: a.rating_action || a.action || '',
                            regulator: a.regulator || ''
                        }));
                    }
                    if (pr.strengths) this.strengthsList = pr.strengths;
                    if (pr.weaknesses) this.weaknessesList = pr.weaknesses;
                    if (pr.positive_sensitivities) this.positiveSensitivities = pr.positive_sensitivities;
                    if (pr.negative_sensitivities) this.negativeSensitivities = pr.negative_sensitivities;
                    if (pr.company_segments_table) this.segmentsTable = pr.company_segments_table;
                    if (pr.fy_columns) this.fyColumns = pr.fy_columns;
                    if (pr.annexure_1_rating_history && pr.annexure_1_rating_history.length > 0) {
                        this.ratingHistory = pr.annexure_1_rating_history;
                        // Populate global dates from the first row if available
                        if (pr.annexure_1_rating_history[0].year1_date) this.ratingHistoryY1Date = pr.annexure_1_rating_history[0].year1_date;
                        if (pr.annexure_1_rating_history[0].year2_date) this.ratingHistoryY2Date = pr.annexure_1_rating_history[0].year2_date;
                        if (pr.annexure_1_rating_history[0].year3_date) this.ratingHistoryY3Date = pr.annexure_1_rating_history[0].year3_date;
                    }
                    if (pr.annexure_1_1_complexity && pr.annexure_1_1_complexity.length > 0) { this.complexityTable = pr.annexure_1_1_complexity; }
                    if (pr.annexure_2_instruments) this.instrumentDetails = pr.annexure_2_instruments;
                    if (pr.annexure_3_lenders) this.lenderDetails = pr.annexure_3_lenders;
                    if (pr.analytical_contacts) this.contacts = pr.analytical_contacts;
                    if (pr.ann6_entities_consolidated) this.entitiesConsolidated = pr.ann6_entities_consolidated;
                    if (pr.applicable_criteria && typeof pr.applicable_criteria !== 'string') {
                        this.ratingCriteria = pr.applicable_criteria;
                    }
                },
                currentStep: 0,
                scrollToStep(index) {
                    this.currentStep = index;
                    const el = document.getElementById('step-' + index);
                    if (el) { el.scrollIntoView({ behavior: 'smooth' }); }
                },
                steps: [
                    { title: 'Header', completed: false },
                    { title: 'Rating Action', completed: false },
                    { title: 'Analytical Approach', completed: false },
                    { title: 'Brief Summary', completed: false },
                    { title: 'Key Rating Drivers - Strengths', completed: false },
                    { title: 'Key Rating Drivers - Weaknesses', completed: false },
                    { title: 'Liquidity', completed: false },
                    { title: 'Rating Sensitivities', completed: false },
                    { title: 'About the company', completed: false },
                    { title: 'Key Financial Indicators', completed: false },
                    { title: 'Status & other information', completed: false },
                    { title: 'Annexure1 - Rating History', completed: false },
                    { title: 'Annexure1.1 - Complexity Level', completed: false },
                    { title: 'Annexure2 - Instrument / Facility Details', completed: false },
                    { title: 'Annexure3 - Facility Wise Lender Details', completed: false },
                    { title: 'Annexure4 - Covenant Details', completed: false },
                    { title: 'Annexure5 - Fsr List', completed: false },
                    { title: 'Annexure6 - Entities Consolidated', completed: false },
                    { title: 'Applicable Rating Criteria', completed: false },
                    { title: 'Analytical Contacts', completed: false }
                ],
                ratingActions: [],
                strengthsList: [],
                weaknessesList: [],
                positiveSensitivities: [],
                negativeSensitivities: [],
                segmentsTable: [],
                fyColumns: [],
                complexityTable: [],
                instrumentDetails: [],
                lenderDetails: [],
                contacts: [],
                entitiesConsolidated: [],
                ratingCriteria: [],

                addRatingAction() { this.ratingActions.push({ instrument_name: '', amount_inr: '', current_rating: '', rating_action: '', regulator: '' }); },
                removeRatingAction(i) { this.ratingActions.splice(i, 1); },

                nextStep() { if (this.currentStep < this.steps.length - 1) { this.scrollToStep(this.currentStep + 1); } },
                prevStep() { if (this.currentStep > 0) { this.scrollToStep(this.currentStep - 1); } }
            }));
        });
    </script>

    <!-- TinyMCE Script (Free CDN - No License Required) -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '.tinymce-editor',
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | table | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    </script>
</x-admin-layout>