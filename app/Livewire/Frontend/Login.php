<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{

    #[Validate('required|email')]
    public string $email = '';
    
    #[Validate('required')]
    public string $password = '';

    public function login()
    {
        $this->validate();

        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            session()->flash('alert', 'Invalid credentials.');
            return;
        }

        $this->redirect(route('admin.dashboard'));
    }

    #[Layout('livewire.frontend.base_layout')]
    public function render()
    {
        return view('livewire.frontend.login');
    }
}
