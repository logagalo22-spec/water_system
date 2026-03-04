<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Water System' }}</title>

    <!-- Tailwind CSS (assuming Vite is used in this app) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#ecf0f5] font-sans antialiased text-[#333]">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-[#3498db] text-white flex-shrink-0 flex flex-col">
            <div class="h-16 flex items-center justify-center font-bold text-xl border-b border-[#2980b9] bg-[#2980b9]">
                Water System
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('dashboard') }}" class="block px-6 py-3 text-white hover:bg-[#2980b9] {{ request()->routeIs('dashboard') ? 'bg-[#2980b9]' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('customers.index') }}" class="block px-6 py-3 text-white hover:bg-[#2980b9] {{ request()->routeIs('customers.*') ? 'bg-[#2980b9]' : '' }}">
                    Customers
                </a>
                <a href="{{ route('billing.index') }}" class="block px-6 py-3 text-white hover:bg-[#2980b9] {{ request()->routeIs('billing.*') ? 'bg-[#2980b9]' : '' }}">
                    Billing Reports
                </a>
                <a href="{{ route('settings.index') }}" class="block px-6 py-3 text-white hover:bg-[#2980b9] {{ request()->routeIs('settings.*') ? 'bg-[#2980b9]' : '' }}">
                    Settings
                </a>
                <a href="{{ route('recovery.index') }}" class="block px-6 py-3 text-white hover:bg-[#2980b9] {{ request()->routeIs('recovery.*') ? 'bg-[#2980b9]' : '' }}">
                    Recovery / Trash
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-left px-6 py-3 text-white hover:bg-[#2980b9]">
                        Log out
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
