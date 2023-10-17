<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => DocumentStatus::Draft,
            //'payload' => json_decode('{}'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
