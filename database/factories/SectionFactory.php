<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Section;
use App\Enums\SectionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Section>
 */
class SectionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'type' => SectionType::ScrollableProduct,
            'sort_order' => 0,
            'is_visible' => true,
        ];
    }
}
