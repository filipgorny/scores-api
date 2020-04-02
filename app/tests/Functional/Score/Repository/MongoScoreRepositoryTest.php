<?php

namespace App\Tests\Functional\Score\Repository;

use App\Document\User as UserDocument;
use App\Scores\Persistence\MongoScorePersistence;
use App\Scores\Repository\MongoScoreRepository;
use App\Scores\Repository\ScoreRepository;
use App\Scores\Score;
use App\Scores\User;
use App\Document\Score as ScoreDocument;
use App\Scores\Values\UUID;
use App\Tests\Functional\DatabaseTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MongoScoreRepositoryTest extends DatabaseTestCase
{
    public function testFetchingScores(): void
    {
        $persister = new MongoScorePersistence($this->documentManager);

        $repository = new MongoScoreRepository($this->documentManager);

        $scores = $this->createScores();

        foreach ($scores as $score) {
            $persister->save($score);
        }

        $fetchedScores = $repository->findScores();

        $this->assertCount(count($scores), $fetchedScores);

        foreach ($fetchedScores as $fetchedScore) {
            if (!isset($scores[(string) $fetchedScore->getUuid()])) {
                $this->fail('Element from repository was not in the generated scores.');
            }

            $generatedScore = $scores[(string) $fetchedScore->getUuid()];

            $this->assertEquals($generatedScore->getScore(), $fetchedScore->getScore());
            $this->assertEquals($generatedScore->getFinishedAt()->format('YmdHi'), $fetchedScore->getFinishedAt()->format('YmdHi'));
            $this->assertEquals((string) $generatedScore->getUser()->getUuid(), (string) $fetchedScore->getUser()->getUuid());
            $this->assertEquals($generatedScore->getUser()->getName(), $fetchedScore->getUser()->getName());
        }
    }

    public function testFetchingWithSorting(): void
    {
        $persister = new MongoScorePersistence($this->documentManager);

        $repository = new MongoScoreRepository($this->documentManager);

        $scores = $this->createScores();

        foreach ($scores as $score) {
            $persister->save($score);
        }

        $fetchedScores = $repository->findScores(ScoreRepository::SORT_BY_SCORE);

        $this->assertCount(count($scores), $fetchedScores);

        $previousScore = -1;

        foreach ($fetchedScores as $fetchedScore) {
            if (!isset($scores[(string) $fetchedScore->getUuid()])) {
                $this->fail('Element from repository was not in the generated scores.');
            }

            if ($previousScore > 0) {
                $this->assertTrue(
                    $fetchedScore->getScore() <= $previousScore,
                    $fetchedScore->getScore() . ' ' . $previousScore
                );
            }

            $previousScore = $fetchedScore->getScore();
        }
    }

    private function createScores(int $limit = 5): array
    {
        $scores = [];

        for ($i=0; $i<$limit; $i++) {
            $score = new Score();
            $score->setScore(rand(0, 100));
            $score->setFinishedAt(new \DateTime('now'));
            $score->setUuid(new UUID());

            $user = new User();
            $user->setUuid(new UUID());
            $user->setName(uniqid('user_name'));

            $score->setUser($user);

            $scores[(string) $score->getUuid()] = $score;
        }

        return $scores;
    }
}