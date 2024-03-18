<div>
    <!-- Button to open the create user modal -->
    <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" wire:click="openCreateModal">Create User</button>

    <!-- User creation/editing modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto" style="display: {{ $showModal ? 'block' : 'none' }}">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">User Form</h2>
                    <button class="text-gray-400 hover:text-gray-600" wire:click="$set('showModal', false)">&times;</button>
                </div>
                <form class="mt-4" wire:submit.prevent="createOrUpdateUser">
                    <div class="mb-4">
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" wire:model="name" placeholder="Name">
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" wire:model="email" placeholder="Email">
                        @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model="user_type">
                            <option value="">Select User Type</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                        @error('user_type') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" wire:model="password" placeholder="Password">
                        @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-gray-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <!-- User list table -->
    <div class="mt-8 overflow-x-auto">
        <table class="min-w-full bg-white">
            <!-- Table headers -->
            <thead>
                <tr>
                    <th class="border-b-2 border-gray-200 px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="border-b-2 border-gray-200 px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="border-b-2 border-gray-200 px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User Type</th>
                    <th class="border-b-2 border-gray-200 px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border-b border-gray-200 px-4 py-2">{{ $user->name }}</td>
                        <td class="border-b border-gray-200 px-4 py-2">{{ $user->email }}</td>
                        <td class="border-b border-gray-200 px-4 py-2">{{ $user->user_type }}</td>
                        <td class="border-b border-gray-200 px-4 py-2">
                            <button class="bg-yellow-500 text-white font-bold py-1 px-2 rounded" wire:click="editUser({{ $user->id }})">Edit</button>
                            <button class="bg-red-500 text-white font-bold py-1 px-2 rounded" wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>