<?php

namespace App\Tests\Functional;

use App\Document\Score as ScoreDocument;
use App\Document\User as UserDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseTestCase extends KernelTestCase
{
    protected DocumentManager $documentManager;

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

        $qb = $this->documentManager->createQueryBuilder(UserDocument::class);
        $qb->remove()
            ->getQuery()
            ->execute();
    }
}