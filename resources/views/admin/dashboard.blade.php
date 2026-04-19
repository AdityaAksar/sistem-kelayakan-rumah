@extends('layouts.dashboard')

@section('title', 'Admin Command Center')
@section('page-title', 'Command Center Eksekutif')

@section('content')

{{-- Filter Cascading & Lapis --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-4">Panel Filter Strategis</h2>
    <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs text-gray-500 font-semibold mb-1">Hierarki Wilayah</label>
            <select name="kecamatan" class="w-full text-sm border-gray-200 rounded-lg focus:ring-amalfi">
                <option value="">Semua Kecamatan</option>
                @foreach($kecamatans as $kec)
                <option value="{{ $kec->id }}" {{ request('kecamatan') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-semibold mb-1">Status Bantuan</label>
            <select name="bantuan" class="w-full text-sm border-gray-200 rounded-lg focus:ring-amalfi">
                <option value="">Semua Status Bantuan</option>
                <option value="Belum Pernah" {{ request('bantuan') == 'Belum Pernah' ? 'selected' : '' }}>Belum Pernah</option>
                <option value="Ya, > 10 Tahun Yang Lalu" {{ request('bantuan') == 'Ya, > 10 Tahun Yang Lalu' ? 'selected' : '' }}>Ya, > 10 Tahun Yang Lalu</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-semibold mb-1">Syarat Legalitas Tanah</label>
            <select name="kepemilikan_tanah" class="w-full text-sm border-gray-200 rounded-lg focus:ring-amalfi">
                <option value="">Semua Legalitas</option>
                <option value="Milik Sendiri" {{ request('kepemilikan_tanah') == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri (Sah)</option>
                <option value="Bukan Milik Sendiri" {{ request('kepemilikan_tanah') == 'Bukan Milik Sendiri' ? 'selected' : '' }}>Bukan Milik Sendiri</option>
                <option value="Tanah Negara" {{ request('kepemilikan_tanah') == 'Tanah Negara' ? 'selected' : '' }}>Tanah Negara</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-semibold mb-1">Tanggal Pendataan Mulai</label>
            <input type="date" name="tanggal_start" value="{{ request('tanggal_start') }}" class="w-full text-sm border-gray-200 rounded-lg focus:ring-amalfi">
        </div>
        <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex justify-end">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700 mr-4 self-center">Reset Filter</a>
            <button type="submit" class="bg-amalfi text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-blue-800 transition">Terapkan Penyesuaian</button>
        </div>
    </form>
</div>

{{-- Top Metrics --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-md">
        <h3 class="text-xs uppercase font-bold text-blue-200 opacity-80">Total Survei</h3>
        <p class="text-3xl font-black mt-1">{{ number_format($tabelBnba->count()) }}</p>
    </div>
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-5 text-white shadow-md">
        <h3 class="text-xs uppercase font-bold text-red-200 opacity-80">Menunggu Validasi</h3>
        <p class="text-3xl font-black mt-1">{{ number_format($totalPending) }}</p>
    </div>
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl p-5 text-white shadow-md">
        <h3 class="text-xs uppercase font-bold text-emerald-200 opacity-80">Data Disetujui</h3>
        <p class="text-3xl font-black mt-1">{{ number_format($totalDisetujui) }}</p>
    </div>

</div>

{{-- Peta Titik Parsial (Geotagging) --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6 relative z-10">
    <h3 class="text-sm font-bold text-gray-800 mb-1">Peta Geotagging Survei BNBA</h3>
    <p class="text-xs text-gray-400 mb-4">Pemetaan titik koordinat survei warga. Merah = Terindikasi RTLH, Hijau = RLH.</p>
    <div id="mapAdmin" class="w-full rounded-xl border border-gray-200 shadow-inner z-0" style="height: 350px;"></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Kuadran Matriks Prioritas --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Matriks Prioritas Intervensi</h3>
        <p class="text-xs text-gray-400 mb-4">Sumbu X: Penghasilan | Sumbu Y: Akumulasi Variabel Rusak/Tidak Layak. Titik kiri atas = Sangat Prioritas.</p>
        <canvas id="scatterPrioritas" class="max-h-72 w-full"></canvas>
    </div>

    {{-- Overcrowding --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Analisis Kepadatan & Overcrowding</h3>
        <p class="text-xs text-gray-400 mb-4">Sumbu X: Luas Rumah (M2) | Sumbu Y: Jumlah Penghuni. Titik kiri atas = Rawan Berkepadatan Tinggi.</p>
        <canvas id="scatterOvercrowding" class="max-h-72 w-full"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Analisis Fisik Bangunan (Stacked Bar 100%) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:col-span-2">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Kondisi Fisik Strutural Bangunan</h3>
        <p class="text-xs text-gray-400 mb-4">Rasio tingkat kelayakan pada kelima pilar utama bangunan rumah.</p>
        <canvas id="barFisik" class="max-h-64 w-full"></canvas>
    </div>

    {{-- Syarat Legalitas --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Pengecekan Syarat Legalitas Tanah</h3>
        <p class="text-xs text-gray-400 mb-4">Syarat penerimaan Bantuan Bedah Rumah Hukum.</p>
        <canvas id="donutLegalitas" class="max-h-64 w-full"></canvas>
    </div>
</div>

{{-- Kelengkapan Geotagging & Demografi --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Grafik Kelengkapan Geotagging</h3>
        <canvas id="barGeotag" class="max-h-60 w-full"></canvas>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Analisis Kerentanan Demografi (KRT Umur)</h3>
        <canvas id="barDemografi" class="max-h-60 w-full"></canvas>
    </div>
</div>

{{-- Tabel Master BNBA Interaktif --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 overflow-hidden">
    <div class="flex justify-between items-end mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-800">Master Data By Name By Address (BNBA)</h3>
            <p class="text-xs text-gray-400">Hasil filter matriks siap dieksekusi / diprint-out untuk lampiran SK.</p>
        </div>
        <button class="text-xs font-bold bg-green-100 text-green-700 px-3 py-1.5 rounded-lg border border-green-200">Export Excel</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">No. KK / NIK</th>
                    <th class="px-4 py-3">Nama KRT</th>
                    <th class="px-4 py-3">Alamat Lengkap</th>
                    <th class="px-4 py-3">Skor ML</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($tabelBnba->take(15) as $bnba)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><span class="font-bold">{{ $bnba->nomor_kartu_keluarga }}</span><br><span class="text-xs">{{ $bnba->nik }}</span></td>
                    <td class="px-4 py-3 font-semibold text-amalfi">{{ $bnba->nama_kepala_rumah_tangga }}</td>
                    <td class="px-4 py-3 truncate max-w-xs">{{ $bnba->alamat }}</td>
                    <td class="px-4 py-3">
                        @if($bnba->hasilPrediksi)
                            <span class="inline-flex items-center gap-1 {{ $bnba->hasilPrediksi->label_prediksi === 'rtlh' ? 'text-red-600 bg-red-100' : 'text-green-600 bg-green-100' }} px-2 py-1 rounded-md mb-1 text-xs font-bold leading-none capitalize">
                                {{ strtoupper($bnba->hasilPrediksi->label_prediksi) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($tabelBnba->count() > 15)
        <div class="text-center py-3 text-xs text-gray-400">Menampilkan 15 data dari total {{ $tabelBnba->count() }} (Sebaiknya gunakan integrasi DataTables di sisi client)</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
// PETA LEAFLET ADMIN
const mapData = @json($mapData);

const map = L.map('mapAdmin').setView([-0.9000, 119.8700], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '© OS' }).addTo(map);

mapData.forEach(point => {
    let color = point.status === 'rtlh' ? '#ef4444' : '#22c55e';
    L.circleMarker([point.lat, point.lng], { radius: 5, fillColor: color, color: '#fff', weight: 1, opacity: 1, fillOpacity: 0.8 })
     .bindPopup('<b>' + point.nama + '</b><br>NIK: ' + point.nik + '<br>Status: ' + point.status.toUpperCase())
     .addTo(map);
});
</script>
<script>
// SCATTER PRIORITAS
const prioritasPts = @json($scatterPrioritas).map(d => ({x: d.x, y: d.y, nama: d.nama}));
new Chart(document.getElementById('scatterPrioritas'), {
    type: 'scatter',
    data: { datasets: [{ label: 'KRT', data: prioritasPts, backgroundColor: 'rgba(239,68,68,0.7)', pointRadius: 5 }] },
    options: { plugins: { tooltip: { callbacks: { label: function(c){ return c.raw.nama + ' (Gaji: ' + c.raw.x + ', Kerusakan: ' + c.raw.y + '/4)'; } } }, legend: { display:false } } }
});

// SCATTER OVERCROWDING
const crowdPts = @json($scatterOvercrowding).map(d => ({x: d.x, y: d.y, nama: d.nama}));
new Chart(document.getElementById('scatterOvercrowding'), {
    type: 'scatter',
    data: { datasets: [{ label: 'Rumah', data: crowdPts, backgroundColor: 'rgba(245,158,11,0.7)', pointRadius: 5 }] },
    options: { plugins: { tooltip: { callbacks: { label: function(c){ return c.raw.nama + ' (Luas: ' + c.raw.x + 'm2, Penghuni: ' + c.raw.y + ' jiwa)'; } } }, legend: { display:false } } }
});

// STACKED BAR FISIK
const fisikRaw = @json($fisikData);
const fisikLabels = Object.keys(fisikRaw);
new Chart(document.getElementById('barFisik'), {
    type: 'bar',
    data: {
        labels: fisikLabels,
        datasets: [
            { label: 'Layak', data: fisikLabels.map(k => fisikRaw[k]['Layak']), backgroundColor: '#22C55E' },
            { label: 'Agak Layak', data: fisikLabels.map(k => fisikRaw[k]['Agak Layak']), backgroundColor: '#F59E0B' },
            { label: 'Tidak Layak', data: fisikLabels.map(k => fisikRaw[k]['Tidak Layak']), backgroundColor: '#EF4444' }
        ]
    },
    options: { scales: { x: { stacked: true }, y: { stacked: true } }, plugins: { legend: { position: 'bottom' } } }
});

// DONUT LEGALITAS
const legObj = {!! json_encode($legalitasTanah) !!};
new Chart(document.getElementById('donutLegalitas'), {
    type: 'doughnut', data: { labels: Object.keys(legObj), datasets: [{ data: Object.values(legObj), backgroundColor: ['#2E5AA7','#86C5FF','#EF4444'] }] },
    options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } }, cutout: '60%' }
});

// BAR HORIZONTAL GEOTAG
const geoObj = {!! json_encode($geotagKecamatan) !!};
const geoLabels = Object.keys(geoObj);
new Chart(document.getElementById('barGeotag'), {
    type: 'bar',
    data: {
        labels: geoLabels,
        datasets: [
            { label: 'Valid GPS', data: geoLabels.map(k => geoObj[k].valid), backgroundColor: '#2E5AA7' },
            { label: 'Kosong/Tidak Lengkap', data: geoLabels.map(k => geoObj[k].kosong), backgroundColor: '#F87171' }
        ]
    },
    options: { indexAxis: 'y', scales: { x: { stacked: true }, y: { stacked: true } }, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
});

// DEMOGRAFI KRT UMUR
const demoObj = {!! json_encode($demografiUmur) !!};
new Chart(document.getElementById('barDemografi'), {
    type: 'bar',
    data: { labels: Object.keys(demoObj), datasets: [{ label: 'Jumlah Kepala Keluarga', data: Object.values(demoObj), backgroundColor: '#8B5CF6', borderRadius: 4 }] },
    options: { plugins: { legend: { display: false } } }
});
</script>
@endpush
