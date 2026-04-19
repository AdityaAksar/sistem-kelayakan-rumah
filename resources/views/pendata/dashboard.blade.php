@extends('layouts.dashboard')
@section('title', 'Ruang Kerja Surveyor')
@section('page-title', 'Dashboard Pendata: ' . $user->name)

@section('content')

{{-- Filter Operasional --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-4">Penyaringan Operasional</h2>
    <form method="GET" action="{{ route('pendata.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Rentang Waktu</label>
            <select name="waktu" class="w-full text-sm border-gray-200 rounded-xl focus:ring-amalfi">
                <option value="">Semua Waktu</option>
                <option value="hari_ini" {{ request('waktu')=='hari_ini'?'selected':'' }}>Hari Ini</option>
                <option value="kemarin" {{ request('waktu')=='kemarin'?'selected':'' }}>Kemarin</option>
                <option value="minggu_ini" {{ request('waktu')=='minggu_ini'?'selected':'' }}>Minggu Ini</option>
                <option value="bulan_ini" {{ request('waktu')=='bulan_ini'?'selected':'' }}>Bulan Ini</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Area Tugas</label>
            <select name="kelurahan_id" class="w-full text-sm border-gray-200 rounded-xl focus:ring-amalfi">
                <option value="">Semua Wilayah</option>
                @foreach($kelurahansTugas as $kel)
                <option value="{{ $kel->id }}" {{ request('kelurahan_id')==$kel->id?'selected':'' }}>{{ $kel->nama_kelurahan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
            <a href="{{ route('pendata.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 font-semibold self-center">Hapus Filter</a>
            <button type="submit" class="bg-amalfi text-white px-5 py-2.5 rounded-xl font-bold shadow-md hover:bg-blue-700 text-sm">Cari</button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
    {{-- Metrik Utama --}}
    <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-amalfi text-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-20">📋</div>
            <h3 class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">Total Data (Filter)</h3>
            <p class="text-4xl font-extrabold">{{ number_format($totalInput) }}</p>
        </div>
        <div class="bg-emerald-500 text-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-20">✅</div>
            <h3 class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">Valid (Disetujui)</h3>
            <p class="text-4xl font-extrabold">{{ number_format($totalDisetujui) }}</p>
        </div>
        <div class="bg-amber-500 text-white rounded-2xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-20">⌛</div>
            <h3 class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">Status Pending</h3>
            <p class="text-4xl font-extrabold">{{ number_format($totalPending) }}</p>
        </div>
    </div>

    {{-- Kinerja Harian --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Kinerja Hari Ini</h3>
        <p class="text-xs text-gray-400 mb-4">Target: {{ $targetHarian }} data survei/hari</p>
        
        <div class="relative w-32 h-32 mx-auto flex items-center justify-center">
            <svg class="w-full h-full transform -rotate-90">
                <circle cx="64" cy="64" r="56" stroke="#f3f4f6" stroke-width="12" fill="none"></circle>
                <circle cx="64" cy="64" r="56" stroke="{{ $dataHariIni >= $targetHarian ? '#10b981' : '#3b82f6' }}" stroke-width="12" fill="none" stroke-dasharray="351.8" stroke-dashoffset="{{ 351.8 - (351.8 * min($dataHariIni/$targetHarian, 1)) }}" class="transition-all duration-1000"></circle>
            </svg>
            <div class="absolute text-3xl font-black text-gray-800">{{ $dataHariIni }}</div>
        </div>
    </div>
</div>

{{-- QC (Quality Assurance) & Anomali --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
        <div class="bg-red-50 border-b border-red-100 px-5 py-4 flex items-center justify-between">
            <h3 class="font-bold text-red-800 text-sm">🔴 Peringatan Quality Assurance (Data Cacat)</h3>
            <span class="bg-red-200 text-red-800 px-2.5 py-0.5 rounded-full text-xs font-bold">{{ $dataCacat->count() }} Data</span>
        </div>
        <div class="p-5">
            <p class="text-xs text-gray-500 mb-3">Segera lengkapi Koordinat GPS, Foto, dan pastikan NIK 16 digit. Jika tidak, data berisiko **ditolak** oleh admin.</p>
            <div class="max-h-48 overflow-y-auto space-y-2">
                @forelse($dataCacat as $d)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $d->nama_kepala_rumah_tangga }}</p>
                        <p class="text-xs text-red-500 font-semibold mt-0.5">
                            @if(empty($d->latitude) || empty($d->longitude)) [GPS Kosong] @endif
                            @if(empty($d->nama_file_foto)) [Tanpa Foto] @endif
                            @if(strlen($d->nik) != 16) [NIK Salah {{strlen($d->nik)}} digit] @endif
                        </p>
                    </div>
                    <a href="{{ route('pendata.survei.edit', $d->id) }}" class="text-xs bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-100">Perbaiki</a>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-green-600 font-semibold bg-green-50 rounded-lg">Semua data lolos filter integritas dasar! ✅</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="bg-orange-50 border-b border-orange-100 px-5 py-4 flex items-center justify-between">
            <h3 class="font-bold text-orange-800 text-sm">👁️ Radar Deteksi Anomali</h3>
            <span class="bg-orange-200 text-orange-800 px-2.5 py-0.5 rounded-full text-xs font-bold">{{ $dataAnomali->count() }} Anomali</span>
        </div>
        <div class="p-5">
            <p class="text-xs text-gray-500 mb-3">Sistem mendeteksi ketidaklogisan input. Misal: Gaji lebih dari Rp5 Juta tapi inputan fisik rumah sangat rusak.</p>
            <div class="max-h-48 overflow-y-auto space-y-2">
                @forelse($dataAnomali as $d)
                <div class="flex flex-col bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-bold text-gray-800">{{ $d->nama_kepala_rumah_tangga }}</span>
                        <a href="{{ route('pendata.survei.edit', $d->id) }}" class="text-xs bg-white border border-gray-200 px-3 py-1 text-gray-600 rounded-md hover:bg-gray-100">Review</a>
                    </div>
                    <p class="text-xs text-orange-600 mt-1 font-semibold">
                        @if($d->penghasilan_per_bulan > 5000000) Anomali Gaji Rp{{ number_format($d->penghasilan_per_bulan) }} tapi RTLH. @endif
                        @if($d->luas_rumah == 0) Luas Rumah tercatat 0m2. @endif
                    </p>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-green-600 font-semibold bg-green-50 rounded-lg">Tidak ditemukan anomali.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Cakupan Area & Log Aktivitas --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Progress Cakupan Area (Desa/Kelurahan)</h3>
        <div class="space-y-4">
            @forelse($progressArea as $area => $total)
            <div>
                <div class="flex justify-between items-end mb-1 text-sm font-mono font-semibold">
                    <span>{{ $area }}</span>
                    <span>{{ $total }} KK</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-amalfi h-2 rounded-full" style="width: {{ min(($total/20)*100, 100) }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500">Belum ada cakupan area.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Aktivitas Terakhir (Log Lapangan)</h3>
        <ul class="space-y-3 relative before:absolute before:inset-y-0 before:left-2.5 before:w-0.5 before:bg-gray-100">
            @forelse($recentData as $d)
            <li class="relative pl-7">
                <div class="absolute left-0 top-1 w-5 h-5 {{ $d->status_validasi == 'disetujui' ? 'bg-green-500' : ($d->status_validasi == 'pendata' ? 'bg-amalfi' : 'bg-gray-300') }} rounded-full flex items-center justify-center border-4 border-white"></div>
                <p class="text-sm font-semibold text-gray-800">{{ $d->nama_kepala_rumah_tangga }}</p>
                <div class="flex gap-2 items-center mt-0.5">
                    <span class="text-xs text-gray-400">{{ $d->created_at->diffForHumans() }}</span>
                    @if($d->hasilPrediksi)
                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded-lg uppercase {{ $d->hasilPrediksi->label_prediksi == 'rtlh' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">{{ $d->hasilPrediksi->label_prediksi }}</span>
                    @endif
                </div>
            </li>
            @empty
            <li class="text-sm text-gray-400 pl-7">Belum ada log aktivitas hari ini.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection
