@extends('layouts.dashboard')
@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita / Artikel')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.berita.index') }}" class="text-sm text-gray-500 hover:text-amalfi mb-6 inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke CMS
    </a>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.berita.update', $berita) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Berita <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
                @error('judul')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Thumbnail Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diganti)</span></label>
                @if($berita->thumbnail_path)
                <div class="mb-3 flex items-start gap-3">
                    <img src="{{ Storage::disk('public')->url($berita->thumbnail_path) }}"
                         alt="Thumbnail saat ini"
                         class="w-32 h-20 object-cover rounded-xl border border-gray-200">
                    <div class="text-xs text-gray-400 mt-1">
                        <p class="font-medium text-gray-600 mb-0.5">Thumbnail saat ini</p>
                        <p>{{ basename($berita->thumbnail_path) }}</p>
                    </div>
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amalfi/10 file:text-amalfi hover:file:bg-amalfi/20 transition">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
                @error('thumbnail')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konten Berita <span class="text-red-500">*</span></label>
                <textarea name="konten" rows="14" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition font-mono leading-relaxed">{{ old('konten', $berita->konten) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Mendukung HTML dasar: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;h2&gt;, &lt;a&gt;, dll.</p>
                @error('konten')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Publikasi <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
                    <option value="draft" {{ old('status', $berita->status) === 'draft' ? 'selected' : '' }}>Draft (Tidak Tampil di Portal)</option>
                    <option value="published" {{ old('status', $berita->status) === 'published' ? 'selected' : '' }}>Published (Tampil di Portal Publik)</option>
                </select>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-xs text-gray-400 flex flex-wrap gap-x-6 gap-y-1.5">
                <span>Slug: <code class="bg-gray-100 px-1 rounded">{{ $berita->slug }}</code></span>
                <span>Penulis: {{ $berita->user?->name }}</span>
                <span>Dibuat: {{ $berita->created_at->isoFormat('D MMM Y, HH:mm') }}</span>
                <span>Diperbarui: {{ $berita->updated_at->isoFormat('D MMM Y, HH:mm') }}</span>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.berita.index') }}" class="flex-1 text-center py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition text-sm">Batal</a>
                <button type="submit" class="flex-1 py-2.5 bg-amalfi text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
