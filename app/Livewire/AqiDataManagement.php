<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AqiData;
use App\Models\Sensor;

class AqiDataManagement extends Component
{
    use WithPagination;

    public $sensor_id;
    public $co2_level;
    public $o2_level;
    public $pm25_level;
    public $pm10_level;
    public $no2_level;
    public $so2_level;
    public $aqi;
    public $recorded_at;
    public $editingAqiDataId = null;
    public $showDeleteModal = false;
    public $aqiDataIdToDelete = null;
    public $search = '';

    protected $rules = [
        'sensor_id' => 'required|exists:sensors,id',
        'co2_level' => 'required|numeric',
        'o2_level' => 'required|numeric',
        'pm25_level' => 'required|numeric',
        'pm10_level' => 'required|numeric',
        'no2_level' => 'required|numeric',
        'so2_level' => 'required|numeric',
        'aqi' => 'required|numeric',
        'recorded_at' => 'required|date',
    ];

    public function render()
    {
        $aqiRecords = AqiData::whereHas('sensor', function ($query) {
                $query->where('sensor_id', 'like', '%'.$this->search.'%');
            })
            ->orderBy('recorded_at', 'desc')
            ->paginate(10);

        $sensors = Sensor::all();

        return view('livewire.aqi-data-management', [
            'aqiRecords' => $aqiRecords,
            'sensors' => $sensors,
        ]);
    }

    public function create()
    {
        $this->validate();

        AqiData::create($this->only([
            'sensor_id', 'co2_level', 'o2_level', 'pm25_level', 'pm10_level', 'no2_level', 'so2_level', 'aqi', 'recorded_at'
        ]));

        $this->resetForm();
        session()->flash('message', 'AQI Data record created successfully.');
    }

    public function edit($id)
    {
        $aqiData = AqiData::findOrFail($id);
        $this->editingAqiDataId = $id;
        $this->sensor_id = $aqiData->sensor_id;
        $this->co2_level = $aqiData->co2_level;
        $this->o2_level = $aqiData->o2_level;
        $this->pm25_level = $aqiData->pm25_level;
        $this->pm10_level = $aqiData->pm10_level;
        $this->no2_level = $aqiData->no2_level;
        $this->so2_level = $aqiData->so2_level;
        $this->aqi = $aqiData->aqi;
        $this->recorded_at = $aqiData->recorded_at;
    }

    public function update()
    {
        $this->validate();

        $aqiData = AqiData::findOrFail($this->editingAqiDataId);
        $aqiData->update($this->only([
            'sensor_id', 'co2_level', 'o2_level', 'pm25_level', 'pm10_level', 'no2_level', 'so2_level', 'aqi', 'recorded_at'
        ]));

        $this->resetForm();
        session()->flash('message', 'AQI Data record updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->aqiDataIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        AqiData::findOrFail($this->aqiDataIdToDelete)->delete();
        $this->showDeleteModal = false;
        $this->aqiDataIdToDelete = null;
        session()->flash('message', 'AQI Data record deleted successfully.');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingAqiDataId = null;
        $this->sensor_id = '';
        $this->co2_level = '';
        $this->o2_level = '';
        $this->pm25_level = '';
        $this->pm10_level = '';
        $this->no2_level = '';
        $this->so2_level = '';
        $this->aqi = '';
        $this->recorded_at = '';
    }
}
