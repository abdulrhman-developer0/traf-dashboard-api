<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = DB::table('bookings')->pluck('id');

        foreach ($bookings as $bookingId) {

            $reviews = [
                [
                    'booking_id' => $bookingId,
                    'reviewable_type' => 'App\Models\ServiceProvider',
                    'reviewable_id' => 1,
                    'comment' => 'الخدمة كانت رائعة، العاملين محترفين جداً.',
                    'rating' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'booking_id' => $bookingId,
                    'reviewable_type' => 'App\Models\ServiceProvider',
                    'reviewable_id' => 2,
                    'comment' => 'الخدمة كانت رائعة، العاملين محترفين جداً.',
                    'rating' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'booking_id' => $bookingId,
                    'reviewable_type' => 'App\Models\ServiceProvider',
                    'reviewable_id' => 3,
                    'comment' => 'الخدمة كانت رائعة، العاملين محترفين جداً.',
                    'rating' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];



            Review::insert($reviews);
        }
    }
}