<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = \App\Models\Attachment::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word() . '.' . fake()->fileExtension(),
            'path' => 'attachments/' . fake()->uuid() . '.' . fake()->fileExtension(),
            'mime_type' => fake()->mimeType(),
            'size' => fake()->numberBetween(1024, 5242880),
            'user_id' => User::factory(),
        ];
    }
}
