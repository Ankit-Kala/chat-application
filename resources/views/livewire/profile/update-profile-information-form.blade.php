<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $croppedImage;
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    #[On('saveCroppedImage')]
    public function saveCroppedImage($croppedImageData)
    {
        // dd('dwd');
        // Decode base64 image data
        $croppedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

        // Generate unique file name
        $fileName = 'cropped_image_' . time() . '.png';

        // Save the cropped image to storage
        $path = 'profile_photos/' . $fileName;
        file_put_contents(storage_path('app/public/' . $path), $croppedImage);

        // Retrieve the authenticated user
        $user = Auth::user();

        // Update the profile photo URL attribute
        $user->profile_photo_url = $path;

        // Save the updated user model
        $user->save();
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $path = session('url.intended', route('dashboard', absolute: false));

            $this->redirect($path);

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="bg-white shadow rounded-lg p-6">
    <header class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">{{ __('Profile Information') }}</h2>
        <p class="text-gray-600">{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form wire:submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
        <div class="sm:ml-6 sm:mt-0 mt-6">

            <label for="file-input" class="sr-only">Profile Image</label>
            <div class="flex items-center">
                @if(auth()->user()->profile_photo_url)
                    <img src="{{ asset('storage/'.auth()->user()->profile_photo_url) }}"
                     alt="Avatar"
                     class="w-20 h-20 rounded-full">
                @else
                     <span class="inline-block h-24 w-24 overflow-hidden rounded-full bg-gray-100">
                         <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                             <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                         </svg>
                     </span>
                 @endif
                 <input type="file" id="profile_image" @change="initCropper($event)" class="ml-4 p-1 w-full text-slate-500 text-sm leading-6 file:bg-zinc-700 file:text-white file:font-semibold file:border-none file:px-4 file:py-1 file:mr-6 hover:file:bg-black border border-gray-300"> </div>
            <div x-ref="image" class="mt-3" style="max-width: 100%;"></div>
            <x-action-message class="me-3 mt-2 text-green-600" on="profile-updated">{{ __('Saved.') }}</x-action-message>
            @if($croppedImage)
                <img :src="croppedImage" alt="Cropped Image" class="mt-3 rounded-md">
            @endif
        </div>
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-2 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-.167 1.398-.195 2.142-.21a6.982 6.982 0 014.78 1.326A7.033 7.033 0 0118 9.692c-.003.825-.169 1.607-.486 2.35a6.986 6.986 0 01-1.326 1.978c-2.177 2.192-5.765 2.931-8.891.674a6.982 6.982 0 01-2.619-5.27 7.033 7.033 0 011.707-4.514c.617-.654 1.34-1.18 2.158-1.565zM16.618 12.32c.323-.982.375-2.036.205-3.063a5.032 5.032 0 00-1.036-2.404 5.058 5.058 0 00-2.398-1.6 6.023 6.023 0 00-3.058-.214 5.029 5.029 0 00-2.91 1.387 5.059 5.059 0 00-1.597 2.398 6.023 6.023 0 00.214 3.058 5.029 5.029 0 001.387 2.91 5.059 5.059 0 002.398 1.597 6.023 6.023 0 003.058-.214 5.029 5.029 0 002.91-1.387 5.059 5.059 0 001.597-2.398zM12 9.5a.5.5 0 00-.5.5v3a.5.5 0 00.5.5h3a.5.5 0 000-1h-2.5V10a.5.5 0 00-.5-.5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ __('Your email address is unverified.') }}

                                <button wire:click.prevent="sendVerification" class="font-medium text-yellow-700 underline hover:text-yellow-600">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button @click="saveCroppedImage">{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>

    function initCropper(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var image = document.querySelector('[x-ref="image"]');
                image.innerHTML = ''; // Clear previous content
                var cropper = new Croppie(image, {
                    enableExif: true,
                    enableZoom: true,
                    viewport: { width: 200, height: 200, type: 'circle' }, // Adjust viewport as needed
                    boundary: { width: 300, height: 300 } // Adjust boundary as needed
                });
                cropper.bind({
                    url: e.target.result
                });
                cropper.setZoom(1); // Set initial zoom level
                cropper.result('base64').then(function(result) {
                    Alpine.store('croppedImage', result);
                });
                Alpine.store('cropper', cropper);
            };
            reader.readAsDataURL(input.files[0]);
        }
        event.target.value = '';
    }
    
    function saveCroppedImage() {
        console.log('awddddawd');
    var cropper = Alpine.store('cropper');
    cropper.result('base64').then(function(result) {
        Livewire.dispatch('saveCroppedImage',{ croppedImageData: result });
    });
}
</script>
@endpush
