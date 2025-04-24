<div class="space-y-8 p-6 bg-gray-50 min-h-screen">
    <!-- Title -->
    <h2 class="text-3xl font-bold text-gray-800">
        {{ $editingSensorId ? 'Edit Sensor' : 'Add Sensor' }}
    </h2>

    <!-- Sensor Form -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <form wire:submit.prevent="{{ $editingSensorId ? 'update' : 'create' }}"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Sensor ID -->
            <div>
                <label for="sensor_id" class="block text-sm font-semibold text-gray-700">Sensor ID</label>
                <input type="text" id="sensor_id" wire:model="sensor_id"
                       class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200">
                @error('sensor_id')
                <p class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-semibold text-gray-700">Location</label>
                <input type="text" id="location" wire:model="location"
                       class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200">
                @error('location')
                <p class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Latitude -->
            <div>
                <label for="latitude" class="block text-sm font-semibold text-gray-700">Latitude</label>
                <input type="number" step="0.0000001" id="latitude" wire:model="latitude"
                       class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200">
                @error('latitude')
                <p class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Longitude -->
            <div>
                <label for="longitude" class="block text-sm font-semibold text-gray-700">Longitude</label>
                <input type="number" step="0.0000001" id="longitude" wire:model="longitude"
                       class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200">
                @error('longitude')
                <p class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="block text-sm font-semibold text-gray-700">Status</label>
                <select wire:model="is_active" id="is_active"
                        class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                <p class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="col-span-full flex flex-wrap gap-3 mt-4">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50"
                        wire:loading.attr="disabled">
                    {{ $editingSensorId ? 'Update Sensor' : 'Create Sensor' }}
                    <span wire:loading wire:target="{{ $editingSensorId ? 'update' : 'create' }}"
                          class="ml-2 animate-spin">⏳</span>
                </button>
                @if ($editingSensorId)
                    <button type="button" wire:click="cancel"
                            class="px-5 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-100 text-green-800 rounded-md shadow">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <input type="text" wire:model.debounce.300ms="search"
               placeholder="🔍 Search by Sensor ID or Location..."
               class="w-full md:w-1/3 px-4 py-2 rounded-md border-gray-300 shadow-sm">
        <select wire:model="filterStatus"
                class="w-full md:w-1/5 px-4 py-2 rounded-md border-gray-300 shadow-sm">
            <option value="">Filter by Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <!-- Sensors Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow-md mt-6">
        <table class="min-w-full">
            <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                <th class="p-3 cursor-pointer" wire:click="sortBy('sensor_id')">Sensor ID</th>
                <th class="p-3 cursor-pointer" wire:click="sortBy('location')">Location</th>
                <th class="p-3">Latitude</th>
                <th class="p-3">Longitude</th>
                <th class="p-3">Status</th>
                <th class="p-3">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($sensors as $sensor)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $sensor->sensor_id }}</td>
                    <td class="p-3">{{ $sensor->location }}</td>
                    <td class="p-3">{{ $sensor->latitude }}</td>
                    <td class="p-3">{{ $sensor->longitude }}</td>
                    <td class="p-3">
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium {{ $sensor->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ $sensor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-3 space-x-2">
                        <button wire:click="edit({{ $sensor->id }})"
                                class="px-3 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $sensor->id }})"
                                class="px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">No sensors found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 px-4">
            {{ $sensors->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
            <div class="bg-white rounded-md shadow-md p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Delete Sensor</h3>
                <p class="mb-4">Are you sure you want to delete this sensor? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button wire:click="deleteSensor"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
