<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,    // 1. создаём админа
            RegularUserSeeder::class,  // 2. создаём обычного пользователя
            CardSeeder::class,         // 3. создаём карточки, привязанные к ним
        ]);
    }
}