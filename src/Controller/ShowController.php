<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ShowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/shows')]
class ShowController extends AbstractController
{
    public function __construct(
        private ShowService $showService
    ) {
    }

    #[Route(path: '/search/{search}', name: 'search_show', methods: 'GET')]
    public function search(string $search): Response
    {
        $searchedShows = $this->showService->searchShow($search);

        return $this->json($searchedShows->toArray(), 200);
    }
}
