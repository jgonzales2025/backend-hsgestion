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

    public function getUserMenusWithRestricted(Authenticatable $user)
    {
        $menus = Menu::active()
            ->main()
            ->ordered()
            ->with(['children' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();

        $customPermissions = \App\Models\UserMenuPermission::where('user_id', $user->id)
            ->pluck('menu_id');

        $accessible = [];
        $restricted = [];

        foreach ($menus as $menu) {
            $hasParentAccess = $this->hasMenuAccess($menu, $user, $customPermissions);

            if ($menu->children->isNotEmpty()) {
                $accessibleChildren = [];
                $restrictedChildren = [];

                // Separar hijos accesibles de restringidos
                foreach ($menu->children as $child) {
                    if ($this->hasMenuAccess($child, $user, $customPermissions)) {
                        $accessibleChildren[] = [
                            'id' => $child->id,
                            'label' => $child->label,
                            'route' => $child->route,
                            'order' => $child->order,
                            'status' => $child->status
                        ];
                    } else {
                        $restrictedChildren[] = [
                            'id' => $child->id,
                            'label' => $child->label,
                            'route' => $child->route,
                            'order' => $child->order,
                            'status' => $child->status
                        ];
                    }
                }

                // CAMBIO CLAVE: Incluir el padre SI tiene al menos un hijo accesible
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

                // Si el padre tiene acceso Y tiene hijos accesibles
                if ($hasParentAccess && !empty($accessibleChildren)) {
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

                // Agregar hijos restringidos a la lista de restringidos
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

                // Si el padre NO tiene acceso, agregar todo a restringidos
                if (!$hasParentAccess) {
                    $allChildren = array_merge($accessibleChildren, $restrictedChildren);
                    if (!empty($allChildren)) {
                        $restricted[] = [
                            'id' => $menu->id,
                            'label' => $menu->label,
                            'icon' => $menu->icon,
                            'route' => $menu->route,
                            'order' => $menu->order,
                            'status' => $menu->status,
                            'children' => $allChildren
                        ];
                    }
                }
            } else {
                // Menús sin hijos
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
