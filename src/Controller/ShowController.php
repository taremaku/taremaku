<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SerializerService;
use App\Service\ShowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/show')]
class ShowController extends AbstractController
{
    public function __construct(
        private ShowService $showService,
        private SerializerService $serializerService
    ) {
    }

    #[Route(path: '/search/{search}', name: 'search_show', methods: 'GET')]
    public function search(
        string $search
    ): Response {
        $searchedShows = $this->showService->searchShow($search);

        return $this->json($searchedShows->toArray(), 200, [], ['groups' => 'search_show']);
    }

    #[Route(path: '/{id}', name: 'get_show_details', methods: 'GET')]
    public function getShowDetails(
        int $id
    ): Response {
        $detailedShow = $this->showService->getShowDetails($id);

        if (empty($detailedShow)) {
            return $this->json(['error' => 'no data'], 404);
        }

        return new Response($this->serializerService->serializeResponse($detailedShow), 200, ['Content-Type' => 'application/json']);
    }

    #[Route(path: '/{id}/full', name: 'get_show_full', methods: 'GET')]
    public function getShowFull(
        int $id
    ): Response {
        $fullShow = $this->showService->getShowFull($id);

        if (empty($fullShow)) {
            return $this->json(['error' => 'no data'], 404);
        }

        return $this->json($fullShow, 200, [], ['groups' => 'full_show']);
    }

    #[Route(path: '/{id}', name: 'save_show', methods: 'POST')]
    public function save(
        int $id
    ): Response {
        $show = $this->showService->saveShow($id);

        if (empty($show)) {
            return $this->json(['error' => 'no data'], 404);
        }

        return $this->json(['message' => 'success'], 200);
    }
}
