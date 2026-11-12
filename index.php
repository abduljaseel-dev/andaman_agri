<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department of Agriculture - Andaman & Nicobar Administration</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            800: '#065f46',
                            900: '#064e3b'
                        },
                        teal: {
                            700: '#0f766e',
                            800: '#115e59'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        };
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            #receipt-modal,
            #receipt-modal * {
                visibility: visible !important;
            }

            #receipt-modal {
                position: absolute !important;
                inset: 0 !important;
                background: white !important;
                display: block !important;
            }

            #receipt-modal > div {
                margin: 0 auto !important;
                box-shadow: none !important;
            }

            #receipt-modal button {
                display: none !important;
            }
        }

        .tab-active {
            border-bottom: 3px solid #059669;
            color: #059669;
            font-weight: 600;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
        }

        .location-select:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            color: #64748b;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Official Bar -->
    <div class="bg-emerald-900 text-white text-xs px-4 py-1.5 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <span>ANDAMAN & NICOBAR ADMINISTRATION</span>
            <span class="hidden md:inline">|</span>
            <span class="hidden md:inline">DEPARTMENT OF AGRICULTURE</span>
        </div>

        <div class="flex items-center space-x-4">
            <span class="text-emerald-200">Helpdesk: 1800-345-1145 (Toll Free)</span>

            <button type="button" onclick="toggleAccessibility()" class="hover:underline flex items-center gap-1">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                High Contrast
            </button>

            <div class="flex items-center space-x-1 border border-emerald-700 rounded px-1.5 py-0.5 bg-emerald-800 text-xs">
                <button type="button" onclick="setLanguage('en')" id="btn-en" class="font-bold text-white">EN</button>
                <span>/</span>
                <button type="button" onclick="setLanguage('hi')" id="btn-hi" class="text-emerald-300">हिन्दी</button>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <header class="bg-white shadow-sm border-b border-emerald-100 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-emerald-700 flex items-center justify-center text-white font-bold shadow-md">
                    <i data-lucide="sprout" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="font-bold text-emerald-900 text-lg md:text-xl leading-tight" id="header-title">
                        Department of Agriculture
                    </h1>
                    <p class="text-xs text-slate-500 font-medium" id="header-subtitle">
                        Andaman & Nicobar Administration, Sri Vijaya Puram
                    </p>
                </div>
            </div>

    <nav class="hidden lg:flex items-center space-x-6 text-sm font-medium text-slate-700">
                    <a href="#home" class="hover:text-emerald-700 transition-colors">Home</a>
                    <a href="#schemes" class="hover:text-emerald-700 transition-colors">Schemes & Minikits</a>
                    <a href="#application-status" class="hover:text-emerald-700 transition-colors flex items-center gap-1">
                        <i data-lucide="clipboard-check" class="w-4 h-4 text-emerald-600"></i> Application Status
                    </a>
                    <a href="#booking" class="bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-shadow shadow-sm flex items-center gap-1.5">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i> Seed Booking 2026-27
                    </a>
                    <a href="#csc-locator" class="hover:text-emerald-700 transition-colors">CSC & Office Locator</a>
                    <a href="#contact" class="hover:text-emerald-700 transition-colors">Contact Us</a>
                </nav>
            <!-- Mobile Menu Toggle -->
            <button onclick="toggleMobileMenu()" class="lg:hidden text-slate-700 p-2 focus:outline-none">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-200 px-4 py-3 space-y-3">
            <a href="#home" class="block text-slate-700 font-medium hover:text-emerald-700">Home</a>
            <a href="#schemes" class="block text-slate-700 font-medium hover:text-emerald-700">Schemes & Minikits</a>
            <a href="#application-status" class="block text-slate-700 font-medium hover:text-emerald-700 flex items-center gap-1.5">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-emerald-600"></i> Application Status
            </a>
            <a href="#booking" class="block bg-emerald-700 text-white text-center py-2 rounded-lg font-medium">Seed Booking 2026-27</a>
            <a href="#csc-locator" class="block text-slate-700 font-medium hover:text-emerald-700">CSC & Office Locator</a>
            <a href="#contact" class="block text-slate-700 font-medium hover:text-emerald-700">Contact Us</a>
        </div>
    </header>

    <main class="flex-grow">

        <!-- Hero -->
        <section id="home" class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 text-white relative overflow-hidden py-10 md:py-14">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>

            <div class="max-w-7xl mx-auto px-4 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center gap-2 bg-emerald-700/60 border border-emerald-500/40 text-emerald-200 px-3 py-1 rounded-full text-xs font-semibold">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        Island Agriculture Empowerment Scheme
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white">
                        Free Seed Minikits & 50% Subsidized Certified Seeds (Rabi 2026–27)
                    </h2>

                    <p class="text-emerald-100 text-sm md:text-base leading-relaxed">
                        Empowering farmers across South Andaman, North & Middle Andaman, and Nicobar Islands.
                        Mandatory online slot booking is now open for pulses, oilseeds, paddy, and vegetable seeds.
                    </p>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="#booking" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                            Start Seed Booking Now
                        </a>

                        <a href="#schemes" class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium px-5 py-3 rounded-lg flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Guidelines & Eligibility
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 bg-white/10 border border-white/20 rounded-2xl p-6 backdrop-blur-md text-white shadow-2xl">
                    <h3 class="text-lg font-bold flex items-center gap-2 text-amber-300 border-b border-white/10 pb-3">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        Important Announcements
                    </h3>

                    <ul class="space-y-3 mt-4 text-xs md:text-sm text-emerald-50">
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="calendar" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                            <span><strong>Paddy & Pulses Booking Deadline:</strong> Portal remains open till September 30, 2026.</span>
                        </li>

                        <li class="flex items-start gap-2.5">
                            <i data-lucide="layers" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                            <span><strong>E-Lottery Phases:</strong> 3-day booking slots starting Mon-Wed with e-Lottery on Thursdays.</span>
                        </li>

                        <li class="flex items-start gap-2.5">
                            <i data-lucide="gift" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                            <span><strong>100% Free Minikits:</strong> Black gram, Green gram, Mustard, Peas, & Vegetables for registered island farmers.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
 <section id="application-status" class="py-8 bg-emerald-50 border-b border-emerald-100">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white rounded-xl border border-emerald-200 shadow-sm p-5 md:p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-emerald-900">Check Your Application Status</h2>
                            <p class="text-xs text-slate-600 mt-1">Enter your application number, or search using the identity card details used during submission.</p>
                        </div>
                    </div>

                    <form id="application-status-form" class="space-y-3" onsubmit="checkApplicationStatus(event)">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                            <div>
                                <label for="status-search-method" class="block text-xs font-semibold text-slate-700 mb-1">Search using</label>
                                <select id="status-search-method" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" onchange="toggleStatusSearchMethod()">
                                    <option value="application">Application Number</option>
                                    <option value="identity">Identity Card</option>
                                </select>
                            </div>
                            <div id="status-application-field" class="md:col-span-2">
                                <label for="status-application-number" class="block text-xs font-semibold text-slate-700 mb-1">Application Number</label>
                                <input id="status-application-number" type="text" maxlength="40" placeholder="e.g. AN-RABI-2026-XXXXXXXX" autocomplete="off" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm uppercase focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div id="status-identity-fields" class="hidden md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="status-id-type" class="block text-xs font-semibold text-slate-700 mb-1">Identity Card Type</label>
                                    <select id="status-id-type" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="">Select ID type</option>
                                        <option value="Aadhaar">Aadhaar</option>
                                        <option value="Voter ID">Voter ID</option>
                                        <option value="Driving Licence">Driving Licence</option>
                                        <option value="Other Government ID">Other Government ID</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="status-id-number" class="block text-xs font-semibold text-slate-700 mb-1">Identity Card Number</label>
                                    <input id="status-id-number" type="text" maxlength="100" placeholder="Enter identity card number" autocomplete="off" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <button id="status-check-button" type="submit" class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 disabled:opacity-60 text-white font-semibold text-sm px-5 py-2.5 rounded-lg">
                                <i data-lucide="search" class="w-4 h-4"></i> Check Status
                            </button>
                            <span class="text-xs text-slate-500">Your identity number is used only to find your application.</span>
                        </div>
                    </form>

                    <div id="status-lookup-message" class="hidden mt-4 rounded-lg px-4 py-3 text-sm" role="status" aria-live="polite"></div>
                    <div id="status-lookup-result" class="hidden mt-4 border border-emerald-200 rounded-lg overflow-hidden">
                        <div class="bg-emerald-800 text-white px-4 py-3 flex flex-wrap justify-between gap-2">
                            <div><p class="text-[11px] text-emerald-200">APPLICATION NUMBER</p><strong id="status-result-number" class="text-sm"></strong></div>
                            <span id="status-result-main" class="self-center bg-white/15 rounded-full px-3 py-1 text-xs font-semibold"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 text-sm">
                            <div><p class="text-xs text-slate-500">Booking Category</p><p id="status-result-category" class="font-semibold text-slate-800"></p></div>
                            <div><p class="text-xs text-slate-500">Crop</p><p id="status-result-crop" class="font-semibold text-slate-800"></p></div>
                            <div><p class="text-xs text-slate-500">District</p><p id="status-result-district" class="font-semibold text-slate-800"></p></div>
                            <div><p class="text-xs text-slate-500">Season</p><p id="status-result-season" class="font-semibold text-slate-800"></p></div>
                            <div><p class="text-xs text-slate-500">Document Verification</p><p id="status-result-docs" class="font-semibold text-slate-800"></p><p id="status-result-doc-note" class="text-xs text-slate-500 mt-1"></p></div>
                            <div><p class="text-xs text-slate-500">Seed Delivery</p><p id="status-result-delivery" class="font-semibold text-slate-800"></p><p id="status-result-delivery-note" class="text-xs text-slate-500 mt-1"></p></div>
                            <div class="sm:col-span-2"><p class="text-xs text-slate-500">Application Submitted</p><p id="status-result-date" class="font-semibold text-slate-800"></p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Schemes -->
        <section id="schemes" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">

                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-xs font-bold tracking-wider text-emerald-700 uppercase bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                        Rabi Season Benefits
                    </span>

                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mt-2">Key Guidelines & Subsidies</h2>
                    <p class="text-slate-600 text-sm mt-1">Direct support and high-quality seeds supplied across all Island Zones.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div class="border border-slate-200 rounded-xl p-5 hover:border-emerald-500 hover:shadow-lg bg-slate-50/50">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-700 mb-4">
                            <i data-lucide="package-check" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base">Free Seed Minikits</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            100% complimentary high-yield minikits distributed for Pulses, Mustard/Toria, and Vegetable seeds.
                        </p>
                    </div>

                    <div class="border border-slate-200 rounded-xl p-5 hover:border-emerald-500 hover:shadow-lg bg-slate-50/50">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-700 mb-4">
                            <i data-lucide="percent" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base">50% Seed Subsidy</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Up to 50% financial subsidy on certified Paddy, Wheat, Maize, and Commercial crop seeds.
                        </p>
                    </div>

                    <div class="border border-slate-200 rounded-xl p-5 hover:border-emerald-500 hover:shadow-lg bg-slate-50/50">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-700 mb-4">
                            <i data-lucide="globe" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base">Island-Wide Portal</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Online reservation from South Andaman to Car Nicobar through home, CSC centers, or Agriculture offices.
                        </p>
                    </div>

                    <div class="border border-slate-200 rounded-xl p-5 hover:border-emerald-500 hover:shadow-lg bg-slate-50/50">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-700 mb-4">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base">Fair Allocation</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Transparent allocation process with farmer registration and department verification.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Booking -->
        <section id="booking" class="py-12 bg-slate-100 border-y border-slate-200">
            <div class="max-w-4xl mx-auto px-4">

                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">

                    <div class="bg-emerald-900 text-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold flex items-center gap-2">
                                    <i data-lucide="clipboard-list" class="w-6 h-6 text-emerald-400"></i>
                                    Online Seed Booking Application (Rabi 2026-27)
                                </h2>

                                <p class="text-xs text-emerald-200 mt-1">
                                    Follow the steps below to reserve your free minikit or subsidized seeds.
                                </p>
                            </div>

                            <span id="step-indicator" class="bg-emerald-800 text-emerald-200 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-700">
                                Step 1 of 5
                            </span>
                        </div>

                        <div class="w-full bg-emerald-950/60 h-2 rounded-full mt-5 overflow-hidden">
                            <div id="progress-bar" class="bg-amber-400 h-full w-1/5 transition-all duration-300"></div>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">

                        <!-- STEP 1 -->
                        <div id="step-1" class="space-y-5">
                            <h3 class="font-bold text-slate-800 text-base border-b pb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 text-white text-xs flex items-center justify-center">1</span>
                                Select Booking Category & Scheme Type
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <label class="border-2 border-emerald-600 bg-emerald-50/50 p-4 rounded-xl cursor-pointer flex items-start gap-3">
                                    <input type="radio" name="booking_type" value="free_minikit" checked class="mt-1 text-emerald-700">
                                    <div>
                                        <span class="font-bold text-emerald-900 block text-sm">Free Seed Minikit</span>
                                        <span class="text-xs text-slate-600">100% subsidized high-quality seed kits.</span>
                                    </div>
                                </label>

                                <label class="border-2 border-slate-200 p-4 rounded-xl cursor-pointer flex items-start gap-3">
                                    <input type="radio" name="booking_type" value="subsidized_seed" class="mt-1 text-emerald-700">
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm">50% Subsidized Certified Seed</span>
                                        <span class="text-xs text-slate-600">Purchase certified seeds with 50% subsidy.</span>
                                    </div>
                                </label>

                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="button" onclick="nextStep(2)" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2">
                                    Next Step <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div id="step-2" class="hidden space-y-5">
                            <h3 class="font-bold text-slate-800 text-base border-b pb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 text-white text-xs flex items-center justify-center">2</span>
                                Select District, Season & Crop
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>
                                    <label for="select-district" class="block text-xs font-bold text-slate-700 uppercase mb-1">Target District</label>
                                    <select id="select-district" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                                        <option value="South Andaman">South Andaman</option>
                                        <option value="North & Middle Andaman">North & Middle Andaman</option>
                                        <option value="Nicobar">Nicobar</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="select-season" class="block text-xs font-bold text-slate-700 uppercase mb-1">Crop Season</label>
                                    <select id="select-season" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                                        <option value="Rabi 2026-27">Rabi 2026–2027</option>
                                        <option value="Kharif 2026">Kharif Season</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="select-crop" class="block text-xs font-bold text-slate-700 uppercase mb-1">Select Crop Variety</label>
                                    <select id="select-crop" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                                        <option value="Black Gram (Urad) - Minikit 4kg">Black Gram (Urad) - Minikit (4 kg)</option>
                                        <option value="Green Gram (Moong) - Minikit 4kg">Green Gram (Moong) - Minikit (4 kg)</option>
                                        <option value="Mustard (Rai/Toria) - Minikit 2kg">Mustard (Toria/Rai) - Minikit (2 kg)</option>
                                        <option value="Peas (Matar) - Minikit 10kg">Field Pea (Matar) - Minikit (10 kg)</option>
                                        <option value="Paddy High Yield - 20kg Bag">Certified Paddy (CARII Selection) - 20 kg</option>
                                        <option value="Island Vegetable Seed Kit">Island Hybrid Vegetable Kit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-between pt-4">
                                <button type="button" onclick="nextStep(1)" class="border border-slate-300 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold">Back</button>
                                <button type="button" onclick="nextStep(3)" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                                    Next Step <i data-lucide="chevron-right" class="w-4 h-4 inline"></i>
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div id="step-3" class="hidden space-y-5">

                            <h3 class="font-bold text-slate-800 text-base border-b pb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 text-white text-xs flex items-center justify-center">3</span>
                                Farmer Verification Credentials
                            </h3>

                            <p class="text-sm text-slate-600">
                                Choose whether you are already registered with the Agriculture Department.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <label id="registered-option" class="border-2 border-emerald-600 bg-emerald-50/50 p-4 rounded-xl cursor-pointer flex items-start gap-3">
                                    <input type="radio" name="farmer_path" value="registered" checked onchange="selectFarmerPath('registered')" class="mt-1">
                                    <div>
                                        <span class="font-bold text-emerald-900 block text-sm">I am a registered farmer</span>
                                        <span class="text-xs text-slate-600">Department verification service is temporarily unavailable.</span>
                                    </div>
                                </label>

                                <label id="unregistered-option" class="border-2 border-slate-200 p-4 rounded-xl cursor-pointer flex items-start gap-3">
                                    <input type="radio" name="farmer_path" value="unregistered" onchange="selectFarmerPath('unregistered')" class="mt-1">
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm">I am not a registered farmer</span>
                                        <span class="text-xs text-slate-600">Submit your details and supporting documents for department review.</span>
                                    </div>
                                </label>

                            </div>

                            <!-- Registered farmer warning -->
                            <div id="registered-farmer-panel" class="border border-amber-200 bg-amber-50 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-700 shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-sm">Registered Farmer Verification Temporarily Unavailable</h4>
                                        <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                            The Department's farmer verification API is not connected yet. This route cannot be completed right now.
                                            It may take some time before the service becomes available. Please select the unregistered farmer option
                                            if you want to submit your details and supporting documents now.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Unregistered farmer form -->
                            <div id="unregistered-farmer-panel" class="hidden border border-slate-200 rounded-xl p-4 space-y-5">

                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Unregistered Farmer Details</h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Enter the farmer's identity and complete agricultural land address.
                                    </p>
                                </div>

                                <!-- Farmer name -->
                                <div>
                                    <label for="new-farmer-name" class="block text-xs font-bold text-slate-700 uppercase mb-1">Farmer Full Name <span class="text-red-600">*</span></label>
                                    <input type="text" id="new-farmer-name" maxlength="150" autocomplete="name"
                                           placeholder="Enter farmer's full name"
                                           class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm">
                                </div>

                                <!-- Identity -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <div>
                                        <label for="new-farmer-id-type" class="block text-xs font-bold text-slate-700 uppercase mb-1">Identity Card Type <span class="text-red-600">*</span></label>
                                        <select id="new-farmer-id-type" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm">
                                            <option value="">Select identity card</option>
                                            <option value="Aadhaar">Aadhaar Card</option>
                                            <option value="Voter ID">Voter ID</option>
                                            <option value="Driving Licence">Driving Licence</option>
                                            <option value="Other Government ID">Other Government ID</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="new-farmer-id-number" class="block text-xs font-bold text-slate-700 uppercase mb-1">Identity Card Number <span class="text-red-600">*</span></label>
                                        <input type="text" id="new-farmer-id-number" maxlength="40"
                                               placeholder="Enter identity card number"
                                               class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>

                                </div>

                                <!-- Farmer address -->
                                <div class="border-t border-slate-200 pt-4 space-y-4">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-emerald-700"></i>
                                        Farmer Residential / Agricultural Address
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Select the district, tehsil and village. Then select the post office to populate the PIN code.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="farmer-district" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            District <span class="text-red-600">*</span>
                                        </label>
                                        <select id="farmer-district" class="location-select w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm">
                                            <option value="">Select district</option>
                                            <option value="South Andaman">South Andaman</option>
                                            <option value="North & Middle Andaman">North & Middle Andaman</option>
                                            <option value="Nicobar">Nicobar</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="farmer-tehsil" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Tehsil <span class="text-red-600">*</span>
                                        </label>
                                        <select id="farmer-tehsil" disabled class="location-select w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm">
                                            <option value="">Select district first</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="farmer-village" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Village <span class="text-red-600">*</span>
                                        </label>
                                        <select id="farmer-village" disabled class="location-select w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm">
                                            <option value="">Select tehsil first</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="land-survey-number" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Land Survey Number <span class="text-red-600">*</span>
                                        </label>
                                        <input type="text" id="land-survey-number" maxlength="100"
                                            placeholder="Enter land survey / plot number"
                                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>

                                    <div>
                                        <label for="farmer-landmark" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Landmark <span class="text-slate-400 normal-case">(Optional)</span>
                                        </label>
                                        <input type="text" id="farmer-landmark" maxlength="255"
                                            placeholder="Nearby school, temple, road, etc."
                                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>

                                    <div>
                                        <label for="farmer-post-office" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Post Office <span class="text-red-600">*</span>
                                        </label>
                                        <select id="farmer-post-office" disabled
                                                class="location-select w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm">
                                            <option value="">Select village first</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="farmer-pincode" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            PIN Code <span class="text-red-600">*</span>
                                        </label>
                                        <input type="text" id="farmer-pincode" maxlength="6" readonly
                                            placeholder="Automatically filled from post office"
                                            class="w-full border border-slate-300 bg-slate-100 rounded-lg px-4 py-2.5 text-sm font-semibold text-emerald-800">
                                        <p class="text-xs text-slate-500 mt-1">
                                            Automatically populated from the selected post office.
                                        </p>
                                    </div>
                                </div>

                                <div class="rounded-lg border border-blue-200 bg-blue-50 p-3 text-xs text-blue-800">
                                    <strong>Location directory information:</strong>
                                    Village options are sourced from the Census 2011 village directory.
                                    Post office options are sourced from the district-level postal directory.
                                    The portal does not currently verify whether a selected post office serves
                                    the selected village. Please confirm with the postal department if unsure.
                                </div>

                                <div id="location-loading" class="hidden text-xs text-emerald-700 font-medium" role="status">
                                    Loading location options...
                                </div>

                                <div id="location-error"
                                    class="hidden rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700"
                                    role="alert"></div>
                            </div>

                                <!-- Uploads -->
                                <div class="border-t border-slate-200 pt-4">
                                    <h4 class="font-bold text-slate-800 text-sm mb-3">Supporting Documents</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <div>
                                            <label for="farmer-id-document" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                                Upload Identity Document <span class="text-red-600">*</span>
                                            </label>

                                            <input type="file" id="farmer-id-document" accept=".pdf,.jpg,.jpeg,.png"
                                                   class="w-full text-xs border border-slate-300 rounded-lg p-2">

                                            <p class="text-xs text-slate-500 mt-1">PDF, JPG or PNG. Maximum 5 MB.</p>
                                        </div>

                                        <div>
                                            <label for="farmer-land-record" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                                Upload Land Records <span class="text-red-600">*</span>
                                            </label>

                                            <input type="file" id="farmer-land-record" accept=".pdf,.jpg,.jpeg,.png"
                                                   class="w-full text-xs border border-slate-300 rounded-lg p-2">

                                            <p class="text-xs text-slate-500 mt-1">Land ownership / cultivation records. Maximum 5 MB.</p>
                                        </div>

                                    </div>
                                </div>

                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-800">
                                    Your application can be submitted without prior registration. The department team will examine the uploaded documents and decide the verification outcome later.
                                </div>

                            </div>

                            <div class="flex justify-between pt-2">
                                <button type="button" onclick="nextStep(2)" class="border border-slate-300 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold">
                                    Back
                                </button>

                                <button type="button" onclick="continueFromFarmerStep()" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2">
                                    Continue <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>

                        </div>

                        <!-- STEP 4 -->
                        <div id="step-4" class="hidden space-y-5">

                            <h3 class="font-bold text-slate-800 text-base border-b pb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 text-white text-xs flex items-center justify-center">4</span>
                                Review Application Details
                            </h3>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3 text-sm">

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Category:</span>
                                    <span id="summary-category" class="font-semibold text-right text-emerald-800"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Booking District:</span>
                                    <span id="summary-district" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Season:</span>
                                    <span id="summary-season" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Selected Crop:</span>
                                    <span id="summary-crop" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Farmer:</span>
                                    <span id="summary-farmer" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Farmer District:</span>
                                    <span id="summary-farmer-district" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Island / Area:</span>
                                    <span id="summary-area" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Tehsil:</span>
                                    <span id="summary-tehsil" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Village:</span>
                                    <span id="summary-village" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Land Survey Number:</span>
                                    <span id="summary-survey" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Landmark:</span>
                                    <span id="summary-landmark" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200 pb-2">
                                    <span class="text-slate-500 text-xs">Post Office:</span>
                                    <span id="summary-post-office" class="font-semibold text-right"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <span class="text-slate-500 text-xs">PIN Code:</span>
                                    <span id="summary-pincode" class="font-semibold text-right"></span>
                                </div>

                            </div>

                            <div id="summary-document-note" class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-800">
                                Your identity document and land records will be saved with this application. Your application status will be Submitted. Department staff will review the documents separately after submission.
                            </div>

                            <div class="flex justify-between pt-4">
                                <button type="button" onclick="nextStep(3)" class="border border-slate-300 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold">
                                    Back
                                </button>

                                <button type="button" onclick="nextStep(5)" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                                    Confirm Details <i data-lucide="chevron-right" class="w-4 h-4 inline"></i>
                                </button>
                            </div>

                        </div>

                        <!-- STEP 5 -->
                        <div id="step-5" class="hidden space-y-5">

                            <h3 class="font-bold text-slate-800 text-base border-b pb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 text-white text-xs flex items-center justify-center">5</span>
                                Declaration & Final Submission
                            </h3>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                <p class="text-sm text-slate-700 font-semibold">Before submitting</p>

                                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                    Please confirm that the application details are correct. A submission receipt with a unique application number will be generated after successful submission.
                                </p>

                                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                    Your application and documents will be recorded as submitted. Document verification will be carried out separately by the Department after submission. The receipt is an acknowledgement only, not an approval.
                                </p>
                            </div>

                            <div class="flex items-start gap-2">
                                <input type="checkbox" id="declaration" class="mt-0.5 rounded text-emerald-700">
                                <label for="declaration" class="text-xs text-slate-600">
                                    I declare that the information and documents submitted by me are true and correct to the best of my knowledge.
                                </label>
                            </div>

                            <div id="submit-error" class="hidden text-xs text-red-600 font-semibold" role="alert"></div>

                            <div class="flex justify-between pt-4">
                                <button type="button" onclick="nextStep(4)" class="border border-slate-300 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold">
                                    Back
                                </button>

                                <button type="button" id="submit-booking-button" onclick="submitBooking()" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-2.5 rounded-lg text-sm shadow-md flex items-center gap-2">
                                    Submit Application <i data-lucide="send" class="w-4 h-4"></i>
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- CSC Locator -->
        <section id="csc-locator" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">

                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                    <div>
                        <span class="text-xs font-bold tracking-wider text-emerald-700 uppercase bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                            Helpdesk & Support
                        </span>

                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mt-2">
                            Agri Offices & CSC Center Directory
                        </h2>

                        <p class="text-slate-600 text-sm">
                            Find your nearest Agriculture Depot, Seed Store or Jan Seva Kendra across the islands.
                        </p>
                    </div>

                    <div class="w-full md:w-auto">
                        <select id="district-filter" onchange="filterCSC()" class="w-full md:w-64 border border-slate-300 rounded-lg px-4 py-2 text-sm">
                            <option value="ALL">All Districts & Islands</option>
                            <option value="South Andaman">South Andaman</option>
                            <option value="North & Middle Andaman">North & Middle Andaman</option>
                            <option value="Nicobar">Nicobar District</option>
                        </select>
                    </div>
                </div>

                <div id="csc-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer id="contact" class="bg-slate-900 text-white border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">

            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                        <i data-lucide="sprout" class="w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-base text-white">Dept. of Agriculture</span>
                </div>

                <p class="text-xs text-slate-400 leading-relaxed">
                    Andaman & Nicobar Administration<br>
                    HQ: Directorate of Agriculture, Haddo, Sri Vijaya Puram - 744102
                </p>
            </div>

            <div>
                <h4 class="font-bold text-sm text-emerald-400 mb-3 uppercase tracking-wider">Quick Links</h4>
                <ul class="space-y-2 text-xs text-slate-300">
                    <li><a href="#booking" class="hover:underline">Seed Booking Portal</a></li>
                    <li><a href="#schemes" class="hover:underline">Free Seed Minikit Scheme</a></li>
                    <li><a href="https://agriculture.up.gov.in" target="_blank" rel="noopener noreferrer" class="hover:underline">National Agri Portal Reference</a></li>
                    <li><a href="#csc-locator" class="hover:underline">Jan Seva Kendra Locations</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-emerald-400 mb-3 uppercase tracking-wider">Farmer Helpline</h4>
                <div class="space-y-2 text-xs text-slate-300">
                    <p class="flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4 text-emerald-400"></i> Toll-Free: 1800-345-1145</p>
                    <p class="flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4 text-emerald-400"></i> diragri.and@nic.in</p>
                    <p class="flex items-center gap-2"><i data-lucide="clock" class="w-4 h-4 text-emerald-400"></i> Mon - Fri: 8:30 AM - 5:00 PM</p>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-sm text-emerald-400 mb-3 uppercase tracking-wider">Portal Disclaimer</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Designed for official island agriculture distribution and digital agriculture governance.
                </p>
            </div>

        </div>

        <div class="border-t border-slate-800 text-center py-4 text-xs text-slate-500">
            © 2026 Department of Agriculture, Andaman & Nicobar Administration. All Rights Reserved.
        </div>
    </footer>

    <!-- Receipt Modal -->
    <div id="receipt-modal" class="hidden fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative space-y-4">

            <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            <div class="text-center space-y-2">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="check-circle" class="w-8 h-8"></i>
                </div>

                <h3 class="text-xl font-bold text-slate-900">Document Submission Acknowledgement</h3>

                <p class="text-xs text-slate-500">
                    Your application details and supporting documents have been received. Keep this acknowledgement for future reference.
                </p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs space-y-2">

                <div class="flex justify-between border-b pb-1">
                    <span class="text-slate-500">Acknowledgement No:</span>
                    <span id="rec-id" class="font-mono font-bold text-emerald-800"></span>
                </div>

                <div class="flex justify-between border-b pb-1">
                    <span class="text-slate-500">District / Region:</span>
                    <span id="rec-district" class="font-semibold"></span>
                </div>

                <div class="flex justify-between border-b pb-1">
                    <span class="text-slate-500">Selected Crop:</span>
                    <span id="rec-crop" class="font-semibold"></span>
                </div>

                <div class="flex justify-between border-b pb-1">
                    <span class="text-slate-500">Applicant:</span>
                    <span id="rec-farmer" class="font-semibold text-right"></span>
                </div>

                <div class="flex justify-between border-b pb-1">
                    <span class="text-slate-500">Pickup Depot:</span>
                    <span id="rec-depot" class="font-semibold"></span>
                </div>

                <div class="flex justify-between border-b pb-1">
                    <span class="text-slate-500">Application Status:</span>
                    <span id="rec-status" class="text-emerald-700 font-bold">Submitted</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Document Verification:</span>
                    <span class="font-semibold text-amber-700 text-right">To be conducted later</span>
                </div>

            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="printReceipt()" class="w-full bg-emerald-700 text-white font-semibold py-2 rounded-lg text-sm hover:bg-emerald-800 flex items-center justify-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Print Receipt
                </button>

                <button type="button" onclick="closeModal()" class="w-full border border-slate-300 text-slate-700 font-semibold py-2 rounded-lg text-sm hover:bg-slate-100">
                    Done
                </button>
            </div>

        </div>
    </div>

    <!-- Application JavaScript -->
