<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatBoxBackup extends Component
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

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body,

        ]);
        $this->reset('body');
        MessageSent::dispatch($createdMessage);
        $this->isPoll = $isPoll;

    }

    // #[On('echo:chat,MessageSent')]
    // public function loadMessages()
    // {

    //     $userId = auth()->id();

    //     $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)->get();
    //     $this->dispatch('scroll-bottom');
    //     return $this->loadedMessages;
    // }

    // public function mount()
    // {

    //     $this->loadMessages();
    // }
    
    #[On('echo:chat,MessageSent')]
    public function render()
    {
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)->get();
        $this->dispatch('scroll-bottom');
        return view('livewire.chatBackup.chat-box',[
            'loadedMessages' => $this->loadedMessages
        ]);
    }
}
