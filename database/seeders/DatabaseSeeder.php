<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(1)->create();

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => Str::random(5) . '@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => 'aghssajsg',
        ]);

        // $this->call([
        //     ProductSeeder::class
        // ]);
    }
}