<script>
'use strict';

const LOCATION_API = 'location_options.php';

const cscLocations = [
    {name: "Zonal Agriculture Office - Haddo", district: "South Andaman", address: "Haddo, Sri Vijaya Puram", phone: "03192-233214", type: "Depot & Office"},
    {name: "Ferrargunj Agri Extension Center", district: "South Andaman", address: "Ferrargunj, South Andaman", phone: "03192-265210", type: "Seed Center"},
    {name: "Swaraj Dweep (Havelock) CSC", district: "South Andaman", address: "Govind Nagar, Swaraj Dweep", phone: "03192-282104", type: "Jan Seva Kendra"},
    {name: "Mayabunder Seed Store & Depot", district: "North & Middle Andaman", address: "Mayabunder Main Bazaar", phone: "03192-273221", type: "Main Depot"},
    {name: "Diglipur Agriculture Demonstration Farm", district: "North & Middle Andaman", address: "Aerial Bay, Diglipur", phone: "03192-271015", type: "Demonstration Farm"},
    {name: "Rangat Jan Seva Kendra", district: "North & Middle Andaman", address: "Rangat Island Center", phone: "03192-269302", type: "Jan Seva Kendra"},
    {name: "Car Nicobar Circle Office", district: "Nicobar", address: "Headquarter, Malacca, Car Nicobar", phone: "03193-265112", type: "District Office"},
    {name: "Campbell Bay Seed Sub-Depot", district: "Nicobar", address: "Great Nicobar Island", phone: "03193-264210", type: "Sub-Depot"}
];

