<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
        $cart = $session->get('cart', []);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'products' => $productRepository->findBy([
                'id' => array_keys($cart),
            ]),
        ]);
    }

    /**
     * @Route("/add/{product}", name="cart_add")
     */
    public function add(SessionInterface $session, Product $product)
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()]++;
        } else {
            $cart[$product->getId()] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('default');
    }

    /**
     * @Route("/remove/{product}", name="cart_remove")
     */
    public function remove(SessionInterface $session, Product $product)
    {
        $cart = $session->get('cart', []);

        $cart[$product->getId()]--;

        if ($cart[$product->getId()] <= 0) unset($cart[$product->getId()]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }
}