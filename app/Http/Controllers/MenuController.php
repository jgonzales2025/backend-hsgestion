<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Obtener menús disponibles para el usuario autenticado
     */
    public function getUserMenus(Request $request)
    {
        $user = $request->user();

        // Obtener todos los menús activos principales con sus hijos
        $menus = Menu::active()
            ->main()
            ->ordered()
            ->with(['children' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();

        // Filtrar menús según permisos del usuario
        $filteredMenus = $menus->filter(function ($menu) use ($user) {
            return $this->userCanAccessMenu($menu, $user);
        })->map(function ($menu) use ($user) {
            return $this->formatMenu($menu, $user);
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'menus' => $filteredMenus
            ]
        ]);
    }

    /**
     * Verificar si el usuario puede acceder al menú
     */
    protected function userCanAccessMenu($menu, $user)
    {
        // Si no requiere permiso, está disponible
        if (!$menu->permission) {
            return true;
        }

        // Verificar permiso
        return $user->can($menu->permission);
    }

    /**
     * Formatear menú con sus hijos filtrados
     */
    protected function formatMenu($menu, $user)
    {
        $formattedMenu = [
            'id' => $menu->id,
            'name' => $menu->name,
            'label' => $menu->label,
            'icon' => $menu->icon,
            'route' => $menu->route,
            'type' => $menu->type,
            'order' => $menu->order,
        ];

        // Si tiene hijos (submenús), filtrarlos por permisos
        if ($menu->children->isNotEmpty()) {
            $children = $menu->children->filter(function ($child) use ($user) {
                return $this->userCanAccessMenu($child, $user);
            })->map(function ($child) use ($user) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'label' => $child->label,
                    'icon' => $child->icon,
                    'route' => $child->route,
                    'type' => $child->type,
                    'order' => $child->order,
                ];
            })->values();

            // Solo incluir el menú padre si tiene al menos un hijo accesible
            if ($children->isNotEmpty()) {
                $formattedMenu['children'] = $children;
            } else if ($menu->type === 'group') {
                // Si es un grupo sin hijos accesibles, no mostrarlo
                return null;
            }
        }

        return $formattedMenu;
    }

    /**
     * Obtener todos los menús (para administración)
     */
    public function index()
    {
        $menus = Menu::with(['children'])
            ->main()
            ->ordered()
            ->get();

        return response()->json((MenuResource::collection($menus)->resolve()));
    }
}
