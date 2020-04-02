<?php

namespace App\Tests\Unit\Command;

use App\Document\Score as ScoreDocument;
use App\Document\User as UserDocument;
use App\Tests\Functional\DatabaseTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class FetchScoresCommandTest extends DatabaseTestCase
{
    public function testCommandShouldPopulateDatabase(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:fetch-scores');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $countScores = $this->documentManager->createQueryBuilder(ScoreDocument::class)
            ->count()->getQuery()->execute();

        $usersCount = $this->documentManager->createQueryBuilder(UserDocument::class)
            ->count()->getQuery()->execute();

        $this->assertGreaterThan(0, $countScores);
        $this->assertGreaterThan(0, $usersCount);
    }
}