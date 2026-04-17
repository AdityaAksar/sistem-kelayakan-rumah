<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class BeritaPublicController extends Controller
{
    public function index()
    {
        $beritas = Berita::where('status','published')->latest()->paginate(9);
        return view('guest.berita.index', compact('beritas'));
    }

    public function show(string $slug)
    {
        $berita = Berita::where('slug', $slug)->where('status','published')->firstOrFail();
        $terkait = Berita::where('status','published')->where('id','!=',$berita->id)->latest()->take(3)->get();
        return view('guest.berita.show', compact('berita','terkait'));
    }
}
