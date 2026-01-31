<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logos/favicon.ico') }}">
    <title>Digital Delivery Policy - Brand Fotos</title>
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

<body class="bg-[#0a192f] text-white font-sans antialiased overflow-x-hidden">

    <!-- Navbar (Dark Theme) -->
    <nav id="navbar"
        class="fixed w-full z-50 transition-all duration-300 bg-[#0a192f]/90 backdrop-blur-md border-b border-white/5 py-4">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('assets/logos/brandfotos-logo.png') }}" alt="BrandFotos Logic"
                    class="h-24 w-auto object-contain brightness-0 invert">
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="https://play.google.com/store/apps/details?id=com.freefestivalpost.freefestivalpost&pcampaignid=web_share"
                    target="_blank" class="btn-primary text-sm shadow-none py-2 px-6">Get App</a>
            </div>
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
        <div id="mobile-menu"
            class="hidden md:hidden bg-white border-b border-gray-100 overflow-hidden shadow-lg absolute w-full left-0 top-full">
            <div class="flex flex-col p-6 gap-4">
                <a href="/#features"
                    class="text-lg text-gray-800 hover:text-brand-secondary font-medium">Features</a>
                <a href="templates.html"
                    class="text-lg text-gray-800 hover:text-brand-secondary font-medium">Templates</a>
                <a href="/#pricing"
                    class="text-lg text-gray-800 hover:text-brand-secondary font-medium">Pricing</a>
                <a href="/#about"
                    class="text-lg text-gray-800 hover:text-brand-secondary font-medium">About</a>
                <button class="btn-primary w-full mt-4">Get App</button>
            </div>
        </div>
    </nav>

    <!-- Policy Layout Content -->
    <div class="min-h-screen bg-[#0a192f] text-white pt-32 md:pt-48 pb-12 relative overflow-hidden">
        <div
            class="fixed top-0 right-0 w-[500px] h-[500px] bg-[#64ffda] rounded-full blur-[120px] opacity-[0.15] -translate-y-1/2 translate-x-1/2 pointer-events-none">
        </div>
        <div
            class="fixed bottom-0 left-0 w-[500px] h-[500px] bg-[#112240] rounded-full blur-[120px] opacity-40 translate-y-1/2 -translate-x-1/2 pointer-events-none">
        </div>

        <div class="container mx-auto px-4 max-w-4xl relative z-10">
            <div class="flex items-center gap-2 mb-8">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-[#64ffda] to-[#112240] rounded-full flex items-center justify-center font-bold text-[#0a192f] text-sm shadow-lg shadow-[#64ffda]/20">
                    BF</div>
                <span class="text-gray-400 text-sm font-medium tracking-wide">BRAND FOTOS • DIGITAL DELIVERY
                    POLICY</span>
            </div>

            <div
                class="backdrop-blur-xl bg-[#112240]/40 border border-[#64ffda]/10 rounded-[2rem] p-8 md:p-12 shadow-2xl relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-96 h-96 bg-[#64ffda] rounded-full blur-[100px] opacity-10 -translate-y-1/2 translate-x-1/2 pointer-events-none">
                </div>

                <h1 class="text-3xl md:text-4xl font-bold mb-8 text-[#64ffda] relative z-10">Digital Delivery Policy
                </h1>

                <div class="space-y-6 text-gray-300 relative z-10 leading-relaxed text-sm md:text-base">
                    <!-- Digital Policy Content -->
                    <section>
                        <p class="mb-4">At Brand Fotos, all our services, templates, and subscription plans are 100%
                            digital products. Therefore, no physical shipping or delivery is required.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">1. Instant Activation</h2>
                        <div class="space-y-2">
                            <p>Once a customer purchases a subscription plan, the plan is activated instantly within a
                                few seconds.</p>
                            <p>All premium features and templates become available immediately inside the app.</p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">2. No Physical Shipment</h2>
                        <div class="space-y-2">
                            <p>Brand Fotos does not ship any physical goods.</p>
                            <p>All content, templates, posters, and features are delivered digitally inside the
                                application.</p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">3. Order Confirmation</h2>
                        <div class="space-y-2">
                            <p>After a successful payment, customers instantly receive plan confirmation within the app.
                            </p>
                            <p>Email or WhatsApp confirmation may also be sent automatically (if enabled).</p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">4. Access Issues</h2>
                        <div class="space-y-2">
                            <p>If the plan does not activate after payment due to any technical issue (rare cases):</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Please contact us within 24 hours</li>
                                <li>We will verify the transaction and manually activate your plan</li>
                            </ul>
                            <div class="mt-2">
                                <p>Support Email: <a href="mailto:support@brandfotos.com"
                                        class="text-blue-400 hover:underline">support@brandfotos.com</a></p>
                                <p>Support Number: <a href="tel:8140331370"
                                        class="text-blue-400 hover:underline">8140331370</a></p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">5. Auto-Renewal (If Applicable)</h2>
                        <div class="space-y-2">
                            <p>If auto-renewal is enabled for your subscription, the plan will renew automatically on
                                the next billing cycle.</p>
                            <p>Customers may manage or disable auto-renewal from their Google Play Store / App Store
                                account settings.</p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">6. Refund Policy</h2>
                        <p>Since Brand Fotos provides digital products with instant access, refunds are handled
                            separately under our Refund Policy.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-white mb-3">7. Contact Us</h2>
                        <div class="space-y-2">
                            <p>For any questions or support regarding digital delivery, please contact us:</p>
                            <p>📧 <a href="mailto:support@brandfotos.com"
                                    class="text-blue-400 hover:underline">support@brandfotos.com</a></p>
                            <p class="flex items-center gap-2">
                            <div class="w-4 h-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg></div> <a href="tel:8140331370"
                                class="text-blue-400 hover:underline">8140331370</a></p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="flex flex-col">
        <footer class="relative bg-gradient-to-b from-[#050A24] to-[#08103d] text-white z-10 pt-12 mt-0">
            <div class="container mx-auto px-4 text-center">
                <div class="flex justify-center gap-6 mb-10 text-gray-400">
                    <a href="https://www.facebook.com/profile.php?id=61581686194007" target="_blank"
                        rel="noopener noreferrer" class="hover:text-white transition-colors"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg></a>
                    <a href="http://www.youtube.com/@Brand_Fotos" class="hover:text-white transition-colors"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path
                                d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.56 49.56 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                            </path>
                            <path d="m10 15 5-3-5-3z"></path>
                        </svg></a>
                    <a href="https://www.instagram.com/brandfotos.official/"
                        class="hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                        </svg></a>


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
                    <a href="{{ route('digital-policy') }}" class="hover:underline">Digital Policy</a><span
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
    </script>
</body>

</html>