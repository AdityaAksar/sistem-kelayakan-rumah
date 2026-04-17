@extends('layouts.dashboard')
@section('title', 'Dashboard Pendata')
@section('page-title', 'Dashboard Saya')

@section('content')
{{-- Stats Kartu --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
    @foreach([
        ['label'=>'Total Input','value'=>$totalInput,'color'=>'bg-amalfi'],
        ['label'=>'Menunggu Validasi','value'=>$totalPending,'color'=>'bg-citrus'],
        ['label'=>'Disetujui','value'=>$totalDisetujui,'color'=>'bg-green-500'],
        ['label'=>'Ditolak','value'=>$totalDitolak,'color'=>'bg-red-500'],
    ] as $m)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">{{ $m['label'] }}</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $m['value'] }}</p>
        <div class="w-full h-1 {{ $m['color'] }} rounded-full mt-3 opacity-40"></div>
    </div>
    @endforeach
</div>

{{-- Aksi Cepat --}}
<div class="mb-8 flex gap-3">
    <a href="{{ route('pendata.survei.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Input Survei Baru
    </a>
    <a href="{{ route('pendata.survei.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
        Lihat Semua Data
    </a>
</div>

{{-- Tabel Data Terbaru --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">5 Data Terbaru Anda</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-400 tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Nama KRT</th>
                    <th class="px-6 py-3 text-left">NIK (Tersensor)</th>
                    <th class="px-6 py-3 text-left">Prediksi ML</th>
                    <th class="px-6 py-3 text-left">Status Validasi</th>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-50">
                @forelse($recentData as $d)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $d->nama_kepala_rumah_tangga }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ substr($d->nik, 0, 6) . '******' . substr($d->nik, -4) }}</td>
                    <td class="px-6 py-4">
                        @if($d->hasilPrediksi)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $d->hasilPrediksi->label_prediksi === 'rtlh' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ strtoupper($d->hasilPrediksi->label_prediksi) }} ({{ round($d->hasilPrediksi->confidence_score * 100, 1) }}%)
                        </span>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            {{ $d->status_validasi === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               ($d->status_validasi === 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($d->status_validasi) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $d->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data yang diinput.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
