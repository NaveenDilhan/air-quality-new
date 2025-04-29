<div class="p-6">

    {{-- Success message --}}
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{--Generate AQI Alerts Button --}}
    <div class="mb-6">
        <button 
            wire:click="generateAqiAlerts" 
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Generate AQI Alerts Now
        </button>
    </div>

    {{-- Alert Form (Create / Update) --}}
    <form wire:submit.prevent="{{ $alertIdBeingEdited ? 'updateAlert' : 'createAlert' }}" class="space-y-4 mb-6">
        <div>
            <label class="block mb-1">AQI Data</label>
            <select wire:model="aqi_data_id" class="w-full border rounded p-2">
                <option value="">Select AQI Data</option>
                @foreach ($aqiDataList as $aqi)
                    <option value="{{ $aqi->id }}">ID: {{ $aqi->id }} (AQI: {{ $aqi->aqi_value ?? 'N/A' }})</option>
                @endforeach
            </select>
            @error('aqi_data_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1">Alert Level</label>
            <input type="text" wire:model="alert_level" class="w-full border rounded p-2" placeholder="e.g., High, Moderate, Low">
            @error('alert_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1">Message</label>
            <textarea wire:model="message" class="w-full border rounded p-2" rows="4" placeholder="Enter alert message"></textarea>
            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                {{ $alertIdBeingEdited ? 'Update' : 'Create' }} Alert
            </button>

            @if ($alertIdBeingEdited)
                <button type="button" wire:click="resetForm" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Cancel
                </button>
            @endif
        </div>
    </form>

    <hr class="my-6">

    {{-- Existing Alerts Table --}}
    <h2 class="text-xl font-semibold mb-4">Existing Alerts</h2>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">ID</th>
                <th class="border p-2">AQI Data ID</th>
                <th class="border p-2">Alert Level</th>
                <th class="border p-2">Message</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alerts as $alert)
                <tr>
                    <td class="border p-2">{{ $alert->id }}</td>
                    <td class="border p-2">{{ $alert->aqi_data_id }}</td>
                    <td class="border p-2">{{ $alert->alert_level }}</td>
                    <td class="border p-2">{{ $alert->message }}</td>
                    <td class="border p-2 flex gap-2">
                        <button wire:click="editAlert({{ $alert->id }})" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button wire:click="confirmAlertDeletion({{ $alert->id }})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $alerts->links() }}
    </div>

    {{-- Confirm Deletion Modal --}}
    @if ($confirmingAlertDeletion)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-700 bg-opacity-75">
            <div class="bg-white p-6 rounded shadow-lg">
                <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
                <p>Are you sure you want to delete this alert?</p>
                <div class="flex gap-4 mt-6">
                    <button wire:click="deleteAlert" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Delete</button>
                    <button wire:click="$set('confirmingAlertDeletion', false)" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>
    @endif

</div>
