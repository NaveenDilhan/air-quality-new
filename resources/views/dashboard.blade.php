<x-layouts.app title="Dashboard">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <!-- Grid of placeholders showing sensor data -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @foreach ($sensors as $sensor)
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-lg">
                    <div class="absolute inset-0 flex justify-center items-center bg-gray-100 dark:bg-gray-800 text-xl text-gray-900 dark:text-white p-4">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <h2 class="font-semibold text-lg">{{ $sensor->sensor_id }} ({{ $sensor->location }})</h2>
                            <p>Status: 
                                <span class="{{ $sensor->is_active ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $sensor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p>Lat: {{ $sensor->latitude }}, Lon: {{ $sensor->longitude }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- AQI Data Table -->
        <div class="relative mt-4 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-lg">
            <div class="p-4">
                <h3 class="text-xl font-semibold mb-4">Recent AQI Data</h3>

                <!-- AQI Data Table -->
                <table class="w-full table-auto text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">Sensor ID</th>
                            <th class="px-4 py-2 border-b">Location</th>
                            <th class="px-4 py-2 border-b">CO2 Level</th>
                            <th class="px-4 py-2 border-b">O2 Level</th>
                            <th class="px-4 py-2 border-b">PM2.5 Level</th>
                            <th class="px-4 py-2 border-b">PM10 Level</th>
                            <th class="px-4 py-2 border-b">NO2 Level</th>
                            <th class="px-4 py-2 border-b">SO2 Level</th>
                            <th class="px-4 py-2 border-b">AQI</th>
                            <th class="px-4 py-2 border-b">Recorded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aqiData as $data)
                            <tr>
                                <td class="px-4 py-2 border-b">{{ $data->sensor->sensor_id }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->sensor->location }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->co2_level }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->o2_level }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->pm25_level }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->pm10_level }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->no2_level }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->so2_level }}</td>
                                <td class="px-4 py-2 border-b">{{ $data->aqi }}</td>
                                <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($data->recorded_at)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($data->recorded_at)->format('H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
