<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => CategoryStatus::Active,
            'sort_order' => 0,
        ];
    }

    public function withParent(int $parentId): static
    {
        return $this->state(['parent_id' => $parentId]);
    }

    public function inactive(): static
    {
        return $this->state(['status' => CategoryStatus::Inactive]);
    }
}
