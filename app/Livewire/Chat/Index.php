<?php

namespace App\Livewire\Chat;

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
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.chat.index');
    }
}
