<div class="p-6 space-y-6">

    @vite('resources/css/app.css')

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div role="alert" class="p-4 mb-4 text-green-700 bg-green-100 rounded dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    {{-- Sensor Form --}}
    <form wire:submit.prevent="{{ $editingSensorId ? 'update' : 'create' }}"
          class="space-y-4 bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-lg transition-all duration-300">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
            {{ $editingSensorId ? 'Edit Sensor' : 'Add New Sensor' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Sensor ID --}}
            <x-form-input id="sensor_id" label="Sensor ID" type="text" model="sensor_id" placeholder="Enter Sensor ID" />

            {{-- Location --}}
            <x-form-input id="location" label="Location" type="text" model="location" placeholder="Enter Location" />

            {{-- Latitude --}}
            <x-form-input id="latitude" label="Latitude" type="number" model="latitude" placeholder="Enter Latitude" />

            {{-- Longitude --}}
            <x-form-input id="longitude" label="Longitude" type="number" model="longitude" placeholder="Enter Longitude" />

            {{-- Active Checkbox --}}
            <div class="flex items-center space-x-2 col-span-full">
                <input type="checkbox" id="is_active" wire:model.defer="is_active"
                       class="h-5 w-5 text-blue-600 rounded focus:ring-blue-500 dark:bg-zinc-700">
                <label for="is_active" class="text-zinc-700 dark:text-zinc-200">Active</label>
            </div>
        </div>

        {{-- Form Buttons --}}
        <div class="flex flex-wrap gap-4 mt-6">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded transition">
                {{ $editingSensorId ? 'Update Sensor' : 'Add Sensor' }}
            </button>
            <button type="button" wire:click="cancel"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded transition">
                Cancel
            </button>
        </div>
    </form>

    {{-- Search and Filter --}}
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center gap-4 mt-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" wire:model.debounce.500ms="search"
                   class="p-3 rounded-md border dark:bg-zinc-700 dark:text-white w-72"
                   placeholder="Search Sensors..." />

            <select wire:model="filterStatus"
                    class="p-3 rounded-md border dark:bg-zinc-700 dark:text-white w-40">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <button type="button" wire:click="clearFilters"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded transition">
            Clear Filters
        </button>
    </div>

    {{-- Sensor Table --}}
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow mt-6">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-zinc-100 dark:bg-zinc-700 text-zinc-900 dark:text-white">
                <tr>
                    <th class="px-6 py-3">Sensor ID</th>
                    <th class="px-6 py-3">Location</th>
                    <th class="px-6 py-3">Latitude</th>
                    <th class="px-6 py-3">Longitude</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sensors as $sensor)
                    <tr class="border-t dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        <td class="px-6 py-3">{{ $sensor->sensor_id }}</td>
                        <td class="px-6 py-3">{{ $sensor->location }}</td>
                        <td class="px-6 py-3">{{ $sensor->latitude }}</td>
                        <td class="px-6 py-3">{{ $sensor->longitude }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-4 py-1 text-xs font-semibold rounded-full
                                {{ $sensor->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $sensor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 space-x-2">
                            <button type="button" wire:click="edit({{ $sensor->id }})"
                                    class="text-blue-600 hover:underline font-semibold">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button type="button" wire:click="confirmDelete({{ $sensor->id }})"
                                    class="text-red-600 hover:underline font-semibold">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                            No sensors found. Try adjusting your search or filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $sensors->links('pagination::tailwind') }}
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center transition-all duration-300">
            <div class="bg-white dark:bg-zinc-900 rounded-lg p-6 shadow-lg max-w-sm w-full">
                <h2 id="modal-title" class="text-lg font-semibold mb-4 dark:text-white">Confirm Deletion</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-6">
                    Are you sure you want to delete this sensor? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-4">
                    <button type="button" wire:click="deleteSensor"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded transition">
                        Delete
                    </button>
                    <button type="button" wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
