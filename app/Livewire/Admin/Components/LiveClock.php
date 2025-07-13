<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\On;

class LiveClock extends Component
{
    public $currentTime;
    public $currentDate;

    protected $listeners = ['settings-updated' => 'updateTime', 'refresh-clock' => 'updateTime'];

    public function mount()
    {
        $this->updateTime();
    }

    #[On('settings-updated')]
    public function updateTime()
    {
        $now = \App\Helpers\Settings::now();
        $this->currentTime = $now->format(\App\Helpers\Settings::timeFormat() . ':s');
        $this->currentDate = \App\Helpers\Settings::formatDate($now);
    }

    public function render()
    {
        return view('livewire.admin.components.live-clock');
    }
}
