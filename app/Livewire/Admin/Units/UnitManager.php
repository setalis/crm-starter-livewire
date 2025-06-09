<?php

namespace App\Livewire\Admin\Units;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitManager extends Component
{
    use WithPagination;

    public ?string $name = null;
    public ?string $short_name = null;

    public ?int $unit_id = null;

    public bool $isModal = false;

    public function render()
    {
        $units = Unit::paginate(10);
        return view('livewire.admin.units.unit-manager', [
            'units' => $units
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModal = true;
    }

    public function closeModal()
    {
        $this->isModal = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->short_name = '';
        $this->unit_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'short_name' => 'required',
        ]);

        Unit::updateOrCreate(['id' => $this->unit_id], [
            'name' => $this->name,
            'short_name' => $this->short_name,
        ]);

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unit_id = $id;
        $this->name = $unit->name;
        $this->short_name = $unit->short_name;

        $this->isModal = true;
    }

    public function delete($id)
    {
        Unit::find($id)->delete();
    }
}