const contentLang = {
    en: {
        title: "Department of Agriculture",
        subtitle: "Andaman & Nicobar Administration, Sri Vijaya Puram"
    },
    hi: {
        title: "कृषि विभाग",
        subtitle: "अंडमान तथा निकोबार प्रशासन, श्री विजय पुरम"
    }
};

let currentStep = 1;
let farmerPath = 'registered';

/*
 * Independent request counters prevent an older API response
 * from overwriting newer selections in another dropdown.
 */
const locationRequestNumbers = {
    'farmer-tehsil': 0,
    'farmer-village': 0,
    'farmer-post-office': 0
};

function setLanguage(lang) {
    if (!contentLang[lang]) return;

    document.getElementById('header-title').innerText = contentLang[lang].title;
    document.getElementById('header-subtitle').innerText = contentLang[lang].subtitle;

    document.getElementById('btn-hi').className =
        lang === 'hi' ? 'font-bold text-white' : 'text-emerald-300';

    document.getElementById('btn-en').className =
        lang === 'en' ? 'font-bold text-white' : 'text-emerald-300';
}

function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
}

function toggleAccessibility() {
    document.body.classList.toggle('invert');
}

/* 
 * FIX: Added the missing toggle function for the status search method dropdown.
 */
function toggleStatusSearchMethod() {
    const method = document.getElementById('status-search-method')?.value;

    const appNumberField = document.getElementById('status-application-field');
    const identityFields = document.getElementById('status-identity-fields');

    if (!appNumberField || !identityFields) {
        console.error('Application status search fields were not found.');
        return;
    }

    if (method === 'identity') {
        appNumberField.classList.add('hidden');
        identityFields.classList.remove('hidden');
    } else {
        identityFields.classList.add('hidden');
        appNumberField.classList.remove('hidden');
    }
}

