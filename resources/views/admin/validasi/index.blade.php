@extends('layouts.dashboard')
@section('title', 'Semua Data RTLH')
@section('page-title', 'Manajemen & Validasi Data')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <form method="GET" action="{{ route('admin.data.index') }}" class="flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIK..." class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-amalfi/50">
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">Filter</button>
    </form>
    <div class="flex gap-2">
        <a href="{{ route('admin.data.export') }}" class="px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition">Export Excel</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-400 tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Nama KRT</th>
                    <th class="px-5 py-3 text-left">NIK</th>
                    <th class="px-5 py-3 text-left">Wilayah</th>
                    <th class="px-5 py-3 text-left">Petugas</th>
                    <th class="px-5 py-3 text-left">Hasil ML</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ $item->nama_kepala_rumah_tangga }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 font-mono">{{ substr($item->nik,0,6).'******'.substr($item->nik,-4) }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        <div>{{ $item->kelurahan?->nama_kelurahan }}</div>
                        <div class="text-xs text-gray-400">{{ $item->kelurahan?->kecamatan?->nama_kecamatan }}</div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">{{ $item->user?->name }}</td>
                    <td class="px-5 py-4">
                        @if($item->hasilPrediksi)
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item->hasilPrediksi->label_prediksi==='rtlh'?'bg-red-100 text-red-700':'bg-green-100 text-green-700' }}">
                            {{ strtoupper($item->hasilPrediksi->label_prediksi) }}
                        </span>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            {{ $item->status_validasi==='pending'?'bg-yellow-100 text-yellow-700':($item->status_validasi==='disetujui'?'bg-green-100 text-green-700':'bg-red-100 text-red-700') }}">
                            {{ ucfirst($item->status_validasi) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.data.show', $item) }}" class="text-xs px-2.5 py-1 bg-amalfi/10 text-amalfi rounded-lg hover:bg-amalfi/20 transition font-medium">Validasi</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-gray-400 text-sm">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $data->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
