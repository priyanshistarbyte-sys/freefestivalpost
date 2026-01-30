<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logos/favicon.ico') }}">
    <title>Contact Us - Brand Fotos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-primary': '#0F172A',
                        'brand-secondary': '#00C853',
                        'brand-accent': '#334155',
                        'brand-dark': '#0B1121', // Darker background
                        'brand-card': '#161F32', // Slightly lighter card bg
                        'brand-input': '#0F1623', // Input bg
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050A24;
            /* Deep dark blue background match */
        }
    </style>
</head>

<body class="text-gray-300 font-sans antialiased overflow-x-hidden selection:bg-brand-secondary selection:text-white">

    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-[#050A24]/90 backdrop-blur-md py-4">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <img src="{{ asset('assets/logos/brandfotos-logo.png') }}" alt="BrandFotos Logic"
                    class="h-16 md:h-24 w-auto object-contain brightness-0 invert">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="https://play.google.com/store/apps/details?id=com.freefestivalpost.freefestivalpost&pcampaignid=web_share"
                    target="_blank" class="btn-primary text-sm shadow-none py-2 px-6">Get App</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white">
                <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="w-6 h-6">
                    <line x1="4" x2="20" y1="12" y2="12" />
                    <line x1="4" x2="20" y1="6" y2="6" />
                    <line x1="4" x2="20" y1="18" y2="18" />
                </svg>
                <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="w-6 h-6 hidden">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 18 18" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-[#0F172A] border-b border-gray-800 overflow-hidden shadow-lg absolute w-full left-0 top-full">
            <div class="flex flex-col p-6 gap-4">
                <a href="/#features"
                    class="text-lg text-white hover:text-brand-secondary font-medium">Features</a>
                <a href="/#templates-slider"
                    class="text-lg text-white hover:text-brand-secondary font-medium">Templates</a>
                <a href="/#pricing"
                    class="text-lg text-white hover:text-brand-secondary font-medium">Pricing</a>
                <a href="/#about" class="text-lg text-white hover:text-brand-secondary font-medium">About</a>
                <a href="https://play.google.com/store/apps/details?id=com.freefestivalpost.freefestivalpost&pcampaignid=web_share"
                    target="_blank" class="btn-primary w-full mt-4 text-center block">Get App</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen pt-32 md:pt-48 pb-12 px-4 md:px-6">
        <div class="container mx-auto max-w-7xl">
            <!-- Breadcrumb / Header area if needed, or just straight to card -->
            <!-- <div class="mb-8 pl-4">
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="bg-teal-900/50 text-teal-400 px-2 py-0.5 rounded text-xs font-bold">BF</span>
                    <span>BRAND FOTOS - CREATIVE FOR EVERY FESTIVAL</span>
                </div>
            </div> -->

            <!-- Main Card -->
            <div class="bg-[#0F172A] rounded-[2rem] border border-gray-800 overflow-hidden shadow-2xl relative">
                <!-- Background decoration -->
                <!-- <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div> -->

                <div class="grid lg:grid-cols-12 gap-0 relative z-10">

                    <!-- Left Column: Form -->
                    <div class="lg:col-span-7 p-6 md:p-12 lg:p-16 lg:pr-12 md:border-r border-gray-800">
                        <div
                            class="inline-flex items-center gap-2 bg-[#1E293B] px-3 py-1.5 rounded-full text-xs font-medium text-teal-400 mb-6">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                            Usually replies within 24-48 hours
                        </div>

                        <h1 class="text-2xl md:text-4xl font-bold text-white mb-4">Let's Connect with Brand Fotos</h1>
                        <p class="text-gray-400 text-sm md:text-base mb-8 leading-relaxed">
                            Questions, feedback ya support chahiye? Bas form fill karo ya niche diye gaye WhatsApp /
                            email se contact karo. Hum aapke business ko grow karne ke liye hamesha ready hain.
                        </p>

                        <div class="flex items-center gap-2 text-white font-semibold mb-3 text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="#00C853" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="w-5 h-5">
                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                            Send us a Message
                        </div>
                        <p class="text-gray-500 text-xs md:text-sm mb-6 leading-relaxed">
                            Apna business aur Brand Fotos app ke baare mein short detail likho. Zyada clear message
                            hoga, utni jaldi aur better help mil payegi.
                        </p>

                        @if(session('success'))
                            <div class="bg-green-900/50 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="bg-red-900/50 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST" class="space-y-4 md:space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-400 ml-1">Full Name *</label>
                                    <input type="text" name="full_name" placeholder="Enter your full name" required
                                        class="w-full px-4 py-3 bg-[#0B1121] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-brand-secondary focus:ring-1 focus:ring-brand-secondary transition-colors text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-400 ml-1">Business Name
                                        (optional)</label>
                                    <input type="text" name="business_name" placeholder="Your shop / brand name"
                                        class="w-full px-4 py-3 bg-[#0B1121] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-brand-secondary focus:ring-1 focus:ring-brand-secondary transition-colors text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-400 ml-1">Email Address *</label>
                                    <input type="email" name="email" placeholder="you@example.com" required
                                        class="w-full px-4 py-3 bg-[#0B1121] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-brand-secondary focus:ring-1 focus:ring-brand-secondary transition-colors text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-400 ml-1">WhatsApp / Mobile Number
                                        *</label>
                                    <input type="tel" name="phone" placeholder="+91-XXXXXXXXXX" required
                                        class="w-full px-4 py-3 bg-[#0B1121] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-brand-secondary focus:ring-1 focus:ring-brand-secondary transition-colors text-sm">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-gray-400 ml-1">Subject *</label>
                                <input type="text" name="subject" placeholder="Example: Plan upgrade / App issue"
                                    required
                                    class="w-full px-4 py-3 bg-[#0B1121] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-brand-secondary focus:ring-1 focus:ring-brand-secondary transition-colors text-sm">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-gray-400 ml-1">Your Message *</label>
                                <textarea name="message" rows="4" required
                                    placeholder="Please describe your query, issue or request in detail..."
                                    class="w-full px-4 py-3 bg-[#0B1121] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-brand-secondary focus:ring-1 focus:ring-brand-secondary transition-colors text-sm resize-none"></textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full md:w-auto btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 rounded-full text-sm font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    Send Message
                                </button>
                            </div>

                            <p class="text-[10px] text-gray-600 leading-tight">
                                By submitting this form, you agree to be contacted by the Brand Fotos support team
                                regarding your query. Kabhi-kabhi reply spam / promotions folder mein bhi ja sakta hai,
                                please check there too.
                            </p>
                        </form>
                    </div>

                    <!-- Right Column: Info -->
                    <div
                        class="lg:col-span-5 bg-[#0B1121] p-6 md:p-12 lg:p-12 border-t lg:border-t-0 lg:border-l border-gray-800">
                        <h2 class="text-lg font-bold text-teal-400 mb-6">Contact Details & Support</h2>

                        <div class="space-y-5">
                            <!-- Email Card -->
                            <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
                                <div class="text-[10px] uppercase font-bold text-gray-500 mb-1">EMAIL SUPPORT</div>
                                <a href="mailto:support@brandfotos.com"
                                    class="text-white font-bold text-base md:text-lg hover:text-brand-secondary transition-colors mb-2 block break-all">support@brandfotos.com</a>
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    2nd Floor, 2044 & 2045, Silver Business Point, VIP Circle, Uttran, Surat, Gujarat,
                                    394105
                                </p>
                            </div>

                            <!-- WhatsApp Card -->
                            <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
                                <div class="text-[10px] uppercase font-bold text-gray-500 mb-1">WHATSAPP SUPPORT</div>
                                <a href="https://wa.me/918140331370" target="_blank"
                                    class="text-white font-bold text-lg hover:text-brand-secondary transition-colors mb-2 block">+91-8140331370</a>
                                <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                                    Quick reply chahiye? WhatsApp par message ke saath screenshot / screen recording bhi
                                    share karo.
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-2 py-1 bg-gray-800 rounded text-[10px] text-gray-400 border border-gray-700">Preferred
                                        for urgent queries</span>
                                    <span
                                        class="px-2 py-1 bg-gray-800 rounded text-[10px] text-gray-400 border border-gray-700">Screenshots
                                        help us solve faster</span>
                                </div>
                            </div>

                            <!-- Business Hours Card -->
                            <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
                                <div class="text-[10px] uppercase font-bold text-gray-500 mb-3">BUSINESS HOURS (IST)
                                </div>
                                <div class="space-y-1 mb-4">
                                    <div class="text-xs text-gray-300">Monday - Saturday: <span
                                            class="font-bold text-white">10:00 AM to 7:00 PM</span></div>
                                    <div class="text-xs text-gray-300">Sunday: <span
                                            class="font-bold text-white">Closed</span></div>
                                </div>
                                <div
                                    class="flex gap-2 items-start py-2 px-3 bg-gray-900/50 rounded-lg border border-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="text-teal-500 mt-0.5 shrink-0">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <p class="text-[10px] text-gray-500">Messages received after hours will be answered
                                        on the next working day.</p>
                                </div>
                            </div>

                            <!-- Stay Updated -->
                            <div class="mt-8 pt-6 border-t border-gray-800">
                                <div class="text-[10px] uppercase font-bold text-gray-500 mb-4">STAY UPDATED</div>
                                <div class="flex flex-col gap-2">
                                    <a href="https://www.instagram.com/brandfotos.official/" target="_blank"
                                        class="flex items-center gap-3 px-4 py-2.5 bg-[#111827] border border-gray-800 rounded-full hover:border-gray-600 transition-colors group">
                                        <span
                                            class="text-xs text-white font-medium group-hover:text-brand-secondary transition-colors">Instagram
                                            - Daily festival designs</span>
                                    </a>
                                    <a href="http://www.youtube.com/@Brand_Fotos" target="_blank"
                                        class="flex items-center gap-3 px-4 py-2.5 bg-[#111827] border border-gray-800 rounded-full hover:border-gray-600 transition-colors group">
                                        <span
                                            class="text-xs text-white font-medium group-hover:text-brand-secondary transition-colors">YouTube
                                            - App tips & features</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-2.5 bg-[#111827] border border-gray-800 rounded-full hover:border-gray-600 transition-colors group">
                                        <span
                                            class="text-xs text-white font-medium group-hover:text-brand-secondary transition-colors">WhatsApp
                                            Channel - New templates alerts</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <div class="flex flex-col">
        <footer class="relative bg-transparent text-white z-10 pt-12 pb-8 border-t border-white/5">
            <div class="container mx-auto px-4 text-center">
                
                <div class="flex justify-center gap-6 mb-8 text-gray-400">
                    <a href="https://www.facebook.com/profile.php?id=61581686194007" target="_blank"
                        rel="noopener noreferrer" class="hover:text-white transition-colors"><svg
                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg></a>
                    <a href="http://www.youtube.com/@Brand_Fotos" target="_blank"
                        class="hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.56 49.56 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                            </path>
                            <path d="m10 15 5-3-5-3z"></path>
                        </svg></a>
                    <a href="https://www.instagram.com/brandfotos.official/" target="_blank"
                        class="hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                        </svg></a>


                </div>

                <h2 class="text-lg font-bold mb-2">BRAND FOTOS - Festival Poster</h2>
                <div class="text-sm text-gray-400 mb-6">If any queries, Please contact on <a
                        href="mailto:support@brandfotos.com"
                        class="text-green-500 hover:text-green-400">brandfotos.com</a></div>

                 <div class="flex flex-wrap justify-center gap-2 mb-4 text-sm text-green-500">
                    <a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a><span
                        class="text-gray-600">||</span>
                    <a href="{{ route('terms') }}" class="hover:underline">Terms & Condition</a><span
                        class="text-gray-600">||</span>
                    <a href="{{ route('refund-policy') }}" class="hover:underline">Refund Policy</a><span class="text-gray-600">||</span>
                    <a href="{{ route('digital-policy') }}" class="hover:underline">Digital Policy</a><span
                        class="text-gray-600">||</span>
                    <a href="{{ route('contact-us') }}" class="hover:underline">Contact Us</a>
                </div>

                <div class="text-xs text-gray-600 flex justify-center items-center">
                    <p>© 2025 All Right Revervd by BRAND FOTOS</p>
                </div>
            </div>
            <a href="https://wa.me/9537267999" target="_blank" rel="noopener noreferrer"
                class="absolute bottom-6 right-6 flex items-center justify-center w-14 h-14 bg-[#25D366] rounded-full shadow-lg hover:bg-[#128C7E] transition-all z-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor"
                    class="w-8 h-8 text-white">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
            </a>
        </footer>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        }

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });
    </script>
</body>

</html>