function filterCSC() {
    const filter = document.getElementById('district-filter').value;
    const container = document.getElementById('csc-grid');

    if (!container) return;
    container.innerHTML = '';

    const filtered = filter === 'ALL'
        ? cscLocations
        : cscLocations.filter(item => item.district === filter);

    filtered.forEach(loc => {
        const card = document.createElement('div');
        card.className = 'border border-slate-200 rounded-xl p-5 hover:shadow-md transition-all bg-white flex flex-col justify-between';

        const top = document.createElement('div');
        const badge = document.createElement('span');
        badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        badge.textContent = loc.type;

        const district = document.createElement('span');
        district.className = 'text-xs text-slate-400 font-medium ml-2';
        district.textContent = loc.district;

        const heading = document.createElement('h4');
        heading.className = 'font-bold text-slate-900 text-base mt-2';
        heading.textContent = loc.name;

        const address = document.createElement('p');
        address.className = 'text-xs text-slate-600 mt-2';
        address.textContent = loc.address;

        const bottom = document.createElement('div');
        bottom.className = 'mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500';

        const phone = document.createElement('span');
        phone.textContent = loc.phone;

        const hours = document.createElement('span');
        hours.className = 'text-emerald-700 font-medium';
        hours.textContent = 'Open 9AM-4PM';

        top.appendChild(badge);
        top.appendChild(district);
        top.appendChild(heading);
        top.appendChild(address);

        bottom.appendChild(phone);
        bottom.appendChild(hours);

        card.appendChild(top);
        card.appendChild(bottom);
        container.appendChild(card);
    });
}

