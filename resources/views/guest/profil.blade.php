@extends('layouts.public')
@section('title', 'Profil Instansi')

@section('content')
<div class="py-16 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Tentang Kami</span>
        <h1 class="text-4xl font-extrabold text-gray-900 mt-2">Profil Instansi & Dasar Hukum</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <h2 class="text-xl font-bold text-amalfi mb-4">Visi</h2>
                <p class="text-gray-600 leading-relaxed">"Terwujudnya Perumahan dan Kawasan Permukiman yang Layak, Sehat, dan Berkelanjutan bagi Seluruh Masyarakat Kota Palu"</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <h2 class="text-xl font-bold text-amalfi mb-4">Misi</h2>
                <ul class="space-y-3 text-gray-600">
                    @foreach([
                        'Meningkatkan kualitas rumah tidak layak huni melalui program bantuan stimulan perumahan swadaya.',
                        'Mengembangkan kawasan permukiman yang tertata, aman, dan berwawasan lingkungan.',
                        'Memperkuat sistem pendataan dan informasi perumahan berbasis teknologi data science.',
                        'Meningkatkan koordinasi antara pemerintah, swasta, dan masyarakat dalam pembangunan perumahan.',
                    ] as $m)
                    <li class="flex gap-3"><span class="text-amalfi font-bold mt-0.5">•</span><span>{{ $m }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <h2 class="text-xl font-bold text-amalfi mb-4">Dasar Hukum</h2>
                <ul class="space-y-3 text-sm text-gray-600">
                    @foreach([
                        'Undang-Undang No. 1 Tahun 2011 tentang Perumahan dan Kawasan Permukiman',
                        'PP No. 14 Tahun 2016 tentang Penyelenggaraan Perumahan dan Kawasan Permukiman',
                        'Peraturan Menteri PUPR No. 7 Tahun 2022 tentang BSPS (Bantuan Stimulan Perumahan Swadaya)',
                        'Peraturan Daerah Kota Palu tentang Program Bantuan RTLH',
                        'SK Walikota Palu tentang Penetapan Kuota Penerima Bantuan RTLH',
                    ] as $hukum)
                    <li class="flex gap-3 items-start">
                        <svg class="w-4 h-4 mt-0.5 text-amalfi shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        <span>{{ $hukum }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-amalfi rounded-2xl p-6 text-white text-center">
                <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <img src="{{ asset('images/dinas_pu.png') }}" alt="Logo">
                </div>
                <h3 class="font-bold text-lg">Dinas Perkimtan</h3>
                <p class="text-white/70 text-sm mt-1">Kota Palu, Sulawesi Tengah</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-sm space-y-4 text-gray-600">
                <h3 class="font-bold text-gray-800">Informasi Kontak</h3>
                <div class="flex gap-3"><span>📍</span><span>Jl. Balai Kota No. 1, Kota Palu</span></div>
                <div class="flex gap-3"><span>📞</span><span>(0451) 401-9143</span></div>
                <div class="flex gap-3"><span>✉️</span><span>perkimtan@palukota.go.id</span></div>
                <div class="flex gap-3"><span>🕐</span><span>Senin – Jumat: 08.00 – 16.00 WITA</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
