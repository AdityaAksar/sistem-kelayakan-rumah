@extends('layouts.dashboard')
@section('title', 'Audit Trail')
@section('page-title', 'Log Aktivitas Sistem (Audit Trail)')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">Riwayat mutasi data secara otomatis direkam oleh sistem.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-400 tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Waktu</th>
                    <th class="px-5 py-3 text-left">Pelaku</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                    <th class="px-5 py-3 text-left">Tabel</th>
                    <th class="px-5 py-3 text-left">ID Record</th>
                    <th class="px-5 py-3 text-left">Data Lama → Baru</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition align-top">
                    <td class="px-5 py-4 text-xs text-gray-400 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-5 py-4 text-sm text-gray-700 font-medium whitespace-nowrap">{{ $log->user?->name ?? 'System' }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full
                            {{ $log->action==='created'?'bg-green-100 text-green-700':($log->action==='deleted'?'bg-red-100 text-red-700':'bg-yellow-100 text-yellow-700') }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500 font-mono">{{ $log->target_table }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500">#{{ $log->target_id }}</td>
                    <td class="px-5 py-4 text-xs text-gray-400 max-w-sm">
                        @if($log->old_values)
                        <details>
                            <summary class="cursor-pointer text-amber-600 font-medium">Lihat perubahan</summary>
                            <div class="mt-2 space-y-1">
                                @foreach($log->old_values as $k => $v)
                                @if(isset($log->new_values[$k]) && $log->new_values[$k] != $v)
                                <div class="font-mono text-xs">
                                    <span class="text-gray-400">{{ $k }}</span>:
                                    <span class="line-through text-red-500">{{ Str::limit((string)$v, 30) }}</span>
                                    → <span class="text-green-600">{{ Str::limit((string)($log->new_values[$k]??''), 30) }}</span>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </details>
                        @elseif($log->new_values)
                        <span class="text-green-600">Record baru dibuat</span>
                        @else
                        <span class="text-red-600">Record dihapus</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-12 text-gray-400 text-sm">Belum ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
