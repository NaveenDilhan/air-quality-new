<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQI History - Sensor {{ $sensor_id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        a {
            color: #00ccff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .no-data {
            color: #555;
            font-size: 1.1rem;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .pagination .current {
            background-color: #00ccff;
            color: white;
            border-color: #00ccff;
        }
    </style>
</head>
<body>
    <h1>AQI History for Sensor {{ $sensor_id }}</h1>
    @if($records->isEmpty())
        <p class="no-data">No data available for this sensor.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>CO2 Level</th>
                    <th>O2 Level</th>
                    <th>PM2.5 Level</th>
                    <th>PM10 Level</th>
                    <th>NO2 Level</th>
                    <th>SO2 Level</th>
                    <th>AQI</th>
                    <th>Recorded At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $record->co2_level }} ppm</td>
                        <td>{{ $record->o2_level }} %</td>
                        <td>{{ $record->pm25_level }} µg/m³</td>
                        <td>{{ $record->pm10_level }} µg/m³</td>
                        <td>{{ $record->no2_level }} ppb</td>
                        <td>{{ $record->so2_level }} ppb</td>
                        <td>{{ $record->aqi }}</td>
                        <td>{{ $record->recorded_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $records->links() }}
        </div>
    @endif
    <p><a href="{{ route('map') }}">Back to Map</a></p>
</body>
</html>