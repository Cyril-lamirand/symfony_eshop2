<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\ContentCart;
use App\Form\CartType;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart_index", methods={"GET"})
     */
    public function index(CartRepository $cartRepository): Response
    {
        // DB Info
        $pdo = $this->getDoctrine()->getManager();
        // Get the current user
        $user = $this->get('security.token_storage')->getToken()->getUser();
        // Get the user carts
        $carts = $pdo->getRepository(Cart::class)->findBy(array('user' => $user,'state' => false));
        // Get the content of the carts
        $contentCarts = $pdo->getRepository(ContentCart::class)->findBy(array('cart' => $carts));

        return $this->render('cart/index.html.twig', [
            'carts' => $carts,
            'contentCarts' => $contentCarts,
        ]);
    }

    /**
     * @Route("/new", name="cart_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // Create a new cart
        $cart = new Cart();
        // Get the current user
        $user = $this->get('security.token_storage')->getToken()->getUser();
        // Set datas
        $cart->setUser($user);
        $cart->setState(false);
        // Create Form
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        // Insert in DB
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/info/{id}", name="cart_show", methods={"GET"})
     */
    public function show(Cart $cart): Response
    {
        // DB infos
        $pdo = $this->getDoctrine()->getManager();
        // Get the carts content
        $contentCarts = $pdo->getRepository(ContentCart::class)->findBy(array('cart' => $cart));

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
            'contentCarts' => $contentCarts
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cart_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cart $cart): Response
    {
        // Create edit form
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        // Initialize datas
        $cart->setState(true);
        $cart->setDatebuy(new \DateTime('now'));
        // Update in DB
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/{id}", name="cart_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cart $cart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_index');
    }
}
