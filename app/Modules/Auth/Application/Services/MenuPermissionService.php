<?php

namespace App\Modules\Auth\Application\Services;

class MenuPermissionService
{
    public function filterMenu(array $menuPermissions, array $userPermissions): array
    {
        return collect($menuPermissions)->map(function ($module) use ($userPermissions) {
            $items = collect($module['items'])->filter(function ($item) use ($userPermissions) {
                return in_array($item['permission'], $userPermissions);
            })->values()->all();

            if (empty($items)) {
                return null;
            }

            return [
                'key' => $module['key'],
                'label' => $module['label'],
                'items' => $items,
            ];
        })->filter()->values()->all();
    }

}
