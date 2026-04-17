@extends('layouts.dashboard')
@section('title', 'MLOps – Kelola Model')
@section('page-title', 'MLOps – Manajemen Model Machine Learning')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Upload Model Baru --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
            <span class="w-6 h-6 bg-amalfi rounded-lg flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </span>
            Upload Model Baru
        </h3>
        <form method="POST" action="{{ route('admin.mlops.upload') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Versi</label>
                <input type="text" name="version_name" placeholder="contoh: XGBoost v2.1" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">File Model (.pkl)</label>
                <input type="file" name="model_file" required class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amalfi/10 file:text-amalfi hover:file:bg-amalfi/20">
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-xs text-yellow-700">
                ⚠️ Mengunggah model baru akan menonaktifkan model yang sedang berjalan. Pastikan file .pkl sudah diuji.
            </div>
            <button type="submit" class="w-full py-2.5 bg-amalfi text-white font-semibold text-sm rounded-xl hover:bg-blue-700 transition">Upload & Aktifkan</button>
        </form>
    </div>

    {{-- Model Aktif + Riwayat --}}
    <div class="lg:col-span-2 space-y-5">
        {{-- Model Aktif --}}
        @if($modelAktif)
        <div class="bg-amalfi rounded-2xl p-6 text-white flex items-center justify-between">
            <div>
                <p class="text-white/60 text-xs uppercase tracking-wider font-bold mb-1">Model Aktif Sekarang</p>
                <p class="text-xl font-extrabold">{{ $modelAktif->version }}</p>
                <p class="text-white/60 text-xs mt-1">Di-upload: {{ $modelAktif->created_at->isoFormat('D MMMM Y, HH:mm') }}</p>
                <p class="text-white/60 text-xs">Oleh: {{ $modelAktif->user?->name ?? 'Admin' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-xs text-white/70 font-medium">Online</span>
            </div>
        </div>
        @else
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
            <p class="text-red-600 font-semibold">⚠️ Belum ada model aktif. Upload model untuk mulai prediksi.</p>
        </div>
        @endif

        {{-- Riwayat Model --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Versi Model</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left">Versi</th>
                        <th class="px-5 py-3 text-left">Di-upload</th>
                        <th class="px-5 py-3 text-left">Oleh</th>
                        <th class="px-5 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($riwayat as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $m->version }}</td>
                        <td class="px-5 py-3 text-sm text-gray-400">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-sm text-gray-400">{{ $m->user?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $m->is_active?'bg-green-100 text-green-700':'bg-gray-100 text-gray-400' }}">
                                {{ $m->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400 text-sm">Belum ada riwayat model.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
