<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CvController extends AbstractController
{
  #[Route('/cv', name: 'app_cv')]
  public function index(): Response
  {    
    $cvPdf = 'id/cv/cv-jonathan-plazanet.pdf';

    return $this->render('cv/index.html.twig', [
      'cv_pdf' => $cvPdf,
    ]);
  }
}
