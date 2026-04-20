@extends('layouts.public')
@section('title', 'Statistik Terbuka RTLH')

@section('content')
<div class="py-12 sm:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10 sm:mb-12 reveal">
        <span class="inline-block px-3 py-1 bg-amalfi/10 text-amalfi text-xs font-bold uppercase tracking-widest rounded-full mb-3">INFORMASI</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-2">Statistik RTLH</h1>
        <p class="text-gray-500 mt-3 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">Informasi singkat tentang kondisi rumah warga</p>
    </div>

    {{-- Filter Slicer --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-8 shadow-sm reveal">
        <form method="GET" action="{{ route('statistik') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Wilayah</label>
                <select name="kecamatan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amalfi/50">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatans as $kec)
                    <option value="{{ $kec->id }}" {{ request('kecamatan') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Kelayakan</label>
                <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amalfi/50">
                    <option value="">Semua Status</option>
                    <option value="rtlh" {{ request('status') == 'rtlh' ? 'selected' : '' }}>Tidak Layak Huni</option>
                    <option value="rlh" {{ request('status') == 'rlh' ? 'selected' : '' }}>Layak Huni</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Kawasan</label>
                <select name="kawasan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amalfi/50">
                    <option value="">Semua Kawasan</option>
                    @foreach(['Kawasan Kumuh','Daerah Tertinggal Terpencil','Kawasan Pesisir Nelayan','Kawasan Transmigrasi'] as $kw)
                    <option value="{{ $kw }}" {{ request('kawasan') == $kw ? 'selected' : '' }}>{{ $kw }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-5 py-2.5 bg-amalfi text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-md">Terapkan Filter</button>
            </div>
        </form>
    </div>

    {{-- Highlight / Big Numbers (Scorecard) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 reveal">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-10">📊</div>
            <h3 class="text-sm font-bold text-blue-800 tracking-wider uppercase mb-1">Total Rumah Terdata</h3>
            <p class="text-4xl font-extrabold text-blue-900">{{ number_format($totalSurvei) }}</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl p-6 border border-red-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-10">⚠️</div>
            <h3 class="text-sm font-bold text-red-800 tracking-wider uppercase mb-1">Rasio Tidak Layak Huni</h3>
            <p class="text-4xl font-extrabold text-red-700">{{ $totalSurvei > 0 ? round(($totalRtlh / $totalSurvei) * 100, 1) : 0 }}%</p>
            <p class="text-xs text-red-600 mt-1 font-medium">{{ number_format($totalRtlh) }} rumah RTLH dari total data</p>
        </div>
    </div>

    {{-- Indikator Kepadatan & Empati --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 flex items-center gap-4 shadow-sm reveal">
        <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center text-2xl flex-shrink-0">👨‍👩‍👧‍👦</div>
        <div>
            <h3 class="text-amber-900 font-bold text-lg leading-tight"> Gambaran Kondisi Hunian</h3>
            <p class="text-amber-800 text-sm mt-1">Rata-rata 1 rumah diisi oleh <strong>{{ round($avgPenghuni, 1) }} jiwa</strong> dengan rata-rata luas bangunan hanya <strong>{{ round($avgLuas, 1) }} meter persegi</strong>.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Peta Agregat Wilayah & Top 5 --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 reveal relative overflow-hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Top 5 Wilayah Perhatian Tinggi</h3>
            <p class="text-xs text-gray-400 mb-6 border-b pb-4">Kecamatan dengan rasio persentase "Tidak Layak Huni" tertinggi</p>
            <div class="space-y-5">
                @forelse($top5KecamatanRtlh as $item)
                <div>
                    <div class="flex justify-between items-end mb-1">
                        <span class="text-sm font-semibold text-gray-700">{{ $item['kecamatan'] }}</span>
                        <span class="text-xs font-bold {{ $item['persentase'] > 50 ? 'text-red-500' : 'text-orange-500' }}">{{ $item['persentase'] }}% RTLH</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-gradient-to-r {{ $item['persentase'] > 50 ? 'from-red-400 to-red-600' : 'from-orange-400 to-orange-500' }} h-2 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Data tidak tersedia</p>
                @endforelse
            </div>
            <!-- PETA PERSEBARAN AGREGAT (LEAFLET) -->
            <div id="mapPalu" class="mt-8 rounded-xl border border-gray-200 shadow-inner z-10" style="height: 300px;"></div>
        </div>

        {{-- Kondisi Utama --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 reveal">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Pemetaan Kondisi Utama</h3>
            <p class="text-xs text-gray-400 mb-6 border-b pb-4">Visualisasi rasio material bangunan dan akses</p>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-bold text-center text-gray-600 mb-3 uppercase">Sumber Air Minum</h4>
                    <canvas id="chartAir" class="max-h-48"></canvas>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-center text-gray-600 mb-3 uppercase">Material Atap Terluas</h4>
                    <canvas id="chartAtap" class="max-h-48"></canvas>
                </div>
            </div>
        </div>

        {{-- Distribusi Tipologi Kawasan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2 reveal">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Distribusi Tipologi Kawasan</h3>
            <p class="text-xs text-gray-400 mb-6 border-b pb-4">Jumlah rumah berdasarkan lokasi kawasan khusus</p>
            <canvas id="chartKawasan" class="max-h-64"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
const palette = ['#2E5AA7','#FFA62B','#86C5FF','#22C55E','#EF4444','#8B5CF6','#06B6D4','#F59E0B'];

// Kumpulan Data Titik Koordinat dari Server
const mapData = @json($allData->filter(fn($d) => !empty($d->latitude) && !empty($d->longitude))->map(fn($d) => [
    'lat' => $d->latitude, 
    'lng' => $d->longitude, 
    'status' => optional($d->hasilPrediksi)->label_prediksi === 'rtlh' ? 'rtlh' : 'rlh'
])->values());

// Inisialisasi Peta (Koordinat titik tengah Kota Palu/Donggala)
const map = L.map('mapPalu').setView([-0.9000, 119.8700], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Tambahkan Circle Markers (Agregat Tanpa Data Sensitif)
mapData.forEach(point => {
    L.circleMarker([point.lat, point.lng], {
        radius: 4,
        fillColor: point.status === 'rtlh' ? '#ef4444' : '#22c55e',
        color: point.status === 'rtlh' ? '#b91c1c' : '#166534',
        weight: 1,
        opacity: 0.8,
        fillOpacity: 0.6
    }).addTo(map);
});
</script>
<script>
// Chart Air
const airObj = {!! json_encode($sumberAir) !!};
new Chart(document.getElementById('chartAir'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(airObj),
        datasets: [{ data: Object.values(airObj), backgroundColor: palette, hoverOffset: 4 }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, cutout: '65%' }
});

// Chart Atap
const atapObj = {!! json_encode($materialAtap) !!};
new Chart(document.getElementById('chartAtap'), {
    type: 'pie',
    data: {
        labels: Object.keys(atapObj),
        datasets: [{ data: Object.values(atapObj), backgroundColor: palette, hoverOffset: 4 }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } } }
});

// Chart Kawasan
const kawasanObj = {!! json_encode($tipologiKawasan) !!};
new Chart(document.getElementById('chartKawasan'), {
    type: 'bar',
    data: {
        labels: Object.keys(kawasanObj),
        datasets: [{ label: 'Jumlah Rumah', data: Object.values(kawasanObj), backgroundColor: '#06B6D4', borderRadius: 4 }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endpush
