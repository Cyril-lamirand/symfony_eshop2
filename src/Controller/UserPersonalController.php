<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\ContentCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserPersonalController extends AbstractController
{
    /**
     * @Route("/user/personal", name="user_personal")
     */
    public function index()
    {
        $pdo = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $carts = $pdo->getRepository(Cart::class)->findBy(array('user' => $user,'state' => true));

        $contentCarts = $pdo->getRepository(ContentCart::class)->findBy(array('cart' => $carts));
        return $this->render('user_personal/index.html.twig', [
            'carts' => $carts,
            'user' => $user,
            'contentCarts' => $contentCarts
        ]);
    }
}
