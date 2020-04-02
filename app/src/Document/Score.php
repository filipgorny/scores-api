<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MongoDB\BSON\Timestamp;

/**
 * @MongoDB\Document
 */
class Score
{
    /**
     * @MongoDB\Id(strategy="UUID")
     */
    private string $id;

    /**
     * @MongoDB\ReferenceOne(
     *        targetDocument="App\Document\User",
     *        cascade="persist"
     * )
     */
    private \App\Document\User $user;

    /**
     * @MongoDB\Field(type="integer")
     */
    private int $score;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    private Timestamp $finishedAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getFinishedAt(): Timestamp
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(int $finishedAt): void
    {
        $this->finishedAt =  new Timestamp(1, $finishedAt);
    }
}