<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupUserTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $leafGroups = Group::whereDoesntHave('children')->get();

        $leafGroups->each(function ($group) use ($users) {
            $group->users()->attach(
                $users->random(rand(3, 7))->pluck('id')
            );
        });

        // Гарантируем, что каждый пользователь будет хотя бы в одной группе
        $users->each(function ($user) use ($leafGroups) {
            if ($user->groups()->count() === 0) {
                $user->groups()->attach(
                    $leafGroups->random(1)->pluck('id')
                );
            }
        });
    }
}
