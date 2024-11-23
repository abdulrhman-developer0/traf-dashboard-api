<?php

namespace Database\Factories;

use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $idsOfProviders = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (! $this->idsOfProviders ) {
            $this->idsOfProviders = ServiceProvider::pluck('id')->toArray();
        }

        return [
            'service_provider_id'        => $this->faker->randomElement($this->idsOfProviders),
            'name'                               => $this->faker->name(),
            'duration'                           => random_int(10, 100),
            'description'                        => $this->faker->text(),
            'rating'                             => random_int(1, 5),
            'price_before'                       => $this->faker->randomElement([100, 150, 200, 250, 300]),
            'price_after'                        => null,
            'is_offer'                           => false,
        ];
    }
}
