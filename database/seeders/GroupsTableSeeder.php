<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    public function run()
    {
        // 1. 2 родительские группы без наследников
        Group::factory()->count(2)->create();

        // 2. 5 родительских групп с одним уровнем вложенности (родитель → ребенок)
        Group::factory()->count(5)
            ->has(
                Group::factory()->count(1),
                'children'
            )
            ->create();

        // 3. 5 родительских групп с двумя уровнями вложенности (родитель → ребенок → внук)
        Group::factory()->count(5)
            ->has(
                Group::factory()->count(2)
                    ->has(
                        Group::factory()->count(2),
                        'children'
                    ),
                'children'
            )
            ->create();
    }
}
