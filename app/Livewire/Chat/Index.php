<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $query;
    public $selectedConversation;


    #[On('check')]
    public function test($id)
    { 
        $this->selectedConversation=$id;
        Message::where('conversation_id',$this->selectedConversation)
        ->where('receiver_id',auth()->id())
        ->whereNull('read_at')
        ->update(['read_at'=>now()]);
    }

    #[On('echo:chat,MessageSent')]
    public function update()
    {

       Message::where('conversation_id',$this->selectedConversation)
                ->where('receiver_id',auth()->id())
                ->whereNull('read_at')
                ->update(['read_at'=>now()]);

    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.chat.index');
    }
}
