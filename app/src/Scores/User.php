<?php

namespace App\Scores;

use App\Scores\Values\UUID;

class User
{
    private UUID $uuid;

    private string $name;

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function setUuid(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}