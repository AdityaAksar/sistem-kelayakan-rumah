@extends('layouts.public')
@section('title', 'FAQ')

@section('content')
<div class="py-16 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Pertanyaan Umum</span>
        <h1 class="text-4xl font-extrabold text-gray-900 mt-2">Frequently Asked Questions</h1>
    </div>

    <div class="space-y-4">
        @foreach([
            ['Apa itu RTLH?','Rumah Tidak Layak Huni (RTLH) adalah rumah yang tidak memenuhi persyaratan keselamatan bangunan, kecukupan minimum luas bangunan, serta kesehatan penghuninya. Penilaian dilakukan berdasarkan kondisi fisik bangunan (atap, dinding, lantai), sanitasi, dan kondisi sosial ekonomi penghuni.'],
            ['Siapa yang berhak mendapatkan bantuan RTLH?','Warga Kota Palu yang memiliki penghasilan rendah, menempati dan memiliki rumah sendiri, serta kondisi rumahnya memenuhi kriteria RTLH berdasarkan hasil survei petugas lapangan dan validasi sistem Machine Learning.'],
            ['Bagaimana proses pengajuan bantuan?','Proses diawali oleh petugas lapangan yang melakukan survei fisik ke rumah Anda. Data kemudian dianalisis oleh sistem AI kami dan divalidasi oleh Admin Dinas Perkimtan. Tidak ada pengajuan mandiri oleh warga.'],
            ['Apakah data saya aman?','Ya. Sistem ini menerapkan Data Masking untuk NIK (nomor identitas) dan tidak pernah menampilkan data personal di halaman publik. Hanya petugas terotorisasi yang dapat melihat data lengkap melalui akun terverifikasi.'],
            ['Apa itu fitur Simulasi Kelayakan?','Fitur Simulasi memungkinkan Anda mengecek secara mandiri kira-kira apakah kondisi rumah Anda memenuhi kriteria RTLH. Data yang dimasukkan tidak disimpan ke dalam sistem, hanya untuk keperluan pengecekan instan.'],
            ['Berapa lama proses dari pendataan hingga keputusan?','Proses prediksi berlangsung otomatis dalam hitungan detik setelah petugas menyimpan data. Proses validasi admin bervariasi, biasanya berlangsung dalam 1-7 hari kerja.'],
            ['Apa yang dimaksud confidence score/skor kepercayaan?','Skor kepercayaan adalah angka antara 0-100% yang menunjukkan seberapa yakin algoritma Machine Learning terhadap hasil prediksinya. Semakin tinggi skor, semakin pasti prediksi tersebut.'],
        ] as [$q,$a])
        <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
            <summary class="cursor-pointer px-6 py-4 font-semibold text-gray-800 flex items-center justify-between list-none">
                {{ $q }}
                <svg class="w-5 h-5 text-amalfi group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">{{ $a }}</div>
        </details>
        @endforeach
    </div>

    <div class="mt-10 bg-amalfi/5 border border-amalfi/20 rounded-2xl p-6 text-center">
        <h3 class="font-bold text-gray-800 mb-2">Tidak menemukan jawaban Anda?</h3>
        <p class="text-gray-500 text-sm mb-4">Hubungi kami melalui:</p>
        <div class="flex justify-center gap-6 text-sm text-gray-600">
            <span>📞 (0451) 000-0000</span>
            <span>✉️ perkimtan@palukota.go.id</span>
        </div>
    </div>
</div>
@endsection
