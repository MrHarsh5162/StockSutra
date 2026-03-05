<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StockSutra') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col md:flex-row h-screen overflow-hidden">
    
    <!-- Sidebar Component (Hidden on mobile, Bottom nav replaces it) -->
    @include('admin.layouts.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col relative overflow-hidden">
        <!-- Top Navigation / Header -->
        @include('admin.layouts.header')
        
        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-6 text-gray-900 min-h-0 overflow-y-auto w-full mx-auto pb-24 md:pb-6">
            @yield('content')
        </main>
    </div>

    @livewireScripts
</body>
</html>
