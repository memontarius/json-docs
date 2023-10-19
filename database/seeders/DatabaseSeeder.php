<?php

namespace Database\Seeders;

use Database\Factories\DocumentFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserFactory::class,
            DocumentFactory::class
        ]);
    }
}
