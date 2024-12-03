<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = User::create([
        //     'name'      => 'personal',
        //     'email'     => 'client@gmail.com',
        //     'password'  => Hash::make('password')
        // ]);

        // Client::create([
        //     'user_id' => $user->id
        // ]);
        $clients = [
            ['user_id' => 5, 'city_id' => 101, 'phone' => '01012345678', 'address' => 'شارع النيل، الجيزة', 'rating' => 4.5],
            ['user_id' => 6, 'city_id' => 102, 'phone' => '01098765432', 'address' => 'شارع فيصل، القاهرة', 'rating' => 4.0],
            ['user_id' => 7, 'city_id' => 103, 'phone' => '01234567890', 'address' => 'مدينة نصر، القاهرة', 'rating' => 3.8],
        ];

        foreach ($clients as $client) {
            DB::table('clients')->insert([
                'user_id' => $client['user_id'],
                'phone' => $client['phone'],
                'address' => $client['address'],
                'rating' => $client['rating'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
