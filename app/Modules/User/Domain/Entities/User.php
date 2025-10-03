<?php

namespace App\Modules\User\Domain\Entities;
use DateTime;
use Exception;
use http\Exception\InvalidArgumentException;

class User
{
    private int $id;
    private string $username;
    private string $firstname;
    private string $lastname;
    private string $password;
    private int $status;
    private string $role;

    /**
     * @param int $id
     * @param string $username
     * @param string $firstname
     * @param string $lastname
     * @param string $password
     * @param int $status
     * @param string $role
     */
    public function __construct(int $id, string $username, string $firstname, string $lastname, string $password, int $status, string $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->status = $status;
        $this->role = $role;
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getFirstname(): string { return $this->firstname; }
    public function getLastname(): string { return $this->lastname; }
    public function getPassword(): string { return $this->password; }
    public function getStatus(): string { return $this->status; }
    public function getRole(): string { return $this->role; }

    // --- Perfil básico
    public function updateUser(string $firstname, string $lastname): void {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    // --- Contraseña
    public function changePassword(string $newPassword): void {
        if (strlen($newPassword) < 8) {
            throw new InvalidArgumentException("La contraseña debe tener al menos 8 caracteres");
        }
        $this->password = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    // --- Estado
    public function activate(): void {
        $this->status = 1;
    }

    public function deactivate(): void {
        $this->status = 0;
    }
}
