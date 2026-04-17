@extends('layouts.dashboard')
@section('title', 'Detail Survei')
@section('page-title', 'Detail Data Survei')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('pendata.survei.index') }}" class="text-sm text-gray-500 hover:text-amalfi flex items-center gap-1">
            ← Kembali ke Daftar
        </a>
        <div class="flex gap-3">
            @if($dataRtlh->status_validasi === 'pending')
            <a href="{{ route('pendata.survei.edit', $dataRtlh) }}" class="px-4 py-2 bg-citrus/10 text-citrus text-sm font-semibold rounded-xl hover:bg-citrus/20 transition">Edit</a>
            @endif
        </div>
    </div>

    {{-- Hasil Prediksi Banner --}}
    @if($dataRtlh->hasilPrediksi)
    <div class="mb-6 rounded-2xl p-5 border-2 flex items-center justify-between
        {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'border-red-200 bg-red-50':'border-green-200 bg-green-50' }}">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'text-red-400':'text-green-400' }} mb-1">Hasil Prediksi Machine Learning</p>
            <p class="text-2xl font-extrabold {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'text-red-700':'text-green-700' }}">
                {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'Tidak Layak Huni (RTLH)':'Layak Huni (RLH)' }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Model: {{ $dataRtlh->hasilPrediksi->modelVersion?->version ?? 'N/A' }}</p>
        </div>
        <div class="text-center">
            <p class="text-3xl font-extrabold {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'text-red-600':'text-green-600' }}">{{ round($dataRtlh->hasilPrediksi->confidence_score*100,1) }}%</p>
            <p class="text-xs text-gray-400">Kepercayaan</p>
        </div>
    </div>
    @endif

    {{-- Status Validasi --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400 mb-1">Status Validasi Admin</p>
            <span class="px-3 py-1.5 text-sm font-bold rounded-full
                {{ $dataRtlh->status_validasi==='pending'?'bg-yellow-100 text-yellow-700':($dataRtlh->status_validasi==='disetujui'?'bg-green-100 text-green-700':'bg-red-100 text-red-700') }}">
                {{ ucfirst($dataRtlh->status_validasi) }}
            </span>
        </div>
        @if($dataRtlh->nama_file_foto)
        <a href="{{ Storage::disk('public')->url($dataRtlh->nama_file_foto) }}" target="_blank" class="text-sm text-amalfi hover:underline flex items-center gap-1">
            📸 Lihat Foto Rumah
        </a>
        @endif
    </div>

    {{-- Detail Data --}}
    @php
    $sections = [
        'Identitas & Demografi' => [
            'Nama KRT' => $dataRtlh->nama_kepala_rumah_tangga,
            'NIK' => substr($dataRtlh->nik,0,6).'******'.substr($dataRtlh->nik,-4),
            'No. KK' => $dataRtlh->nomor_kartu_keluarga,
            'Kelurahan' => $dataRtlh->kelurahan?->nama_kelurahan,
            'Kecamatan' => $dataRtlh->kelurahan?->kecamatan?->nama_kecamatan,
            'Alamat' => $dataRtlh->alamat,
            'Umur' => $dataRtlh->umur.' tahun',
            'Jenis Kelamin' => $dataRtlh->jenis_kelamin,
            'Pendidikan' => $dataRtlh->pendidikan_terakhir,
            'Pekerjaan' => $dataRtlh->pekerjaan,
            'Penghasilan/Bulan' => 'Rp '.number_format($dataRtlh->penghasilan_per_bulan),
            'Jumlah Anggota KK' => $dataRtlh->jumlah_keluarga_kk,
            'Jumlah Penghuni' => $dataRtlh->jumlah_penghuni,
        ],
        'Lahan & Aset' => [
            'Kepemilikan Rumah' => $dataRtlh->kepemilikan_rumah,
            'Kepemilikan Tanah' => $dataRtlh->kepemilikan_tanah,
            'Jenis Kawasan' => $dataRtlh->jenis_kawasan,
            'Fungsi Ruang' => $dataRtlh->fungsi_ruang,
            'Luas Rumah' => $dataRtlh->luas_rumah.' m²',
            'Luas Lahan' => $dataRtlh->luas_lahan.' m²',
            'Aset Rumah Lain' => $dataRtlh->aset_rumah_di_lokasi_lain?'Ya':'Tidak',
            'Aset Tanah Lain' => $dataRtlh->aset_tanah_di_lokasi_lain?'Ya':'Tidak',
        ],
        'Kondisi Fisik' => [
            'Pondasi' => $dataRtlh->pondasi,
            'Kondisi Kolom' => $dataRtlh->kondisi_kolom,
            'Kondisi Rangka Atap' => $dataRtlh->kondisi_rangka_atap,
            'Kondisi Plafon' => $dataRtlh->kondisi_plafon,
            'Material Atap' => $dataRtlh->material_atap_terluas,
            'Kondisi Atap' => $dataRtlh->kondisi_atap,
            'Material Dinding' => $dataRtlh->material_dinding_terluas,
            'Kondisi Dinding' => $dataRtlh->kondisi_dinding,
            'Material Lantai' => $dataRtlh->material_lantai_terluas,
            'Kondisi Lantai' => $dataRtlh->kondisi_lantai,
            'Kondisi Jendela' => $dataRtlh->kondisi_jendela,
            'Kondisi Ventilasi' => $dataRtlh->kondisi_ventilasi,
        ],
        'Sanitasi & Utilitas' => [
            'Sumber Penerangan' => $dataRtlh->sumber_penerangan,
            'Sumber Air Minum' => $dataRtlh->sumber_air_minum,
            'Jarak Sumber Air ke Tinja' => $dataRtlh->jarak_sumber_air_tinja,
            'Kamar Mandi/Jamban' => $dataRtlh->kamar_mandi_jamban,
            'Jenis Jamban' => $dataRtlh->jenis_jamban,
            'Jenis TPA Tinja' => $dataRtlh->jenis_tpa_tinja,
            'Bantuan Pemerintah' => $dataRtlh->bantuan_pemerintah ?? 'Belum Pernah',
        ],
    ];
    @endphp

    <div class="space-y-5">
        @foreach($sections as $title => $fields)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">{{ $title }}</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($fields as $label => $value)
                <div>
                    <dt class="text-xs text-gray-400 font-medium">{{ $label }}</dt>
                    <dd class="text-sm text-gray-800 font-medium mt-0.5">{{ $value ?? '—' }}</dd>
                </div>
                @endforeach
            </dl>
        </div>
        @endforeach
    </div>
</div>
@endsection
