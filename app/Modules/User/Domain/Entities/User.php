<?php

namespace App\Modules\User\Domain\Entities;
use DateTime;
use Exception;
use http\Exception\InvalidArgumentException;

class User
{
    private int $id;
    private ?string $username;
    private string $firstname;
    private string $lastname;
    private ?string $password;
    private int $status;
    private string|null $role;
    private array|null $assignments;

    /**
     * @param int $id
     * @param string|null $username
     * @param string $firstname
     * @param string $lastname
     * @param ?string $password
     * @param int $status
     * @param string|null $role
     * @param array|null $assignments
     */
    public function __construct(int $id, ?string $username, string $firstname, string $lastname, ?string $password, int $status, string|null $role, array|null $assignments)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->status = $status;
        $this->role = $role;
        $this->assignments = $assignments;
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string|null { return $this->username; }
    public function getFirstname(): string { return $this->firstname; }
    public function getLastname(): string { return $this->lastname; }
    public function getPassword(): string|null { return $this->password; }
    public function getStatus(): string { return $this->status; }
    public function getRole(): string|null { return $this->role; }
    public function getAssignments(): ?array { return $this->assignments; }


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
