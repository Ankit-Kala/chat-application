@props([
'name',
'show' => false,
'mypopup' =>false,
'maxWidth' => '2xl'
])

@php
$maxWidth = [
'sm' => 'sm:max-w-sm',
'md' => 'sm:max-w-md',
'lg' => 'sm:max-w-lg',
'xl' => 'sm:max-w-xl',
'2xl' => 'sm:max-w-2xl',
'w-66' => 'sm:max-w-[66%]',
'w-95' => 'max-w-[95%]'
][$maxWidth];
@endphp
<div x-data="modalData()" x-cloak x-init="init" x-on:open-modal.window="openModal($event.detail)" x-on:close.stop="closeModal" x-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" :style="{ display: show ? 'block' : 'none' }" @keydown.escape.window="closeModal">
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click.self="show = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div x-show="show" class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}
    </div>
</div>

<script>
    function modalData() {
        return {
            show: false,
            shiftKey: false,

            init() {
                this.$watch('show', (value) => {
                    if (value) {
                        document.body.classList.add('overflow-y-hidden');
                        if (this.$el.hasAttribute('focusable')) {
                            setTimeout(() => this.firstFocusable().focus(), 100);
                        }
                    } else {
                        document.body.classList.remove('overflow-y-hidden');
                    }
                });
                Livewire.on('closeModal', () => {
                    this.show = false;
                });
                Livewire.on('editModal', (event) => {
                    this.openModal(event.name)
                });
                Livewire.on('viewImage', (event) => {
                    if (event.imageUrl !== '') {
                        this.openModal(event.name);
                        this.mypopup = true;
                    }

                });

            },

            openModal(name) {
                // This is for when calling the model from the controller; at that time, we are getting names into an array.
                if (Array.isArray(name)) {
                    name = name[0];
                }
                if (name === '{{ $name }}') {
                    this.show = true;
                }
            },

            closeModal() {
                this.show = false;
            },
        };
    }
</script>