function setSelectOptions(selectId, options, placeholder) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '';

    const first = document.createElement('option');
    first.value = '';
    first.textContent = placeholder;
    select.appendChild(first);

    options.forEach(item => {
        const option = document.createElement('option');

        if (typeof item === 'string') {
            option.value = item;
            option.textContent = item;
        } else {
            option.value = item.value;
            option.textContent = item.pincode ? `${item.value} - ${item.pincode}` : item.value;

            if (item.pincode) {
                option.dataset.pincode = item.pincode;
            }
        }

        select.appendChild(option);
    });

    select.disabled = options.length === 0;
}

function resetLocationSelect(selectId, placeholder) {
    setSelectOptions(selectId, [], placeholder);
}

function invalidateLocationRequest(selectId) {
    locationRequestNumbers[selectId]++;
}

function showLocationError(message) {
    const box = document.getElementById('location-error');
    if (!box) return;
    box.textContent = message;
    box.classList.remove('hidden');
}

function clearLocationError() {
    const box = document.getElementById('location-error');
    if (!box) return;
    box.textContent = '';
    box.classList.add('hidden');
}

async function loadLocationOptions(action, params, selectId, placeholder) {
    const requestNumber = ++locationRequestNumbers[selectId];
    const loading = document.getElementById('location-loading');
    const select = document.getElementById(selectId);

    clearLocationError();
    if (loading) loading.classList.remove('hidden');
    if (select) select.disabled = true;

    try {
        const query = new URLSearchParams({
            action: action,
            ...params
        });

        const response = await fetch(
            `${LOCATION_API}?${query.toString()}`,
            {
                method: 'GET',
                headers: {'Accept': 'application/json'},
                cache: 'no-store'
            }
        );

        let data;
        try {
            data = await response.json();
        } catch (error) {
            throw new Error('The location service returned an invalid response.');
        }

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Unable to load location options.');
        }

        if (requestNumber !== locationRequestNumbers[selectId]) {
            return [];
        }

        const options = Array.isArray(data.options) ? data.options : [];
        setSelectOptions(selectId, options, placeholder);

        if (options.length === 0) {
            showLocationError('No location entries were found for this selection. Please check the selected district or tehsil.');
        }

        return options;

    } catch (error) {
        if (requestNumber === locationRequestNumbers[selectId]) {
            resetLocationSelect(selectId, placeholder);
            showLocationError(error.message || 'Unable to connect to the location service.');
        }
        return [];

    } finally {
        if (requestNumber === locationRequestNumbers[selectId]) {
            if (loading) loading.classList.add('hidden');
        }
    }
}

