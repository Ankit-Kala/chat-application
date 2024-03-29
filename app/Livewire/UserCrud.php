<?php

namespace App\Livewire;

use App\Http\Requests\CreateUserRequest;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

class UserCrud extends Component
{
    public $users;
    public $userIdBeingEdited = null;
    public $name, $email, $user_type, $password;
    public $isEdited = false;

    #[On('check')]
    public function test(){
        $this->showModal = false;
        $this->isEdited = false;
    }
    
    public function mount()
    {
        $this->users = User::where('id', '!=', auth()->id())->get();
    }

    public $showModal = false; 

    protected function rules(): array
    {
        return (new CreateUserRequest($this->userIdBeingEdited))->rules();
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
        $this->users = User::where('id', '!=', auth()->id())->get();
        
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
        $this->isEdited = true;

    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
        $this->users = User::where('id', '!=', auth()->id())->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // return view('livewire.user-crud')->layout('layouts.app');

        //or

        return view('livewire.user-crud');
    }

    // public function message($userId)
    // {

    //   $authenticatedUserId = auth()->id();
    //   # Check if conversation already exists
    //   $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
    //             $query->where('sender_id', $authenticatedUserId)
    //                 ->where('receiver_id', $userId);
    //             })
    //         ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
    //             $query->where('sender_id', $userId)
    //                 ->where('receiver_id', $authenticatedUserId);
    //         })->first();
        
    //   if ($existingConversation) {
    //     // dd($existingConversation);
    //       # Conversation already exists, redirect to existing conversation
    //       return redirect()->route('chat', ['query' => $existingConversation->id]);
    //   }
  
    //   # Create new conversation
    //   $createdConversation = Conversation::create([
    //       'sender_id' => $authenticatedUserId,
    //       'receiver_id' => $userId,
    //   ]);
 
    //     return redirect()->route('chat', ['query' => $createdConversation->id]);
        
    // }
}
