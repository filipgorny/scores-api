<?php

namespace App\Scores\Provider;

use App\Document\Score;
use App\Scores\Provider\Exception\UnableToGetDataException;

interface ScoresProvider
{
    /**
     * @return \App\Scores\Score[]
     * @throws UnableToGetDataException
     */
    public function fetch(): array;
}