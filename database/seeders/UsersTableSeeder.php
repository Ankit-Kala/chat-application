<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(5)->create();

        User::create([
            'name'  => 'Admin',
            'email' => 'admin@gmail.com',
            'user_type'=>'admin',
            'password' => Hash::make('admin@123'),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'user_type'=>'user',
            'password' => Hash::make('user@1234'),
        ]);

        User::create([
            'name' => 'Sam Curran',
            'email' => 'sam@example.com',
            'user_type'=>'user',
            'password' => Hash::make('user@1234'),
        ]);

        User::create([
            'name' => 'Jason Roy',
            'email' => 'jason@example.com',
            'user_type'=>'user',
            'password' => Hash::make('user@1234'),
        ]);

        User::create([
            'name' => 'Alex Hails',
            'email' => 'alex@example.com',
            'user_type'=>'user',
            'password' => Hash::make('user@1234'),
        ]);
    }
}
