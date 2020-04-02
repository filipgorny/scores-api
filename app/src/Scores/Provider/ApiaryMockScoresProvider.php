<?php

namespace App\Scores\Provider;

use App\Scores\Provider\Exception\UnableToGetDataException;
use App\Scores\Score;
use App\Scores\User;
use App\Scores\Values\UUID;

class ApiaryMockScoresProvider implements ScoresProvider
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function fetch(): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            throw new UnableToGetDataException();
        }

        $jsonData = @json_decode($result, true);

        if (!$jsonData) {
            throw new UnableToGetDataException();
        }

        $result = [];

        foreach ($jsonData as $entry) {
            if ($this->validEntry($entry)) {
                $user = new User();
                $user->setUuid(new UUID($entry['user']['id']));
                $user->setName($entry['user']['name']);

                $score = new Score();
                $score->setUuid(new UUID($entry['id']));
                $score->setUser($user);
                $score->setScore($entry['score']);

                try {
                    $score->setFinishedAt(new \DateTime($entry['finished_at']));
                    $result[] = $score;
                } catch (\Exception $e) {
                }
            }
        }

        return $result;
    }

    private function validEntry(array $entry): bool
    {
        return (
            $this->validField($entry, 'id') &&
            $this->validField($entry, 'user') &&
            is_array($entry['user']) &&
            $this->validField($entry['user'], 'id') &&
            $this->validField($entry['user'], 'name') &&
            $this->validField($entry, 'finished_at') &&
            $this->validField($entry, 'score')
        );
    }

    private function validField(array $entry, string $field): bool
    {
        return !(!isset($entry[$field]) || empty($entry[$field]));
    }
}