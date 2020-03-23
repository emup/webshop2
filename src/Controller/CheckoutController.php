<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Factuurregel;
use App\Entity\User;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
    /**
     * @Route("/", name="checkout")
     */
    public function index(UserInterface $user, SessionInterface $session, EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        if (!$user) return $this->redirectToRoute('default');
        $cart = $session->get('cart', []);
        $products = $productRepository->findBy([
            'id' => array_keys($cart),
        ]);

        $factuur  = new Factuur();
        $factuur->setKlantnummer($user);
        $factuur->setFactuurdatum(new DateTime());
        $factuur->setVervaldatum(new DateTime("+4 weeks"));
        $factuur->setInzakeopdracht("WOSM394");
        $factuur->setKorting(0);
        $entityManager->persist($factuur);
//        $entityManager->flush();
        foreach ($products as $product) {
            $row = new Factuurregel();
            $row->setFactuurnummer($factuur);
            $row->setProductcode($product);
            $row->setProductaantal($cart[$product->getId()]);
            $entityManager->persist($row);
//            $entityManager->flush();
        }
        $entityManager->flush();

        $session->remove('cart');

        return $this->redirectToRoute('checkout_success');
    }

    /**
     * @Route("/thanks", name="checkout_success")
     */
    public function finished()
    {
        return $this->render('checkout/success.html.twig');
    }
}