<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    x-init="$watch('darkMode', val => { localStorage.setItem('theme', val ? 'dark' : 'light') })"
    :class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="POS System">

    <title>{{ config('app.name', 'POS System') }} — @yield('title', 'Dashboard')</title>

    <!-- Google Fonts: Inter + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Prevent flash of wrong theme -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-slate-950 text-gray-900 dark:text-slate-100 antialiased font-sans min-h-screen transition-colors duration-300">
    {{-- Supports both Livewire $slot and Blade @yield --}}
    {{ $slot ?? '' }}
    @yield('content')

    @livewireScripts
    @stack('scripts')
</body>
</html>
