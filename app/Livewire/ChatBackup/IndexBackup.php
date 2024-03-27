<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class IndexBackup extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.chatBackup.index');
    }

    public function mount()
    {
       #mark message belogning to receiver as read 
       Message::whereNull('notification_status')
                ->update(['notification_status'=>now()]);
    }
}
