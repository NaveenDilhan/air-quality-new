<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQI History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #222;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2rem;
            text-align: center;
        }

        .summary {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .summary-card {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            background: #e0f7fa;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
            align-items: center;
        }

        .controls input, .controls select, .controls button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .controls button {
            background: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .controls button:hover {
            background: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #3498db;
            color: #fff;
            cursor: pointer;
        }

        th:hover {
            background: #2980b9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .aqi-healthy { background-color: green; color: #fff; }
        .aqi-moderate { background-color: yellow; color: #333; }
        .aqi-unhealthy { background-color: orange; color: #fff; }
        .aqi-hazardous { background-color: red; color: #fff; }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .summary {
                flex-direction: column;
            }

            .controls {
                flex-direction: column;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    <div class="container">
        <h1>AQI History for {{ $sensor->location }}</h1>

        <!-- Summary Section -->
        <div class="summary">
            <div class="summary-card">
                <h3>Average AQI</h3>
                <p id="avg-aqi">{{ number_format($averages['aqi'], 2) }}</p>
            </div>
            <div class="summary-card">
                <h3>Average CO2</h3>
                <p id="avg-co2">{{ number_format($averages['co2_level'], 2) }} ppm</p>
            </div>
            <div class="summary-card">
                <h3>Average PM2.5</h3>
                <p id="avg-pm25">{{ number_format($averages['pm25_level'], 2) }} µg/m³</p>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls">
            <input type="date" id="start-date" aria-label="Start Date">
            <input type="date" id="end-date" aria-label="End Date">
            <button id="filter-btn"><i class="fas fa-filter"></i> Filter</button>
            <button id="export-btn"><i class="fas fa-download"></i> Export CSV</button>
            <select id="sort-by" aria-label="Sort By">
                <option value="recorded_at-desc">Date (Newest First)</option>
                <option value="recorded_at-asc">Date (Oldest First)</option>
                <option value="aqi-desc">AQI (High to Low)</option>
                <option value="aqi-asc">AQI (Low to High)</option>
            </select>
        </div>

        <!-- Data Table -->
        <table id="aqi-table">
            <thead>
                <tr>
                    <th>AQI</th>
                    <th>CO2 (ppm)</th>
                    <th>O2 (%)</th>
                    <th>PM2.5 (µg/m³)</th>
                    <th>PM10 (µg/m³)</th>
                    <th>NO2 (ppb)</th>
                    <th>SO2 (ppb)</th>
                    <th>Recorded At</th>
                </tr>
            </thead>
            <tbody id="aqi-table-body">
                @foreach ($aqiData as $data)
                    <tr>
                        <td class="{{ $data->aqi <= 50 ? 'aqi-healthy' : ($data->aqi <= 100 ? 'aqi-moderate' : ($data->aqi <= 150 ? 'aqi-unhealthy' : 'aqi-hazardous')) }}">
                            {{ $data->aqi }}
                        </td>
                        <td>{{ $data->co2_level }}</td>
                        <td>{{ $data->o2_level }}</td>
                        <td>{{ $data->pm25_level }}</td>
                        <td>{{ $data->pm10_level }}</td>
                        <td>{{ $data->no2_level }}</td>
                        <td>{{ $data->so2_level }}</td>
                        <td>{{ $data->recorded_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const CONFIG = {
            API_URL: '/api/aqi-history/{{ $sensor->id }}',
            DEBOUNCE_TIME: 300
        };

        // Utility functions
        const utils = {
            getAQIClass(aqi) {
                if (aqi <= 50) return 'aqi-healthy';
                if (aqi <= 100) return 'aqi-moderate';
                if (aqi <= 150) return 'aqi-unhealthy';
                return 'aqi-hazardous';
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
                $('#loading-overlay').css('display', show ? 'flex' : 'none');
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
                    <tr>
                        <td class="${utils.getAQIClass(item.aqi)}">${item.aqi}</td>
                        <td>${item.co2_level}</td>
                        <td>${item.o2_level}</td>
                        <td>${item.pm25_level}</td>
                        <td>${item.pm10_level}</td>
                        <td>${item.no2_level}</td>
                        <td>${item.so2_level}</td>
                        <td>${item.recorded_at}</td>
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

        // Event handlers
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