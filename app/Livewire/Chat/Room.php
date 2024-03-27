<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class Room extends Component
{
    public $query;
    public $selectedConversation;

    #[On('echo:chat,MessageSent')]
    public function mount()
    {

        $this->selectedConversation= Conversation::findOrFail($this->query);

       #mark message belogning to receiver as read 
       Message::where('conversation_id',$this->selectedConversation->id)
                ->where('receiver_id',auth()->id())
                ->whereNull('read_at')
                ->update(['read_at'=>now()]);

    }
    
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.chat.room');
    }
}
