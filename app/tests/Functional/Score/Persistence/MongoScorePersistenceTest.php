<?php

namespace App\Tests\Functional\Score\Persistence;

use App\Document\Score as ScoreDocument;
use App\Document\User as UserDocument;
use App\Scores\Persistence\MongoScorePersistence;
use App\Scores\Score;
use App\Scores\User;
use App\Scores\Values\UUID;
use App\Tests\Functional\DatabaseTestCase;

class MongoScorePersistenceTest extends DatabaseTestCase
{
    public function testUpdatesExistingEntries(): void
    {
        $mongoPersistence = new MongoScorePersistence($this->documentManager);

        $score = new Score();
        $score->setScore(10);
        $score->setFinishedAt(new \DateTime('now'));
        $score->setUuid(new UUID());
        $user = new User();
        $user->setUuid(new UUID());
        $user->setName(uniqid('user_name'));
        $score->setUser($user);

        $mongoPersistence->save($score);

        $score2 = new Score();
        $score2->setScore(20);
        $score2->setFinishedAt(new \DateTime('now'));
        $score2->setUuid(new UUID());
        $score2->setUser($user);

        $mongoPersistence->save($score2);

        $score3 = new Score();
        $score3->setScore(1000);
        $score3->setFinishedAt(new \DateTime('now'));
        $score3->setUuid(new UUID((string) $score2->getUuid()));
        $score3->setUser($user);

        $mongoPersistence->save($score3);

        $countScores = $this->documentManager->createQueryBuilder(ScoreDocument::class)
            ->count()->getQuery()->execute();

        $usersCount = $this->documentManager->createQueryBuilder(UserDocument::class)
            ->count()->getQuery()->execute();

        $this->assertEquals(2, $countScores);
        $this->assertEquals(1, $usersCount);
    }
}