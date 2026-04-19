<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website resmi Dinas Perumahan dan Kawasan Permukiman Kota Palu. Sistem Informasi Prediksi Kelayakan Bantuan RTLH.">
    <title>@yield('title', 'Sistem RTLH') – Perkimtan Kota Palu</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @stack('head')
    <style>
        /* ===== SCROLL REVEAL ANIMATIONS ===== */
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.65s cubic-bezier(.4,0,.2,1), transform 0.65s cubic-bezier(.4,0,.2,1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-left { opacity: 0; transform: translateX(-30px); transition: opacity 0.65s cubic-bezier(.4,0,.2,1), transform 0.65s cubic-bezier(.4,0,.2,1); }
        .reveal-left.visible { opacity: 1; transform: translateX(0); }
        .reveal-scale { opacity: 0; transform: scale(0.95); transition: opacity 0.55s ease, transform 0.55s ease; }
        .reveal-scale.visible { opacity: 1; transform: scale(1); }

        /* ===== NAV ACTIVE HIGHLIGHT ===== */
        nav a.nav-active { color: #2E5AA7; font-weight: 700; }
        nav a.nav-active::after { content: ''; display: block; height: 2px; background: #2E5AA7; border-radius: 4px; margin-top: 1px; }

        /* ===== SMOOTH PAGE TRANSITIONS ===== */
        main { animation: fadeInPage 0.4s ease forwards; }
        @keyframes fadeInPage { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== COUNTER ANIMATION ===== */
        .count-anim { transition: all 0.3s; }

        /* ===== MOBILE MENU ===== */
        #mobile-menu { transition: max-height 0.35s cubic-bezier(.4,0,.2,1), opacity 0.25s ease; max-height: 0; opacity: 0; overflow: hidden; }
        #mobile-menu.open { max-height: 500px; opacity: 1; }

        /* ===== FLOATING ANIMATION (hero) ===== */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .float-anim { animation: float 4s ease-in-out infinite; }

        /* ===== PULSE BADGE ===== */
        @keyframes pulse-ring { 0% { transform: scale(1); opacity: 0.8; } 80%, 100% { transform: scale(1.6); opacity: 0; } }
        .pulse-ring::before { content: ''; position: absolute; inset: -4px; border-radius: inherit; background: currentColor; animation: pulse-ring 1.5s ease-out infinite; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

{{-- NAV PUBLIC --}}
<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-amalfi flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/dinas_pu.png') }}" alt="Logo" class="w-5 h-5 text-white">
                </div>
                <div>
                    <span class="font-bold text-amalfi text-base leading-tight block">Perkimtan</span>
                    <span class="text-xs text-gray-400 leading-none">Kota Palu</span>
                </div>
            </a>

            {{-- DESKTOP NAV --}}
            <div class="hidden md:flex items-center gap-1 text-sm font-medium">
                @php $navLinks = [['home','Beranda'],['profil','Profil'],['prosedur','Prosedur'],['statistik','Statistik'],['simulasi.index','Simulasi'],['berita.index','Berita'],['faq','FAQ']]; @endphp
                @foreach($navLinks as [$route, $label])
                <a href="{{ route($route) }}" class="px-3 py-2 rounded-lg text-gray-600 hover:text-amalfi hover:bg-amalfi/5 transition-all duration-200 {{ request()->routeIs($route) ? 'text-amalfi bg-amalfi/5 font-semibold' : '' }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-amalfi text-white text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-amalfi text-amalfi text-sm font-semibold hover:bg-amalfi hover:text-white transition">Login</a>
                @endauth

                {{-- HAMBURGER MOBILE --}}
                <button id="nav-toggle" type="button" aria-label="Buka Menu" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition focus:outline-none">
                    <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div id="mobile-menu" class="md:hidden bg-white border-t border-gray-100">
        <div class="px-4 py-3 space-y-1">
            @foreach($navLinks as [$route, $label])
            <a href="{{ route($route) }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs($route) ? 'bg-amalfi text-white' : 'text-gray-700 hover:bg-gray-50' }} transition">{{ $label }}</a>
            @endforeach
            @auth
            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium bg-citrus/10 text-citrus hover:bg-citrus/20 transition mt-2">→ Masuk Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium bg-amalfi/10 text-amalfi hover:bg-amalfi/20 transition mt-2">Login Petugas</a>
            @endauth
        </div>
    </div>
</nav>

{{-- FLASH MESSAGES --}}
@if(session('success') || session('error') || session('info'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-2">
    @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center gap-2 shadow-sm animate-[fadeDown_0.3s_ease]">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="rounded-xl bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-700 flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 4a1 1 0 00-1 1v2a1 1 0 102 0v-2a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('info') }}
        </div>
    @endif
</div>
@endif

<main>
    @yield('content')
</main>

<footer class="bg-amalfi text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <img src="{{ asset('images/dinas_pu.png') }}" alt="Logo" class="w-5 h-5 text-white">
                    </div>
                    <span class="font-bold text-lg">Perkimtan Kota Palu</span>
                </div>
                <p class="text-white/75 text-sm leading-relaxed">Dinas Perumahan dan Kawasan Permukiman Kota Palu. Melayani program bantuan RTLH secara transparan dan akuntabel.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm text-white/75">
                    <li><a href="{{ route('profil') }}" class="hover:text-white transition hover:pl-1 inline-block">Profil Instansi</a></li>
                    <li><a href="{{ route('prosedur') }}" class="hover:text-white transition hover:pl-1 inline-block">Alur & Prosedur</a></li>
                    <li><a href="{{ route('statistik') }}" class="hover:text-white transition hover:pl-1 inline-block">Data & Statistik</a></li>
                    <li><a href="{{ route('simulasi.index') }}" class="hover:text-white transition hover:pl-1 inline-block">Simulasi Kelayakan</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white transition hover:pl-1 inline-block">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Kontak</h4>
                <ul class="space-y-3 text-sm text-white/75">
                    <li class="flex gap-2"><span class="shrink-0">📍</span><span>Jl. Balai Kota, Kota Palu, Sulawesi Tengah</span></li>
                    <li class="flex gap-2"><span class="shrink-0">📞</span><span>(0451) 000-0000</span></li>
                    <li class="flex gap-2"><span class="shrink-0">✉️</span><span>perkimtan@palukota.go.id</span></li>
                    <li class="flex gap-2"><span class="shrink-0">🕐</span><span>Senin–Jumat, 08.00–16.00 WITA</span></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/20 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-white/60">
            <span>© {{ date('Y') }} Dinas Perumahan dan Kawasan Permukiman Kota Palu.</span>
            <span>Sistem RTLH</span>
        </div>
    </div>
</footer>

@stack('scripts')

{{-- ===== GLOBAL SCRIPTS ===== --}}
<script>
// ── Mobile Nav Toggle ──────────────────────────────
const navToggle = document.getElementById('nav-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const iconOpen   = document.getElementById('icon-open');
const iconClose  = document.getElementById('icon-close');
navToggle?.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.toggle('open');
    iconOpen.classList.toggle('hidden', isOpen);
    iconClose.classList.toggle('hidden', !isOpen);
});

// ── Scroll Reveal ──────────────────────────────────
const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-scale');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), i * 80);
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.12 });
revealEls.forEach(el => revealObserver.observe(el));

// ── Counter Animation ──────────────────────────────
function animateCount(el) {
    const target = parseInt(el.getAttribute('data-count'));
    if (!target) return;
    let current = 0;
    const step = Math.max(1, Math.floor(target / 40));
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString('id-ID');
        if (current >= target) clearInterval(timer);
    }, 30);
}
const countEls = document.querySelectorAll('[data-count]');
const countObserver = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { animateCount(e.target); countObserver.unobserve(e.target); } });
}, { threshold: 0.5 });
countEls.forEach(el => countObserver.observe(el));

// ── Active nav highlight ───────────────────────────
document.querySelectorAll('nav a[href]').forEach(a => {
    if (a.href === window.location.href) a.classList.add('nav-active');
});

// ── Auto-dismiss flash messages ────────────────────
document.querySelectorAll('[data-flash]').forEach(el => {
    setTimeout(() => { el.style.transition = 'opacity 0.4s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 4500);
});
</script>
</body>
</html>
