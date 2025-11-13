<?php

namespace App\Modules\User\Application\DTOs;

class UserDTO
{
    public $username;
    public $firstname;
    public $lastname;
    public $userRoles;
    public $password;
    public ?string $password_item;

    public function __construct(array $data) {
        $this->username = $data['username'] ?? null;
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->userRoles = $data['user_roles'] ?? [];
        $this->password = $data['password'] ?? null;
        $this->password_item = $data['password_item'] ?? null;
    }
}
