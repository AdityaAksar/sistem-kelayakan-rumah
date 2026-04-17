@extends('layouts.public')
@section('title', $berita->judul)

@section('content')
<div class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('berita.index') }}" class="text-amalfi text-sm hover:underline">← Kembali ke Berita</a>
    </div>

    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($berita->thumbnail_path)
        <img src="{{ Storage::disk('public')->url($berita->thumbnail_path) }}" alt="{{ $berita->judul }}" class="w-full max-h-96 object-cover">
        @endif
        <div class="p-8 lg:p-12">
            <div class="flex items-center gap-3 mb-5 text-sm text-gray-400">
                <span>{{ $berita->created_at->isoFormat('dddd, D MMMM Y') }}</span>
                <span>•</span>
                <span>{{ $berita->user?->name ?? 'Admin Perkimtan' }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 leading-snug mb-8">{{ $berita->judul }}</h1>
            <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                {!! $berita->konten !!}
            </div>
        </div>
    </article>

    @if($terkait->count())
    <div class="mt-14">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Berita Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($terkait as $b)
            <a href="{{ route('berita.show', $b->slug) }}" class="group bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                <div class="w-full h-32 bg-amalfi/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amalfi/30" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-400 mb-1">{{ $b->created_at->isoFormat('D MMM Y') }}</p>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-amalfi transition leading-snug">{{ $b->judul }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
