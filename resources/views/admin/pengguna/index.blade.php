@extends('layouts.dashboard')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Akun Petugas Pendata')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.pengguna.create') }}" class="px-5 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Petugas
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-400 tracking-wider">
            <tr>
                <th class="px-5 py-3 text-left">Nama</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">Bergabung</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amalfi/10 text-amalfi text-xs font-bold flex items-center justify-center">{{ strtoupper(substr($user->name,0,2)) }}</div>
                        <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                <td class="px-5 py-4 text-sm text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-5 py-4">
                    <form method="POST" action="{{ route('admin.pengguna.toggle-aktif', $user) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $user->is_active?'bg-green-100 text-green-700 hover:bg-green-200':'bg-red-100 text-red-600 hover:bg-red-200' }} transition">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 flex items-center gap-2">
                    <a href="{{ route('admin.pengguna.edit', $user) }}" class="text-xs px-2.5 py-1 bg-citrus/10 text-citrus rounded-lg hover:bg-citrus/20 transition font-medium">Edit</a>
                    <button onclick="document.getElementById('hapus-modal-{{ $user->id }}').classList.remove('hidden')" class="text-xs px-2.5 py-1 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition font-medium">Hapus</button>
                </td>
            </tr>

            {{-- Modal hapus dengan konfirmasi "HAPUS" --}}
            <tr id="hapus-modal-{{ $user->id }}" class="hidden">
                <td colspan="5" class="px-5 py-4 bg-red-50 border border-red-100">
                    <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}" class="flex items-center gap-3">
                        @csrf @method('DELETE')
                        <p class="text-sm text-red-700 font-medium">Nonaktifkan akun <strong>{{ $user->name }}</strong>? Ketik <code class="bg-red-100 px-1 rounded">HAPUS</code>:</p>
                        <input type="text" name="konfirmasi" placeholder='HAPUS' class="border border-red-200 rounded-xl px-3 py-2 text-sm w-32 focus:outline-none focus:ring-2 focus:ring-red-200">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition">Konfirmasi</button>
                        <button type="button" onclick="document.getElementById('hapus-modal-{{ $user->id }}').classList.add('hidden')" class="text-sm text-gray-500 hover:text-gray-700">Batal</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-gray-400 text-sm">Belum ada akun petugas.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
