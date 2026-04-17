@extends('layouts.dashboard')
@section('title', 'Validasi Data')
@section('page-title', 'Detail & Validasi Data')

@section('content')
<div class="max-w-5xl mx-auto">
    <a href="{{ route('admin.data.index') }}" class="text-sm text-gray-500 hover:text-amalfi flex items-center gap-1 mb-6">← Kembali ke Daftar</a>

    {{-- Hasil ML & Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        @if($dataRtlh->hasilPrediksi)
        <div class="rounded-2xl p-5 border-2 {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'border-red-200 bg-red-50':'border-green-200 bg-green-50' }}">
            <p class="text-xs font-bold uppercase tracking-widest {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'text-red-400':'text-green-400' }} mb-2">Prediksi Machine Learning</p>
            <p class="text-2xl font-extrabold {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'text-red-700':'text-green-700' }}">
                {{ $dataRtlh->hasilPrediksi->label_prediksi==='rtlh'?'Tidak Layak Huni':'Layak Huni' }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Confidence: <strong>{{ round($dataRtlh->hasilPrediksi->confidence_score*100,1) }}%</strong> | Model: {{ $dataRtlh->hasilPrediksi->modelVersion?->version ?? 'N/A' }}</p>
        </div>
        @endif

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Foto Bukti Lapangan</p>
            @if($dataRtlh->nama_file_foto)
                <a href="{{ Storage::disk('public')->url($dataRtlh->nama_file_foto) }}" target="_blank">
                    <img src="{{ Storage::disk('public')->url($dataRtlh->nama_file_foto) }}" alt="Foto Rumah" class="w-full h-40 object-cover rounded-xl hover:opacity-90 transition">
                </a>
            @else
                <div class="w-full h-40 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 text-sm">Tidak ada foto</div>
            @endif
        </div>
    </div>

    {{-- Tombol Validasi (hanya jika masih pending) --}}
    @if($dataRtlh->status_validasi === 'pending')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h3 class="font-bold text-gray-800 mb-4">Keputusan Validasi</h3>
        <div class="flex gap-4">
            <form method="POST" action="{{ route('admin.data.validasi', $dataRtlh) }}" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="keputusan" value="disetujui">
                <button type="submit" class="w-full py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition">
                    ✓ Setujui
                </button>
            </form>
            <form method="POST" action="{{ route('admin.data.validasi', $dataRtlh) }}" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="keputusan" value="ditolak">
                <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition">
                    ✗ Tolak
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex items-center gap-3">
        <span class="px-3 py-1.5 text-sm font-bold rounded-full {{ $dataRtlh->status_validasi==='disetujui'?'bg-green-100 text-green-700':'bg-red-100 text-red-700' }}">
            {{ ucfirst($dataRtlh->status_validasi) }}
        </span>
        <p class="text-sm text-gray-500">Data ini telah divalidasi.</p>
    </div>
    @endif

    {{-- Hapus dengan konfirmasi 2 Langkah --}}
    <details class="bg-white rounded-2xl border border-red-100 shadow-sm mb-6">
        <summary class="px-6 py-4 text-sm font-semibold text-red-600 cursor-pointer list-none flex items-center gap-2">⚠️ Hapus Permanen Data Ini</summary>
        <div class="px-6 pb-6">
            <p class="text-sm text-gray-600 mb-4">Tindakan ini permanen dan tidak dapat dibatalkan. Ketik kata <strong>HAPUS</strong> untuk konfirmasi.</p>
            <form method="POST" action="{{ route('admin.data.destroy', $dataRtlh) }}" class="flex gap-3">
                @csrf @method('DELETE')
                <input type="text" name="konfirmasi" placeholder='Ketik "HAPUS" di sini' class="border border-red-200 rounded-xl px-4 py-2.5 text-sm flex-1 focus:outline-none focus:ring-2 focus:ring-red-200">
                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition">Hapus</button>
            </form>
        </div>
    </details>

    {{-- Detail Data (sama struktur seperti show pendata) --}}
    @php
    $sections = [
        'Identitas & Demografi' => [
            'Nama KRT' => $dataRtlh->nama_kepala_rumah_tangga,
            'NIK (Masked)' => substr($dataRtlh->nik,0,6).'******'.substr($dataRtlh->nik,-4),
            'No. KK' => $dataRtlh->nomor_kartu_keluarga,
            'Diinput Oleh' => $dataRtlh->user?->name,
            'Kelurahan' => $dataRtlh->kelurahan?->nama_kelurahan,
            'Kecamatan' => $dataRtlh->kelurahan?->kecamatan?->nama_kecamatan,
            'Alamat' => $dataRtlh->alamat,
            'Umur' => $dataRtlh->umur.' tahun',
            'Jenis Kelamin' => $dataRtlh->jenis_kelamin,
            'Pendidikan' => $dataRtlh->pendidikan_terakhir,
            'Pekerjaan' => $dataRtlh->pekerjaan,
            'Penghasilan/Bulan' => 'Rp '.number_format($dataRtlh->penghasilan_per_bulan),
        ],
        'Kondisi Fisik' => [
            'Pondasi' => $dataRtlh->pondasi,
            'Material Atap' => $dataRtlh->material_atap_terluas,
            'Kondisi Atap' => $dataRtlh->kondisi_atap,
            'Material Dinding' => $dataRtlh->material_dinding_terluas,
            'Kondisi Dinding' => $dataRtlh->kondisi_dinding,
            'Material Lantai' => $dataRtlh->material_lantai_terluas,
            'Kondisi Lantai' => $dataRtlh->kondisi_lantai,
            'Luas Rumah' => $dataRtlh->luas_rumah.' m²',
        ],
        'Sanitasi' => [
            'Sumber Air Minum' => $dataRtlh->sumber_air_minum,
            'Jenis Jamban' => $dataRtlh->jenis_jamban,
            'Sumber Penerangan' => $dataRtlh->sumber_penerangan,
            'TPA Tinja' => $dataRtlh->jenis_tpa_tinja,
        ],
    ];
    @endphp

    <div class="space-y-4">
        @foreach($sections as $title => $fields)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">{{ $title }}</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($fields as $label => $value)
                <div>
                    <dt class="text-xs text-gray-400">{{ $label }}</dt>
                    <dd class="text-sm text-gray-800 font-medium mt-0.5">{{ $value ?? '—' }}</dd>
                </div>
                @endforeach
            </dl>
        </div>
        @endforeach
    </div>
</div>
@endsection
