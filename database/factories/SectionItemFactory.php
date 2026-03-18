<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Section;
use App\Models\SectionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SectionItem>
 */
class SectionItemFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'title' => fake()->words(3, true),
            'sort_order' => 0,
            'is_visible' => true,
        ];
    }
}
