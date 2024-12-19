<?php

declare(strict_types=1);

namespace App\Cart\Application\ReadModel;

final class CustomerReadModel implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $email
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
