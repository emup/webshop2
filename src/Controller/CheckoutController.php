<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Factuurregel;
use App\Entity\User;
use App\Repository\FactuurRepository;
use App\Repository\ProductRepository;
use Core23\DompdfBundle\Wrapper\DompdfWrapperInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
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
        $session->set('last_factuur', $factuur->getId());

        return $this->redirectToRoute('checkout_success');
    }

    /**
     * @Route("/thanks", name="checkout_success")
     */
    public function finished()
    {
        return $this->render('checkout/success.html.twig');
    }
    public function __construct(DompdfWrapperInterface $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * @Route("/pdf", name="factuurpdf")
     */
    public function factuurpdf(SessionInterface $session, DompdfWrapperInterface $wrapper, FactuurRepository $factuurRepository)
    {
        $factuur = $factuurRepository->findOneBy(['id' => $session->get('last_factuur')]);

        $html = $this->renderView('checkout/factuurpdf.html.twig', ['factuur' => $factuur]);
        return $wrapper->getStreamResponse($html, "factuur.pdf");
    }
}