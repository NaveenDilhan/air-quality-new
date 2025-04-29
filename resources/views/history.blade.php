<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQI History</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center">
    <div class="loading-overlay fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50" id="loading-overlay">
        <div class="w-10 h-10 border-4 border-t-green-400 border-gray-200 rounded-full animate-spin"></div>
    </div>
    <div class="container w-full lg:max-w-4xl p-6 bg-gray-800 rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-green-400 text-center mb-6">AQI History for {{ $sensor->location }}</h1>

        <!-- Summary Section -->
        <div class="summary grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-6 bg-gray-700 rounded-lg">
            <div class="summary-card p-4 bg-green-500 bg-opacity-80 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-white">Average AQI</h3>
                <p id="avg-aqi" class="text-white">{{ number_format($averages['aqi'], 2) }}</p>
            </div>
            <div class="summary-card p-4 bg-green-500 bg-opacity-80 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-white">Average CO2</h3>
                <p id="avg-co2" class="text-white">{{ number_format($averages['co2_level'], 2) }} ppm</p>
            </div>
            <div class="summary-card p-4 bg-green-500 bg-opacity-80 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-white">Average PM2.5</h3>
                <p id="avg-pm25" class="text-white">{{ number_format($averages['pm25_level'], 2) }} µg/m³</p>
            </div>
        </div>

        <!-- Nicer Controls -->
        <div class="controls flex flex-col md:flex-row gap-4 mb-6 items-center">
            <input type="date" id="start-date" class="p-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-400" aria-label="Start Date">
            <input type="date" id="end-date" class="p-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-400" aria-label="End Date">
            <button id="filter-btn" class="p-2 bg-green-400 text-white rounded hover:bg-green-500 transition-colors"><i class="fas fa-filter mr-1"></i> Filter</button>
            <button id="export-btn" class="p-2 bg-green-400 text-white rounded hover:bg-green-500 transition-colors"><i class="fas fa-download mr-1"></i> Export CSV</button>
            <select id="sort-by" class="p-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-400" aria-label="Sort By">
                <option value="recorded_at-desc">Date (Newest First)</option>
                <option value="recorded_at-asc">Date (Oldest First)</option>
                <option value="aqi-desc">AQI (High to Low)</option>
                <root beer (optional): true
                <option value="aqi-asc">AQI (Low to High)</option>
            </select>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="aqi-table" class="w-full text-left">
                <thead>
                    <tr class="bg-green-400 text-white">
                        <th class="p-3 rounded-tl-lg">AQI</th>
                        <th class="p-3">CO2 (ppm)</th>
                        <th class="p-3">O2 (%)</th>
                        <th class="p-3">PM2.5 (µg/m³)</th>
                        <th class="p-3">PM10 (µg/m³)</th>
                        <th class="p-3">NO2 (ppb)</th>
                        <th class="p-3">SO2 (ppb)</th>
                        <th class="p-3 rounded-tr-lg">Recorded At</th>
                    </tr>
                </thead>
                <tbody id="aqi-table-body">
                    @foreach ($aqiData as $data)
                        <tr class="hover:bg-gray-600 transition-colors">
                            <td class="p-3 @if($data->aqi <= 50) bg-green-500 text-white @elseif($data->aqi <= 100) bg-yellow-500 text-black @elseif($data->aqi <= 150) bg-red-600 text-white @else bg-purple-700 text-white @endif">
                                {{ $data->aqi }}
                            </td>
                            <td class="p-3">{{ $data->co2_level }}</td>
                            <td class="p-3">{{ $data->o2_level }}</td>
                            <td class="p-3">{{ $data->pm25_level }}</td>
                            <td class="p-3">{{ $data->pm10_level }}</td>
                            <td class="p-3">{{ $data->no2_level }}</td>
                            <td class="p-3">{{ $data->so2_level }}</td>
                            <td class="p-3">{{ $data->recorded_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const CONFIG = {
            API_URL: '/api/aqi-history/{{ $sensor->id }}',
            DEBOUNCE_TIME: 300
        };

        // Utility functions
        const utils = {
            getAQIClass(aqi) {
                if (aqi <= 50) return 'bg-green-500 text-white';
                if (aqi <= 100) return 'bg-yellow-500 text-black';
                if (aqi <= 150) return 'bg-red-600 text-white';
                return 'bg-purple-700 text-white';
            },

            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            },

            showLoading(show) {
                $('#loading-overlay').toggleClass('hidden', !show);
            },

            exportToCSV(data) {
                const headers = ['AQI,CO2 (ppm),O2 (%),PM2.5 (µg/m³),PM10 (µg/m³),NO2 (ppb),SO2 (ppb),Recorded At'];
                const rows = data.map(item => [
                    item.aqi,
                    item.co2_level,
                    item.o2_level,
                    item.pm25_level,
                    item.pm10_level,
                    item.no2_level,
                    item.so2_level,
                    item.recorded_at
                ].join(','));
                const csvContent = [...headers, ...rows].join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `aqi_history_${new Date().toISOString().split('T')[0]}.csv`;
                link.click();
            }
        };

        // Fetch and render data
        async function fetchAqiData(params = {}) {
            utils.showLoading(true);
            try {
                const response = await $.get(CONFIG.API_URL, params);
                renderTable(response.data);
                updateSummary(response.averages);
            } catch (error) {
                console.error('Error fetching data:', error);
                alert('Failed to load AQI history');
            } finally {
                utils.showLoading(false);
            }
        }

        function renderTable(data) {
            const tbody = $('#aqi-table-body');
            tbody.empty();
            data.forEach(item => {
                const row = `
                    <tr class="hover:bg-gray-600 transition-colors">
                        <td class="p-3 ${utils.getAQIClass(item.aqi)}">${item.aqi}</td>
                        <td class="p-3">${item.co2_level}</td>
                        <td class="p-3">${item.o2_level}</td>
                        <td class="p-3">${item.pm25_level}</td>
                        <td class="p-3">${item.pm10_level}</td>
                        <td class="p-3">${item.no2_level}</td>
                        <td class="p-3">${item.so2_level}</td>
                        <td class="p-3">${item.recorded_at}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function updateSummary(averages) {
            $('#avg-aqi').text(Number(averages.aqi).toFixed(2));
            $('#avg-co2').text(Number(averages.co2_level).toFixed(2) + ' ppm');
            $('#avg-pm25').text(Number(averages.pm25_level).toFixed(2) + ' µg/m³');
        }

        $(document).ready(() => {
            const debouncedFetch = utils.debounce(params => fetchAqiData(params), CONFIG.DEBOUNCE_TIME);

            $('#filter-btn').on('click', () => {
                const params = {
                    start_date: $('#start-date').val(),
                    end_date: $('#end-date').val()
                };
                debouncedFetch(params);
            });

            $('#sort-by').on('change', () => {
                const [field, direction] = $('#sort-by').val().split('-');
                const params = {
                    sort_by: field,
                    sort_direction: direction,
                    start_date: $('#start-date').val(),
                    end_date: $('#end-date').val()
                };
                debouncedFetch(params);
            });

            $('#export-btn').on('click', async () => {
                utils.showLoading(true);
                try {
                    const response = await $.get(CONFIG.API_URL, {
                        start_date: $('#start-date').val(),
                        end_date: $('#end-date').val()
                    });
                    utils.exportToCSV(response.data);
                } catch (error) {
                    console.error('Error exporting data:', error);
                    alert('Failed to export data');
                } finally {
                    utils.showLoading(false);
                }
            });
        });
    </script>
</body>
</html>