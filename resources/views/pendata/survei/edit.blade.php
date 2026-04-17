@extends('layouts.dashboard')
@section('title', 'Edit Survei')
@section('page-title', 'Edit Data Survei')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('pendata.survei.index') }}" class="text-sm text-gray-500 hover:text-amalfi mb-6 inline-block">← Kembali</a>

    <form method="POST" action="{{ route('pendata.survei.update', $dataRtlh) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')
    <div class="space-y-6">

        {{-- Identitas --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="font-bold text-gray-900 mb-5">Identitas & Demografi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelurahan</label>
                    <select name="kelurahan_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                        @foreach($kelurahans->groupBy('kecamatan.nama_kecamatan') as $kec => $kels)
                        <optgroup label="Kec. {{ $kec }}">
                            @foreach($kels as $kel)
                            <option value="{{ $kel->id }}" {{ $dataRtlh->kelurahan_id==$kel->id?'selected':'' }}>{{ $kel->nama_kelurahan }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>
                @foreach([
                    ['Nama KRT','nama_kepala_rumah_tangga','text'],
                    ['No. KK','nomor_kartu_keluarga','text'],
                    ['NIK','nik','text'],
                ] as [$label,$name,$type])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $dataRtlh->$name) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                </div>
                @endforeach
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" required rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">{{ old('alamat',$dataRtlh->alamat) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Umur</label>
                    <input type="number" name="umur" value="{{ old('umur',$dataRtlh->umur) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Penghasilan/Bulan</label>
                    <input type="number" name="penghasilan_per_bulan" value="{{ old('penghasilan_per_bulan',$dataRtlh->penghasilan_per_bulan) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Anggota KK</label>
                    <input type="number" name="jumlah_keluarga_kk" value="{{ old('jumlah_keluarga_kk',$dataRtlh->jumlah_keluarga_kk) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Penghuni</label>
                    <input type="number" name="jumlah_penghuni" value="{{ old('jumlah_penghuni',$dataRtlh->jumlah_penghuni) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                </div>
            </div>
        </div>

        {{-- Kondisi Fisik --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="font-bold text-gray-900 mb-5">Kondisi Fisik</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @php $konOptions=['Baik','Rusak Ringan','Rusak Sedang','Rusak Berat']; @endphp
                @foreach([
                    ['Kondisi Atap','kondisi_atap',$konOptions],
                    ['Kondisi Dinding','kondisi_dinding',$konOptions],
                    ['Kondisi Lantai','kondisi_lantai',$konOptions],
                    ['Material Atap','material_atap_terluas',['Seng','Asbes','Genteng Tanah','Genteng Beton','Sirap','Lainnya']],
                    ['Material Dinding','material_dinding_terluas',['Bata Merah Plester','Bata Merah Tanpa Plester','Papan','Bambu/Anyaman','Lainnya']],
                    ['Material Lantai','material_lantai_terluas',['Ubin/Keramik','Semen/Plester','Kayu/Papan','Tanah','Lainnya']],
                ] as [$label,$name,$opts])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                    <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                        @foreach($opts as $o)<option value="{{ $o }}" {{ old($name,$dataRtlh->$name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                    </select>
                </div>
                @endforeach
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Luas Rumah (m²)</label>
                    <input type="number" name="luas_rumah" value="{{ old('luas_rumah',$dataRtlh->luas_rumah) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                </div>
            </div>
        </div>

        {{-- Sanitasi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="font-bold text-gray-900 mb-5">Sanitasi & Fasilitas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach([
                    ['Sumber Air Minum','sumber_air_minum',['PDAM','Sumur Bor/Pompa','Sumur Gali','Mata Air','Air Hujan','Sungai','Lainnya']],
                    ['Jenis Jamban','jenis_jamban',['Kloset Leher Angsa','Kloset Cemplung','Jamban Helikopter','Tidak ada Jamban']],
                    ['Sumber Penerangan','sumber_penerangan',['PLN','Genset','Lentera/Lampu Minyak','Lainnya']],
                    ['Jenis TPA Tinja','jenis_tpa_tinja',['Tangki Septik','Cubluk','Sungai/Laut/Danau','Lubang Tanah','Tidak Ada']],
                ] as [$label,$name,$opts])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                    <select name="{{ $name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                        @foreach($opts as $o)<option value="{{ $o }}" {{ old($name,$dataRtlh->$name)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Foto --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="font-bold text-gray-900 mb-5">Update Foto Rumah (opsional)</h2>
            @if($dataRtlh->nama_file_foto)
            <img src="{{ Storage::disk('public')->url($dataRtlh->nama_file_foto) }}" alt="Foto" class="w-40 h-32 object-cover rounded-xl mb-4">
            @endif
            <input type="file" name="foto" accept="image/*" class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amalfi/10 file:text-amalfi hover:file:bg-amalfi/20">
            <p class="text-xs text-gray-400 mt-2">Kosongkan jika tidak ingin mengganti foto.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('pendata.survei.show', $dataRtlh) }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-amalfi text-white font-bold text-sm rounded-xl hover:bg-blue-700 transition">Simpan & Re-prediksi ML</button>
        </div>
    </div>
    </form>
</div>
@endsection
