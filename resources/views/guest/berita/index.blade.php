@extends('layouts.public')
@section('title', 'Berita & Pengumuman')

@section('content')
<div class="py-16 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Terbaru</span>
        <h1 class="text-4xl font-extrabold text-gray-900 mt-2">Berita & Pengumuman</h1>
    </div>

    @if($beritas->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($beritas as $berita)
        <a href="{{ route('berita.show', $berita->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition border border-gray-100">
            @if($berita->thumbnail_path)
                <img src="{{ Storage::disk('public')->url($berita->thumbnail_path) }}" alt="{{ $berita->judul }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-amalfi to-sea flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/40" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                </div>
            @endif
            <div class="p-5">
                <p class="text-xs text-gray-400 mb-2">{{ $berita->created_at->isoFormat('D MMMM Y') }}</p>
                <h2 class="font-bold text-gray-900 leading-snug group-hover:text-amalfi transition text-base">{{ $berita->judul }}</h2>
                <p class="text-xs text-gray-500 mt-2">Oleh: {{ $berita->user?->name ?? 'Admin' }}</p>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-10">{{ $beritas->links() }}</div>
    @else
    <div class="text-center py-20 text-gray-400">
        <svg class="w-14 h-14 mx-auto mb-4 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" clip-rule="evenodd"/></svg>
        <p class="text-lg font-medium">Belum ada berita yang dipublikasikan.</p>
    </div>
    @endif
</div>
@endsection
