<?php

use App\Http\Livewire\UserComponent;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\ChatMain;
use App\Livewire\Chat\Index;
use App\Livewire\Chat\Room;
use App\Livewire\Chat\View;
use App\Livewire\UserCrud;
use App\Livewire\UsersList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/chat', Index::class)->name('chat.index');
    Route::get('/chat/{query}', Room::class)->name('chat');
    // Route::get('/chat/{query}', Chat::class)->name('chat');
    // Route::get('/users-list', UsersList::class)->name('users.list');

    // Route::middleware(['check.admin'])->group(function () {
        Route::get('/users', UserCrud::class)->name('users');
    // });

    Route::view('profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
