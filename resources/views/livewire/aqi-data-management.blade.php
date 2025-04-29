<div class="p-6 space-y-6">

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div role="alert" class="p-4 mb-4 text-green-700 bg-green-100 rounded dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    {{-- AQI Form --}}
    <form wire:submit.prevent="{{ $editingAqiDataId ? 'update' : 'create' }}"
          class="space-y-4 bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
            {{ $editingAqiDataId ? 'Edit AQI Data' : 'Add New AQI Data' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sensor</label>
                <select wire:model="sensor_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-zinc-700 dark:text-white">
                    <option value="">Select Sensor</option>
                    @foreach ($sensors as $sensor)
                        <option value="{{ $sensor->id }}">{{ $sensor->sensor_id }}</option>
                    @endforeach
                </select>
                @error('sensor_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <x-form-input id="co2_level" label="CO₂ Level (ppm)" type="number" model="co2_level" placeholder="Enter CO₂ Level" />
            <x-form-input id="o2_level" label="O₂ Level (%)" type="number" model="o2_level" placeholder="Enter O₂ Level" />
            <x-form-input id="pm25_level" label="PM2.5 Level (µg/m³)" type="number" model="pm25_level" placeholder="Enter PM2.5 Level" />
            <x-form-input id="pm10_level" label="PM10 Level (µg/m³)" type="number" model="pm10_level" placeholder="Enter PM10 Level" />
            <x-form-input id="no2_level" label="NO₂ Level (ppb)" type="number" model="no2_level" placeholder="Enter NO₂ Level" />
            <x-form-input id="so2_level" label="SO₂ Level (ppb)" type="number" model="so2_level" placeholder="Enter SO₂ Level" />
            <x-form-input id="aqi" label="AQI" type="number" model="aqi" placeholder="Enter AQI Value" />
            <x-form-input id="recorded_at" label="Recorded At" type="datetime-local" model="recorded_at" placeholder="Select Date and Time" />
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                {{ $editingAqiDataId ? 'Update' : 'Add' }}
            </button>
            <button type="button" wire:click="cancel" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded">
                Cancel
            </button>
        </div>
    </form>

    {{-- Search --}}
    <div class="flex items-center gap-4 mt-6">
        <input type="text" wire:model="search"
               class="p-3 rounded-md border dark:bg-zinc-700 dark:text-white w-72"
               placeholder="Search by Sensor ID..." />
    </div>

    {{-- AQI Table --}}
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow mt-6">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-zinc-100 dark:bg-zinc-700 text-zinc-900 dark:text-white">
                <tr>
                    <th class="px-6 py-3">Sensor ID</th>
                    <th class="px-6 py-3">CO₂</th>
                    <th class="px-6 py-3">O₂</th>
                    <th class="px-6 py-3">PM2.5</th>
                    <th class="px-6 py-3">PM10</th>
                    <th class="px-6 py-3">AQI</th>
                    <th class="px-6 py-3">Recorded At</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aqiRecords as $record)
                    <tr class="border-t dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        <td class="px-6 py-3">{{ $record->sensor->sensor_id }}</td>
                        <td class="px-6 py-3">{{ $record->co2_level }}</td>
                        <td class="px-6 py-3">{{ $record->o2_level }}</td>
                        <td class="px-6 py-3">{{ $record->pm25_level }}</td>
                        <td class="px-6 py-3">{{ $record->pm10_level }}</td>
                        <td class="px-6 py-3">{{ $record->aqi }}</td>
                        <td class="px-6 py-3">{{ \Carbon\Carbon::parse($record->recorded_at)->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-3 space-x-2">
                            <button wire:click="edit({{ $record->id }})" class="text-blue-600 hover:underline font-semibold">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button wire:click="confirmDelete({{ $record->id }})" class="text-red-600 hover:underline font-semibold">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                            No AQI records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $aqiRecords->links('pagination::tailwind') }}
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white dark:bg-zinc-900 rounded-lg p-6 shadow-lg max-w-sm w-full">
                <h2 class="text-lg font-semibold mb-4 dark:text-white">Confirm Deletion</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-6">
                    Are you sure you want to delete this AQI record?
                </p>
                <div class="flex justify-end gap-4">
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded">
                        Delete
                    </button>
                    <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
