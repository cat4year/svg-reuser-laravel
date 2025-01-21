<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Database\Factories;

use Cat4year\SvgReuserLaravel\Models\Icon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Icon>
 */
final class IconFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(1),
            'slug' => static fn (array $attributes) => Str::slug($attributes['name']),
            'sort' => $this->faker->numberBetween(0, 500),
            'icon_id' => null,
        ];
    }
}
