<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title'
    ];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Group::class, 'parent_id');
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
        ];
    }

    /**
     * @return array
     */
    public function getUsersCountByLevel()
    {
        $result = [];

        // Рекурсивная функция для обхода дерева
        $traverse = function ($group, $level) use (&$result, &$traverse) {
            // Если это самый глубокий уровень (лист)
            if ($group->children->isEmpty()) {
                $count = $group->users->count();
                $userIds = $group->users->pluck('id')->toArray();

                $result[$group->id] = [
                    'title' => $group->title,
                    'level' => $level,
                    'count' => $count,
                    'user_ids' => $userIds,
                    'type' => 'leaf',
                ];

                return [
                    'count' => $count,
                    'user_ids' => $userIds,
                ];
            }

            // Для родительских групп
            $allUserIds = [];

            foreach ($group->children as $child) {
                $childData = $traverse($child, $level + 1);
                $allUserIds = array_merge($allUserIds, $childData['user_ids']);
            }

            // Уникальные пользователи среди всех дочерних групп
            $uniqueUserIds = array_unique($allUserIds);
            $count = count($uniqueUserIds);

            $result[$group->id] = [
                'title' => $group->title,
                'level' => $level,
                'count' => $count,
                'user_ids' => $uniqueUserIds,
                'type' => 'parent'
            ];

            return [
                'count' => $count,
                'user_ids' => $uniqueUserIds
            ];
        };

        $traverse($this, 0);
        return $result;
    }
}
