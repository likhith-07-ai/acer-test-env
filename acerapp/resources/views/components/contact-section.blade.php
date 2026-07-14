@props([
'layout' => 'split', // 'split' or 'stacked'
'title' => '',
'subtitle' => '',
'offices' => [],
'formTitle' => '',
'formSubtitle' => '',
'formAction' => '#',
'showContactButton' => true,
'contactButtonUrl' => route('public.contact'),
'contactButtonText' => 'Contact Us',
'sectionClass' => 'py-6 bg-white dark:bg-gray-900',
'officeIcon' => 'acericon-office', // Default icon for offices
'showLabels' => true,
'sectionId' => null,
])
<section @if($sectionId) id="{{ $sectionId }}" class="scroll-mt-24 {{ $sectionClass }}" @else class="{{ $sectionClass }}" @endif>
    <div class="cmsContainer">
        <div class="grid grid-cols-1 {{ $layout === 'split' ? 'md:grid-cols-2 gap-12 xl:gap-36 items-start' : 'gap-8' }}">

            <!-- Info Column (Title & Subtitle) -->
            <div class="{{ $layout === 'stacked' ? 'max-w-4xl mx-auto text-center order-1' : 'order-1' }}">
                <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary-900 font-regular">
                    {{ $title }}
                </h2>
                @if($subtitle)
                <p class="font-medium mb-8 md:mb-12">{{ $subtitle }}</p>
                @endif

                @if($layout === 'split')
                @foreach($offices as $office)
                <div class="p-4 md:p-8 space-y-6 mb-8 border border-quaternary-100 rounded-[1.5rem] bg-white">
                    <div class="flex items-center gap-3">
                        <i class="{{ $office['icon'] ?? $officeIcon }} text-[1.75rem] text-primary-500"></i>
                        <h3 class="text-black text-[1.25rem] leading-[1.3] font-medium font-sans">
                            {{ $office['name'] }}
                        </h3>
                    </div>

                    @if(isset($office['address']) && !empty($office['address']))
                    <p class="font-medium text-[1.125rem] md:text-[1.25rem]">
                        {{ $office['address'] }}
                    </p>
                    @endif

                    <!-- Phone & Email Row -->
                    <div class="flex flex-wrap items-center gap-6">
                        @if(isset($office['phone']))
                        <a href="tel:{{ $office['phone'] }}" class="flex items-center gap-2 font-medium underline hover:text-primary-500 transition-colors duration-200">
                            <i class="acericon-phone text-base" aria-hidden="true"></i> {{ $office['phone'] }}
                        </a>
                        @endif

                        @if(isset($office['email']))
                        <a href="mailto:{{ $office['email'] }}" class="flex items-center font-medium gap-2 underline hover:text-primary-500 transition-colors duration-200">
                            <i class="acericon-email text-base" aria-hidden="true"></i> {{ $office['email'] }}
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($showContactButton)
                <a href="{{ $contactButtonUrl }}" class="items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-base font-medium transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-lg">
                    {{ $contactButtonText }}
                </a>
                @endif
                @endif
            </div>

            <!-- Form Column -->

            <div class="max-w-[720px] mx-auto p-4 md:p-8 border border-quaternary-100 rounded-[1.5rem] bg-white {{ $layout === 'stacked' ? 'max-w-3xl mx-auto w-full order-2' : 'order-2' }}">
                <h3 class="text-[1.5rem] leading-[1.3] font-medium mb-2 text-quaternary-900 font-sans">{{ $formTitle }}</h3>
                <p class="mb-8 md:mb-12 font-medium font-sans">{{ $formSubtitle }}</p>

                <!-- Success Message -->
                @if(session('success'))
                <div id="success-message" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="document.getElementById('success-message').remove()" class="ml-3 text-green-400 hover:text-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <script>
                    // Scroll to success message
                    document.addEventListener('DOMContentLoaded', function() {
                        const successMsg = document.getElementById('success-message');
                        if (successMsg) {
                            successMsg.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });

                            // Auto-hide after 5 seconds
                            setTimeout(function() {
                                successMsg.style.transition = 'opacity 0.5s ease-out';
                                successMsg.style.opacity = '0';
                                setTimeout(function() {
                                    successMsg.remove();
                                }, 500);
                            }, 5000);
                        }
                    });
                </script>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                <div id="error-message" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-800 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="document.getElementById('error-message').remove()" class="ml-3 text-red-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <script>
                    // Scroll to error message
                    document.addEventListener('DOMContentLoaded', function() {
                        const errorMsg = document.getElementById('error-message');
                        if (errorMsg) {
                            errorMsg.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }
                    });
                </script>
                @endif

                <form class="space-y-4 md:space-y-4" action="{{ $formAction }}" method="POST">
                    @csrf
                     
                    <div style="display:none;">
                        <label for="website">Website</label>
                        <input 
                            type="text"
                            id="website"
                            name="website"
                            autocomplete="off"
                            tabindex="-1"
                        >
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="{{ $showLabels ? 'block' : 'sr-only' }} text-quaternary-900 text-[1rem] leading-[1.25] font-medium mb-2">
                            Full Name <span class="text-red-800">*</span>
                        </label>
                        <input id="name" type="text" name="name" autocomplete="name" placeholder="Enter your name" class="w-full p-3  font-medium border border-quaternary-100 rounded-[0.75rem] bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none" required aria-required="true">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="{{ $showLabels ? 'block' : 'sr-only' }} text-quaternary-900 text-[1rem] leading-[1.25] font-medium mb-2">
                            Email <span class="text-red-800">*</span>
                        </label>
                        <input id="email" type="email" name="email" autocomplete="email" placeholder="Enter your email"
                            pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Enter a complete email address, e.g. name@example.com"
                            aria-describedby="email-hint"
                            class="w-full p-3  font-medium border border-quaternary-100 rounded-[0.75rem] bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none" required aria-required="true">
                        <p id="email-hint" class="sr-only">Enter a complete email address, for example name@example.com</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="{{ $showLabels ? 'block' : 'sr-only' }} text-quaternary-900 text-[1rem] leading-[1.25] font-medium mb-2">
                            Phone
                        </label>
                        <input id="phone" type="text" name="phone" autocomplete="tel" placeholder="Enter your phone" class="w-full p-3  font-medium border border-quaternary-100 rounded-[0.75rem] bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
                    </div>

                    <!-- Organization -->
                    <div>
                        <label for="organization" class="{{ $showLabels ? 'block' : 'sr-only' }} text-quaternary-900 text-[1rem] leading-[1.25] font-medium mb-2">
                            Organization
                        </label>
                        <input id="organization" type="text" name="organization" autocomplete="organization" placeholder="Enter your organization" class="w-full p-3  font-medium border border-quaternary-100 rounded-[0.75rem] bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="{{ $showLabels ? 'block' : 'sr-only' }} text-quaternary-900 text-[1rem] leading-[1.25] font-medium mb-2">
                            Subject
                        </label>
                        <div class="relative">
                            <select id="subject" name="subject" class="w-full p-3  font-medium border border-quaternary-100 rounded-[0.75rem] bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none appearance-none pr-10">
                                <option value="" disabled selected>Please select Subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="partnership">Partnership</option>
                                <option value="support">Support</option>
                                <option value="other">Other</option>
                            </select>
                            <i class="acericon-down-angle absolute end-3 top-1/2 -translate-y-1/2 pointer-events-none text-quaternary-500" aria-hidden="true"></i>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="{{ $showLabels ? 'block' : 'sr-only' }} text-quaternary-900 text-[1rem] leading-[1.25] font-medium mb-2">
                            Message
                        </label>
                        <textarea id="message" name="message" placeholder="Type your message..." rows="4" class="w-full p-3  font-medium border border-quaternary-100 rounded-[0.75rem] bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="flex flex-row items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-base leading-[1.5] font-medium transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-lg w-full">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stacked Layout: Offices at Bottom -->
            @if($layout === 'stacked')
            <div class="order-3 mx-auto w-full mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    @foreach($offices as $office)
                    <div class="p-4 md:p-8 space-y-6 border border-quaternary-100 rounded-[1.5rem] bg-white">
                        <div class="flex items-center gap-3">
                            <i class="acericon-office text-[1.75rem] text-primary-500"></i>
                            <h4 class="text-quaternary-900 text-[1.5rem] leading-[1.3] font-medium">
                                {{ $office['name'] }}
                            </h4>
                        </div>

                        @if(isset($office['address']) && !empty($office['address']))
                        <p class="font-medium text-[1.125rem] md:text-[1.25rem] ">
                            {{ $office['address'] }}
                        </p>
                        @endif

                        <!-- Phone & Email Row -->
                        <div class="flex flex-wrap items-center gap-6">
                            @if(isset($office['phone']))
                            <a href="tel:{{ $office['phone'] }}" class="flex items-center gap-2 font-medium underline hover:text-primary-500 transition-colors duration-200 ">
                                <i class="acericon-phone text-base" aria-hidden="true"></i> {{ $office['phone'] }}
                            </a>
                            @endif

                            @if(isset($office['email']))
                            <a href="mailto:{{ $office['email'] }}" class="flex items-center font-medium gap-2 underline hover:text-primary-500 transition-colors duration-200 ">
                                <i class="acericon-email text-base" aria-hidden="true"></i> {{ $office['email'] }}
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($showContactButton)
                <div class="mt-8 text-center">
                    <a href="{{ $contactButtonUrl }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-base font-medium transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-lg">
                        {{ $contactButtonText }}
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</section>