<?php

namespace Database\Seeders;

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
        //
        $reviews = [
            [
                'user_id' => 1,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 1, // الخدمة الأولى
                'comment' => 'الخدمة كانت رائعة، العاملين محترفين جداً.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 2, // الخدمة الثانية
                'comment' => 'لم أكن راضيًا عن الخدمة، كنت أتوقع أفضل.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 3, // الخدمة الثالثة
                'comment' => 'الخدمة كانت جيدة ولكن أعتقد أنه كان من الممكن أن تكون أسرع.',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 4, // الخدمة الرابعة
                'comment' => 'أحببت الخدمة والنتيجة كانت مرضية جداً.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 5, // الخدمة الخامسة
                'comment' => 'الخدمة كانت بطيئة، لا أعتقد أنني سأعيد استخدامها.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 6, // الخدمة السادسة
                'comment' => 'كانت الخدمة ممتازة، وكنت سعيداً بالنتيجة.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 7, // الخدمة السابعة
                'comment' => 'الخدمة كانت جيدة، لكن السعر كان مرتفعاً بالنسبة للجودة.',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 8, // الخدمة الثامنة
                'comment' => 'النتيجة لم تكن كما توقعت، وأعتقد أنني سأبحث عن خيارات أخرى.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 9, // الخدمة التاسعة
                'comment' => 'الخدمة كانت رائعة وكان فريق العمل محترفاً جداً.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'reviewable_type' => 'App\Models\Service',
                'reviewable_id' => 10, // الخدمة العاشرة
                'comment' => 'الخدمة كانت معقولة ولكن يمكن تحسين بعض التفاصيل.',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($reviews as $review) {
            DB::table('reviews')->insert($review);
        }
    }
}
