<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class AnalyseUsersInGroups extends Command
{
    protected $signature = 'app:groups-analyse-users';
    protected $description = 'Show users count in groups hierarchy';

    public function handle()
    {
        $rootGroups = Group::whereNull('parent_id')->with('children')->get();

        foreach ($rootGroups as $rootGroup) {
            $stats = $rootGroup->getUsersCountByLevel();

            $this->info("\nИерархия группы: " . $rootGroup->title);

            foreach ($stats as $groupData) {
                $indent = str_repeat('  • ', $groupData['level']);
                $this->line("{$indent}{$groupData['title']} - {$groupData['count']} пользователей (" . implode(',', $groupData['user_ids']) . ")");
            }
        }

        return Command::SUCCESS;
    }
}
