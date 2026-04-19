@extends('layouts.dashboard')
@section('title', 'Input Survei Rumah')
@section('page-title', 'Input Data Survei Rumah Baru')

@section('content')
<div class="max-w-4xl mx-auto">

{{-- Progress Steps --}}
<div id="progress-bar" class="flex items-center gap-0 mb-10">
    @foreach(['Identitas & Demografi','Informasi Lahan','Kondisi Fisik','Sanitasi & Fasilitas','Lampiran & Konfirmasi'] as $i => $label)
    <div class="flex items-center {{ $i < 4 ? 'flex-1' : '' }}">
        <button type="button" onclick="goToStep({{ $i+1 }})" class="step-btn flex flex-col items-center gap-1">
            <div class="step-circle w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all
                {{ $i === 0 ? 'bg-amalfi border-amalfi text-white' : 'border-gray-200 text-gray-400 bg-white' }}">{{ $i+1 }}</div>
            <span class="step-label text-xs font-medium {{ $i === 0 ? 'text-amalfi' : 'text-gray-400' }} hidden md:block text-center leading-tight max-w-[80px]">{{ $label }}</span>
        </button>
        @if($i < 4)
        <div class="step-line flex-1 h-0.5 mx-1 {{ $i === 0 ? 'bg-amalfi' : 'bg-gray-200' }} transition-all"></div>
        @endif
    </div>
    @endforeach
</div>

<form id="surveiForm" method="POST" action="{{ route('pendata.survei.store') }}" enctype="multipart/form-data">
@csrf

