<?php

namespace App\Scores\Repository;

use App\Document\Score;
use App\Scores\Repository\Exception\CorruptedDataException;
use App\Scores\Repository\Exception\RepositoryFetchException;

interface ScoreRepository
{
    const SORT_BY_DATE = 'date';
    const SORT_BY_SCORE = 'score';

    /**
     * @param string $sortBy
     * @return Score[]
     * @throws RepositoryFetchException|CorruptedDataException
     */
    public function findScores($sortBy = self::SORT_BY_DATE): array;
}