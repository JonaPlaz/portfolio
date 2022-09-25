<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{

  public function __construct(
    private VisitRepository $visitRepository,
  )
  {
  }

    #[Route('/stats', name: 'app_stats')]
    public function index(): Response
    {
      $visits = $this->visitRepository->findAll();

      $totalVisitsNumber = null;
      $totalVisitsIp = null;

      foreach ($visits as $visit) {
        $visitNumber = $visit->getNumberOfVisits();
        $visitIp = $visit->getIp();
        $totalVisitsNumber += $visitNumber;
        $totalVisitsIp++;
      }

        return $this->render('stats/index.html.twig', [
            'visits' => $visits,
            'total_visits_number' => $totalVisitsNumber,
            'total_ip_number' => $totalVisitsIp,
        ]);
    }
}
