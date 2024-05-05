<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{

    #[Layout('livewire.frontend.base_layout')]
    public function render()
    {
        return view('livewire.frontend.login');
    }
}
