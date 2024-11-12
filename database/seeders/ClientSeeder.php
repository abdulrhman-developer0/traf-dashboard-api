<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'      => 'personal',
            'email'     => 'client@gmail.com',
            'password'  => Hash::make('password')
        ]);

        Client::create([
            'user_id' => $user->id
        ]);
    }
}
