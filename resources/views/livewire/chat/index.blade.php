<div class=" fixed  h-full  flex bg-white border  lg:shadow-sm overflow-hidden inset-0 lg:top-16  lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

    @if (!$selectedConversation)
    <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border" >

        <livewire:chat.panel key="{{ now() }}">
    </div>
    @else
    <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border" > 
        <livewire:chat.panel key="{{ now() }}" :selectedConversation="$selectedConversation">
    </div>
    @endif

    <div class="hidden md:grid   w-full border-l h-full relative overflow-y-auto" style="contain:content">
    @if (!$selectedConversation)
        <div class="m-auto text-center justify-center flex flex-col gap-3">
            <h4 class="font-medium text-lg"> Choose a conversation to start chatting </h4>
        </div>
    @else

        <div class="grid   w-full border-l h-full relative overflow-y-auto" style="contain:content">
  
                <livewire:chat.box key="{{ now() }}" :selectedConversation="$selectedConversation">
 
          </div>
    @endif
    </div>

</div>