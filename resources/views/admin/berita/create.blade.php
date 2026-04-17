@extends('layouts.dashboard')
@section('title', 'Tulis Berita')
@section('page-title', 'Tulis Berita / Artikel Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.berita.index') }}" class="text-sm text-gray-500 hover:text-amalfi mb-6 inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke CMS
    </a>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Berita <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul berita yang menarik..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
                @error('judul')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Thumbnail <span class="text-gray-400 font-normal">(opsional)</span></label>
                <div id="dropzone" class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-amalfi/50 hover:bg-amalfi/2 transition-all">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm text-gray-500 mb-1">Klik atau seret gambar ke sini</p>
                    <p class="text-xs text-gray-400">Format: JPG, PNG, WebP — Maks 2MB</p>
                    <input type="file" id="thumbnailInput" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                        class="absolute inset-0 opacity-0 cursor-pointer" style="width:0;height:0">
                </div>
                <div id="thumbnailPreview" class="hidden mt-3">
                    <img id="previewImg" src="" alt="Preview" class="w-40 h-28 object-cover rounded-xl border border-gray-200">
                </div>
                @error('thumbnail')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konten Berita <span class="text-red-500">*</span></label>
                <textarea name="konten" rows="14" required placeholder="Tulis isi berita di sini. Mendukung HTML dasar..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition font-mono leading-relaxed">{{ old('konten') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Mendukung HTML dasar: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;h2&gt;, &lt;a&gt;, dll.</p>
                @error('konten')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Publikasi <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50 focus:border-amalfi transition">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Tidak Tampil di Portal)</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published (Langsung Tampil di Portal Publik)</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.berita.index') }}" class="flex-1 text-center py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition text-sm">Batal</a>
                <button type="submit" class="flex-1 py-2.5 bg-amalfi text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">Simpan & Publikasikan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Thumbnail preview & dropzone click
const dropzone = document.getElementById('dropzone');
const thumbnailInput = document.getElementById('thumbnailInput');
const preview = document.getElementById('thumbnailPreview');
const previewImg = document.getElementById('previewImg');

dropzone?.addEventListener('click', () => thumbnailInput.click());
thumbnailInput?.addEventListener('change', function() {
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { previewImg.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(this.files[0]);
        dropzone.style.borderColor = '#2E5AA7';
        dropzone.querySelector('p').textContent = this.files[0].name;
    }
});
dropzone?.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = '#2E5AA7'; });
dropzone?.addEventListener('dragleave', () => { dropzone.style.borderColor = ''; });
dropzone?.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) { const dt = new DataTransfer(); dt.items.add(file); thumbnailInput.files = dt.files; thumbnailInput.dispatchEvent(new Event('change')); }
});
</script>
@endpush
