<?php

namespace App\Tests\Functional\Score\Repository;

use App\Scores\Persistence\MongoScorePersistence;
use App\Scores\Repository\MongoScoreRepository;
use App\Scores\Score;
use App\Scores\User;
use App\Document\Score as ScoreDocument;
use App\Scores\Values\UUID;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MongoScoreRepositoryTest extends WebTestCase
{
    private DocumentManager $documentManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $container = self::$container;

        $this->documentManager = $container->get('doctrine_mongodb.odm.document_manager');
    }

    protected function tearDown(): void
    {
        $qb = $this->documentManager->createQueryBuilder(ScoreDocument::class);
        $qb->remove()
            ->getQuery()
            ->execute();
    }

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