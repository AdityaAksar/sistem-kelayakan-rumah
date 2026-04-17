@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
{{-- HERO SECTION --}}
<section class="relative bg-gradient-to-br from-amalfi via-blue-700 to-blue-900 overflow-hidden min-h-[520px] flex items-center">
    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-citrus/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 w-full flex flex-col md:flex-row items-center gap-10 lg:gap-16">
        {{-- Text side --}}
        <div class="flex-1 text-white reveal-left">
            <span class="inline-block px-3 py-1 bg-citrus/20 text-citrus rounded-full text-xs font-bold uppercase tracking-wider mb-4">Program Bantuan RTLH Kota Palu</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight mb-5">
                Sistem Prediksi<br>Kelayakan Rumah<br><span class="text-citrus">Berbasis Data</span>
            </h1>
            <p class="text-white/80 text-base sm:text-lg leading-relaxed mb-8 max-w-xl">Teknologi Machine Learning untuk mengidentifikasi Rumah Tidak Layak Huni (RTLH) secara akurat, transparan, dan akuntabel demi kesejahteraan masyarakat Kota Palu.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('simulasi.index') }}" class="px-5 sm:px-6 py-3 bg-citrus text-white font-bold rounded-xl hover:bg-orange-400 transition shadow-lg hover:-translate-y-0.5 transform inline-block">Cek Kelayakan Rumah →</a>
                <a href="{{ route('statistik') }}" class="px-5 sm:px-6 py-3 bg-white/15 text-white font-semibold rounded-xl hover:bg-white/25 transition backdrop-blur-sm border border-white/20 inline-block">Lihat Statistik</a>
            </div>
        </div>

        {{-- Stats cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-1 gap-4 w-full sm:w-auto md:w-64 reveal">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 sm:p-5 border border-white/20 hover:bg-white/15 transition">
                <p class="text-white/60 text-xs mb-1">Total Data Survei</p>
                <p class="text-3xl sm:text-4xl font-extrabold text-white" data-count="{{ $totalSurvei }}">0</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 sm:p-5 border border-white/20 hover:bg-white/15 transition">
                <p class="text-white/60 text-xs mb-1">Terindikasi RTLH</p>
                <p class="text-3xl sm:text-4xl font-extrabold text-citrus" data-count="{{ $totalRtlh }}">0</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 sm:p-5 border border-white/20 hover:bg-white/15 transition col-span-2 sm:col-span-1">
                <p class="text-white/60 text-xs mb-1">Teknologi</p>
                <p class="text-base font-bold text-white">Machine Learning AI</p>
                <p class="text-white/50 text-xs mt-0.5">XGBoost / Random Forest</p>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Cara Kerja</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">Alur Program Bantuan RTLH</h2>
            <p class="text-gray-500 mt-2 text-sm max-w-xl mx-auto">Dari pendataan lapangan hingga keputusan penerima bantuan, semua terintegrasi dalam satu sistem.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['01','Pendataan Petugas','Petugas lapangan mengisi form survei 40+ atribut kondisi rumah secara langsung.','bg-amalfi','from-amalfi'],
                ['02','Analisis ML','Data diklasifikasi otomatis oleh algoritma Machine Learning kami.','bg-citrus','from-citrus'],
                ['03','Validasi Admin','Admin memverifikasi hasil prediksi dan memberi keputusan final.','bg-sea','from-sea'],
                ['04','Penyerahan Bantuan','Rumah layak masuk daftar penerima bantuan program RTLH.','bg-green-500','from-green-500'],
            ] as $i => [$num, $title, $desc, $color, $grad])
            <div class="text-center group reveal" style="transition-delay: {{ $i * 100 }}ms">
                <div class="relative w-16 h-16 mx-auto mb-5">
                    <div class="{{ $color }} w-16 h-16 rounded-2xl text-white font-extrabold text-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">{{ $num }}</div>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-base">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- BERITA TERKINI --}}
@if($beritaTerkini->count())
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-3 reveal">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Terbaru</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">Berita & Pengumuman</h2>
            </div>
            <a href="{{ route('berita.index') }}" class="text-sm text-amalfi font-semibold hover:underline whitespace-nowrap">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($beritaTerkini as $i => $berita)
            <a href="{{ route('berita.show', $berita->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition-all duration-300 hover:-translate-y-1 reveal" style="transition-delay: {{ $i * 80 }}ms">
                @if($berita->thumbnail_path)
                    <img src="{{ Storage::disk('public')->url($berita->thumbnail_path) }}" alt="{{ $berita->judul }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-amalfi/10 to-sea/20 flex items-center justify-center">
                        <svg class="w-12 h-12 text-amalfi/20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                    </div>
                @endif
                <div class="p-5">
                    <p class="text-xs text-gray-400 mb-2">{{ $berita->created_at->isoFormat('D MMMM Y') }}</p>
                    <h3 class="font-bold text-gray-900 group-hover:text-amalfi transition-colors leading-snug text-sm sm:text-base">{{ $berita->judul }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA SIMULASI --}}
<section class="py-14 sm:py-16 bg-gradient-to-r from-citrus to-orange-500">
    <div class="max-w-3xl mx-auto px-4 text-center reveal">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-4">Apakah Rumah Anda Layak Huni?</h2>
        <p class="text-white/85 mb-8 text-sm sm:text-base max-w-xl mx-auto">Gunakan fitur simulasi kami untuk mengecek kelayakan hunian rumah Anda berdasarkan kriteria program RTLH secara <strong>gratis dan instan</strong>.</p>
        <a href="{{ route('simulasi.index') }}" class="px-8 py-3.5 bg-white text-citrus font-bold rounded-xl text-base hover:bg-gray-50 transition shadow-lg hover:-translate-y-0.5 transform inline-block">Mulai Simulasi Sekarang →</a>
    </div>
</section>
@endsection
