<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Uuid\Uuid;

final class User
{
    private Uuid $id;

    private string $email = '';

    private string $fullName = '';

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function setId(string $id): self
    {
        $this->id = Uuid::fromString($id);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }
}
