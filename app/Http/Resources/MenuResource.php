<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'order' => $this->order,
            'status' => ($this->status) == 1 ? 'Activo' : 'Inactivo',
        ];

        // Aplica el mismo Resource a los hijos si existen
        if ($this->children->isNotEmpty()) {
            $data['children'] = MenuResource::collection($this->children);
        }

        return $data;
    }
}
