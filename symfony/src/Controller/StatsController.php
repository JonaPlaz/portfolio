<?php

namespace App\Controller;

use App\Repository\CvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{

  public function __construct(
    private CvRepository $cv,
  )
  {
  }


    #[Route('/stats', name: 'app_stats')]
    public function index(): Response
    {
      $visitors = $this->cv->findAll();

        return $this->render('stats/index.html.twig', [
            'visitors' => $visitors,
        ]);
    }
}
