@extends('layouts.dashboard')
@section('title', 'CMS Berita')
@section('page-title', 'Manajemen Berita & Konten Publik')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.berita.create') }}" class="px-5 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tulis Berita Baru
    </a>
</div>

@if($beritas->count())
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
    @foreach($beritas as $berita)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition reveal">
        {{-- Thumbnail --}}
        @if($berita->thumbnail_path)
        <img src="{{ Storage::disk('public')->url($berita->thumbnail_path) }}" alt="{{ $berita->judul }}" class="w-full h-40 object-cover">
        @else
        <div class="w-full h-40 bg-gradient-to-br from-amalfi/10 to-sea/20 flex items-center justify-center">
            <svg class="w-8 h-8 text-amalfi/30" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
        </div>
        @endif

        {{-- Content --}}
        <div class="p-4 flex-1 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $berita->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($berita->status) }}
                </span>
                <span class="text-xs text-gray-400">{{ $berita->created_at->format('d/m/Y') }}</span>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm leading-snug flex-1 mb-4">{{ $berita->judul }}</h3>

            {{-- Actions --}}
            <div class="flex gap-2 pt-3 border-t border-gray-50">
                <a href="{{ route('admin.berita.edit', $berita) }}" class="flex-1 text-center text-xs px-3 py-1.5 bg-citrus/10 text-citrus rounded-lg hover:bg-citrus/20 transition font-medium">Edit</a>
                @if($berita->status === 'published')
                <a href="{{ route('berita.show', $berita->slug) }}" target="_blank" class="flex-1 text-center text-xs px-3 py-1.5 bg-amalfi/10 text-amalfi rounded-lg hover:bg-amalfi/20 transition font-medium">Lihat</a>
                @endif
                {{-- Delete button triggers modal — URL dibuat oleh Blade bukan JS --}}
                <button
                    onclick="openDeleteModal(this)"
                    data-url="{{ route('admin.berita.destroy', $berita) }}"
                    data-title="{{ addslashes(Str::limit($berita->judul, 60)) }}"
                    class="flex-1 text-center text-xs px-3 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition font-medium">Hapus</button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($beritas->hasPages())
<div class="mt-6">{{ $beritas->links() }}</div>
@endif

@else
<div class="text-center py-20 text-gray-400">
    <svg class="w-14 h-14 mx-auto mb-4 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" clip-rule="evenodd"/></svg>
    <p class="text-lg font-medium">Belum ada berita.</p>
    <a href="{{ route('admin.berita.create') }}" class="text-amalfi text-sm hover:underline mt-2 inline-block">Tulis berita pertama →</a>
</div>
@endif

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="font-bold text-gray-900 text-lg">Hapus Berita?</h3>
            <p class="text-sm text-gray-500 mt-1" id="modalDesc">Berita ini akan dihapus permanen.</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="konfirmasi" id="deleteKonfirmasiHidden" value="">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Ketik <strong>HAPUS</strong> untuk konfirmasi:</label>
                <input type="text" id="deleteConfirmInput" placeholder='Ketik "HAPUS" di sini'
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-200" autocomplete="off">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition text-sm">Batal</button>
                <button type="submit" id="deleteSubmitBtn" class="flex-1 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition text-sm">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDeleteModal(btn) {
    const url   = btn.getAttribute('data-url');
    const title = btn.getAttribute('data-title');
    document.getElementById('modalDesc').textContent = 'Hapus berita "' + title + '"?';
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteConfirmInput').value = '';
    document.getElementById('deleteConfirmInput').classList.remove('border-red-400', 'ring-2', 'ring-red-200');
    document.getElementById('deleteConfirmInput').placeholder = 'Ketik "HAPUS" di sini';
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Validasi ketik "HAPUS" — sinkronkan ke hidden input
document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
    const inputEl = document.getElementById('deleteConfirmInput');
    const val = inputEl.value.trim();
    if (val !== 'HAPUS') {
        e.preventDefault();
        inputEl.classList.add('border-red-400', 'ring-2', 'ring-red-200');
        inputEl.placeholder = 'Ketik tepat: HAPUS';
        inputEl.focus();
        return;
    }
    // Salin nilai ke hidden field agar dikirim ke controller
    document.getElementById('deleteKonfirmasiHidden').value = val;
});


document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush
