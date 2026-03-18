<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SizeTemplate;
use App\Models\SizeTemplateItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SizeTemplateItem>
 */
class SizeTemplateItemFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'size_template_id' => SizeTemplate::factory(),
            'label' => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'value' => fake()->sentence(3),
        ];
    }
}
