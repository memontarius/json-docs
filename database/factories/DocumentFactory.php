<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;
    private array $payloads = [
        null, null,
        [
            "actor" => "The fox",
            "meta" => [
                "type" => "quick",
                "color" => "brown"
            ],
            "actions" => [
                [
                    "action" => "jump over",
                    "actor" => "lazy dog"
                ]
            ]
        ],
        [
            "actor" => "The fox",
            "position" => 'bottom'
        ]
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => DocumentStatus::Draft,
            'user_id' => User::factory(),
            'payload' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }

    public function withRandomStatusAndPayload(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => $this->faker->randomElement([DocumentStatus::Draft, DocumentStatus::Published]),
                'payload' => $this->faker->randomElement($this->payloads)
            ];
        });
    }
}
