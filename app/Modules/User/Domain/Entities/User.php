<?php

namespace App\Modules\User\Domain\Entities;
use App\Modules\UserAssignment\Domain\Entities\UserAssignment;
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
    private ?int $status;
    private array|string|null $roles;
    private ?array $assignment;
    private ?int $st_login;
    private ?string $password_item;

    public function __construct(int $id, ?string $username, string $firstname, string $lastname, ?string $password, array|string|null $roles, array|null $assignment, int|null $st_login, string|null $password_item = null, ?int $status = 1)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->status = $status;
        $this->roles = $roles;
        $this->assignment = $assignment;
        $this->st_login = $st_login;
        $this->password_item = $password_item;
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string|null { return $this->username; }
    public function getFirstname(): string { return $this->firstname; }
    public function getLastname(): string { return $this->lastname; }
    public function getPassword(): string|null { return $this->password; }
    public function getStatus(): ?int { return $this->status; }
    public function getRoles(): array|string|null { return $this->roles; }
    public function getAssignment(): ?array { return $this->assignment; }
    public function getStLogin(): ?int { return $this->st_login; }
    public function getPasswordItem(): ?string { return $this->password_item; }


    // --- Perfil básico
    public function updateUser(string $firstname, string $lastname): void {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    // --- Estado
    public function activate(): void {
        $this->status = 1;
    }

    public function deactivate(): void {
        $this->status = 0;
    }
}
