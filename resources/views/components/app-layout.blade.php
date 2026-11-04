@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <title>{{ $title ? $title . ' — ' : '' }}{{ setting('site_name', 'StarCliqs') }} Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // StarCliqs gold, from the logo.
                        primary: {
                            50: '#fdf8ec', 100: '#fbefd0', 200: '#f6dc9c', 300: '#f1c868', 400: '#e8b03d',
                            500: '#d99a1e', 600: '#bd7f12', 700: '#9a6311', 800: '#7d4f14', 900: '#674115',
                        },
                    },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Tailwind's CDN build has no forms plugin, so fields get no border unless asked. Give every
           field a 1px border by default; utilities such as border-0 still win (low specificity). */
        body :where(input:not([type=checkbox]):not([type=radio]):not([type=file]):not([type=range]):not([type=hidden]):not([type=submit]):not([type=button]), select, textarea) {
            border-width: 1px;
            padding: .55rem .75rem;
        }

        .gradient-bg { background: linear-gradient(135deg, #f7d26a 0%, #d99a1e 45%, #a86f08 100%); }

        /* Desktop sidebar collapse (icon-only rail). Scoped to md+ so the mobile overlay always shows labels. */
        @media (min-width: 768px) {
            .sidebar-collapsed nav a > span { display: none; }
            .sidebar-collapsed nav a { justify-content: center; padding-left: .5rem; padding-right: .5rem; }
            .sidebar-collapsed nav .nav-heading { height: 1px; padding: 0; margin: .75rem .5rem; overflow: hidden; color: transparent; background-color: #334155; }
        }
        .sidebar-scroll { scrollbar-width: thin; scrollbar-color: #334155 transparent; }
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    @php
        $unread = \App\Models\Enquiry::whereNull('read_at')->count();
    @endphp
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" class="min-h-screen flex">
        <!-- Mobile backdrop -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 md:hidden"></div>

        <!-- Sidebar -->
        <aside
            x-cloak
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'md:w-20': sidebarCollapsed,
                'md:w-64': !sidebarCollapsed,
                'sidebar-collapsed': sidebarCollapsed,
            }"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 shadow-lg transform transition-all duration-300 ease-in-out md:translate-x-0 md:sticky md:top-0 md:h-screen md:shadow-none flex-shrink-0 flex flex-col"
        >
            <button
                @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                class="hidden md:flex absolute -right-3 top-20 w-6 h-6 items-center justify-center rounded-full bg-slate-800 border border-slate-600 shadow-sm text-slate-300 hover:text-white hover:border-primary-400 transition-colors z-10"
                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
            >
                <i class="fas text-[10px]" :class="sidebarCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
            </button>

            <!-- Logo: white badge so the dark "CliQs" lettering stays readable on the slate sidebar -->
            <a href="{{ route('admin.dashboard') }}" class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-800 px-4 overflow-hidden" :class="sidebarCollapsed ? 'md:justify-center' : ''">
                <span x-show="!sidebarCollapsed" class="rounded-lg bg-white px-2.5 py-1.5"><img src="{{ asset('images/logo.webp') }}" alt="{{ setting('site_name', 'StarCliqs') }}" class="h-7 w-auto"></span>
                <img x-show="sidebarCollapsed" x-cloak src="{{ asset('images/favicon.png') }}" alt="" class="h-9 w-9">
            </a>

            <nav class="sidebar-scroll flex-1 space-y-1 overflow-y-auto p-4">
                <x-nav-link :href="route('admin.dashboard')" icon="fa-gauge" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>

                <x-nav-heading>Website content</x-nav-heading>
                <x-nav-link :href="route('admin.services.index')" icon="fa-screwdriver-wrench" :active="request()->routeIs('admin.services.*')">Services</x-nav-link>
                <x-nav-link :href="route('admin.products.index')" icon="fa-cubes" :active="request()->routeIs('admin.products.*')">Products</x-nav-link>
                <x-nav-link :href="route('admin.projects.index')" icon="fa-briefcase" :active="request()->routeIs('admin.projects.*')">Projects</x-nav-link>
                <x-nav-link :href="route('admin.trainings.index')" icon="fa-graduation-cap" :active="request()->routeIs('admin.trainings.*')">Academy programmes</x-nav-link>
                <x-nav-link :href="route('admin.clients.index')" icon="fa-handshake" :active="request()->routeIs('admin.clients.*')">Clients</x-nav-link>
                <x-nav-link :href="route('admin.testimonials.index')" icon="fa-quote-left" :active="request()->routeIs('admin.testimonials.*')">Testimonials</x-nav-link>
                <x-nav-link :href="route('admin.posts.index')" icon="fa-newspaper" :active="request()->routeIs('admin.posts.*')">Blog</x-nav-link>
                <x-nav-link :href="route('admin.faqs.index')" icon="fa-circle-question" :active="request()->routeIs('admin.faqs.*')">FAQs</x-nav-link>

                <x-nav-heading>Audience</x-nav-heading>
                <a href="{{ route('admin.enquiries.index') }}"
                   class="flex items-center gap-3 px-4 py-3 transition-all duration-200 rounded-lg {{ request()->routeIs('admin.enquiries.*') ? 'bg-primary-600 text-white font-medium shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-inbox w-5 {{ request()->routeIs('admin.enquiries.*') ? '' : 'text-slate-400' }}"></i>
                    <span class="flex flex-1 items-center justify-between">Messages
                        @if($unread)<span class="rounded-full bg-red-500 px-2 py-0.5 text-[11px] font-semibold text-white">{{ $unread }}</span>@endif
                    </span>
                </a>
                <x-nav-link :href="route('admin.visitors.index')" icon="fa-chart-line" :active="request()->routeIs('admin.visitors.*')">Visitors</x-nav-link>
                <x-nav-link :href="route('admin.subscribers.index')" icon="fa-envelope-open-text" :active="request()->routeIs('admin.subscribers.*')">Subscribers</x-nav-link>

                <x-nav-heading>System</x-nav-heading>
                <x-nav-link :href="route('admin.settings.edit')" icon="fa-sliders" :active="request()->routeIs('admin.settings.*')">Site settings</x-nav-link>
                <x-nav-link :href="route('admin.profile')" icon="fa-user-shield" :active="request()->routeIs('admin.profile')">Account & admins</x-nav-link>
            </nav>

            <div class="shrink-0 border-t border-slate-800 p-3">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-lg px-2 py-2 text-slate-300 transition hover:bg-slate-800 hover:text-white" :class="sidebarCollapsed ? 'md:justify-center' : ''">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full gradient-bg text-sm text-slate-900"><i class="fas fa-arrow-up-right-from-square"></i></span>
                    <span class="min-w-0" x-show="!sidebarCollapsed" x-cloak>
                        <span class="block truncate text-sm font-medium text-white">View website</span>
                        <span class="block truncate text-xs text-slate-400">Opens in a new tab</span>
                    </span>
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-h-screen min-w-0">
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between h-14 px-4 md:px-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 md:hidden focus:outline-none" aria-label="Open menu">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="font-semibold text-gray-800 truncate">{{ $title ?? 'Dashboard' }}</div>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-sm font-semibold text-primary-700">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            <i class="hidden md:block fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 z-30 w-48 mt-2 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="p-2">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md"><i class="fas fa-user-circle mr-2"></i> Profile</a>
                                <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md"><i class="fas fa-cog mr-2"></i> Settings</a>
                                <hr class="my-1 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md"><i class="fas fa-sign-out-alt mr-2"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 flex flex-col min-w-0 px-4 md:px-6 py-4 space-y-4">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="p-3 bg-green-50 border-l-4 border-green-500 text-green-700 flex justify-between items-center rounded">
                        <div><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
                        <button @click="show = false" class="text-green-700 hover:text-green-900"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700 flex justify-between items-center rounded">
                        <div><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                        <p class="font-medium"><i class="fas fa-exclamation-circle mr-2"></i>Please fix the highlighted fields.</p>
                    </div>
                @endif

                @isset($header)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-3 border-b border-gray-200">{{ $header }}</div>
                    </div>
                @endisset

                {{ $slot }}
            </main>

            <footer class="py-4 px-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ setting('site_name', 'StarCliqs') }}. Website administration.</p>
            </footer>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
