<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.chat.index');
    }

    public function mount()
    {
        // dd('wdwd');
       #mark message belogning to receiver as read 
       Message::whereNull('notification_status')
                ->update(['notification_status'=>now()]);
    }
}
