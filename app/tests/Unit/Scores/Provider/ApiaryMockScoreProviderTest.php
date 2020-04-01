<?php

namespace App\Tests\Unit\Scores\Provider;

use App\Scores\Provider\ApiaryMockScoresProvider;
use App\Scores\Provider\Exception\UnableToGetDataException;
use App\Scores\Score;
use App\Scores\User;
use PHPUnit\Framework\TestCase;

class ApiaryMockScoreProviderTest extends TestCase
{
    public function testShouldThrowExceptionIfInvalidUrl(): void
    {
        $serviceProvider = new ApiaryMockScoresProvider(
            'invalid+https://private-b5236a-jacek10.apiary-mock.com/results/games/1'
        );

        $this->expectException(UnableToGetDataException::class);

        $scores = $serviceProvider->fetch();
    }

    public function testShouldReturnValidScores(): void
    {
        $serviceProvider = new ApiaryMockScoresProvider(
            'https://private-b5236a-jacek10.apiary-mock.com/results/games/1'
        );

        $scores = $serviceProvider->fetch();

        foreach ($scores as $score) {
            $this->assertInstanceOf(Score::class, $score);
            $this->assertInstanceOf(User::class, $score->getUser());
        }
    }
}