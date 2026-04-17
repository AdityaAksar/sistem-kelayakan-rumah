@extends('layouts.dashboard')
@section('title', 'Daftar Data Survei Saya')
@section('page-title', 'Data Survei Saya')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('pendata.survei.index') }}" class="flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIK, atau alamat..." class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-amalfi/50">
        <button type="submit" class="px-4 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">Cari</button>
    </form>
    <a href="{{ route('pendata.survei.create') }}" class="px-5 py-2.5 bg-citrus text-white text-sm font-semibold rounded-xl hover:bg-orange-500 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Input Baru
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-400 tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Nama KRT</th>
                    <th class="px-5 py-3 text-left">NIK</th>
                    <th class="px-5 py-3 text-left">Kelurahan</th>
                    <th class="px-5 py-3 text-left">Hasil ML</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Tgl Input</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ $item->nama_kepala_rumah_tangga }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 font-mono">{{ substr($item->nik,0,6).'******'.substr($item->nik,-4) }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500">{{ $item->kelurahan?->nama_kelurahan }}</td>
                    <td class="px-5 py-4">
                        @if($item->hasilPrediksi)
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item->hasilPrediksi->label_prediksi==='rtlh'?'bg-red-100 text-red-700':'bg-green-100 text-green-700' }}">
                            {{ strtoupper($item->hasilPrediksi->label_prediksi) }} {{ round($item->hasilPrediksi->confidence_score*100,0) }}%
                        </span>
                        @else
                        <span class="text-xs text-gray-300">Pending ML</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            {{ $item->status_validasi==='pending'?'bg-yellow-100 text-yellow-700':($item->status_validasi==='disetujui'?'bg-green-100 text-green-700':'bg-red-100 text-red-700') }}">
                            {{ ucfirst($item->status_validasi) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-400">{{ $item->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('pendata.survei.show', $item) }}" class="text-xs px-2.5 py-1 bg-amalfi/10 text-amalfi rounded-lg hover:bg-amalfi/20 transition font-medium">Detail</a>
                            @if($item->status_validasi==='pending')
                            <a href="{{ route('pendata.survei.edit', $item) }}" class="text-xs px-2.5 py-1 bg-citrus/10 text-citrus rounded-lg hover:bg-citrus/20 transition font-medium">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Belum ada data survei. <a href="{{ route('pendata.survei.create') }}" class="text-amalfi font-medium hover:underline">Input sekarang →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $data->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
