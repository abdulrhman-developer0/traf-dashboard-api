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
        
        $reviews = [
            [
                'booking_id' => 1,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 1,
                'comment' => 'الخدمة كانت رائعة، العاملين محترفين جداً.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 2,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 2,
                'comment' => 'لم أكن راضيًا عن الخدمة، كنت أتوقع أفضل.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 3,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 3,
                'comment' => 'الخدمة كانت جيدة ولكن أعتقد أنه كان من الممكن أن تكون أسرع.',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 4,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 4,
                'comment' => 'أحببت الخدمة والنتيجة كانت مرضية جداً.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 5,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 5,
                'comment' => 'الخدمة كانت بطيئة، لا أعتقد أنني سأعيد استخدامها.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 6,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 6,
                'comment' => 'كانت الخدمة ممتازة، وكنت سعيداً بالنتيجة.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 7,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 7,
                'comment' => 'الخدمة كانت جيدة، لكن السعر كان مرتفعاً بالنسبة للجودة.',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 8,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 8,
                'comment' => 'النتيجة لم تكن كما توقعت، وأعتقد أنني سأبحث عن خيارات أخرى.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 9,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 9,
                'comment' => 'الخدمة كانت رائعة وكان فريق العمل محترفاً جداً.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 10,
                'reviewable_type' => 'App\Models\ServiceProvider',
                'reviewable_id' => 10,
                'comment' => 'الخدمة كانت معقولة ولكن يمكن تحسين بعض التفاصيل.',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        

       Review::insert($reviews);
    }
}
