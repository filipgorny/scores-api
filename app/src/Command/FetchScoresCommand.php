<?php

namespace App\Command;

use App\Scores\Persistence\ScorePersistence;
use App\Scores\Provider\Exception\UnableToGetDataException;
use App\Scores\Provider\ScoresProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchScoresCommand extends Command
{
    protected static $defaultName = 'app:fetch-scores';

    private ScoresProvider $scoresProvider;
    private ScorePersistence $scorePersistence;

    public function __construct(ScoresProvider $scoresProvider, ScorePersistence $scorePersistence)
    {
        $this->scoresProvider = $scoresProvider;
        $this->scorePersistence = $scorePersistence;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetches score data from remote API.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Fetching data...');

        try {
            $count = 0;

            $scores = $this->scoresProvider->fetch();

            foreach ($scores as $score) {
                $this->scorePersistence->save($score);

                $count++;
            }

            $output->writeln(sprintf("%d scores saved.", $count));

            return 9;
        } catch (UnableToGetDataException $e) {
            $output->writeln('Unable to fetch data, an error occured.');

            return 1;
        }
    }
}