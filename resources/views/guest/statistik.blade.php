@extends('layouts.public')
@section('title', 'Statistik RTLH')

@section('content')
<div class="py-12 sm:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10 sm:mb-12 reveal">
        <span class="text-xs font-bold uppercase tracking-widest text-amalfi">Data Terbuka</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-2">Dashboard Statistik RTLH</h1>
        <p class="text-gray-500 mt-3 max-w-2xl mx-auto text-sm sm:text-base">Data agregat kondisi perumahan Kota Palu. Tidak menampilkan data personal (NIK/Nama).</p>
    </div>

    {{-- Filter --}}
    <div class="bg-amalfi/5 border border-amalfi/20 rounded-2xl p-5 sm:p-6 mb-8 sm:mb-10 reveal">
        <h2 class="text-sm font-bold text-amalfi uppercase tracking-widest mb-3">Filter Wilayah</h2>
        <form method="GET" action="{{ route('statistik') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="kecamatan" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amalfi/50">
                <option value="">Semua Kecamatan</option>
                @foreach($kecamatans as $kec)
                <option value="{{ $kec->id }}" {{ request('kecamatan') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition w-full sm:w-auto">Tampilkan</button>
        </form>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-8">
        @foreach([
            ['chartKecamatan','Distribusi RLH vs RTLH Per Kecamatan','Perbandingan kelayakan di tiap kecamatan'],
            ['chartAir','Sumber Air Minum Warga','Distribusi akses air bersih warga survei'],
            ['chartAtap','Kondisi Fisik Atap Bangunan','Mayoritas kondisi fisik atap rumah'],
            ['chartLantai','Material Lantai Terluas','Jenis material lantai yang digunakan'],
        ] as $i => [$id, $title, $sub])
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-md transition reveal" style="transition-delay:{{ $i * 60 }}ms">
            <h3 class="text-base font-bold text-gray-800 mb-1">{{ $title }}</h3>
            <p class="text-xs text-gray-400 mb-4">{{ $sub }}</p>
            <canvas id="{{ $id }}" class="max-h-72"></canvas>
        </div>
        @endforeach

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-md transition lg:col-span-2 reveal">
            <h3 class="text-base font-bold text-gray-800 mb-1">Status Kepemilikan Rumah</h3>
            <p class="text-xs text-gray-400 mb-4">Distribusi jenis kepemilikan rumah warga survei</p>
            <canvas id="chartKepemilikan" class="max-h-56"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const palette = ['#2E5AA7','#FFA62B','#86C5FF','#22C55E','#EF4444','#8B5CF6','#06B6D4','#F59E0B'];

const kecData = @json($perKecamatan);
new Chart(document.getElementById('chartKecamatan'), { type:'bar', data:{ labels:Object.keys(kecData), datasets:[
    { label:'RLH', data:Object.values(kecData).map(d=>d.rlh??0), backgroundColor:'#86C5FF', borderRadius:4 },
    { label:'RTLH', data:Object.values(kecData).map(d=>d.rtlh??0), backgroundColor:'#FFA62B', borderRadius:4 },
]}, options:{ plugins:{ legend:{ position:'bottom' } }, scales:{ x:{ stacked:true, ticks:{ font:{ size:10 }, maxRotation:45 } }, y:{ stacked:true } }, responsive:true } });

new Chart(document.getElementById('chartAir'), { type:'doughnut', data:{ labels:{!! json_encode($sumberAir->keys()) !!}, datasets:[{ data:{!! json_encode($sumberAir->values()) !!}, backgroundColor:palette, hoverOffset:6 }]}, options:{ plugins:{ legend:{ position:'bottom', labels:{ font:{ size:11 } } } }, cutout:'55%' } });

new Chart(document.getElementById('chartAtap'), { type:'bar', data:{ labels:{!! json_encode($kondisiAtap->keys()) !!}, datasets:[{ label:'Kondisi Atap', data:{!! json_encode($kondisiAtap->values()) !!}, backgroundColor:['#86C5FF','#FFA62B','#EF4444','#8B5CF6'], borderRadius:6 }]}, options:{ indexAxis:'y', plugins:{ legend:{ display:false } }, responsive:true } });

new Chart(document.getElementById('chartLantai'), { type:'pie', data:{ labels:{!! json_encode($materialLantai->keys()) !!}, datasets:[{ data:{!! json_encode($materialLantai->values()) !!}, backgroundColor:palette, hoverOffset:6 }]}, options:{ plugins:{ legend:{ position:'bottom', labels:{ font:{ size:11 } } } } } });

new Chart(document.getElementById('chartKepemilikan'), { type:'bar', data:{ labels:{!! json_encode($kepemilikanRumah->keys()) !!}, datasets:[{ label:'Jumlah', data:{!! json_encode($kepemilikanRumah->values()) !!}, backgroundColor:'#2E5AA7', borderRadius:6 }]}, options:{ plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ font:{ size:11 } } } } } });
</script>
@endpush
