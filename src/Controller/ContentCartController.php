<?php

namespace App\Controller;

use App\Entity\ContentCart;
use App\Form\ContentCartType;
use App\Repository\ContentCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/content/cart")
 */
class ContentCartController extends AbstractController
{
    /**
     * @Route("/", name="content_cart_index", methods={"GET"})
     */
    public function index(ContentCartRepository $contentCartRepository): Response
    {
        return $this->render('content_cart/index.html.twig', [
            'content_carts' => $contentCartRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="content_cart_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contentCart = new ContentCart();
        $form = $this->createForm(ContentCartType::class, $contentCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contentCart);
            $entityManager->flush();

            return $this->redirectToRoute('content_cart_index');
        }

        return $this->render('content_cart/new.html.twig', [
            'content_cart' => $contentCart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_cart_show", methods={"GET"})
     */
    public function show(ContentCart $contentCart): Response
    {
        return $this->render('content_cart/show.html.twig', [
            'content_cart' => $contentCart,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_cart_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContentCart $contentCart): Response
    {
        $form = $this->createForm(ContentCartType::class, $contentCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_cart_index');
        }

        return $this->render('content_cart/edit.html.twig', [
            'content_cart' => $contentCart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_cart_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContentCart $contentCart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contentCart->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contentCart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_index');
    }
}
