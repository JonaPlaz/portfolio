<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\Visit;
use App\Repository\CvRepository;
use App\Repository\VisitRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CvController extends AbstractController
{

  public function __construct(
    private EntityManagerInterface $em,
    private CvRepository $cvRepository,
    private VisitRepository $visitRepository,
  ) {
  }

  #[Route('/moncv', name: 'app_cv')]
  public function index(Request $request): Response
  {

    // path to cv file
    $cvV1 = 'id/cv/cv-jonathan-plazanet-v1.pdf';

    // IP of CV checker
    $ipVisit = $request->server->get('REMOTE_ADDR');

    // Search if Ip is already connected
    $visit = $this->visitRepository->findOneBy(['ip' => $ipVisit]);
    if ($visit) {
      if ($visit->getCv()->getVersion() === 'v1') {
        $visits = $visit->getNumberOfVisits();
        $visits++;
        $visit
          ->setNumberOfVisits($visits)
          ->setUpdatedAt(new DateTimeImmutable());
      }
    } else {
      $visit = new Visit;

      $cv = $this->cvRepository->findOneBy(['id' => 1]);
      
      $visit
        ->setIp($ipVisit)
        ->setNumberOfVisits(1)
        ->setCv($cv);

      $this->em->persist($visit);
    }
    $this->em->flush();

    return $this->render('cv/index.html.twig', [
      'cv_v1' => $cvV1,
    ]);
  }
}
