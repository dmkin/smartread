<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,    // Создает 50 пользователей
            GroupsTableSeeder::class,   // Создает группы с иерархией
            GroupUserTableSeeder::class,// Заполняет связи
        ]);
    }
}
