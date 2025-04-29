<x-layouts.app title="Dashboard">
    <div class="flex flex-col gap-8 p-6 w-full h-full rounded-xl">
        <!-- Sensor Cards Grid -->
        <section>
            <h2 class="text-2xl font-bold mb-4">Sensors Overview</h2>
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                @foreach ($sensors as $sensor)
                    <div class="relative h-52 overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-md bg-white dark:bg-neutral-800 hover:shadow-xl transition-all">
                        <div class="flex flex-col justify-between h-full p-5 text-gray-900 dark:text-white">
                            <div>
                                <h3 class="text-lg font-bold truncate">{{ $sensor->sensor_id }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                    📍 {{ $sensor->location }}
                                </p>
                            </div>
                            <div class="mt-4 text-sm space-y-1">
                                <p>
                                    Status:
                                    <span class="font-medium {{ $sensor->is_active ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $sensor->is_active ? '🟢 Active' : '🔴 Inactive' }}
                                    </span>
                                </p>
                                <p>Lat: {{ $sensor->latitude }}, Lon: {{ $sensor->longitude }}</p>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- AQI Data Table -->
        <section>
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-md overflow-x-auto">
                <div class="p-6 bg-white dark:bg-neutral-900">
                    <h3 class="text-2xl font-semibold mb-6">Recent AQI Data</h3>
                    <table class="min-w-full table-auto text-sm text-left border-collapse">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 border-b">Sensor ID</th>
                                <th class="px-4 py-3 border-b">Location</th>
                                <th class="px-4 py-3 border-b">CO₂</th>
                                <th class="px-4 py-3 border-b">O₂</th>
                                <th class="px-4 py-3 border-b">PM2.5</th>
                                <th class="px-4 py-3 border-b">PM10</th>
                                <th class="px-4 py-3 border-b">NO₂</th>
                                <th class="px-4 py-3 border-b">SO₂</th>
                                <th class="px-4 py-3 border-b">AQI</th>
                                <th class="px-4 py-3 border-b">Recorded At</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-900 dark:text-gray-100">
                            @foreach ($aqiData as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                                    <td class="px-4 py-3 border-b">{{ $data->sensor->sensor_id }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->sensor->location }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->co2_level }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->o2_level }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->pm25_level }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->pm10_level }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->no2_level }}</td>
                                    <td class="px-4 py-3 border-b">{{ $data->so2_level }}</td>
                                    <td class="px-4 py-3 border-b font-semibold {{ 
                                        $data->aqi <= 50 ? 'text-green-500' :
                                        ($data->aqi <= 100 ? 'text-yellow-500' :
                                        ($data->aqi <= 150 ? 'text-orange-500' :
                                        ($data->aqi <= 200 ? 'text-red-500' :
                                        'text-purple-600'))) }}">
                                        {{ $data->aqi }}
                                    </td>
                                    <td class="px-4 py-3 border-b">
                                        {{ \Carbon\Carbon::parse($data->recorded_at)->toFormattedDateString() }}<br>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($data->recorded_at)->format('H:i:s') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
