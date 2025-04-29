<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alert;
use App\Models\AqiData;
use App\Services\AqiAlertService;
use Livewire\WithPagination;

class AlertManagement extends Component
{
    use WithPagination;

    public $aqi_data_id;
    public $alert_level;
    public $message;
    public $alertIdBeingEdited = null;

    public $confirmingAlertDeletion = false;
    public $alertIdBeingDeleted = null;

    protected $rules = [
        'aqi_data_id' => 'required|exists:aqi_data,id',
        'alert_level' => 'required|string|max:255',
        'message' => 'required|string|max:1000',
    ];

    public function resetForm()
    {
        $this->aqi_data_id = null;
        $this->alert_level = '';
        $this->message = '';
        $this->alertIdBeingEdited = null;
    }

    public function createAlert()
    {
        $this->validate();

        Alert::create([
            'aqi_data_id' => $this->aqi_data_id,
            'alert_level' => $this->alert_level,
            'message' => $this->message,
        ]);

        session()->flash('success', 'Alert created successfully.');
        $this->resetForm();
    }

    public function editAlert($id)
    {
        $alert = Alert::findOrFail($id);
        $this->alertIdBeingEdited = $alert->id;
        $this->aqi_data_id = $alert->aqi_data_id;
        $this->alert_level = $alert->alert_level;
        $this->message = $alert->message;
    }

    public function updateAlert()
    {
        $this->validate();

        $alert = Alert::findOrFail($this->alertIdBeingEdited);

        $alert->update([
            'aqi_data_id' => $this->aqi_data_id,
            'alert_level' => $this->alert_level,
            'message' => $this->message,
        ]);

        session()->flash('success', 'Alert updated successfully.');
        $this->resetForm();
    }

    public function confirmAlertDeletion($id)
    {
        $this->confirmingAlertDeletion = true;
        $this->alertIdBeingDeleted = $id;
    }

    public function deleteAlert()
    {
        Alert::findOrFail($this->alertIdBeingDeleted)->delete();
        $this->confirmingAlertDeletion = false;
        $this->alertIdBeingDeleted = null;
        session()->flash('success', 'Alert deleted successfully.');
    }

    public function generateAqiAlerts()
    {
        $aqiAlertService = new AqiAlertService();
        $aqiAlertService->generateAlerts();

        session()->flash('success', 'AQI Alerts generated successfully.');
    }

    public function render()
    {
        return view('livewire.alert-management', [
            'alerts' => Alert::orderBy('created_at', 'desc')->paginate(10),
            'aqiDataList' => AqiData::all(),
        ]);
    }
}
