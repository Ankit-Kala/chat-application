<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Input box --}}
                    <input id="typing" type="text" name="message" data-user="{{ Auth::user()->name }}">
                    {{ __("You're logged in!") }}
    
                    {{-- Typing indicator --}}
                    <div id="typingIndicator" style="display: none;">{{ Auth::user()->name }} is typing...</div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <!-- Your custom script -->
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var typingTimeout; // Variable to store the timeout for hiding the typing indicator
        
            // Initialize Echo and define listeners inside this block
            Echo.private('typing')
                .listenForWhisper('typing', function(e) {
                    console.log(e.user + ' is typing');
                    // Show typing indicator when a whisper is received
                    showTypingIndicator(e.user);
        
                    // Clear the existing timeout and set a new one to hide the typing indicator
                    clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(hideTypingIndicator, 2000); // Adjust the delay as needed
                });
        
            // Event listener for typing
            document.getElementById("typing").addEventListener("keypress", function() {
                var user = this.getAttribute('data-user'); // Get the user's name from data attribute
                Echo.private('typing')
                    .whisper('typing', {
                        user: user,
                        typing: true
                    }, 300);
        
                // Clear the existing timeout and set a new one to hide the typing indicator
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(hideTypingIndicator, 2000); // Adjust the delay as needed
            });
        
            // Event listener for when the user stops typing
            document.getElementById("typing").addEventListener("keyup", function() {
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(hideTypingIndicator, 2000); // Adjust the delay as needed
            });
        
            // Function to show typing indicator
            function showTypingIndicator(user) {
                document.getElementById('typingIndicator').style.display = 'block';
                document.getElementById('typingIndicator').innerText = user + ' is typing...';
            }
        
            // Function to hide typing indicator
            function hideTypingIndicator() {
                document.getElementById('typingIndicator').style.display = 'none';
            }
        });
        </script>
        
    {{-- <script>
        $(document).ready(function(){
            // Initialize Echo and define listeners inside this block
            Echo.private('typing')
                .listenForWhisper('typing', (e) => {
                    console.log(e.user + ' is typing');
                });
    
            // Event listener for typing
            $("#typing").keypress(function(){
                var user = $(this).data('user'); // Get the user's name from data attribute
                Echo.private('typing')
                    .whisper('typing', {
                        user: "{{Auth::user()->name}}",
                        typing: true
                    }, 300);
            });
        });
    </script> --}}
    @endpush
</x-app-layout>