function clearPostOfficeAndPin() {
    invalidateLocationRequest('farmer-post-office');
    resetLocationSelect('farmer-post-office', 'Select village first');
    const pinEl = document.getElementById('farmer-pincode');
    if (pinEl) pinEl.value = '';
}

async function onFarmerDistrictChange() {
    const districtEl = document.getElementById('farmer-district');
    if (!districtEl) return;
    const district = districtEl.value;

    invalidateLocationRequest('farmer-village');
    invalidateLocationRequest('farmer-post-office');

    resetLocationSelect('farmer-tehsil', 'Select district first');
    resetLocationSelect('farmer-village', 'Select tehsil first');
    clearPostOfficeAndPin();
    clearLocationError();

    if (!district) return;

    await loadLocationOptions(
        'tehsils',
        {district: district},
        'farmer-tehsil',
        'Select tehsil'
    );
}

async function onFarmerTehsilChange() {
    const districtEl = document.getElementById('farmer-district');
    const tehsilEl = document.getElementById('farmer-tehsil');
    if (!districtEl || !tehsilEl) return;

    const district = districtEl.value;
    const tehsil = tehsilEl.value;

    invalidateLocationRequest('farmer-village');
    resetLocationSelect('farmer-village', 'Select tehsil first');
    clearPostOfficeAndPin();
    clearLocationError();

    if (!district || !tehsil) return;

    await loadLocationOptions(
        'villages',
        {district: district, tehsil: tehsil},
        'farmer-village',
        'Select village'
    );
}

async function onFarmerVillageChange() {
    const districtEl = document.getElementById('farmer-district');
    const villageEl = document.getElementById('farmer-village');
    if (!districtEl || !villageEl) return;

    const district = districtEl.value;
    const village = villageEl.value;

    clearPostOfficeAndPin();
    clearLocationError();

    if (!district || !village) return;

    await loadLocationOptions(
        'post_offices',
        {district: district},
        'farmer-post-office',
        'Select post office'
    );
}

function onPostOfficeChange() {
    const select = document.getElementById('farmer-post-office');
    const pinInput = document.getElementById('farmer-pincode');
    if (!select || !pinInput) return;

    const selectedOption = select.options[select.selectedIndex];
    pinInput.value = selectedOption ? (selectedOption.dataset.pincode || '') : '';
}

function getFarmerAddress() {
    const district = document.getElementById('farmer-district')?.value || '';
    const tehsil = document.getElementById('farmer-tehsil')?.value || '';
    const village = document.getElementById('farmer-village')?.value || '';
    const survey = document.getElementById('land-survey-number')?.value.trim() || '';
    const landmark = document.getElementById('farmer-landmark')?.value.trim() || '';
    const postOffice = document.getElementById('farmer-post-office')?.value || '';
    const pincode = document.getElementById('farmer-pincode')?.value || '';

    return [
        `District: ${district}`,
        `Tehsil: ${tehsil}`,
        `Village: ${village}`,
        `Land Survey Number: ${survey}`,
        landmark ? `Landmark: ${landmark}` : '',
        `Post Office: ${postOffice}`,
        `PIN Code: ${pincode}`
    ].filter(Boolean).join(', ');
}

function updateProgressBar(step) {
    const progressBar = document.getElementById('progress-bar');
    const stepIndicator = document.getElementById('step-indicator');
    if (progressBar) progressBar.style.width = `${(step / 5) * 100}%`;
    if (stepIndicator) stepIndicator.innerText = `Step ${step} of 5`;
}

function selectFarmerPath(path) {
    farmerPath = path;

    const registeredPanel = document.getElementById('registered-farmer-panel');
    const unregisteredPanel = document.getElementById('unregistered-farmer-panel');
    const registeredOption = document.getElementById('registered-option');
    const unregisteredOption = document.getElementById('unregistered-option');

    if (registeredPanel) registeredPanel.classList.toggle('hidden', path !== 'registered');
    if (unregisteredPanel) unregisteredPanel.classList.toggle('hidden', path !== 'unregistered');

    if (registeredOption) {
        registeredOption.className = path === 'registered'
            ? 'border-2 border-emerald-600 bg-emerald-50/50 p-4 rounded-xl cursor-pointer flex items-start gap-3'
            : 'border-2 border-slate-200 p-4 rounded-xl cursor-pointer flex items-start gap-3';
    }

    if (unregisteredOption) {
        unregisteredOption.className = path === 'unregistered'
            ? 'border-2 border-emerald-600 bg-emerald-50/50 p-4 rounded-xl cursor-pointer flex items-start gap-3'
            : 'border-2 border-slate-200 p-4 rounded-xl cursor-pointer flex items-start gap-3';
    }
}

