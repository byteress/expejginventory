<?php

namespace App\Livewire\Admin\User;

use App\Exceptions\ErrorHandler;
use App\Models\User;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Users')]
class Users extends Component
{
    use WithPagination;

    public function delete(IIdentityAndAccessService $IdentityAndAccessService, $userId)
    {
        $result = $IdentityAndAccessService->delete($userId);

        $this->dispatch('close-modal');

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }
        
        session()->flash('success', 'Branch deleted successfully.');

    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.users', [
            'users' => User::paginate(10),
        ]);
    }
}
