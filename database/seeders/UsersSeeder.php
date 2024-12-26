<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Instructor;
use App\Models\Student;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $dev_user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminadmin'),
            'account_type' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $dev_user->assignRole('Admin');

        $dev_user = User::factory()->create([
            'name' => 'Abed Said',
            'email' => 'abed.allah.said@gmail.com',
            'password' => Hash::make('abedsaid'),
            'account_type' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),

        ]);
        $dev_user->assignRole('Admin');


        

        $users = [
            //1
            ['name' => 'أحمد علي', 'email' => 'ahmed.ali@example.com', 'account_type' => 'service-provider'],
            //2
            ['name' => 'سارة محمد', 'email' => 'sara.mohamed@example.com', 'account_type' => 'service-provider'],
            //3
            ['name' => 'خالد حسن', 'email' => 'khaled.hassan@example.com', 'account_type' => 'service-provider'],
            // 4
            ['name' => 'منى محمود', 'email' => 'mona.mahmoud@example.com', 'account_type' => 'service-provider'],

            ['name' => 'يوسف إبراهيم', 'email' => 'youssef.ibrahim@example.com', 'account_type' => 'client'],
            ['name' => 'هدى أحمد', 'email' => 'hoda.ahmed@example.com', 'account_type' => 'client'],
            ['name' => 'علي سعيد', 'email' => 'ali.saeed@example.com', 'account_type' => 'client'],
            ['name' => 'نورا سامي', 'email' => 'nora.samy@example.com', 'account_type' => 'client'],
            ['name' => 'عمر فؤاد', 'email' => 'omar.fouad@example.com', 'account_type' => 'client'],
            ['name' => 'ليلى سمير', 'email' => 'laila.samir@example.com', 'account_type' => 'client'],
        ];


        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'account_type' => $user['account_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
