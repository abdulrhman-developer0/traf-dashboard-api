<?php

namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workerData = [
            [
                "name" => "د/الهام ماضي",
                "phone" => "012345674",
                "address" => "Saudi Arabia, ElReyad",
                "image" => 'public/images/imgs/dr-elham.jpg',
            ],
            [
                "name" => "د/نورا يوسف",
                "phone" => "010987654",
                "address" => "Egypt, Cairo",
                "image" => 'public/images/imgs/norayousef.jpg',
            ],
            [
                "name" => "د/رحمة علي",
                "phone" => "011234567",
                "address" => "Saudi Arabia, Jeddah",
                "image" => 'public/images/imgs/rahmaAli.jpg',
            ],
            [
                "name" => "د/سارة حسن",
                "phone" => "012345678",
                "address" => "UAE, Dubai",
                "image" => 'public/images/imgs/sarahHasan.jpeg',
            ],
            [
                "name" => "د/نورا عبد الله",
                "phone" => "010123456",
                "address" => "Qatar, Doha",
                "image" => 'public/images/imgs/noraAbdallah.jpg',
            ],
        ];
    
        foreach ($workerData as $workerDetails) {
            $worker = Worker::create([
                'name' => $workerDetails['name'],
                'phone' => $workerDetails['phone'],
                'address' => $workerDetails['address'],
            ]);
    
            // Add image to media collection
            $worker->addMedia(storage_path('app/' . $workerDetails['image']))->toMediaCollection();
        }
    }
    
}
