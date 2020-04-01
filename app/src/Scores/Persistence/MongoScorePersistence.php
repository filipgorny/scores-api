<?php

namespace App\Scores\Persistence;

use App\Document\User as ODMUser;
use App\Document\Score as ODMScore;
use App\Scores\Score;
use Doctrine\ODM\MongoDB\DocumentManager;

class MongoScorePersistence implements ScorePersistence
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function save(Score $score): void
    {
        $userDocument = $this->documentManager
            ->getRepository(ODMUser::class)
            ->findBy(['id' => (string) $score->getUser()->getUuid()]);

        if (!$userDocument) {
            $userDocument = new ODMUser();
        }

        $userDocument->setId((string) $score->getUser()->getUuid());
        $userDocument->setName($score->getUser()->getName());

        $scoreDocument = $this->documentManager
            ->getRepository(ODMScore::class)
            ->findBy(['id' => (string) $score->getUuid()]);

        if (!$scoreDocument) {
            $scoreDocument = new ODMScore();
        }

        $scoreDocument->setId((string) $score->getUuid());
        $scoreDocument->setFinishedAt($score->getFinishedAt()->getTimestamp());
        $scoreDocument->setScore($score->getScore());
        $scoreDocument->setUser($userDocument);

        $this->documentManager->persist($scoreDocument);

        $this->documentManager->flush();
    }
}