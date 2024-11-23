<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = \App\Models\Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['science', 'technology', 'health', 'sports']),
            'source' => $this->faker->randomElement(['BBC', 'CNN', 'The Guardian']),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'url' => $this->faker->url, // Generates a random URL
        ];
    }

}

