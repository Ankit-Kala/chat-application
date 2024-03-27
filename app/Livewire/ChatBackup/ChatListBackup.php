<?php

namespace App\Livewire\Chat;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatListBackup extends Component
{
    public $selectedConversation;

    #[On('echo:chat,MessageSent')]
    public function render()
    {

        $user= auth()->user();
                return view('livewire.chatBackup.chat-list',[
            'conversations'=>$user->conversations()->latest('updated_at')->get()
        ]);
    }
}
