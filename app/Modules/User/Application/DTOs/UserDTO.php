<?php

namespace App\Modules\User\Application\DTOs;

class UserDTO
{
    public $username;
    public $firstname;
    public $lastname;
    public $role;
    public $password;
    public $status;

    public function __construct(array $data) {
        $this->username = $data['username'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->role = $data['role_id'];
        $this->password = $data['password'];
        $this->status = $data['status'];
    }
}
