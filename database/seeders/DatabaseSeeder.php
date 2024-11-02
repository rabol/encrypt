<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@encrypt.test',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@encrypt.test',
        ]);
        $this->call(GenerateRsaKeysSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
