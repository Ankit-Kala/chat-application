<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;



    public function sendMessage()
    {

        $this->validate(['body' => 'required|string']);

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body

        ]);


        $this->reset('body');
        $this->loadedMessages->push($createdMessage);

    }

    public function loadMessages()
    {

        $userId = auth()->id();

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)->get();


        return $this->loadedMessages;
    }

    public function mount()
    {

        $this->loadMessages();
    }
    
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
