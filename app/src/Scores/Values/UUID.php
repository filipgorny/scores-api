<?php

namespace App\Scores\Values;

use Ramsey\Uuid\Uuid as RamseyUuid;

class UUID
{
    private string $value;

    public function __construct(string $value = null)
    {
        if ($value === null) {
            $value = RamseyUuid::uuid4();
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}