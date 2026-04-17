<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – Perkimtan Kota Palu</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @stack('head')
    <style>
        /* ===== SIDEBAR TRANSITIONS ===== */
        #sidebar { transition: transform 0.3s cubic-bezier(.4,0,.2,1); }
        #sidebar-overlay { transition: opacity 0.3s ease; }
        .sidebar-item { transition: background 0.18s, color 0.18s, padding-left 0.18s; }
        .sidebar-item:hover { padding-left: 1.25rem; }
        .sidebar-item.active { background: rgba(255,255,255,0.2); color: white; font-weight: 600; border-left: 3px solid #FFA62B; }

        /* ===== REVEAL ANIMATION ===== */
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ===== FLASH AUTO FADE ===== */
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .flash { animation: slideDown 0.3s ease; }

        /* ===== PAGE FADE ===== */
        main { animation: pageFade 0.35s ease; }
        @keyframes pageFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== MOBILE SIDEBAR ===== */
        @media (max-width: 767px) {
            #sidebar { transform: translateX(-100%); position: fixed; z-index: 50; }
            #sidebar.mobile-open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen">

{{-- MOBILE SIDEBAR OVERLAY --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

{{-- SIDEBAR --}}
<aside id="sidebar" class="w-64 min-h-screen bg-amalfi text-white flex flex-col fixed left-0 top-0 z-50 shadow-xl">
    {{-- Logo --}}
    <div class="p-5 border-b border-white/20 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h3a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
            </div>
            <div>
                <span class="font-bold leading-tight block text-sm">Perkimtan</span>
                <span class="text-xs text-white/60">Kota Palu</span>
            </div>
        </a>
        {{-- Close btn mobile --}}
        <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg hover:bg-white/10 transition text-white/70">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- User Info --}}
    <div class="px-4 py-3 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-citrus flex items-center justify-center text-white font-bold text-xs shadow">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <p class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</p>
                <span class="text-xs text-white/50 capitalize">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 space-y-0.5">
        @if(auth()->user()->role === 'admin')
        <div class="px-4 mb-2 pt-2 text-xs text-white/40 uppercase tracking-widest">Menu Utama</div>
        @foreach([
            ['admin.dashboard','Dashboard','M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z','admin.dashboard'],
            ['admin.data.index','Validasi Data','M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','admin.data.*'],
            ['admin.pengguna.index','Kelola Pengguna','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','admin.pengguna.*'],
            ['admin.mlops.index','MLOps – Model','M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18','admin.mlops.*'],
            ['admin.berita.index','CMS Berita','M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','admin.berita.*'],
            ['admin.audit.index','Audit Trail','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','admin.audit.*'],
        ] as [$route, $label, $icon, $pattern])
        <a href="{{ route($route) }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-white/80 hover:bg-white/10 rounded-lg mx-2 {{ request()->routeIs($pattern) ? 'active' : '' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" /></svg>
            {{ $label }}
        </a>
        @endforeach
        @else
        <div class="px-4 mb-2 pt-2 text-xs text-white/40 uppercase tracking-widest">Menu</div>
        @foreach([
            ['pendata.dashboard','Dashboard','M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z','pendata.dashboard'],
            ['pendata.survei.create','Input Survei Baru','M12 4v16m8-8H4',''],
            ['pendata.survei.index','Data Saya','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','pendata.survei.*'],
        ] as [$route, $label, $icon, $pattern])
        <a href="{{ route($route) }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-white/80 hover:bg-white/10 rounded-lg mx-2 {{ $pattern && request()->routeIs($pattern) ? 'active' : '' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" /></svg>
            {{ $label }}
        </a>
        @endforeach
        @endif
    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-white/20">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-white/70 hover:bg-white/10 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar / Logout
            </button>
        </form>
    </div>
</aside>

{{-- MAIN CONTENT AREA --}}
<div id="main-content" class="md:ml-64 flex-1 flex flex-col min-h-screen">
    {{-- Top Header --}}
    <header class="bg-white border-b border-gray-100 px-4 sm:px-8 py-4 flex items-center justify-between shadow-sm sticky top-0 z-20">
        <div class="flex items-center gap-4">
            {{-- Hamburger (mobile only) --}}
            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg sm:text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="flex items-center gap-3 sm:gap-4">
            <span class="text-sm text-gray-400 hidden sm:inline">{{ now()->isoFormat('D MMM Y') }}</span>
            <a href="{{ route('home') }}" target="_blank" class="text-xs sm:text-sm text-amalfi hover:underline whitespace-nowrap">Portal Publik →</a>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('info'))
    <div class="px-4 sm:px-8 pt-4 space-y-2">
        @if(session('success'))
            <div class="flash rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center gap-2">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-center gap-2">❌ {{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="flash rounded-xl bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-700 flex items-center gap-2">ℹ️ {{ session('info') }}</div>
        @endif
    </div>
    @endif

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>
</div>

@stack('scripts')

<script>
// ── Mobile Sidebar Toggle ──────────────────────────
function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const isOpen   = sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('hidden', !isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
}

// ── Scroll Reveal ──────────────────────────────────
const revealEls = document.querySelectorAll('.reveal');
const obs = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) { setTimeout(() => e.target.classList.add('visible'), i * 60); obs.unobserve(e.target); }
    });
}, { threshold: 0.1 });
revealEls.forEach(el => obs.observe(el));

// ── Auto-dismiss flash ─────────────────────────────
document.querySelectorAll('.flash').forEach(el => {
    setTimeout(() => { el.style.transition = 'opacity 0.4s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 5000);
});

// ── Sidebar active route highlight ────────────────
document.querySelectorAll('#sidebar .sidebar-item').forEach(a => {
    if (a.href && window.location.href.startsWith(a.href) && a.href !== window.location.origin + '/') {
        a.classList.add('active');
    }
});
</script>
</body>
</html>
