<?php

namespace App\Controller;

use App\Entity\Analytics;
use App\Repository\AnalyticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractController
{
    #[Route('/analytics', name: 'app_analytics')]
    public function index(AnalyticsRepository $analyticsRepository): Response
    {
        $results = $analyticsRepository->selectCountPlayBySong();

        return $this->render('analytics/index.html.twig', [
            'results' => $results,
        ]);
    }
}