function validateUnregisteredFarmer() {
    const name = document.getElementById('new-farmer-name')?.value.trim();
    const idType = document.getElementById('new-farmer-id-type')?.value;
    const idNumber = document.getElementById('new-farmer-id-number')?.value.trim();

    const district = document.getElementById('farmer-district')?.value;
    const tehsil = document.getElementById('farmer-tehsil')?.value;
    const village = document.getElementById('farmer-village')?.value;

    const survey = document.getElementById('land-survey-number')?.value.trim();
    const postOffice = document.getElementById('farmer-post-office')?.value;
    const pincode = document.getElementById('farmer-pincode')?.value;

    const identityFile = document.getElementById('farmer-id-document')?.files[0];
    const landFile = document.getElementById('farmer-land-record')?.files[0];

    if (!name || !idType || !idNumber) {
        alert('Please complete the farmer name and identity details.');
        return false;
    }

    if (!district || !tehsil || !village) {
        alert('Please select the farmer district, tehsil and village.');
        return false;
    }

    if (!survey) {
        alert('Please enter the land survey number.');
        return false;
    }

    if (!postOffice || !/^\d{6}$/.test(pincode)) {
        alert('Please select a post office with a valid six-digit PIN code.');
        return false;
    }

    if (!identityFile || !landFile) {
        alert('Please upload both the identity document and land records.');
        return false;
    }

    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    const maxSize = 5 * 1024 * 1024;

    for (const file of [identityFile, landFile]) {
        if (!allowedTypes.includes(file.type)) {
            alert('Only PDF, JPG and PNG documents are accepted.');
            return false;
        }

        if (file.size > maxSize) {
            alert('Each uploaded document must be 5 MB or smaller.');
            return false;
        }
    }

    return true;
}

function continueFromFarmerStep() {
    if (farmerPath === 'registered') {
        alert('Registered farmer verification is temporarily unavailable because the Department API has not yet been connected. Please select the unregistered farmer option.');
        return;
    }

    if (!validateUnregisteredFarmer()) return;
    nextStep(4);
}

function populateApplicationSummary() {
    const bookingTypeEl = document.querySelector('input[name="booking_type"]:checked');
    const bookingType = bookingTypeEl ? bookingTypeEl.value : 'free_minikit';

    const setVal = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.innerText = val;
    };

    setVal('summary-category', bookingType === 'free_minikit' ? 'Free Seed Minikit' : '50% Subsidized Certified Seed');
    setVal('summary-district', document.getElementById('select-district')?.value || '');
    setVal('summary-season', document.getElementById('select-season')?.value || '');
    setVal('summary-crop', document.getElementById('select-crop')?.value || '');
    setVal('summary-farmer', document.getElementById('new-farmer-name')?.value.trim() || '');
    setVal('summary-farmer-district', document.getElementById('farmer-district')?.value || '');
    setVal('summary-tehsil', document.getElementById('farmer-tehsil')?.value || '');
    setVal('summary-village', document.getElementById('farmer-village')?.value || '');
    setVal('summary-survey', document.getElementById('land-survey-number')?.value.trim() || '');
    setVal('summary-landmark', document.getElementById('farmer-landmark')?.value.trim() || 'Not provided');
    setVal('summary-post-office', document.getElementById('farmer-post-office')?.value || '');
    setVal('summary-pincode', document.getElementById('farmer-pincode')?.value || '');
}

function nextStep(step) {
    if (step < 1 || step > 5) return;

    if (step === 4 && farmerPath === 'registered') {
        alert('Registered farmer verification is currently unavailable. Please select the unregistered farmer option.');
        return;
    }

    if (step === 5 && !validateUnregisteredFarmer()) {
        return;
    }

    const currentStepEl = document.getElementById(`step-${currentStep}`);
    const nextStepEl = document.getElementById(`step-${step}`);

    if (currentStepEl) currentStepEl.classList.add('hidden');
    if (nextStepEl) nextStepEl.classList.remove('hidden');

    currentStep = step;
    updateProgressBar(step);

    if (step === 4) {
        populateApplicationSummary();
    }

    if (step === 3) {
        const selected = document.querySelector('input[name="farmer_path"]:checked');
        selectFarmerPath(selected ? selected.value : 'registered');
    }

    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
}

async function submitBooking() {
    const errorBox = document.getElementById('submit-error');
    const submitButton = document.getElementById('submit-booking-button');

    if (errorBox) {
        errorBox.classList.add('hidden');
        errorBox.textContent = '';
    }

    const declaration = document.getElementById('declaration');
    if (declaration && !declaration.checked) {
        if (errorBox) {
            errorBox.textContent = 'Please accept the declaration before submitting.';
            errorBox.classList.remove('hidden');
        }
        return;
    }

    if (farmerPath === 'registered') {
        if (errorBox) {
            errorBox.textContent = 'Registered farmer verification is unavailable. Please select the unregistered farmer option.';
            errorBox.classList.remove('hidden');
        }
        return;
    }

    if (!validateUnregisteredFarmer()) return;

    const formData = new FormData();
    const bookingTypeEl = document.querySelector('input[name="booking_type"]:checked');

    formData.append('action', 'submit_booking');
    formData.append('booking_type', bookingTypeEl ? bookingTypeEl.value : 'free_minikit');
    formData.append('district', document.getElementById('select-district')?.value || '');
    formData.append('season', document.getElementById('select-season')?.value || '');
    formData.append('crop', document.getElementById('select-crop')?.value || '');

    formData.append('farmer_path', 'unregistered');
    formData.append('farmer_name', document.getElementById('new-farmer-name')?.value.trim() || '');
    formData.append('id_card_type', document.getElementById('new-farmer-id-type')?.value || '');
    formData.append('id_card_number', document.getElementById('new-farmer-id-number')?.value.trim() || '');

    formData.append('farmer_location', getFarmerAddress());
    formData.append('farmer_district', document.getElementById('farmer-district')?.value || '');
    formData.append('farmer_area', '');

    formData.append('farmer_tehsil', document.getElementById('farmer-tehsil')?.value || '');
    formData.append('farmer_village', document.getElementById('farmer-village')?.value || '');
    formData.append('land_survey_number', document.getElementById('land-survey-number')?.value.trim() || '');
    formData.append('landmark', document.getElementById('farmer-landmark')?.value.trim() || '');
    formData.append('post_office', document.getElementById('farmer-post-office')?.value || '');
    formData.append('pincode', document.getElementById('farmer-pincode')?.value || '');

    const idDoc = document.getElementById('farmer-id-document')?.files[0];
    const landRec = document.getElementById('farmer-land-record')?.files[0];

    if (idDoc) formData.append('identity_document', idDoc);
    if (landRec) formData.append('land_record', landRec);

    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
    }

    try {
        const response = await fetch('process_booking.php', {
            method: 'POST',
            body: formData,
            headers: {'Accept': 'application/json'}
        });

        let data;
        try {
            data = await response.json();
        } catch (error) {
            throw new Error('The server returned an invalid response. Please contact the portal administrator.');
        }

        if (response.ok && data.status === 'success') {
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.innerText = val;
            };

            setVal('rec-id', data.application_no || '');
            setVal('rec-district', document.getElementById('select-district')?.value || '');
            setVal('rec-crop', document.getElementById('select-crop')?.value || '');
            setVal('rec-farmer', document.getElementById('new-farmer-name')?.value.trim() || '');
            setVal('rec-depot', `Zonal Office, ${document.getElementById('select-district')?.value || ''}`);
            setVal('rec-status', 'Submitted');

            const receiptModal = document.getElementById('receipt-modal');
            if (receiptModal) receiptModal.classList.remove('hidden');

            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        } else {
            if (errorBox) {
                errorBox.textContent = data.message || 'The application could not be submitted. Please try again.';
                errorBox.classList.remove('hidden');
            }
        }

    } catch (error) {
        console.error('Application submission error:', error);
        if (errorBox) {
            errorBox.textContent = error.message || 'Unable to connect to the application server. Please try again.';
            errorBox.classList.remove('hidden');
        }
    } finally {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Submit Application <i data-lucide="send" class="w-4 h-4"></i>';
        }
        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    }
}

