<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $session = new Session();
        $value = $session->get('value');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'value' => $value,
        ]);
    }
}
