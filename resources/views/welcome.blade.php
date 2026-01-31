<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logos/favicon.ico') }}">
    <title>Brand Fotos - Festival Poster</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        'brand-light': '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white text-brand-primary font-sans antialiased overflow-x-hidden">

    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-transparent py-4">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('assets/logos/brandfotos-logo.png') }}" alt="BrandFotos Logic"
                    class="h-24 w-auto object-contain text-brand-primary">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features"
                    class="text-gray-600 hover:text-brand-secondary transition-colors text-sm font-semibold">Features</a>
                <a href="#templates-slider"
                    class="text-gray-600 hover:text-brand-secondary transition-colors text-sm font-semibold">Templates</a>
                <a href="#pricing"
                    class="text-gray-600 hover:text-brand-secondary transition-colors text-sm font-semibold">Pricing</a>

                <a href="https://play.google.com/store/apps/details?id=com.freefestivalpost.freefestivalpost&pcampaignid=web_share"
                    target="_blank" class="btn-primary text-sm shadow-none py-2 px-6">Get App</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-brand-primary">
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
            class="hidden md:hidden bg-white border-b border-gray-100 overflow-hidden shadow-lg absolute w-full left-0 top-full">
            <div class="flex flex-col p-6 gap-4">
                <a href="#features" class="text-lg text-gray-800 hover:text-brand-secondary font-medium">Features</a>
                <a href="#templates-slider"
                    class="text-lg text-gray-800 hover:text-brand-secondary font-medium">Templates</a>
                <a href="#pricing" class="text-lg text-gray-800 hover:text-brand-secondary font-medium">Pricing</a>
                <a href="https://play.google.com/store/apps/details?id=com.freefestivalpost.freefestivalpost&pcampaignid=web_share"
                    target="_blank" class="btn-primary w-full mt-4 text-center block">Get App</a>
            </div>
        </div>
    </nav>


    <!-- Hero -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-brand-light">
        <div class="container mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10 pb-32">
            <!-- Text -->
            <div data-aos="fade-up" class="space-y-6 text-center lg:text-left">
                <h1 class="text-4xl md:text-6xl font-extrabold text-brand-primary leading-[1.15]">
                    This is an App to increase <br class="hidden md:block" />
                    the <span class="text-brand-secondary">growth of your business.</span>
                </h1>
                <p class="text-lg text-gray-500 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    The only app you need to create branded marketing content in seconds.
                    Choose a template, add your logo, and share.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    <a href="https://play.google.com/store/apps/details?id=com.freefestivalpost.freefestivalpost&pcampaignid=web_share"
                        target="_blank" class="btn-primary flex items-center justify-center gap-2">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center">
                            <div class="text-brand-secondary font-bold text-xs">B</div>
                        </div>
                        Download App
                    </a>
                    <a href="http://www.youtube.com/@Brand_Fotos" target="_blank"
                        class="flex items-center justify-center gap-2 px-6 py-3 rounded-full text-brand-primary font-bold hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="white" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="w-3 h-3 text-white ml-0.5">
                                <polygon points="6 3 20 12 6 21 6 3" />
                            </svg>
                        </div>
                        Watch Video
                    </a>
                </div>
            </div>

            <!-- Hero Image / Mockup -->
            <div data-aos="fade-left" data-aos-delay="200" class="relative flex justify-center">
                <!-- Phone 1 -->
                <div
                    class="relative w-64 md:w-72 aspect-[1080/1989] bg-gray-900 rounded-[2.5rem] shadow-2xl border-[12px] border-gray-900 mr-8 transform -rotate-12 z-10 overflow-hidden ring-1 ring-white/20">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-6 bg-gray-900 rounded-b-xl z-20"></div>
                    <img src="{{ asset('assets/screenshots/ss1.png') }}" alt="App Screenshot 1"
                        class="w-full h-full object-cover rounded-[2rem]">
                </div>

                <!-- Phone 2 -->
                <div
                    class="relative w-64 md:w-72 aspect-[1080/1989] bg-gray-900 rounded-[2.5rem] shadow-2xl border-[12px] border-gray-900 transform rotate-6 -ml-16 mt-12 z-20 overflow-hidden ring-1 ring-white/20">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-6 bg-gray-900 rounded-b-xl z-20"></div>
                    <img src="assets/screenshots/ss2.png" alt="App Screenshot 2"
                        class="w-full h-full object-cover rounded-[2rem]">
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-24 bg-brand-light">
        <div class="container mx-auto px-6 text-center">
            <div data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-brand-primary">About The App</h2>
                <p class="text-gray-500 max-w-2xl mx-auto mb-16">
                    Everything you need to create professional branded content is right here. Simple, fast, and
                    effective.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Item 1 -->
                <div data-aos="fade-up" data-aos-delay="0" class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 rounded-full border-2 border-brand-primary/10 flex items-center justify-center mb-6 text-brand-primary transition-all hover:scale-110 hover:rotate-6 hover:bg-brand-secondary hover:text-white hover:border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-8 h-8">
                            <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                            <path d="M12 18h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-brand-primary">Easy to Use Interface</h3>
                    <p class="text-gray-500 max-w-xs">Just select a template and we'll handle the rest. No design skills
                        needed.</p>
                </div>

                <!-- Item 2 -->
                <div data-aos="fade-up" data-aos-delay="200" class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 rounded-full border-2 border-brand-primary/10 flex items-center justify-center mb-6 text-brand-primary transition-all hover:scale-110 hover:rotate-6 hover:bg-brand-secondary hover:text-white hover:border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-8 h-8">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-brand-primary">Safe & Secure</h3>
                    <p class="text-gray-500 max-w-xs">Your brand assets and data are protected with enterprise-grade
                        security.</p>
                </div>
                <!-- Item 3 -->
                <div data-aos="fade-up" data-aos-delay="400" class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 rounded-full border-2 border-brand-primary/10 flex items-center justify-center mb-6 text-brand-primary transition-all hover:scale-110 hover:rotate-6 hover:bg-brand-secondary hover:text-white hover:border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-8 h-8">
                            <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
                            <path
                                d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-brand-primary">24/7 Support</h3>
                    <p class="text-gray-500 max-w-xs">Our dedicated support team is always here to help you grow.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-32 bg-brand-primary relative text-white">
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                preserveAspectRatio="none" class="fill-brand-light block w-full h-10 md:h-24 lg:h-32">
                <path fill-opacity="1"
                    d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
                </path>
            </svg>
        </div>

        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center relative z-10">
            <!-- Mockup -->
            <div class="relative flex justify-center hover:translate-y-[-10px] transition-transform duration-500">
                <div class="w-full max-w-md rounded-[2.5rem] relative overflow-hidden shadow-2xl animate-float">
                    <img src="assets/screenshots/ss.png" alt="App Feature Screenshot" class="w-full h-auto block">
                </div>
            </div>

            <!-- List -->
            <div>
                <div data-aos="fade-up">
                    <h2 class="text-3xl md:text-5xl font-bold mb-12">Application Features</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-12">
                    <!-- Feature Items -->
                    <div data-aos="fade-up" data-aos-delay="0" class="flex gap-4">
                        <div
                            class="mt-1 shrink-0 w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 text-white">
                                <path fill-rule="evenodd"
                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-2">User Friendly</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Intuitive interface for
                                instant results.</p>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="100" class="flex gap-4">
                        <div
                            class="mt-1 shrink-0 w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 text-white">
                                <path fill-rule="evenodd"
                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-2">High Res Export</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Download in 4k quality
                                for print or web.</p>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="200" class="flex gap-4">
                        <div
                            class="mt-1 shrink-0 w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 text-white">
                                <path fill-rule="evenodd"
                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-2">Brand Library</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Store your logos and
                                colors securely.</p>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="300" class="flex gap-4">
                        <div
                            class="mt-1 shrink-0 w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 text-white">
                                <path fill-rule="evenodd"
                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-2">Daily Updates</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Fresh templates added
                                every single day.</p>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="400" class="flex gap-4">
                        <div
                            class="mt-1 shrink-0 w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 text-white">
                                <path fill-rule="evenodd"
                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-2">Social Ready</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">One-click resize for
                                Instagram, LinkedIn, etc.</p>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="500" class="flex gap-4">
                        <div
                            class="mt-1 shrink-0 w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 text-white">
                                <path fill-rule="evenodd"
                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-2">Team Access</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Collaborate with your
                                team in real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                preserveAspectRatio="none" class="fill-white block w-full h-10 md:h-24 lg:h-32">
                <path fill-opacity="1"
                    d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,208C1248,192,1344,192,1392,192L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>

    <!-- Templates -->
    <section id="templates-slider" class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-6 text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-bold mb-6 text-brand-primary">Stunning Festivals</h2>
            <p class="text-gray-500 text-lg">Thousands of professionally designed templates ready for your brand.</p>
        </div>

        <div class="relative w-full overflow-hidden mask-fade-sides group">
            <div class="flex gap-8 w-max animate-marquee">
                <!-- using local images, duplicated for marquee -->
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/17.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Christmas.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Diwali_Deepavali.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Ganesh Chaturthi.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Navratri.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Republic Day.jpg" class="w-full h-full object-cover">
                </div>
                <!-- Duplicate -->
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/17.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Christmas.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Diwali_Deepavali.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Ganesh Chaturthi.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Navratri.jpg" class="w-full h-full object-cover">
                </div>
                <div
                    class="w-64 h-80 rounded-2xl overflow-hidden shadow-lg border-4 border-white shrink-0 relative hover:scale-105 transition-transform duration-300">
                    <img src="assets/slider_photos/Republic Day.jpg" class="w-full h-full object-cover">
                </div>
            </div>
        </div>


    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-brand-primary">Pricing Plans</h2>
                <p class="text-gray-500">Choose the perfect plan for your business growth.</p>
            </div>

            @php
                $colors = [
                    ['border' => 'border-gray-700', 'text' => 'text-gray-700', 'bg' => 'bg-gray-700', 'hover' => 'hover:bg-gray-800', 'icon' => 'text-gray-700'],
                    ['border' => 'border-blue-500', 'text' => 'text-blue-600', 'bg' => 'bg-blue-500', 'hover' => 'hover:bg-blue-600', 'icon' => 'text-blue-500'],
                    ['border' => 'border-cyan-500', 'text' => 'text-cyan-600', 'bg' => 'bg-cyan-500', 'hover' => 'hover:bg-cyan-600', 'icon' => 'text-cyan-500'],
                    ['border' => 'border-green-500', 'text' => 'text-green-600', 'bg' => 'bg-green-500', 'hover' => 'hover:bg-green-600', 'icon' => 'text-green-500'],
                    ['border' => 'border-purple-500', 'text' => 'text-purple-600', 'bg' => 'bg-purple-500', 'hover' => 'hover:bg-purple-600', 'icon' => 'text-purple-500'],
                    ['border' => 'border-pink-500', 'text' => 'text-pink-600', 'bg' => 'bg-pink-500', 'hover' => 'hover:bg-pink-600', 'icon' => 'text-pink-500']
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                @foreach($subscriptionPlans as $index => $plan)
                    @php
                        $colorIndex = $index % 6;
                        $color = $colors[$colorIndex];
                        $delay = $index * 100;
                    @endphp
                    
                    <div data-aos="fade-up" data-aos-delay="{{ $delay }}"
                        class="relative bg-white rounded-3xl p-6 shadow-xl border-t-8 {{ $color['border'] }} flex flex-col transition-all hover:-translate-y-2 hover:shadow-2xl">
                       
                        <h3 class="text-xl font-bold {{ $color['text'] }} mb-2">{{ $plan->plan_name }}</h3>
                        
                        <div class="text-4xl font-extrabold {{ $color['text'] }} mb-6">
                            @if($plan->discount > 0 && $plan->discount_price != $plan->price)
                                <div>
                                    <span class="text-lg line-through text-gray-400">₹{{ number_format($plan->price, 0) }}</span>
                                    <br>
                                    ₹{{ number_format($plan->discount_price, 0) }}
                                </div>
                            @else
                                ₹{{ number_format($plan->discount_price, 0) }}
                            @endif
                        </div>
                        
                        <ul class="space-y-3 mb-8 flex-grow">
                            @foreach($plan->descriptionsItem as $item)
                                <li class="flex items-start gap-3 text-sm {{ $item->sign == 1 ? 'text-gray-600' : 'text-gray-400 line-through' }}">
                                    @if($item->sign == 1)
                                        <svg class="w-5 h-5 {{ $color['icon'] }} mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                    {{ $item->title }}
                                </li>
                            @endforeach
                        </ul>
                        
                        <button class="w-full py-3 rounded-full font-bold text-white transition-all shadow-md hover:shadow-lg {{ $color['bg'] }} {{ $color['hover'] }}">
                            Select Plan
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="reviews" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div data-aos="fade-up" class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-6 text-brand-primary">What our clients say</h2>
                <p class="text-gray-500 text-lg">Join thousands of satisfied users who have elevated their brand with
                    BrandFotos.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- T1 -->
                <div data-aos="fade-up" class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 flex flex-col">
                    <div class="flex gap-1 mb-6 text-yellow-400">★★★★★</div>
                    <p class="text-gray-600 mb-8 flex-grow leading-relaxed">"A great app for creating festival posters
                        and business banners. Lots of ready-made designs and easy tools — perfect for quick social media
                        posts"</p>
                    <div class="flex items-center gap-4 mt-auto pt-6 border-t border-gray-50">
                        <div>
                            <div class="font-bold text-brand-primary">Kevin Vasoya</div>
                        </div>
                    </div>
                </div>
                <!-- T2 -->
                <div data-aos="fade-up" data-aos-delay="100"
                    class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 flex flex-col">
                    <div class="flex gap-1 mb-6 text-yellow-400">★★★★★</div>
                    <p class="text-gray-600 mb-8 flex-grow leading-relaxed">"Dear Ms. Khushi, The posters and videos
                        your team created for my business are absolutely outstanding! The designs are creative,
                        professional, and perfectly match my brand. Customers are loving them and I’m already seeing
                        great response. Thank you so much for the quick delivery and amazing work. Highly recommended!
                        Best regards, Kedar Shakti PHARMA"</p>
                    <div class="flex items-center gap-4 mt-auto pt-6 border-t border-gray-50">
                        <div>
                            <div class="font-bold text-brand-primary">Kedar Shakti PHARMA</div>
                        </div>
                    </div>
                </div>
                <!-- T3 -->
                <div data-aos="fade-up" data-aos-delay="200"
                    class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 flex flex-col">
                    <div class="flex gap-1 mb-6 text-yellow-400">★★★★★</div>
                    <p class="text-gray-600 mb-8 flex-grow leading-relaxed">"Excellent app! The customer support is
                        really amazing, they respond quickly and are always ready to help. New posts and updates keep
                        coming regularly, which makes the app even more interesting and useful. Highly recommended"</p>
                    <div class="flex items-center gap-4 mt-auto pt-6 border-t border-gray-50">
                        <div>
                            <div class="font-bold text-brand-primary">Metro Plasto</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Logos -->
    <section class="pt-16 pb-40 bg-gray-50 border-t border-gray-100 overflow-hidden">
        <div class="container mx-auto px-6 text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-brand-primary" data-aos="fade-in">Trusted By Industry Leaders
            </h2>
        </div>
        <div class="relative w-full overflow-hidden mask-fade-sides">
            <div class="flex gap-16 w-max animate-marquee items-center text-center">
                <!-- Logos -->
                <div class="shrink-0"><img src="assets/logos/Aliya%20Diagnostic%20center.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/AMV%20Engineering.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/Aquaneel%20Water%20Tech.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Clinosys%20HealthCare%20Solution.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/DHURUV%20PROMOTERS.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/EduKonnect.png" class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Export%20Minds.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/KEYA%20FASHION.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/NEW%20STAR%20COMMUNICATION.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/OMIKA%20HEALTH%20CARE%20PVT%20LTD.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Optho%20+.png" class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/ROOP%20SUKAN%20GREEN%20ENERGY.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Sepmed%20Healthcare.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shaurya%20Collection%20and%20dresses.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shaurygatha%20Construction.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shree%20Sai%20Real%20Estate.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shyam%20Metal.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/STUDENTS%20CORNER%20logo.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/The%20Valuation%20Experts.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Xpress%20Insurance.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/vedant.png" class="h-24 w-auto object-contain"></div>
                <!-- Duplicate -->
                <div class="shrink-0"><img src="assets/logos/Aliya%20Diagnostic%20center.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/AMV%20Engineering.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/Aquaneel%20Water%20Tech.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Clinosys%20HealthCare%20Solution.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/DHURUV%20PROMOTERS.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/EduKonnect.png" class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Export%20Minds.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/KEYA%20FASHION.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/NEW%20STAR%20COMMUNICATION.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/OMIKA%20HEALTH%20CARE%20PVT%20LTD.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Optho%20+.png" class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/ROOP%20SUKAN%20GREEN%20ENERGY.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Sepmed%20Healthcare.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shaurya%20Collection%20and%20dresses.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shaurygatha%20Construction.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shree%20Sai%20Real%20Estate.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Shyam%20Metal.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/STUDENTS%20CORNER%20logo.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/The%20Valuation%20Experts.png"
                        class="h-24 w-auto object-contain"></div>
                <div class="shrink-0"><img src="assets/logos/Xpress%20Insurance.png" class="h-24 w-auto object-contain">
                </div>
                <div class="shrink-0"><img src="assets/logos/vedant.png" class="h-24 w-auto object-contain"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="flex flex-col">
        <footer class="relative bg-gradient-to-b from-[#050A24] to-[#08103d] text-white z-10 pt-32 lg:pt-10 mt-0">
            <div class="absolute bottom-full left-0 w-full overflow-hidden leading-none">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none" class="fill-[#050A24] block w-full h-10 md:h-24 lg:h-32">
                    <path
                        d="M0,64L48,58.7C96,53,192,43,288,48C384,53,480,75,576,80C672,85,768,75,864,64C960,53,1056,43,1152,42.7L1200,42.7L1200,120L1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z">
                    </path>
                </svg>
            </div>

            <div class="container mx-auto px-4 text-center">
                <nav class="flex flex-wrap justify-center gap-8 mb-12 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-blue-400 transition-colors">Home</a>
                    <a href="#about" class="hover:text-blue-400 transition-colors">About</a>
                    <a href="#features" class="hover:text-blue-400 transition-colors">Features</a>

                    <a href="#reviews" class="hover:text-blue-400 transition-colors">Review</a>
                </nav>

                <div class="flex justify-center gap-6 mb-10 text-gray-400">
                    <a href="https://www.facebook.com/profile.php?id=61581686194007" target="_blank"
                        rel="noopener noreferrer" class="hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="http://www.youtube.com/@Brand_Fotos" target="_blank" rel="noopener noreferrer"
                        class="hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path
                                d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.56 49.56 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                            </path>
                            <path d="m10 15 5-3-5-3z"></path>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/brandfotos.official/" target="_blank" rel="noopener noreferrer"
                        class="hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                        </svg>
                    </a>


                </div>

                <h2 class="text-xl font-bold mb-4">BRAND FOTOS - Festival Poster</h2>
                <p class="text-sm text-gray-400 mb-6">If any queries, Please contact on <a href="mailto:brandfotos.com"
                        class="text-green-500 hover:underline">brandfotos.com</a></p>

                <div class="flex flex-wrap justify-center gap-2 mb-4 text-sm text-green-500">
                    <a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a><span
                        class="text-gray-600">||</span>
                    <a href="{{ route('terms') }}" class="hover:underline">Terms & Condition</a><span
                        class="text-gray-600">||</span>
                    <a href="{{ route('refund-policy') }}" class="hover:underline">Refund Policy</a><span class="text-gray-600">||</span>
                    <a href="digital-policy" class="hover:underline">Digital Policy</a><span
                        class="text-gray-600">||</span>
                    <a href="{{ route('contact-us') }}" class="hover:underline">Contact Us</a>
                </div>

                <div class="text-xs text-white pb-8 flex justify-center items-center">
                    <p>© 2025 All Right Revervd by BRAND FOTOS</p>
                </div>
            </div>
            <a href="https://wa.me/9537267999" target="_blank" rel="noopener noreferrer"
                class="fixed bottom-6 right-6 flex items-center justify-center w-14 h-14 bg-[#25D366] rounded-full shadow-lg hover:bg-[#128C7E] transition-all z-50">
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
        AOS.init({
            duration: 800,
            once: true,
        });

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
            if (window.scrollY > 20) {
                navbar.classList.add('bg-white/90', 'backdrop-blur-md', 'shadow-sm', 'py-2');
                navbar.classList.remove('bg-transparent', 'py-4');
            } else {
                navbar.classList.remove('bg-white/90', 'backdrop-blur-md', 'shadow-sm', 'py-2');
                navbar.classList.add('bg-transparent', 'py-4');
            }
        });
    </script>
</body>

</html>