<?php

namespace App\Scores\Provider;

use App\Document\Score;

interface ScoresProvider
{
    /** @return Score[] */
    public function fetch(): array;
}