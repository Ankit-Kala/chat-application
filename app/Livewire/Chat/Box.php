<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class Box extends Component
{
    public $selectedConversation;
    public $body = '';
    public $loadedMessages = [];

    public $userId = null;
    public $showModal = false;
    public $isPoll = false;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function sendMessage($isPoll = false)
    {
        $this->validate(['body' => 'required|string']);
    
        // Retrieve the conversation object using its ID
        $conversation = Conversation::find($this->selectedConversation);
    
        $createdMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $conversation->getReceiver()->id,
            'body' => $this->body,
    
        ]);
        $this->reset('body');
        MessageSent::dispatch($createdMessage);
        $this->isPoll = $isPoll;
    }
    
    #[On('echo:chat,MessageSent')]
    public function render()
    {
        $conversation = Conversation::find($this->selectedConversation);
        if ($conversation) {
            $this->loadedMessages = Message::where('conversation_id', $conversation->id)->get();
            $this->dispatch('scroll-bottom');
        }
    
        return view('livewire.chat.box',[
            'loadedMessages' => $this->loadedMessages,
            'selected'=>$conversation
        ]);
    }
}
