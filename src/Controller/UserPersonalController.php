<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserPersonalController extends AbstractController
{
    /**
     * @Route("/user/personal", name="user_personal")
     */
    public function index()
    {
        return $this->render('user_personal/index.html.twig', [
            'controller_name' => 'UserPersonalController',
        ]);
    }
}
