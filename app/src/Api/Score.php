<?php

namespace App\Api;

class Score
{
    /** @var string  */
    public string $id;

    /** @var string  */
    public string $finishedAt;

    /** @var int  */
    public int $score;

    /** @var User */
    public User $user;
}