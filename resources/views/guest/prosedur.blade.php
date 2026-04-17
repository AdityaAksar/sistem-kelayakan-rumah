@extends('layouts.public')
@section('title', 'Alur & Prosedur')

@section('content')
<div class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14">
        <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Panduan</span>
        <h1 class="text-4xl font-extrabold text-gray-900 mt-2">Alur & Prosedur Bantuan RTLH</h1>
        <p class="text-gray-500 mt-3">Pahami langkah-langkah proses pendataan hingga penyerahan bantuan.</p>
    </div>

    <div class="relative">
        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-amalfi/20 hidden md:block"></div>
        <div class="space-y-8">
            @php
            $steps = [
                ['Pendataan oleh Petugas Lapangan', 'Petugas pendata yang telah ditugaskan Dinas Perkimtan melakukan survei langsung ke rumah warga dan mengisi formulir pendataan melalui sistem digital secara lengkap, termasuk dokumentasi foto.','Petugas Pendata','bg-amalfi','1'],
                ['Analisis Otomatis oleh Algoritma ML', 'Data yang telah diinput oleh petugas secara otomatis dikirimkan ke server Machine Learning untuk dianalisis dan diklasifikasikan menjadi Layak Huni (RLH) atau Tidak Layak Huni (RTLH) beserta skor kepercayaannya.','Sistem Otomatis','bg-citrus','2'],
                ['Verifikasi dan Validasi oleh Admin', 'Administrator Dinas Perkimtan melakukan verifikasi silang antara hasil prediksi Machine Learning dengan foto bukti lapangan yang diunggah petugas, kemudian memberikan keputusan final: Disetujui atau Ditolak.','Admin / Pejabat','bg-sea','3'],
                ['Penetapan Penerima Bantuan', 'Data yang telah disetujui akan masuk ke dalam daftar calon penerima bantuan RTLH. Admin dapat mengekspor data ini untuk keperluan administrasi dan pelaporan.','Admin','bg-green-500','4'],
                ['Penyerahan Bantuan kepada Warga', 'Setelah proses verifikasi selesai, warga yang memenuhi syarat akan menerima bantuan stimulan perumahan swadaya sesuai ketentuan program yang berlaku.','Dinas Perkimtan','bg-purple-500','5'],
            ];
            @endphp

            @foreach($steps as $s)
            <div class="flex gap-6 items-start">
                <div class="shrink-0 w-16 h-16 {{ $s[3] }} rounded-2xl text-white flex flex-col items-center justify-center shadow-lg text-xs font-bold z-10">
                    <span class="text-xl font-extrabold">{{ $s[4] }}</span>
                </div>
                <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-gray-900 text-base">{{ $s[0] }}</h3>
                        <span class="text-xs px-2.5 py-1 bg-gray-100 text-gray-500 rounded-full">{{ $s[2] }}</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $s[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-14 bg-cream border border-yellow-200 rounded-2xl p-8 text-center">
        <h3 class="font-bold text-gray-900 text-lg mb-3">Ingin Tahu Apakah Rumah Anda Termasuk RTLH?</h3>
        <p class="text-gray-600 text-sm mb-5">Gunakan fitur Simulasi Kelayakan untuk mengecek secara mandiri dan instan.</p>
        <a href="{{ route('simulasi.index') }}" class="px-6 py-2.5 bg-amalfi text-white font-semibold rounded-xl hover:bg-blue-700 transition">Mulai Simulasi →</a>
    </div>
</div>
@endsection
