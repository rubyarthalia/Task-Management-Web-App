<!DOCTYPE html>
<html lang="en" x-data="themeManager()" :class="{ 'dark': isDark }" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Task Manager') — Task Manager</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Page transition - hides white flash on reload */
        body {
            opacity: 1;
            transition: opacity 0.15s ease;
        }
        body.is-leaving {
            opacity: 0;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 999px; }
        .dark ::-webkit-scrollbar-thumb { background: #3730a3; }

        /* Toast animation */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(120%); opacity: 0; }
        }
        .toast-enter { animation: slideIn 0.35s ease forwards; }
        .toast-leave { animation: slideOut 0.35s ease forwards; }

        /* Page fade-in */
        .page-content { animation: fadeUp 0.4s ease both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="h-full bg-slate-50 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100">

{{-- ===================== LAYOUT WRAPPER ===================== --}}
<div class="flex h-full min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- ===== SIDEBAR ===== --}}
    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"
    ></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed z-30 flex h-full w-64 flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out dark:border-slate-800 dark:bg-slate-900 lg:static lg:z-auto"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 dark:border-slate-800">
            <a href="{{ route('tasks.index') }}" class="font-display text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                Task Manager
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 space-y-1 px-3 py-4">
            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium
                      {{ request()->routeIs('tasks.index') ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                All Tasks
            </a>
            <a href="{{ route('tasks.create') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium
                      {{ request()->routeIs('tasks.create') ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Task
            </a>
        </nav>

        {{-- Dark mode toggle --}}
        <div class="border-t border-slate-100 px-4 py-4 dark:border-slate-800">
            <button
                @click="toggle()"
                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
            >
                <span x-show="!isDark">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </span>
                <span x-show="isDark" x-cloak>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </span>
                <span x-text="isDark ? 'Light Mode' : 'Dark Mode'"></span>
            </button>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex flex-1 flex-col min-w-0">

        {{-- Top bar (mobile) --}}
        <header class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-200 bg-white/80 px-4 py-3 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 lg:hidden">
            <button @click="sidebarOpen = true" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-display text-lg font-bold text-slate-900 dark:text-white">Task Manager</span>
            <div class="w-9"></div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</div>

{{-- ===================== TOAST NOTIFICATION ===================== --}}
@if (session('success') || session('error'))
<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 3500)"
    x-show="show"
    x-cloak
    :class="show ? 'toast-enter' : 'toast-leave'"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl px-5 py-4 shadow-2xl
           {{ session('success') ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}"
>
    @if(session('success'))
        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    @else
        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    @endif
</div>
@endif

{{-- ===================== ALPINE THEME MANAGER ===================== --}}
<script>
    function themeManager() {
        return {
            isDark: localStorage.getItem('theme') === 'dark' ||
                    (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggle() {
                this.isDark = !this.isDark;
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            }
        }
    }
</script>

{{-- ===================== PAGE TRANSITION ===================== --}}
<script>
    // Fade out before any navigation or form submit to hide white flash
    document.addEventListener('DOMContentLoaded', () => {
        // Fade in on load
        document.body.style.opacity = '0';
        requestAnimationFrame(() => {
            document.body.style.transition = 'opacity 0.15s ease';
            document.body.style.opacity = '1';
        });

        // Fade out before page unloads
        document.addEventListener('submit', () => {
            document.body.classList.add('is-leaving');
        });

        window.addEventListener('beforeunload', () => {
            document.body.classList.add('is-leaving');
        });
    });
</script>

</body>
</html>