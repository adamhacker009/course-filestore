<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BaseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('secret'),
            'is_admin' => true
        ]);
        User::create([
            'name' => 'User1',
            'email' => 'user1@user1.com',
            'password' => Hash::make('secret'),
        ]);
    }
}
