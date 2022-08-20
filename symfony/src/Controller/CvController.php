<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Repository\CvRepository;
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
  ) {
  }

  #[Route('/moncv', name: 'app_cv')]
  public function index(Request $request): Response
  {

    // path to cv file
    $cvPdf = 'id/cv/cv-jonathan-plazanet.pdf';

    // IP of CV checker
    $ipVisitor = $request->server->get('REMOTE_ADDR');

    // Search if Ip is already connected
    $cv = $this->cvRepository->findOneBy(['ip' => $ipVisitor]);
    
    if ($cv) {
      $numberConsultations = $cv->getNumberConsultations();
      $numberConsultations++;
      $cv
        ->setNumberConsultations($numberConsultations)
        ->setUpdatedAt(new DateTimeImmutable());
      $this->em->flush();
    } else {
      $cv = new Cv;

      $cv
        ->setVersion('v1')
        ->setIp($ipVisitor)
        ->setNumberConsultations(1);

      $this->em->persist($cv);
      $this->em->flush();
    }

    return $this->render('cv/index.html.twig', [
      'cv_pdf' => $cvPdf,
    ]);
  }
}
