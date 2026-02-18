<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEN-Sentinel Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        #map { height: 100%; min-height: 400px; border-radius: 0.75rem; }
        .sidebar-link { @apply flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors duration-200; }
        .sidebar-link.active { @apply bg-gray-800 text-white border-l-4 border-blue-500; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-gray-800">
                <h1 class="text-xl font-bold tracking-wider">DEN-SENTINEL</h1>
            </div>
            <nav class="flex-1 mt-6 overflow-y-auto">
                <a href="#" class="sidebar-link active">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 7m0 13V7m0 0L9.553 4.553A1 1 0 019 3.618" /></svg>
                    Peta Sebaran
                </a>
                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Analisa Risiko
                </a>
                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Settings
                </a>
            </nav>
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center">
                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="User Avatar">
                    <div class="ml-3">
                        <p class="text-sm font-medium">Administrator</p>
                        <p class="text-xs text-gray-500">View Profile</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
                <div class="flex items-center">
                    <button class="md:hidden text-gray-500 focus:outline-none focus:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-700 ml-4 md:ml-0">Dashboard Monitoring DBD</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        Last Update: {{ $avgStats->last_update ? \Carbon\Carbon::parse($avgStats->last_update)->diffForHumans() : 'No Data' }}
                    </span>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Status Card -->
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 {{ $riskCounts['bahaya'] > 0 ? 'border-red-500' : ($riskCounts['waspada'] > 0 ? 'border-yellow-500' : 'border-green-500') }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Status Wilayah</p>
                                <h3 class="text-2xl font-bold mt-1 text-gray-800">
                                    @if($riskCounts['bahaya'] > 0)
                                        <span class="text-red-600">BAHAYA</span>
                                    @elseif($riskCounts['waspada'] > 0)
                                        <span class="text-yellow-600">WASPADA</span>
                                    @else
                                        <span class="text-green-600">AMAN</span>
                                    @endif
                                </h3>
                            </div>
                            <div class="p-2 bg-gray-50 rounded-lg">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-gray-500">
                             {{ $riskCounts['bahaya'] }} Bahaya, {{ $riskCounts['waspada'] }} Waspada
                        </div>
                    </div>

                    <!-- Temp Card -->
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Rata-rata Suhu</p>
                                <h3 class="text-2xl font-bold mt-1 text-gray-800">{{ number_format($avgStats->avg_temp, 1) }}°C</h3>
                            </div>
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                         <div class="mt-4 text-xs text-gray-500">
                             Ideal: 25-30°C
                        </div>
                    </div>

                    <!-- Rainfall Card -->
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-cyan-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Curah Hujan</p>
                                <h3 class="text-2xl font-bold mt-1 text-gray-800">{{ number_format($avgStats->avg_rain, 1) }} mm</h3>
                            </div>
                            <div class="p-2 bg-cyan-50 rounded-lg">
                                <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            </div>
                        </div>
                         <div class="mt-4 text-xs text-gray-500">
                             Rata-rata hari ini
                        </div>
                    </div>

                    <!-- Sensors Card -->
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Sensor Aktif</p>
                                <h3 class="text-2xl font-bold mt-1 text-gray-800">{{ $activeSensors }} / {{ $totalSensors }}</h3>
                            </div>
                            <div class="p-2 bg-purple-50 rounded-lg">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                        </div>
                         <div class="mt-4 text-xs text-gray-500">
                             Perangkat terpasang
                        </div>
                    </div>
                </div>

                <!-- Map & Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Map -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 px-2">Peta Sebaran Risiko (Real-time)</h3>
                        <div id="map" class="z-0"></div>
                    </div>
                    
                    <!-- Risk Distribution Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 px-2">Grafik Tren Hari Ini</h3>
                        <div id="tempChart"></div>
                    </div>
                </div>

                <!-- Recent Activity Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-700">Data Masuk Terakhir</h3>
                        <button class="text-blue-500 text-sm hover:underline">Lihat Semua</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-50">Waktu</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-50">Lokasi / Sensor</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-50">Suhu</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-50">Kelembaban</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-50">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $log->sensor->name }} <br>
                                        <span class="text-xs text-gray-400">{{ $log->sensor->device_id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $log->temperature }}°C
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $log->humidity }}%
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $log->risk_level == 'bahaya' ? 'bg-red-100 text-red-800' : 
                                               ($log->risk_level == 'waspada' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ strtoupper($log->risk_level) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // Data from Controller
        const sensors = @json($sensors);
        const chartData = @json($chartData);

        // 1. Initialize Leaflet Map
        const map = L.map('map').setView([-6.200000, 106.816666], 13); // Default Jakarta
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add Markers
        sensors.forEach(sensor => {
            if(sensor.logs && sensor.logs.length > 0) {
                const log = sensor.logs[0];
                let color = 'green';
                if(log.risk_level === 'bahaya') color = 'red';
                else if(log.risk_level === 'waspada') color = 'orange';

                // Simple Circle Marker
                const circle = L.circleMarker([sensor.latitude, sensor.longitude], {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.5,
                    radius: 10
                }).addTo(map);

                circle.bindPopup(`
                    <b>${sensor.name}</b><br>
                    Status: <b style="color:${color}">${log.risk_level.toUpperCase()}</b><br>
                    Temp: ${log.temperature}°C<br>
                    Hujan: ${log.rainfall}mm
                `);
            } else {
                 // No data marker
                 L.circleMarker([sensor.latitude, sensor.longitude], {
                    color: 'gray',
                    radius: 8
                }).addTo(map).bindPopup(`<b>${sensor.name}</b><br>Belum ada data`);
            }
        });

        // 2. Initialize ApexCharts
        const options = {
            series: [{
                name: 'Suhu (°C)',
                data: chartData.map(d => d.temp)
            }, {
                name: 'Kelembaban (%)',
                data: chartData.map(d => d.hum)
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: chartData.map(d => `${d.hour}:00`),
            },
            colors: ['#3B82F6', '#10B981'],
        };

        const chart = new ApexCharts(document.querySelector("#tempChart"), options);
        chart.render();
    </script>
</body>
</html>
