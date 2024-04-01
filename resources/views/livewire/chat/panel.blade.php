<div
  class="flex flex-col transition-all h-full overflow-hidden">

  <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">
    <div class="border-b justify-between flex items-center pb-2">
        <div class="flex items-center gap-2">
            <h5 class="font-extrabold text-2xl">Chats</h5>
            <button wire:click="$toggle('showSearch')" class="ml-4">
                @if($showSearch)
                <!-- Minus Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                </svg>
            @else
                <!-- Plus Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            @endif
            </button>
        </div>
    </div>
  
    @if($showSearch)
        <input type="text" wire:model.live="searchQuery" placeholder="Search users" class="border border-gray-300 px-3 py-2 rounded-md mt-2 w-full"> 
    @endif
</header>

@if($showSearch)
    <div class="overflow-y-scroll overflow-hidden grow h-full relative">
        <ul>
            @forelse($users as $user)
                <li class="flex items-center space-x-2 py-2">
                    <x-avatar src="https://source.unsplash.com/500x500?face-{{$user->id}}" class="shrink-0 h-12 w-12 rounded-full" />
                    <span class="flex-grow">{{ $user->name }}</span>
                    <button wire:click="message({{ $user->id }})" class="text-blue-500 ml-auto">Message</button>
                </li>
                <li class="border-b border-gray-200"></li>
            @empty
                <li class="text-center mt-4">No users found.</li>
                <div class="flex justify-center mt-4">
                    <x-primary-button type="button" class="!py-2 button-info w-auto mt-2 flex items-center" x-data="" x-on:click.prevent="$dispatch('open-modal', 'inviteUser');">
                        {{ __('Invite User') }}
                    </x-primary-button>
                    {{-- <button wire:click="invite" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Invite User</button> --}}
                </div>               
            @endforelse      
        </ul>
    </div>
@endif

<x-modal name="inviteUser" focusable key="edit_{{ strtotime(now()) }}">
    <div class="flex justify-between items-center border-b pb-4">
        <h6 class="text-dark font-semibold text-lg">
            Invite User
        </h6>
        <div class="cursor-pointer" x-on:click.prevent="$dispatch('close');">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
    <div class="p-4">
        <x-text-input wire:model="inviteEmail" id="invite" name="invite" type="text" class="internal-input px-3 py-2 rounded-md w-full mb-4" autocomplete="invite" placeholder="Enter user's email" />
        @error('inviteEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        <div class="flex justify-end">
            <x-primary-button wire:click="sendInvitation" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="button-info py-2 px-4">
                <span wire:loading wire:target="sendInvitation">Invitation Sending...</span>
                <span wire:loading.remove>Send Invitation</span>
            </x-primary-button>  
        </div>
    </div>
</x-modal>    

@if(!$showSearch)
    <main wire:poll class=" overflow-y-scroll overflow-hidden grow  h-full relative " style="contain:content">

        {{-- chatlist  --}}
     <ul class="p-2 grid w-full spacey-y-2">

        @if ($conversations)
        {{-- @dd($conversations); --}}
        @foreach ($conversations as $key=> $conversation)
        {{-- <a wire:click="$dispatch('check',{ id: {{ $conversation->id }} })" class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">
                checkTo</a> --}}
                {{-- @dd($conversation->id); --}}
        <li
              id="conversation-{{$conversation->id}}" wire:key="{{$conversation->id}}"
             class="py-3 hover:bg-gray-50 rounded-2xl dark:hover:bg-gray-700/70 transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2">
             <a href="#" class="flex items-center space-x-2">
                @if ($conversation->getReceiver()->is_active)
                <span class="h-3 w-3 bg-green-500 rounded-full"></span> 
                @else
                <span class="h-3 w-3 bg-gray-700 rounded-full"></span> <!-- Live dot -->
                @endif
                <x-avatar src="https://source.unsplash.com/500x500?face-{{$key}}" class="shrink-0 h-12 w-12 rounded-full" /> <!-- Avatar -->
            </a>
            
            
          <aside class="grid grid-cols-12 w-full">
            <a wire:click="$dispatch('check',{ id: {{ $conversation->id }} })" x-on:click="$wire.$refresh()" class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">
                {{-- <a href="{{route('chat',$conversation->id)}}" class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1" wire:navigate> --}}
                  {{-- name and date  --}}
                  <div class="flex justify-between w-full items-center">

                      <h6 class="truncate font-medium tracking-wider text-gray-900">
                         {{$conversation->getReceiver()->name}}
                      </h6>

                      <small class="text-gray-700">{{$conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans()}}</small>

                  </div>

                  {{-- Message body --}}


                <div  class="flex gap-x-2 items-center">

                    @if ($conversation->messages?->last()?->sender_id==auth()->id())

                    @if ($conversation->isLastMessageReadByUser())
                              {{-- double tick  --}}
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                            <path fill="red" d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                            <path fill="red" d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                          </svg>
                    </span>
                    @else

                            {{-- single tick  --}}
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </span>
                            
                    @endif
                    @endif

                     <p class="grow truncate text-sm font-[100]">
                        {{ strip_tags($conversation->messages?->last()?->body??' ') }} 
                       {{-- {!! $conversation->messages?->last()?->body??' ' !!} --}}
                    </p>

                     {{-- unread count --}}
                     @if ($conversation->unreadMessagesCount()>0)
                     <span class="font-bold p-px px-2 text-xs shrink-0 rounded-full bg-blue-500 text-white">
                        {{$conversation->unreadMessagesCount()}}
                     </span>
                         
                     @endif


                </div>

              </a>

              {{-- Dropdown --}}

              <div class="col-span-1 flex flex-col text-center my-auto">

              </div>
          </aside>

      </li>
        @endforeach

        @else
            
        @endif

     </ul>
    </main>
    @endif
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
        Livewire.on('invitation-sent', function (data) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: 'success',
                title: data.message
            });
        });
</script>
@endpush
