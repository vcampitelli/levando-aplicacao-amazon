<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;

#[Entity]
class User implements JsonSerializable
{
    #[Column(type: "primary")]
    private ?int $id;

    #[Column(type: "string")]
    private string $username;

    #[Column(type: "string")]
    private string $name;

    public function __construct(?int $id, string $username, string $name)
    {
        $this->id = $id;
        $this->username = strtolower($username);
        $this->name = ucfirst($name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
        ];
    }
}
