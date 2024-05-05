<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
