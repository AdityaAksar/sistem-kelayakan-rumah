@extends('layouts.public')
@section('title', 'Simulasi Kelayakan Rumah')

@section('content')
<div class="py-12 sm:py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10 reveal">
        <span class="inline-block px-3 py-1 bg-amalfi/10 text-amalfi text-xs font-bold uppercase tracking-widest rounded-full mb-3">Gratis & Instan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-2">Simulasi Kelayakan Rumah</h1>
        <p class="text-gray-500 mt-3 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">Isi form lengkap berikut untuk mengecek secara mandiri apakah kondisi rumah Anda masuk kriteria RTLH. <strong>Data tidak tersimpan</strong> ke dalam sistem.</p>
    </div>

    {{-- Hasil Simulasi --}}
    @if(session('simulasi_label'))
    <div class="mb-8 rounded-2xl p-6 sm:p-8 text-center border-2 shadow-lg reveal
        {{ session('simulasi_label') === 'rtlh' ? 'border-red-200 bg-gradient-to-br from-red-50 to-orange-50' : 'border-green-200 bg-gradient-to-br from-green-50 to-emerald-50' }}">
        <div class="text-5xl sm:text-6xl mb-4">{{ session('simulasi_label') === 'rtlh' ? '⚠️' : '✅' }}</div>
        <h2 class="text-xl sm:text-2xl font-extrabold mb-2 {{ session('simulasi_label') === 'rtlh' ? 'text-red-700' : 'text-green-700' }}">
            {{ session('simulasi_label') === 'rtlh' ? 'Terindikasi Tidak Layak Huni (RTLH)' : 'Terindikasi Layak Huni (RLH)' }}
        </h2>
        <p class="text-sm {{ session('simulasi_label') === 'rtlh' ? 'text-red-600' : 'text-green-600' }} mb-2">
            Tingkat kepercayaan prediksi: <strong class="text-lg">{{ round(session('simulasi_score', 0) * 100, 1) }}%</strong>
        </p>
        @if(session('simulasi_label') === 'rtlh')
        <p class="text-xs text-red-500 mt-3 max-w-md mx-auto">Hasil ini bersifat simulasi. Untuk kepastian, hubungi Dinas Perkimtan Kota Palu untuk pendataan resmi.</p>
        @endif
    </div>
    @endif

    <form method="POST" action="{{ route('simulasi.process') }}" class="space-y-6">
    @csrf

    {{-- STEP 1: IDENTITAS & EKONOMI --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden reveal">
        <div class="bg-gradient-to-r from-amalfi to-blue-600 px-6 py-4">
            <h2 class="text-white font-bold text-base flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-white/20 text-white text-xs font-extrabold flex items-center justify-center">1</span>
                Informasi Ekonomi & Hunian
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Penghasilan per Bulan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="penghasilan_per_bulan" value="{{ old('penghasilan_per_bulan') }}" min="0" required placeholder="Contoh: 1500000" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Penghuni <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_penghuni" value="{{ old('jumlah_penghuni') }}" min="1" required placeholder="Contoh: 4" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Luas Rumah (m²) <span class="text-red-500">*</span></label>
                <input type="number" name="luas_rumah" value="{{ old('luas_rumah') }}" min="1" required placeholder="Contoh: 36" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Luas Lahan (m²) <span class="text-red-500">*</span></label>
                <input type="number" name="luas_lahan" value="{{ old('luas_lahan') }}" min="1" required placeholder="Contoh: 60" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
            </div>
            @foreach([
                ['Kepemilikan Rumah','kepemilikan_rumah',['Milik Sendiri', 'Bukan Milik Sendiri', 'Kontrak/ Sewa']],
                ['Jenis Kawasan','jenis_kawasan',['Daerah Tertinggal Terpencil', 'KEK', 'KSPN', 'Kawasan Kumuh', 'Kawasan Perbatasan', 'Kawasan Pesisir Nelayan', 'Kawasan Rawan Air', 'Kawasan Transmigrasi', 'Pulau-Pulau Kecil/ Terluar']],
            ] as [$label,$name,$opts])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
                <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
                    <option value="">Pilih...</option>
                    @foreach($opts as $o)<option value="{{ $o }}" {{ old($name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>

    {{-- STEP 2: KONDISI FISIK --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden reveal">
        <div class="bg-gradient-to-r from-citrus to-orange-400 px-6 py-4">
            <h2 class="text-white font-bold text-base flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-white/20 text-white text-xs font-extrabold flex items-center justify-center">2</span>
                Kondisi Fisik Bangunan
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
            @php $kondisiOpts = ['Layak', 'Menuju Layak', 'Agak Layak', 'Kurang Layak', 'Tidak Layak']; @endphp
            @foreach([
                ['Pondasi','pondasi',$kondisiOpts],
                ['Material Atap Terluas','material_atap_terluas',['Asbes', 'Bambu', 'Daun-daunan', 'Genteng', 'Ijuk', 'Jerami', 'Kayu/ Sirap', 'Rumbia', 'Seng']],
                ['Kondisi Atap','kondisi_atap',$kondisiOpts],
                ['Material Dinding Terluas','material_dinding_terluas',['Anyaman Bambu', 'Bambu', 'GRC (Asbes)', 'Kayu', 'Lainnya', 'Plesteran Anyaman Bambu', 'Rumbia', 'Tembok']],
                ['Kondisi Dinding','kondisi_dinding',$kondisiOpts],
                ['Material Lantai Terluas','material_lantai_terluas',['Bambu', 'Kayu', 'Keramik', 'Marmer/ Granit', 'Plesteran', 'Tanah', 'Ubin/ Tegel']],
                ['Kondisi Lantai','kondisi_lantai',$kondisiOpts],
                ['Kondisi Kolom','kondisi_kolom',$kondisiOpts],
                ['Kondisi Rangka Atap','kondisi_rangka_atap',$kondisiOpts],
                ['Kondisi Plafon','kondisi_plafon',$kondisiOpts],
                ['Kondisi Balok','kondisi_balok',$kondisiOpts],
                ['Kondisi Sloof','kondisi_sloof',$kondisiOpts],
                ['Kondisi Jendela','kondisi_jendela',$kondisiOpts],
                ['Kondisi Ventilasi','kondisi_ventilasi',$kondisiOpts],
            ] as [$label,$name,$opts])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
                <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-citrus/50 focus:border-citrus transition">
                    <option value="">Pilih...</option>
                    @foreach($opts as $o)<option value="{{ $o }}" {{ old($name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>

    {{-- STEP 3: SANITASI & UTILITAS --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden reveal">
        <div class="bg-gradient-to-r from-sea to-cyan-500 px-6 py-4">
            <h2 class="text-white font-bold text-base flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-white/20 text-white text-xs font-extrabold flex items-center justify-center">3</span>
                Sanitasi & Fasilitas Utilitas
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach([
                ['Sumber Penerangan','sumber_penerangan',['Bukan Listrik', 'Listrik Non PLN', 'Listrik PLN Dengan Meteran', 'Listrik PLN Tanpa Meteran']],
                ['Sumber Air Minum','sumber_air_minum',['Air Hujan', 'Air Kemasan/ Isi Ulang', 'Lainnya', 'Mata Air', 'PDAM', 'Sumur']],
                ['Jarak Sumber Air ke Tinja','jarak_sumber_air_tinja',['< 10 M', '> 10 M']],
                ['Kamar Mandi / Jamban','kamar_mandi_jamban',['Bersama/ MCK Komunal', 'Sendiri', 'Tidak Ada']],
                ['Jenis Jamban','jenis_jamban',['Cemplung/ Cubluk', 'Leher Angsa', 'Plengsengan']],
                ['Jenis Tempat Pembuang Tinja','jenis_tpa_tinja',['IPAL', 'Kolam/ Sawah/ Sungai/ Danau/ Laut', 'Lubang Tanah', 'Pantai/ Tanah Lapang/ Kebun', 'Tangki Septik']],
                ['Bantuan Pemerintah Sebelumnya','bantuan_pemerintah',['Belum Pernah', 'Ya, < 5 Tahun Yang Lalu', 'Ya, > 5 Tahun Yang Lalu']],
            ] as [$label,$name,$opts])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
                <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sea/50 focus:border-sea transition">
                    <option value="">Pilih...</option>
                    @foreach($opts as $o)<option value="{{ $o }}" {{ old($name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Keterangan & Submit --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 reveal">
        <div class="flex gap-3 items-start">
            <span class="text-xl mt-0.5">🔒</span>
            <div>
                <p class="font-semibold text-amber-800 text-sm">Kerahasiaan Data Terjamin</p>
                <p class="text-amber-700 text-xs mt-1 leading-relaxed">Data yang Anda masukkan ke dalam form simulasi ini <strong>tidak disimpan</strong> ke database sistem kami. Formulir ini hanya digunakan untuk keperluan pengecekan mandiri secara instan menggunakan algoritma Machine Learning yang sama dengan sistem resmi.</p>
            </div>
        </div>
    </div>

    <button type="submit" class="w-full py-4 bg-gradient-to-r from-amalfi to-blue-600 text-white font-bold text-base rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-xl hover:shadow-amalfi/30 hover:-translate-y-0.5 transform reveal">
        Analisis Kelayakan Sekarang →
    </button>
    </form>
</div>
@endsection
