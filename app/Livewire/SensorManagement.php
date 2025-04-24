<?php

namespace App\Livewire;

use App\Models\Sensor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class SensorManagement extends Component
{
    use WithPagination;

    #[Rule('required|string|max:255|unique:sensors,sensor_id')]
    public $sensor_id = '';

    #[Rule('required|string|max:255')]
    public $location = '';

    #[Rule('required|numeric|between:-90,90')]
    public $latitude = '';

    #[Rule('required|numeric|between:-180,180')]
    public $longitude = '';

    #[Rule('required|boolean')]
    public $is_active = true;

    public $editingSensorId = null;

    // NEW: For delete confirmation
    public $showDeleteModal = false;
    public $sensorToDelete = null;

    public function create()
    {
        $this->validate();

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
        $this->editingSensorId = $id;
        $this->sensor_id = $sensor->sensor_id;
        $this->location = $sensor->location;
        $this->latitude = $sensor->latitude;
        $this->longitude = $sensor->longitude;
        $this->is_active = $sensor->is_active;
    }

    public function update()
    {
        $this->rules['sensor_id'] = "required|string|max:255|unique:sensors,sensor_id,{$this->editingSensorId}";
        $this->validate();

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

    // Replaces delete($id) with confirmation flow
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

    private function resetForm()
    {
        $this->reset(['sensor_id', 'location', 'latitude', 'longitude', 'is_active', 'editingSensorId']);
    }

    public function render()
    {
        return view('livewire.sensor-management', [
            'sensors' => Sensor::paginate(10),
        ]);
    }
}
