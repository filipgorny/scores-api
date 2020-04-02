<?php

namespace App\Scores\Repository;

use App\Scores\Repository\Exception\CorruptedDataException;
use App\Scores\Repository\Exception\RepositoryFetchException;
use App\Scores\Score;
use App\Scores\User;
use App\Scores\Values\UUID;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use App\Document\Score as ScoreDocument;

class MongoScoreRepository implements ScoreRepository
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function findScores($sortBy = ScoreRepository::SORT_BY_DATE): array
    {
        $query = $this->documentManager->createQueryBuilder(ScoreDocument::class);

        switch ($sortBy) {
            case 'score':
                $query->sort('score', -1);
                break;
            default:
                $query->sort('finishedAt', 1);
        }

        $result = [];

        try {
            $resultDocuments = $query->getQuery()->execute();
        } catch (MongoDBException $e) {
            throw new RepositoryFetchException();
        }

        /**
         * @var \App\Document\Score $scoreDocument
         */
        foreach ($resultDocuments as $scoreDocument) {
            $score = new Score();

            try {
                $date = new \DateTime();
                $date->setTimestamp($scoreDocument->getFinishedAt()->getTimestamp());
                $score->setFinishedAt($date);
            } catch (\Exception $e) {
                throw new CorruptedDataException();
            }

            $score->setUuid(new UUID($scoreDocument->getId()));
            $score->setScore($scoreDocument->getScore());
            $user = new User();
            $user->setUuid(new UUID($scoreDocument->getUser()->getId()));
            $user->setName($scoreDocument->getUser()->getName());

            $score->setUser($user);

            $result[] = $score;
        }

        return $result;
    }
}