<?php

namespace Database\Factories;

use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $idsOfProviders;

    public function __construct() {
        
        $this->idsOfProviders = ServiceProvider::pluck('id')->toArray();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'partner_service_provider_id'        => $this->faker->randomElement($this->idsOfProviders),
            'name'                               => $this->faker->name(),
            'duration'                           => random_int(10, 100),
            'description'                        => $this->faker->text(),
            'rating'                             => random_int(1, 5),
            'price_before'                       => $this->faker->randomElement([100, 150, 200, 250, 300]),
            'is_offer'                           => false,
        ];
    }
}
