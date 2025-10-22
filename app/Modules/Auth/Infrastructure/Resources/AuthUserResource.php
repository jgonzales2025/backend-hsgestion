<?php

namespace App\Modules\Auth\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id'          => $this->resource->getId(),
            'username'    => $this->resource->getUsername(),
            'role'       => $this->resource->getRoles(),
            'assignments' => $this->resource->getAssignment() ?? [],
            'st_login'    => $this->resource->getStLogin(),
        ];

    }

}
