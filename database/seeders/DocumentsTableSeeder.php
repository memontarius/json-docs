<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Database\Factories\DocumentFactory;
use Illuminate\Database\Seeder;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            /** @var DocumentFactory $documentFactory */
            $documentFactory = Document::factory();

            $documentFactory
                ->count(5)
                ->for(User::factory())
                ->withRandomStatusAndPayload()
                ->create();
        }
    }
}
