<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sistem RTLH') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Vite Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <!-- Navbar (Meraki UI Style) -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-4">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex items-center justify-between">
                    <div class="text-xl font-semibold text-gray-700">
                        <a class="text-amalfi font-bold text-2xl hover:text-gray-700" href="/">Perkimtan Palu</a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex md:hidden">
                        <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="toggle menu">
                            <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current">
                                <path fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="hidden -mx-4 md:flex md:items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block mx-4 mt-2 text-sm text-gray-700 capitalize md:mt-0 hover:text-amalfi">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="block mx-4 mt-2 text-sm text-gray-700 capitalize md:mt-0 hover:text-red-500">Logout</button>
                        </form>
                    @else
                        <a href="/" class="block mx-4 mt-2 text-sm text-gray-700 capitalize md:mt-0 hover:text-amalfi">Beranda</a>
                        <a href="#" class="block mx-4 mt-2 text-sm text-gray-700 capitalize md:mt-0 hover:text-amalfi">Simulasi Kelayakan</a>
                        <a href="{{ route('login') }}" class="block mx-4 mt-2 text-sm text-amalfi font-bold capitalize md:mt-0 hover:text-blue-600">Login Petugas</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container mx-auto px-6 py-8">
        @if(session('success'))
            <div class="flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-md mb-6">
                <div class="flex items-center justify-center w-12 bg-green-500">
                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                    </svg>
                </div>
                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-green-500">Sukses</span>
                        <p class="text-sm text-gray-600">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-md mb-6">
                <div class="flex items-center justify-center w-12 bg-red-500">
                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
                    </svg>
                </div>
                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-red-500">Error</span>
                        <p class="text-sm text-gray-600">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white">
        <div class="container px-6 py-8 mx-auto text-center">
            <p class="text-gray-500 mt-8 sm:mt-0">Dinas Perumahan dan Kawasan Permukiman Kota Palu © 2026. All rights reserved.</p>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
