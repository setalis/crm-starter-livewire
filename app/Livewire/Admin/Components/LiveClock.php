<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Carbon\Carbon;

class LiveClock extends Component
{
    public $currentTime;
    public $currentDate;

    public function mount()
    {
        $this->updateTime();
    }

    public function updateTime()
    {
        $now = Carbon::now();
        $this->currentTime = $now->format('H:i:s');
        $this->currentDate = $now->format('d.m.Y');
    }

    public function render()
    {
        return view('livewire.admin.components.live-clock');
    }
}
