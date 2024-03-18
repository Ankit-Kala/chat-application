<?php

namespace App\Livewire;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;

class UserCrud extends Component
{
    public $users;
    public $userIdBeingEdited = null;
    public $name, $email, $user_type, $password;

    public function mount()
    {
        $this->users = User::all();
    }

    public $showModal = false; 

    protected function rules(): array
    {
        return (new CreateUserRequest())->rules();
    }

    public function createOrUpdateUser()
    {
        $this->validate();
        User::updateOrCreate(
            ['id' => $this->userIdBeingEdited],
            [
                'name' => $this->name,
                'email' => $this->email,
                'user_type' => $this->user_type,
                'password' => Hash::make($this->password),
            ]
        );

        $this->reset(['name', 'email', 'user_type', 'password', 'userIdBeingEdited', 'showModal']);
        $this->users = User::all();
    }


    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'user_type', 'password', 'userIdBeingEdited']);
        $this->showModal = true;
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->userIdBeingEdited = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->user_type = $user->user_type;
        $this->showModal = true;
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
        $this->users = User::all();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // return view('livewire.user-crud')->layout('layouts.app');

        //or

        return view('livewire.user-crud');
    }
}
