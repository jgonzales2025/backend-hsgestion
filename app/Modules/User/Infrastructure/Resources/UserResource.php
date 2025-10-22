<?php

namespace App\Modules\User\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'username' => $this->resource->getUsername(),
            'firstname' => $this->resource->getFirstname(),
            'lastname' => $this->resource->getLastname(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            'user_roles' => $this->resource->getRoles(),
            'assignments' => $this->resource->getAssignment(),
            'st_login' => $this->resource->getStLogin(),
        ];
    }
}
