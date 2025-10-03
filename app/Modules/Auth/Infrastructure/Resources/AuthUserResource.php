<?php

namespace App\Modules\Auth\Infrastructure\Resources;

use App\Modules\Auth\Application\Services\MenuPermissionService;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id'          => $this->resource->getId(),
            'username'    => $this->resource->getUsername(),
            'role'       => $this->resource->getRole()
        ];

    }

}