function closeModal() {
    const receiptModal = document.getElementById('receipt-modal');
    if (receiptModal) receiptModal.classList.add('hidden');

    const declaration = document.getElementById('declaration');
    if (declaration) declaration.checked = false;

    const setVal = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val;
    };

    setVal('new-farmer-name', '');
    setVal('new-farmer-id-type', '');
    setVal('new-farmer-id-number', '');
    setVal('farmer-district', '');
    setVal('land-survey-number', '');
    setVal('farmer-landmark', '');
    setVal('farmer-id-document', '');
    setVal('farmer-land-record', '');
    setVal('farmer-pincode', '');

    invalidateLocationRequest('farmer-tehsil');
    invalidateLocationRequest('farmer-village');
    invalidateLocationRequest('farmer-post-office');

    resetLocationSelect('farmer-tehsil', 'Select district first');
    resetLocationSelect('farmer-village', 'Select tehsil first');
    resetLocationSelect('farmer-post-office', 'Select village first');

    clearLocationError();
    farmerPath = 'registered';

    const regRadio = document.querySelector('input[name="farmer_path"][value="registered"]');
    if (regRadio) regRadio.checked = true;

    currentStep = 1;

    document.querySelectorAll('[id^="step-"]').forEach(element => {
        element.classList.add('hidden');
    });

    const step1 = document.getElementById('step-1');
    if (step1) step1.classList.remove('hidden');

    updateProgressBar(1);
    selectFarmerPath('registered');

    const submitError = document.getElementById('submit-error');
    if (submitError) {
        submitError.classList.add('hidden');
        submitError.textContent = '';
    }

    const loading = document.getElementById('location-loading');
    if (loading) loading.classList.add('hidden');
}

async function checkApplicationStatus(event) {
    if (event) event.preventDefault();

    const method = document.getElementById('status-search-method')?.value;
    const messageBox = document.getElementById('status-lookup-message');
    const resultBox = document.getElementById('status-lookup-result');
    const button = document.getElementById('status-check-button');
    const formData = new FormData();

    formData.append('action', 'check_application_status');

    if (method === 'application') {
        const applicationNo = document.getElementById('status-application-number')?.value.trim();
        if (!applicationNo) {
            if (messageBox) {
                messageBox.textContent = 'Please enter your application number.';
                messageBox.className = 'mt-4 rounded-lg px-4 py-3 text-sm bg-amber-50 border border-amber-200 text-amber-800';
                messageBox.classList.remove('hidden');
            }
            if (resultBox) resultBox.classList.add('hidden');
            return;
        }
        formData.append('application_no', applicationNo);
    } else {
        const idType = document.getElementById('status-id-type')?.value;
        const idNumber = document.getElementById('status-id-number')?.value.trim();
        if (!idType || !idNumber) {
            if (messageBox) {
                messageBox.textContent = 'Please select your identity card type and enter its number.';
                messageBox.className = 'mt-4 rounded-lg px-4 py-3 text-sm bg-amber-50 border border-amber-200 text-amber-800';
                messageBox.classList.remove('hidden');
            }
            if (resultBox) resultBox.classList.add('hidden');
            return;
        }
        formData.append('id_card_type', idType);
        formData.append('id_card_number', idNumber);
    }

    if (button) {
        button.disabled = true;
        button.innerHTML = '<i data-lucide="loader-circle" class="w-4 h-4 animate-spin"></i> Checking...';
    }
    if (messageBox) messageBox.classList.add('hidden');
    if (resultBox) resultBox.classList.add('hidden');

    try {
        const response = await fetch('process_booking.php', {
            method: 'POST',
            body: formData,
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();

        if (data.status === 'success' && data.application) {
            const app = data.application;
            const setText = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            setText('status-result-number', app.application_no || '—');
            setText('status-result-main', app.application_status || 'Status unavailable');
            setText('status-result-category', app.booking_type === 'free_minikit' ? 'Free Seed Minikit' : (app.booking_type === 'subsidized_seed' ? '50% Subsidized Certified Seed' : app.booking_type));
            setText('status-result-crop', app.crop || '—');
            setText('status-result-district', app.district || '—');
            setText('status-result-season', app.season || '—');
            setText('status-result-docs', app.document_review_status || 'Not updated');
            setText('status-result-doc-note', app.document_review_note || '');
            setText('status-result-delivery', app.seed_delivery_status || 'Not updated');
            setText('status-result-delivery-note', app.seed_delivery_note || '');
            setText('status-result-date', app.submitted_at ? new Date(app.submitted_at.replace(' ', 'T')).toLocaleString('en-IN') : '—');

            if (resultBox) resultBox.classList.remove('hidden');
            if (messageBox) messageBox.classList.add('hidden');
        } else {
            if (messageBox) {
                messageBox.textContent = data.message || 'No application status could be retrieved.';
                messageBox.className = 'mt-4 rounded-lg px-4 py-3 text-sm bg-amber-50 border border-amber-200 text-amber-800';
                messageBox.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Application status lookup error:', error);
        if (messageBox) {
            messageBox.textContent = 'Unable to connect to the application server. Please try again.';
            messageBox.className = 'mt-4 rounded-lg px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-700';
            messageBox.classList.remove('hidden');
        }
    } finally {
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i data-lucide="search" class="w-4 h-4"></i> Check Status';
        }
        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    }
}

function printReceipt() {
    window.print();
}

document.addEventListener('DOMContentLoaded', function () {
    filterCSC();

    const addEv = (id, event, fn) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener(event, fn);
    };

    addEv('farmer-district', 'change', onFarmerDistrictChange);
    addEv('farmer-tehsil', 'change', onFarmerTehsilChange);
    addEv('farmer-village', 'change', onFarmerVillageChange);
    addEv('farmer-post-office', 'change', onPostOfficeChange);
    addEv('status-search-method', 'change', toggleStatusSearchMethod);
    toggleStatusSearchMethod();
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
});
</script>

</body>
</html>