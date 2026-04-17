@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Eksekutif')

@section('content')

{{-- 4 KARTU METRIK --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    @foreach([
        ['label' => 'Total Data Survei', 'value' => number_format($totalSurvei), 'color' => 'bg-amalfi', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['label' => 'Menunggu Validasi', 'value' => number_format($totalPending), 'color' => 'bg-citrus', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Terindikasi RTLH', 'value' => number_format($rtlhCount), 'color' => 'bg-red-500', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ['label' => 'Petugas Aktif', 'value' => number_format($totalPendata), 'color' => 'bg-green-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ] as $metric)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-4">
            <div class="p-3 {{ $metric['color'] }} rounded-xl">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $metric['icon'] }}" /></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">{{ $metric['label'] }}</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">{{ $metric['value'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Model Aktif Banner --}}
<div class="bg-amalfi/5 border border-amalfi/20 rounded-2xl p-4 mb-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse"></div>
        <span class="text-sm font-medium text-gray-700">Model Aktif: <strong>{{ $modelAktif ? $modelAktif->version : 'Belum ada model aktif' }}</strong></span>
    </div>
    <a href="{{ route('admin.mlops.index') }}" class="text-xs text-amalfi font-semibold hover:underline">Kelola Model →</a>
</div>

{{-- CHARTS --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Distribusi Kelayakan (RLH vs RTLH)</h3>
        <canvas id="pieKelayakan" class="max-h-64"></canvas>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Distribusi Per Kecamatan</h3>
        <canvas id="barKecamatan" class="max-h-64"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Mayoritas Sumber Air Minum</h3>
        <canvas id="doughnutAir" class="max-h-64"></canvas>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Tren Survei Bulanan {{ now()->year }}</h3>
        <canvas id="lineSurvei" class="max-h-64"></canvas>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">Kondisi Atap Dominan</h3>
    <canvas id="barAtap" class="max-h-52"></canvas>
</div>

@endsection

@push('scripts')
<script>
const colorPalette = ['#2E5AA7','#FFA62B','#86C5FF','#F8E6A0','#EF4444','#22C55E'];

new Chart(document.getElementById('pieKelayakan'), { type:'pie', data:{ labels:['Layak Huni (RLH)','Tidak Layak Huni (RTLH)'], datasets:[{ data:[{{ $rlhCount }},{{ $rtlhCount }}], backgroundColor:['#86C5FF','#FFA62B'] }] }, options:{ plugins:{ legend:{ position:'bottom' } } } });

new Chart(document.getElementById('barKecamatan'), { type:'bar', data:{ labels:{!! json_encode($perKecamatan->keys()) !!}, datasets:[{ label:'Jumlah Survei', data:{!! json_encode($perKecamatan->values()) !!}, backgroundColor:'#2E5AA7' }] }, options:{ plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ maxRotation:45, font:{ size:10 } } } } } });

new Chart(document.getElementById('doughnutAir'), { type:'doughnut', data:{ labels:{!! json_encode($sumberAir->keys()) !!}, datasets:[{ data:{!! json_encode($sumberAir->values()) !!}, backgroundColor:colorPalette }] }, options:{ plugins:{ legend:{ position:'bottom' } } } });

const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
const trendData = @json($trendSurvei);
const trendLabels = Object.keys(trendData).map(k => months[parseInt(k)-1]);
const trendValues = Object.values(trendData);
new Chart(document.getElementById('lineSurvei'), { type:'line', data:{ labels:trendLabels, datasets:[{ label:'Survei Masuk', data:trendValues, borderColor:'#FFA62B', backgroundColor:'rgba(255,166,43,0.1)', fill:true, tension:0.4 }] }, options:{ plugins:{ legend:{ display:false } } } });

new Chart(document.getElementById('barAtap'), { type:'bar', data:{ labels:{!! json_encode($kondisiAtap->keys()) !!}, datasets:[{ label:'Jumlah', data:{!! json_encode($kondisiAtap->values()) !!}, backgroundColor:'#86C5FF' }] }, options:{ indexAxis:'y', plugins:{ legend:{ display:false } } } });
</script>
@endpush
