<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Panel extends Component
{
    public $selectedConversation;
    public $users;
    public $searchQuery;
    public $searchResults;

    public function render()
    {
        $user = auth()->user();
        return view('livewire.chat.panel',[
            'conversations'=>$user->conversations()->latest('updated_at')->get(),
        ]);
    }

    public function message($userId)
    {
        $authenticatedUserId = auth()->id();
     
        # Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);
    }

    public function updatedSearchQuery()
    {
        $user = auth()->user();
        $conversations = $user->conversations()->latest('updated_at')->get();

        $senderIds = $conversations->pluck('sender_id')->toArray();
        $receiverIds = $conversations->pluck('receiver_id')->toArray();
        $conversationUsers = array_merge($senderIds, $receiverIds);

        $this->searchResults = User::where('name', 'like', '%'.$this->searchQuery.'%')
        ->whereNotIn('id', $conversationUsers)
        ->where('id', '!=', $user->id)
        ->get();
    }
}
