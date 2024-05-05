<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Users')]
class Users extends Component
{
    use WithPagination;

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.users', [
            'users' => User::paginate(10),
        ]);
    }
}
