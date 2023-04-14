<?php

namespace Modules\Accommodation\database\factories;

use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccommodationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Accommodation\Models\Accommodation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $trip = Trip::inRandomOrder()->first();
        return [
            'trip_id'           => $trip->id,
            'name'              => $trip->user->name,
            'country'           => $this->faker->country,
            'number_overnights' => $this->faker->numberBetween(1,10),
            'number_rooms'      => $this->faker->numberBetween(1,10),
            'hotel_stars'      => $this->faker->numberBetween(1,5),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ];
    }
}
