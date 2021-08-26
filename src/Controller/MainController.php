<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Time;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('main/index.html.twig', [
            'titre' => 'Bucket-List',
        ]);
    }

    /**
     * @Route("/aboutus", name="aboutus")
     */
    public function aboutus(): Response
    {
        return $this->render('main/aboutus.html.twig', [
            'titre' => 'About us',
        ]);
    }

    /**
     * @Route("/legal-stuff", name="legal")
     */
    public function legal(): Response
    {
        return $this->render('main/legal.html.twig', [
            'titre' => 'Legal stuff',
        ]);
    }
}
