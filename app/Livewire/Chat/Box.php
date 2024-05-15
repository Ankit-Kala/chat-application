<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Box extends Component
{
    use WithFileUploads;

    public $selectedConversation;
    public $body = '';
    public $loadedMessages = [];

    public $userId = null;
    public $showModal = false;
    public $isPoll = false;
    public $attachment;
    // public $data;
    const EVENT_VALUE_UPDATED = 'trix_value_updated';

    public $value;
    public $trixId;
    public $photos = [];

    public function mount($value = ''){
        $this->value = $value;
        $this->trixId = 'trix-' . uniqid();
    }

    // public function updatedValue($value){
    //     $this->dispatch(self::EVENT_VALUE_UPDATED, $this->value);
    // }

    public function completeUpload(string $uploadedUrl, string $trixUploadCompletedEvent){

        foreach($this->photos as $photo){
            
            if($photo->getFilename() == $uploadedUrl) {

                // store in the public/photos location
                $newFilename = $photo->store('public/photos');

                // get the public URL of the newly uploaded file
                // $url = Storage::url($newFilename);
                // $this->dispatch($trixUploadCompletedEvent, [
                //     'url' => $url,
                //     'href' => $url,
                // ]);
                $conversation = Conversation::find($this->selectedConversation);
    
                $createdMessage = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => auth()->id(),
                    'receiver_id' => $conversation->getReceiver()->id,
                    'body' => $newFilename,
            
                ]);
                MessageSent::dispatch($createdMessage);
            }
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function sendMessage()
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
    }
    

    // #[On('fileUploaded')]
    // public function uploadFile($data)
    // {
    //     dd($data);
    // }
    
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
