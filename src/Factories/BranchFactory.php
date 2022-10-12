<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Factories;

use Inisiatif\Package\Common\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

final class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->sentence(1),
            'name' => $this->faker->name(),
            'is_active' => true,
            'is_head_office' => false,
            'parent_id' => $this->faker->uuid,
        ];
    }
}
