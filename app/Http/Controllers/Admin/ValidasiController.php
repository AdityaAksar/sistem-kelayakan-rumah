<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataRtlh;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    public function index()
    {
        $data = DataRtlh::with(['user','kelurahan.kecamatan','hasilPrediksi'])
            ->latest()->paginate(20);
        return view('admin.validasi.index', compact('data'));
    }

    public function show(DataRtlh $dataRtlh)
    {
        $dataRtlh->load(['user','kelurahan.kecamatan','hasilPrediksi.modelVersion']);
        return view('admin.validasi.show', compact('dataRtlh'));
    }

    public function validasi(Request $request, DataRtlh $dataRtlh)
    {
        $request->validate(['keputusan' => 'required|in:disetujui,ditolak']);
        $dataRtlh->update(['status_validasi' => $request->keputusan]);
        return redirect()->route('admin.data.index')->with('success', "Data berhasil di-{$request->keputusan}.");
    }

    public function destroy(Request $request, DataRtlh $dataRtlh)
    {
        if ($request->konfirmasi !== 'HAPUS') {
            return back()->with('error', 'Ketik kata HAPUS untuk melanjutkan penghapusan.');
        }
        $dataRtlh->delete();
        return redirect()->route('admin.data.index')->with('success', 'Data berhasil dihapus.');
    }

    public function export()
    {
        // Will return Excel export – placeholder until maatwebsite/excel is installed
        return back()->with('info', 'Fitur ekspor akan tersedia setelah instalasi paket Excel.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xlsx']);
        // Will process bulk import – placeholder
        return back()->with('info', 'Fitur impor akan tersedia setelah instalasi paket Excel.');
    }
}