{{-- STEP 1: IDENTITAS & DEMOGRAFI --}}
<div id="step-1" class="step-content">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-7 h-7 bg-amalfi rounded-lg text-white text-xs font-bold flex items-center justify-center">1</span>
            Identitas & Demografi
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelurahan <span class="text-red-500">*</span></label>
                <select name="kelurahan_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">-- Pilih Kelurahan --</option>
                    @foreach($kelurahans->groupBy('kecamatan.nama_kecamatan') as $kec => $kels)
                    <optgroup label="Kec. {{ $kec }}">
                        @foreach($kels as $kel)
                        <option value="{{ $kel->id }}" {{ old('kelurahan_id') == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kepala Rumah Tangga <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kepala_rumah_tangga" value="{{ old('nama_kepala_rumah_tangga') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Kartu Keluarga <span class="text-red-500">*</span></label>
                <input type="text" name="nomor_kartu_keluarga" value="{{ old('nomor_kartu_keluarga') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Pendataan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_pendataan" value="{{ old('tanggal_pendataan', date('Y-m-d')) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                <textarea name="alamat" required rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">{{ old('alamat') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Umur KRT <span class="text-red-500">*</span></label>
                <input type="number" name="umur" value="{{ old('umur') }}" min="0" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="jenis_kelamin" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                <select name="pendidikan_terakhir" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach(['Tidak Sekolah','SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D3/S1','S2/S3'] as $p)
                    <option value="{{ $p }}" {{ old('pendidikan_terakhir') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan <span class="text-red-500">*</span></label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Penghasilan per Bulan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="penghasilan_per_bulan" value="{{ old('penghasilan_per_bulan') }}" min="0" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Anggota KK <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_keluarga_kk" value="{{ old('jumlah_keluarga_kk') }}" min="0" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Penghuni Rumah <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_penghuni" value="{{ old('jumlah_penghuni') }}" min="0" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
        </div>
    </div>
    <div class="flex justify-end mt-5"><button type="button" onclick="nextStep(1)" class="px-6 py-2.5 bg-amalfi text-white font-semibold rounded-xl hover:bg-blue-700 transition">Lanjut →</button></div>
</div>

{{-- STEP 2: LAHAN & ASET --}}
<div id="step-2" class="step-content hidden">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-7 h-7 bg-amalfi rounded-lg text-white text-xs font-bold flex items-center justify-center">2</span>
            Informasi Lahan & Aset
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach([
                ['Kepemilikan Rumah','kepemilikan_rumah',['Milik Sendiri', 'Bukan Milik Sendiri', 'Kontrak/ Sewa']],
                ['Kepemilikan Tanah','kepemilikan_tanah',['Milik Sendiri', 'Bukan Milik Sendiri', 'Tanah Negara']],
                ['Jenis Kawasan','jenis_kawasan',['Daerah Tertinggal Terpencil', 'KEK', 'KSPN', 'Kawasan Kumuh', 'Kawasan Perbatasan', 'Kawasan Pesisir Nelayan', 'Kawasan Rawan Air', 'Kawasan Transmigrasi', 'Pulau-Pulau Kecil/ Terluar']],
                ['Fungsi Ruang','fungsi_ruang',['Non Perumahan', 'Perumahan']],
            ] as [$label, $name, $options])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
                <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($options as $o)<option value="{{ $o }}" {{ old($name) == $o ? 'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            @endforeach
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Luas Rumah (m²) <span class="text-red-500">*</span></label>
                <input type="number" name="luas_rumah" value="{{ old('luas_rumah') }}" min="0" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Luas Lahan (m²) <span class="text-red-500">*</span></label>
                <input type="number" name="luas_lahan" value="{{ old('luas_lahan') }}" min="0" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Aset Rumah di Lokasi Lain <span class="text-red-500">*</span></label>
                <select name="aset_rumah_di_lokasi_lain" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    <option value="1" {{ old('aset_rumah_di_lokasi_lain') == '1' ? 'selected':'' }}>Ya</option>
                    <option value="0" {{ old('aset_rumah_di_lokasi_lain') == '0' ? 'selected':'' }}>Tidak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Aset Tanah di Lokasi Lain <span class="text-red-500">*</span></label>
                <select name="aset_tanah_di_lokasi_lain" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    <option value="1" {{ old('aset_tanah_di_lokasi_lain') == '1' ? 'selected':'' }}>Ya</option>
                    <option value="0" {{ old('aset_tanah_di_lokasi_lain') == '0' ? 'selected':'' }}>Tidak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Latitude (opsional)</label>
                <input type="number" step="any" name="latitude" value="{{ old('latitude') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Longitude (opsional)</label>
                <input type="number" step="any" name="longitude" value="{{ old('longitude') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
        </div>
    </div>
    <div class="flex justify-between mt-5">
        <button type="button" onclick="prevStep(2)" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">← Kembali</button>
        <button type="button" onclick="nextStep(2)" class="px-6 py-2.5 bg-amalfi text-white font-semibold rounded-xl hover:bg-blue-700 transition">Lanjut →</button>
    </div>
</div>

{{-- STEP 3: KONDISI FISIK BANGUNAN --}}
<div id="step-3" class="step-content hidden">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-7 h-7 bg-amalfi rounded-lg text-white text-xs font-bold flex items-center justify-center">3</span>
            Kondisi Fisik Bangunan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @php
            $kondisiOptions = ['Layak', 'Menuju Layak', 'Agak Layak', 'Kurang Layak', 'Tidak Layak'];
            $materialAtap   = ['Asbes', 'Bambu', 'Daun-daunan', 'Genteng', 'Ijuk', 'Jerami', 'Kayu/ Sirap', 'Rumbia', 'Seng'];
            $materialDinding= ['Anyaman Bambu', 'Bambu', 'GRC (Asbes)', 'Kayu', 'Lainnya', 'Plesteran Anyaman Bambu', 'Rumbia', 'Tembok'];
            $materialLantai = ['Bambu', 'Kayu', 'Keramik', 'Marmer/ Granit', 'Plesteran', 'Tanah', 'Ubin/ Tegel'];
            $pondasiOptions = ['Layak', 'Menuju Layak', 'Agak Layak', 'Kurang Layak', 'Tidak Layak'];
            @endphp

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pondasi <span class="text-red-500">*</span></label>
                <select name="pondasi" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($pondasiOptions as $o)<option value="{{ $o }}" {{ old('pondasi')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>

            @foreach([
                ['Kondisi Kolom','kondisi_kolom',$kondisiOptions],
                ['Kondisi Rangka Atap','kondisi_rangka_atap',$kondisiOptions],
                ['Kondisi Plafon','kondisi_plafon',$kondisiOptions],
                ['Kondisi Balok','kondisi_balok',$kondisiOptions],
                ['Kondisi Sloof','kondisi_sloof',$kondisiOptions],
                ['Kondisi Jendela','kondisi_jendela',$kondisiOptions],
                ['Kondisi Ventilasi','kondisi_ventilasi',$kondisiOptions],
            ] as [$label,$name,$options])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
                <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($options as $o)<option value="{{ $o }}" {{ old($name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            @endforeach

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Material Atap Terluas <span class="text-red-500">*</span></label>
                <select name="material_atap_terluas" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($materialAtap as $o)<option value="{{ $o }}" {{ old('material_atap_terluas')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Atap <span class="text-red-500">*</span></label>
                <select name="kondisi_atap" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($kondisiOptions as $o)<option value="{{ $o }}" {{ old('kondisi_atap')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Material Dinding Terluas <span class="text-red-500">*</span></label>
                <select name="material_dinding_terluas" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($materialDinding as $o)<option value="{{ $o }}" {{ old('material_dinding_terluas')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Dinding <span class="text-red-500">*</span></label>
                <select name="kondisi_dinding" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($kondisiOptions as $o)<option value="{{ $o }}" {{ old('kondisi_dinding')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Material Lantai Terluas <span class="text-red-500">*</span></label>
                <select name="material_lantai_terluas" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($materialLantai as $o)<option value="{{ $o }}" {{ old('material_lantai_terluas')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Lantai <span class="text-red-500">*</span></label>
                <select name="kondisi_lantai" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($kondisiOptions as $o)<option value="{{ $o }}" {{ old('kondisi_lantai')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="flex justify-between mt-5">
        <button type="button" onclick="prevStep(3)" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">← Kembali</button>
        <button type="button" onclick="nextStep(3)" class="px-6 py-2.5 bg-amalfi text-white font-semibold rounded-xl hover:bg-blue-700 transition">Lanjut →</button>
    </div>
</div>

{{-- STEP 4: SANITASI & FASILITAS --}}
<div id="step-4" class="step-content hidden">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-7 h-7 bg-amalfi rounded-lg text-white text-xs font-bold flex items-center justify-center">4</span>
            Sanitasi & Fasilitas Utilitas
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach([
                ['Sumber Penerangan','sumber_penerangan',['Bukan Listrik', 'Listrik Non PLN', 'Listrik PLN Dengan Meteran', 'Listrik PLN Tanpa Meteran']],
                ['Bantuan Pemerintah Sebelumnya','bantuan_pemerintah',['Belum Pernah', 'Ya, < 5 Tahun Yang Lalu', 'Ya, > 5 Tahun Yang Lalu']],
                ['Sumber Air Minum','sumber_air_minum',['Air Hujan', 'Air Kemasan/ Isi Ulang', 'Lainnya', 'Mata Air', 'PDAM', 'Sumur']],
                ['Jarak Sumber Air ke Tinja','jarak_sumber_air_tinja',['< 10 M', '> 10 M']],
                ['Kamar Mandi / Jamban','kamar_mandi_jamban',['Bersama/ MCK Komunal', 'Sendiri', 'Tidak Ada']],
                ['Jenis Jamban','jenis_jamban',['Cemplung/ Cubluk', 'Leher Angsa', 'Plengsengan']],
                ['Jenis Tempat Pembuang Tinja','jenis_tpa_tinja',['IPAL', 'Kolam/ Sawah/ Sungai/ Danau/ Laut', 'Lubang Tanah', 'Pantai/ Tanah Lapang/ Kebun', 'Tangki Septik']],
            ] as [$label,$name,$options])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
                <select name="{{ $name }}" {{ $name !== 'bantuan_pemerintah' ? 'required' : '' }} class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi">
                    <option value="">Pilih...</option>
                    @foreach($options as $o)<option value="{{ $o }}" {{ old($name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>
    <div class="flex justify-between mt-5">
        <button type="button" onclick="prevStep(4)" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">← Kembali</button>
        <button type="button" onclick="nextStep(4)" class="px-6 py-2.5 bg-amalfi text-white font-semibold rounded-xl hover:bg-blue-700 transition">Lanjut →</button>
    </div>
</div>

{{-- STEP 5: LAMPIRAN & KONFIRMASI --}}
<div id="step-5" class="step-content hidden">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-7 h-7 bg-amalfi rounded-lg text-white text-xs font-bold flex items-center justify-center">5</span>
            Lampiran & Konfirmasi
        </h2>
        <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center mb-6">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-sm text-gray-500 mb-3">Upload 1 foto kondisi rumah (maks. 5MB, format JPG/PNG)</p>
            <input type="file" name="foto" accept="image/jpeg,image/png" class="mx-auto block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amalfi/10 file:text-amalfi hover:file:bg-amalfi/20">
        </div>
        <div class="bg-cream/50 border border-yellow-200 rounded-xl p-4">
            <p class="text-sm text-gray-700"><strong>⚠️ Perhatian:</strong> Setelah data disimpan, sistem akan otomatis mengirim ke API Machine Learning untuk mendapatkan hasil klasifikasi kelayakan hunian. Pastikan seluruh data yang diisi sudah benar dan akurat sebelum mengirim.</p>
        </div>
    </div>
    <div class="flex justify-between mt-5">
        <button type="button" onclick="prevStep(5)" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">← Kembali</button>
        <button type="submit" class="px-8 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg">Simpan & Kirim ke ML</button>
    </div>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 5;

function updateProgressBar(step) {
    document.querySelectorAll('.step-circle').forEach((el, i) => {
        const isDone = i + 1 < step;
        const isCurrent = i + 1 === step;
        el.className = el.className.replace(/bg-\w+|border-\w+|text-\w+/g, '');
        if (isDone) {
            el.classList.add('bg-amalfi','border-amalfi','text-white');
            el.innerHTML = '✓';
        } else if (isCurrent) {
            el.classList.add('bg-amalfi','border-amalfi','text-white');
            el.innerHTML = i + 1;
        } else {
            el.classList.add('border-gray-200','text-gray-400','bg-white');
            el.innerHTML = i + 1;
        }
    });
    document.querySelectorAll('.step-line').forEach((el, i) => {
        el.className = el.className.replace(/bg-\w+-\d+|bg-\w+/g, 'bg-white');
        if (i + 1 < step) el.classList.remove('bg-white'), el.classList.add('bg-amalfi');
        else el.classList.add('bg-gray-200');
    });
}

function goToStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('step-' + step).classList.remove('hidden');
    currentStep = step;
    updateProgressBar(step);
}

function nextStep(from) { if (from < totalSteps) goToStep(from + 1); }
function prevStep(from) { if (from > 1) goToStep(from - 1); }
</script>
@endpush
