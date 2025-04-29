<?php

namespace App\Livewire;

use App\Models\Sensor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class SensorManagement extends Component
{
    use WithPagination;

    // Make search and filter available via query string
    public $search = '';
    public $filterStatus = '';

    public $sensor_id = '';
    public $location = '';
    public $latitude = '';
    public $longitude = '';
    public $is_active = true;

    public $editingSensorId = null;

    public $showDeleteModal = false;
    public $sensorToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    #[Rule('required|string|max:255|unique:sensors,sensor_id')]
    public $rules = [
        'sensor_id' => 'required|string|max:255|unique:sensors,sensor_id',
        'location' => 'required|string|max:255',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'is_active' => 'required|boolean',
    ];

    public function create()
    {
        $this->validateOnly('sensor_id');
        $this->validateOnly('location');
        $this->validateOnly('latitude');
        $this->validateOnly('longitude');
        $this->validateOnly('is_active');

        Sensor::create([
            'sensor_id' => $this->sensor_id,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        session()->flash('message', 'Sensor created successfully.');
    }

    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);

        $this->editingSensorId = $sensor->id;
        $this->sensor_id = $sensor->sensor_id;
        $this->location = $sensor->location;
        $this->latitude = $sensor->latitude;
        $this->longitude = $sensor->longitude;
        $this->is_active = $sensor->is_active;
    }

    public function update()
    {
        $this->validate([
            'sensor_id' => 'required|string|max:255|unique:sensors,sensor_id,' . $this->editingSensorId,
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_active' => 'required|boolean',
        ]);

        Sensor::findOrFail($this->editingSensorId)->update([
            'sensor_id' => $this->sensor_id,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        session()->flash('message', 'Sensor updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->sensorToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteSensor()
    {
        if ($this->sensorToDelete) {
            Sensor::findOrFail($this->sensorToDelete)->delete();
            session()->flash('message', 'Sensor deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->sensorToDelete = null;
    }

    public function cancel()
    {
        $this->resetForm();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    private function resetForm()
    {
        $this->reset([
            'sensor_id',
            'location',
            'latitude',
            'longitude',
            'is_active',
            'editingSensorId',
        ]);
    }

    public function render()
    {
        $query = Sensor::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('sensor_id', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus);
        }

        $sensors = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.sensor-management', [
            'sensors' => $sensors,
        ]);
    }
}
