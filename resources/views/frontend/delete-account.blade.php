<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logos/favicon.ico') }}">
    <title>Delete Account - Brand Fotos</title>
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
        .container { padding-left: 6%; padding-right: 5%; padding-top: 2%; }
        :root{--bg:#f7f7fb;--card:#fff;--ink:#111;--muted:#666;--danger:#b91c1c;--brand:#2f3e9e}
        .wrap{max-width:720px;margin:24px auto;padding:0 16px}
        .card{padding: 100px 20px;position: relative;border: none}
        h1{margin:0}
        h2{margin:6px 0 0;font-size:18px;color:#eee;opacity:.95}
        .danger{color:var(--danger)}
        label{display:block;margin:14px 0 6px;font-weight:600}
        input[type="text"], input[type="number"], input[type="password"], textarea, select{
            width:100%;padding:12px 12px;border:1px solid #d7d8e1;border-radius:8px;background:#fff;font:inherit
        }
        textarea{min-height:110px;resize:vertical}
        .hint{font-size:13px;color:var(--muted);margin-top:4px}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .ack{display:flex;gap:10px;align-items:flex-start;margin-top:12px}
        .btns{display:flex;gap:12px;margin-top:18px;flex-wrap:wrap}
        button{appearance:none;border:0;border-radius:10px;padding:12px 16px;font-weight:700;cursor:pointer}
        .btn-danger{background:var(--danger);color:#fff}
        .btn-danger[disabled]{opacity:.6;cursor:not-allowed}
        .btn-ghost{background:#eef0ff;color:#2f3e9e; display: flex; align-items: center; padding: 5px 15px; border-radius: 10px; text-decoration: none;}
        .state{margin-top:14px;font-size:14px}
        .state.ok{color:#166534}
        .state.err{color:#991b1b}
        .hr{height:1px;background:#ececf4;margin:18px 0}
        .note{background:#fff7f7;border:1px solid #ffd8d8;color:#7a1111;padding:12px;border-radius:10px}
        .spinner{display:inline-block;width:16px;height:16px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;vertical-align:-2px;animation:spin .9s linear infinite;margin-right:6px}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head>

<body class="text-gray-300 font-sans antialiased overflow-x-hidden selection:bg-brand-secondary selection:text-white">

    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-[#050A24]/90 backdrop-blur-md py-4">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
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
            <div class="rounded-[2rem] border border-gray-800 overflow-hidden shadow-2xl relative">
                <div class="card">
                <p class="note"><strong>Warning:</strong> Deleting your account is <em>permanent</em>. Your profile, posts, and settings will be removed. This cannot be undone.</p>

                <!-- Progressive enhancement: works without JS too -->
                <form id="deleteForm" action="https://api.freefestivalpost.in/v1/user-settings/delete-account" method="post" novalidate>
                    <!-- CSRF (server should validate this) -->
                    <input type="hidden" name="csrf" id="csrfField" value="">

                    <label for="number">Account Number</label>
                    <input type="number" id="number" name="number" autocomplete="number" required placeholder="Number" />
                    <div class="hint">Use the number associated with this account.</div>

                    <label for="password">enter Password</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required placeholder="••••••••" />
                    <div class="hint">For security, please confirm it’s you.</div>

                    <div class="hr"></div>

                    <label for="confirm" class="danger">Type DELETE to confirm</label>
                    <input type="text" id="confirm" name="confirm" inputmode="latin" autocomplete="off" placeholder="DELETE" pattern="^DELETE$" required />
                    <div class="ack">
                        <input type="checkbox" id="ack" required />
                        <label for="ack" style="margin: 0">I understand my account and all associated data will be permanently deleted and cannot be recovered.</label>
                    </div>

                    <div class="btns">
                        <button type="submit" class="btn-danger" id="submitBtn" disabled>
                            <span class="spinner" id="spin" style="display:none"></span>Delete my account
                        </button>
                        <a class="btn-ghost" href="/">Cancel</a>
                    </div>

                    <div class="state" id="stateMsg" aria-live="polite"></div>
                </form>

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