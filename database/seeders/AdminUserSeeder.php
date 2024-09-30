<?php

// database/seeders/AdminUserSeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin 01',
            'email' => 'alexescurel@gmail.com',
            'password' => Hash::make('theprogrammer17'),
            'role' => 'admin',
        ]);
    }
}

