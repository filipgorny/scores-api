<?php

namespace App\Scores\Persistence;

use App\Scores\Score;

interface ScorePersistence
{
    public function save(Score $score): void;
}