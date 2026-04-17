@extends('layouts.dashboard')
@section('title', 'Edit Petugas')
@section('page-title', 'Edit Akun Petugas')

@section('content')
<div class="max-w-xl mx-auto">
    <a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-500 hover:text-amalfi mb-6 inline-block">← Kembali</a>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.pengguna.update', $user) }}" class="space-y-5">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru (isi jika ingin ganti)</label>
                <input type="password" name="password" minlength="8" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
            </div>
            <button type="submit" class="w-full py-3 bg-amalfi text-white font-bold rounded-xl hover:bg-blue-700 transition">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
