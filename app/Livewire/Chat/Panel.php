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
    public $showSearch = false;

    public function render()
    {
        $user = auth()->user();
        $conversations = $user->conversations()->latest('updated_at')->get();

        // Retrieve users based on the search query or all users if no search query
        $this->users = $this->searchQuery
            ? $this->searchResults
            : User::where('id', '!=', auth()->id())->get();

        return view('livewire.chat.panel', [
            'conversations' => $conversations,
        ]);
    }

    public function message($userId)
    {
        $authenticatedUserId = auth()->id();
        # Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
                    $query->where('sender_id', $authenticatedUserId)
                        ->where('receiver_id', $userId);
                    })
                ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', $authenticatedUserId);
                })->first();
        // dd($existingConversation->id);
      if ($existingConversation) {
           
            $existingConversation->touch();
            // Close the search section
            $this->showSearch = false;
            $this->dispatch('check', id: $existingConversation->id)->to(\App\Livewire\Chat\Index::class);
            return;
       }
        // Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);

         // Close the search section
         $this->showSearch = false;
         $this->dispatch('check', id: $createdConversation->id)->to(\App\Livewire\Chat\Index::class);
    }

    public function updatedSearchQuery()
    {
        $user = auth()->user();

        // Search users based on the search query
        $this->searchResults = User::where('name', 'like', '%'.$this->searchQuery.'%')
                               ->where('id', '!=', auth()->id())->get();
    }
}
