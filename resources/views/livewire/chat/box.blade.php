
<div 
    x-data="{
        height:0,
        conversationElement:document.getElementById('conversation'),
    }"
    x-init="
        height = conversationElement.scrollHeight;
        $nextTick(() => conversationElement.scrollTop = height);
    "
    @scroll-bottom.window="
        $nextTick(() => conversationElement.scrollTop = conversationElement.scrollHeight);
    "
    class="w-full overflow-hidden"
>


    <div class="border-b flex flex-col overflow-y-scroll grow h-full">

    
    {{-- header --}}
    <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b " >

        <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">

            <a class="" href="{{route('chat.index')}}">


                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                  </svg>
                  
            </a>


            {{-- avatar --}}

            <!-- Avatar -->
            <button wire:click="openModal">
                <div class="shrink-0">
                    <x-avatar class="h-9 w-9 lg:w-11 lg:h-11" />
                </div>
            </button>
        
            <h6 class="font-bold truncate"> {{$selected->getReceiver()->email}} </h6>

        </div>
        @if($showModal)   
        <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
            <div class="max-w-xl mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="bg-cover h-40" style="background-image: url('https://images.unsplash.com/photo-1522093537031-3ee69e6b1746?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=a634781c01d2dd529412c2d1e2224ec0&auto=format&fit=crop&w=2098&q=80');"></div>
                <div class="border-b px-8">
                    <div class="text-center sm:text-left sm:flex mb-1">
                        <img class="h-32 w-32 rounded-full border-4 border-white -mt-16 mr-4" src="https://randomuser.me/api/portraits/women/21.jpg" alt="">
                        <div class="py-2">
                            <h3 class="font-bold text-2xl mb-1">{{$selected->getReceiver()->name}}</h3>
                            <div class="inline-flex text-gray-600 sm:flex items-center">
                                <svg class="h-5 w-5 text-gray-600 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5.64 16.36a9 9 0 1 1 12.72 0l-5.65 5.66a1 1 0 0 1-1.42 0l-5.65-5.66zm11.31-1.41a7 7 0 1 0-9.9 0L12 19.9l4.95-4.95zM12 14a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                                Mohali
                            </div>
                            <div class="inline-flex text-gray-600 sm:flex items-center">
                                <svg class="h-5 w-5 text-gray-600 mr-1" data-name="1-Email" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="20" height="20"><path d="M29 4H3a3 3 0 0 0-3 3v18a3 3 0 0 0 3 3h26a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3zm-.72 2L16 14.77 3.72 6zM30 25a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.23l13.42 9.58a1 1 0 0 0 1.16 0L30 7.23z"/></svg>
                                {{ $selected->getReceiver()->email }}
                            </div>
                            <div class="flex items-center text-grey-darker mb-4">
                                <svg class="h-5 w-5 text-gray-600 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/></svg>
                                <span><strong class="h-5 w-5 text-gray-600 mr-1">Joined </strong>{{ $selected->getReceiver()->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4">
                    <div class="text-center">
                        <button wire:click="closeModal" class="rounded-full border-2 border-violet-900 px-6 py-2 font-semibold uppercase tracking-wide text-violet-900 hover:bg-violet-900 hover:text-white focus:outline-none">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
    
        
    @endif

    </header>

    <main 
     @scroll="
      scropTop = $el.scrollTop;

      if(scropTop <= 0){

        window.livewire.emit('loadMore');

      }
     
     "

     @update-chat-height.window="

         newHeight= $el.scrollHeight;

         oldHeight= height;
         $el.scrollTop= newHeight- oldHeight;

         height=newHeight;
     
     "
    id="conversation"  class="flex flex-col gap-3 p-2.5 overflow-y-auto  flex-grow overscroll-contain overflow-x-hidden w-full my-auto">

        @if ($loadedMessages)

        @foreach ($loadedMessages as $key=> $message)
            
            
   
        <div 
        wire:key="{{time().$key}}"
        @class([
            'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
            'ml-auto'=>$message->sender_id=== auth()->id(),
                ]) >

        {{-- avatar --}}

        <div @class(['shrink-0'])>
            <x-avatar />
        </div>
            {{-- messsage body --}}

            <div @class(['flex flex-wrap text-[15px]  rounded-xl p-2.5 flex flex-col text-black bg-[#f6f6f8fb]',
                         'rounded-bl-none border  border-gray-200/40 '=>!($message->sender_id=== auth()->id()),
                         'rounded-br-none bg-blue-500/80 text-white'=>$message->sender_id=== auth()->id()
               ])>


            
            <p class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
              {!! $message->body !!}
            </p>

        {{-- @if ($isPoll)
            <div wire:poll.150ms class="ml-auto flex gap-2">
        @else --}}
            <div class="ml-auto flex gap-2">
        {{-- @endif --}}
            {{-- <div wire:poll.150ms class="ml-auto flex gap-2"> --}}

                <p @class([
                    'text-xs ',
                    'text-gray-500'=>!($message->sender_id=== auth()->id()),
                    'text-white'=>$message->sender_id=== auth()->id(),

                        ]) >
                
                    {{$message->created_at->format('g:i a')}}

                </p>
                @if ($message->sender_id === auth()->id())
                {{-- @if ($isPoll)
                   <div wire:poll.150ms>
                @else --}}
                   <div>
                {{-- @endif --}}
                    @if ($message->read_at === null)
                        {{-- Single tick --}}
                        <span class="text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                        </span>
                    @else
                        {{-- Double ticks --}}
                            <span class="text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                <path fill="red" d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                <path fill="red" d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                            </svg>
                        </span>
                    @endif
                </div>
             @endif            
            </div>
        </div>

    </div>
        
    @endforeach
    @endif

    </main>
    {{-- body --}}


    {{-- send message  --}}

    <footer class="shrink-0 z-10 bg-white inset-x-0">
        <div id="typingIndicator" style="display: none;">typing...</div>
        <div class=" p-2 border-t" x-data="{
            sendFunction: function() {
                const trixEditor = document.getElementById('x')
                $wire.set('body', trixEditor.value)
                $wire.sendMessage(true);

            }
        }">
          
                <input id="x" type="hidden" name="content">
                <trix-editor  wire:model="body" input="x"></trix-editor>
                <button class="col-span-2" type="button" @click="sendFunction()">Send</button>
            
            @error('body')

            <p> {{$message}} </p>
                
            @enderror

        </div>

    </footer>

</div>
@script
<script>
   document.addEventListener('trix-change', function(event) {
    var typingIndicator = document.getElementById('typingIndicator');
    var trixEditorValue = event.target.value.trim(); // Trim to handle spaces
    if (trixEditorValue.length > 0) {
        typingIndicator.style.display = 'inline-block';
    } else {
        typingIndicator.style.display = 'none';
    }
});

    Echo.channel('chat')
    .listen('MessageSent', (e) => {
        console.log(e.message);
    });
</script>
@endscript

</div>


