<?php

namespace App\Livewire\Admin\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Settings extends Component
{
    public $oldPassword;
    public $password;
    public $password_confirmation;

    // Define validation rules
    protected $rules = [
        'oldPassword' => 'required',
        'password' => 'required|min:8|confirmed',
    ];

    public function updateInfo()
    {
        $this->validate(); // Uses the rules defined above

        $user = Auth::user();

        if (!Hash::check($this->oldPassword, $user->password)) {
            $this->addError('oldPassword', 'The current password is incorrect.');
            return;
        }

        $user->update(['password' => Hash::make($this->password)]);

        // Reset form fields after updating password
        $this->reset(['oldPassword', 'password', 'password_confirmation']);

        // Flash success message
        session()->flash('success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.profile.settings');
    }
}
