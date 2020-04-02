<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\Score;
use App\Api\User;
use App\Scores\Repository\ScoreRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ScoresController extends AbstractFOSRestController
{
    private ScoreRepository $scoresRepository;

    public function __construct(ScoreRepository $scoresRepository)
    {
        $this->scoresRepository = $scoresRepository;
    }

    /**
     * @Route("/api/scores", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of scores.",
     *         @Model(type=App\Api\Score::class)
     * )
     *
     * @Rest\QueryParam(name="sort",  nullable=true, description="Sort by 'date' or 'score'.", requirements="date|score")
     *
     * @Rest\Get("/scores")
     */
    public function scores(ParamFetcher $paramFetcher): View {
        $sort = $paramFetcher->get('sort');

        $scoresSort = ScoreRepository::SORT_BY_DATE;

        if ($sort == 'score') {
            $scoresSort = ScoreRepository::SORT_BY_SCORE;
        }

        $scores = $this->scoresRepository->findScores($scoresSort);

        $result = [];

        foreach ($scores as $score) {
            $apiScore = new Score();

            $apiScore->finishedAt = $score->getFinishedAt()->format('Y-m-d H:i:s');
            $apiScore->score = $score->getScore();
            $apiScore->id = (string) $score->getUuid();

            $apiUser = new User();
            $apiUser->id = (string) $score->getUser()->getUuid();
            $apiUser->name = $score->getUser()->getName();

            $apiScore->user = $apiUser;

            $result[] = $apiScore;
        }

        return View::create($result);
    }
}