<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Chat::factory(10)->create();
        Message::factory(1000)->create();
    }
}
