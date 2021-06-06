<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ShowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/show')]
class ShowController extends AbstractController
{
    public function __construct(
        private ShowService $showService
    ) {
    }

    #[Route(path: '/search/{search}', name: 'search_show', methods: 'GET')]
    public function search(
        string $search
    ): Response {
        $searchedShows = $this->showService->searchShow($search);

        return $this->json($searchedShows->toArray(), 200);
    }

    #[Route(path: '/{id}', name: 'get_show_details', methods: 'GET')]
    public function getShowDetails(
        int $id
    ): Response {
        $detailedShow = $this->showService->getShowDetails($id);

        if (empty($detailedShow)) {
            return $this->json('error: no data', 404);
        }

        return $this->json($detailedShow, 200);
    }

    #[Route(path: '/{id}', name: 'save_show', methods: 'POST')]
    public function save(
        int $id
    ): Response {
        $show = $this->showService->saveShow($id);

        if (empty($show)) {
            return $this->json('error: no data', 404);
        }

        return $this->json($show, 200);
    }
}
