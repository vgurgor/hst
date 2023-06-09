<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\BranchType::create([
            'code' => 'AO',
            'name' => 'Ana Okulu',
        ]);
        \App\Models\BranchType::create([
            'code' => 'IO',
            'name' => 'İlk Okul',
        ]);
        \App\Models\BranchType::create([
            'code' => 'OO',
            'name' => 'Orta Okul',
        ]);
        \App\Models\BranchType::create([
            'code' => 'LS',
            'name' => 'Lise',
        ]);
        \App\Models\BranchType::create([
            'code' => 'K',
            'name' => 'Kurs',
        ]);
    }
}
