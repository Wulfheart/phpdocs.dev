<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Package;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vendor' => $this->faker->word,
            'package' => $this->faker->word,
            'repository_url' => $this->faker->word,
            'license' => $this->faker->word,
            'package_url' => $this->faker->word,
        ];
    }
}
