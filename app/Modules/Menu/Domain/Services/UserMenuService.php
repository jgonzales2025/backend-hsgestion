<?php

namespace App\Modules\Menu\Domain\Services;

use App\Models\Menu;
use Illuminate\Contracts\Auth\Authenticatable;

class UserMenuService
{
    public function getUserMenus(Authenticatable $user)
    {
        $menus = Menu::active()
            ->main()
            ->ordered()
            ->with(['children' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();

        return $menus->filter(function ($menu) use ($user) {
            if ($menu->permission && !$user->can($menu->permission)) {
                return false;
            }
            if ($menu->children->isNotEmpty()) {
                $accessibleChildren = $menu->children->filter(function ($child) use ($user) {
                    return !$child->permission || $user->can($child->permission);
                });
                return $accessibleChildren->isNotEmpty();
            }
            return !$menu->permission || $user->can($menu->permission);
        })->map(function ($menu) use ($user) {
            $formattedMenu = [
                'id' => $menu->id,
                'label' => $menu->label,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'order' => $menu->order,
                'status' => $menu->status
            ];
            if ($menu->children->isNotEmpty()) {
                $children = $menu->children->filter(function ($child) use ($user) {
                    return !$child->permission || $user->can($child->permission);
                })->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label' => $child->label,
                        'route' => $child->route,
                        'order' => $child->order,
                        'status' => $child->status
                    ];
                })->values();
                if ($children->isNotEmpty()) {
                    $formattedMenu['children'] = $children;
                }
            }
            return $formattedMenu;
        })->values();
    }

    public function getUserMenusWithRestricted(Authenticatable $user, $customRole = null)
    {
        if (!$customRole) {
            // Consulta directa a la tabla pivot SIN usar relaciones cacheadas
            $userRoleId = \DB::table('model_has_roles')
                ->where('model_type', get_class($user))
                ->where('model_id', $user->id)
                ->value('role_id');

            if (!$userRoleId) {
                return [
                    'accessible' => [],
                    'restricted' => []
                ];
            }

            // Consulta fresca con los menús
            $customRole = \App\Models\Role::with(['menus' => function($query) {
                $query->where('status', 1)->orderBy('order');
            }])
                ->where('id', $userRoleId)
                ->first();
        }

        if (!$customRole || !$customRole->menus) {
            return [
                'accessible' => [],
                'restricted' => []
            ];
        }

        $roleMenus = $customRole->menus;
        $roleMenuIds = $roleMenus->pluck('id');

        // Consulta fresca de permisos personalizados
        $customPermissions = \DB::table('user_menu_permissions')
            ->where('user_id', $user->id)
            ->pluck('menu_id');

        // Obtener menús padres (del conjunto de menús del rol)
        $parentMenus = $roleMenus->whereNull('parent_id')->sortBy('order');

        // Incluir padres cuyos hijos están en el rol
        $childrenParentIds = $roleMenus->whereNotNull('parent_id')->pluck('parent_id')->unique();
        $additionalParents = Menu::whereIn('id', $childrenParentIds)
            ->whereNotIn('id', $roleMenuIds)
            ->where('status', 1)
            ->orderBy('order')
            ->get();

        $allParents = $parentMenus->merge($additionalParents)->sortBy('order')->unique('id');

        $accessible = [];
        $restricted = [];

        foreach ($allParents as $menu) {
            $hasParentAccess = $this->hasMenuAccess($menu, $user, $customPermissions);

            // Obtener solo los hijos que están en el rol
            $children = $roleMenus->where('parent_id', $menu->id)->sortBy('order');

            if ($children->isNotEmpty()) {
                $accessibleChildren = [];
                $restrictedChildren = [];

                foreach ($children as $child) {
                    $childData = [
                        'id' => $child->id,
                        'label' => $child->label,
                        'route' => $child->route,
                        'order' => $child->order,
                        'status' => $child->status
                    ];

                    if ($this->hasMenuAccess($child, $user, $customPermissions)) {
                        $accessibleChildren[] = $childData;
                    } else {
                        $restrictedChildren[] = $childData;
                    }
                }

                if (!empty($accessibleChildren)) {
                    $accessible[] = [
                        'id' => $menu->id,
                        'label' => $menu->label,
                        'icon' => $menu->icon,
                        'route' => $menu->route,
                        'order' => $menu->order,
                        'status' => $menu->status,
                        'children' => $accessibleChildren
                    ];
                }

                if (!empty($restrictedChildren)) {
                    $restricted[] = [
                        'id' => $menu->id,
                        'label' => $menu->label,
                        'icon' => $menu->icon,
                        'route' => $menu->route,
                        'order' => $menu->order,
                        'status' => $menu->status,
                        'children' => $restrictedChildren
                    ];
                }
            } else {
                $menuData = [
                    'id' => $menu->id,
                    'label' => $menu->label,
                    'icon' => $menu->icon,
                    'route' => $menu->route,
                    'order' => $menu->order,
                    'status' => $menu->status
                ];

                if ($hasParentAccess) {
                    $accessible[] = $menuData;
                } else {
                    $restricted[] = $menuData;
                }
            }
        }

        return [
            'accessible' => $accessible,
            'restricted' => $restricted
        ];
    }

    private function hasMenuAccess($menu, $user, $customPermissions)
    {
        // Si el usuario tiene permisos personalizados configurados
        if ($customPermissions->isNotEmpty()) {
            // Verificar si el menú está en los permisos personalizados
            return $customPermissions->contains($menu->id);
        }

        // Si no tiene permisos personalizados, usar los permisos del rol
        return !$menu->permission || $user->can($menu->permission);
    }

    private function formatMenu($menu, $user, $customPermissions = null)
    {
        $formattedMenu = [
            'id' => $menu->id,
            'label' => $menu->label,
            'icon' => $menu->icon,
            'route' => $menu->route,
            'order' => $menu->order,
            'status' => $menu->status
        ];

        if ($menu->children->isNotEmpty()) {
            $children = $menu->children
                ->filter(function ($child) use ($user, $customPermissions) {
                    return $this->hasMenuAccess($child, $user, $customPermissions ?? collect());
                })
                ->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label' => $child->label,
                        'route' => $child->route,
                        'order' => $child->order,
                        'status' => $child->status
                    ];
                })->values();

            if ($children->isNotEmpty()) {
                $formattedMenu['children'] = $children;
            }
        }

        return $formattedMenu;
    }

}
