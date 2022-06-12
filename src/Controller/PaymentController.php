<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Class\Cart;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    //private $stripe;//= Stripe::setApiKey($this->getParameter('app.stripe_api_key'));
    

    public function __construct(CacheItemPoolInterface $cache){
        $this->cache = $cache;
    }

    #[Route('/payment', name: 'app_payment', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig',[
                'datas' => $this->cache->getItem('payment.id.2'),
            ]   
        );
    }
    
    #[Route('/create-checkout-session', name: 'checkout', methods:['POST'])]
    public function checkout(): Response
    {
        // Check si id de quoi existe
        $productLine = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'T-shirt',
                ],
                'unit_amount' => 2000,
            ],
            'quantity' => 1,
        ];

        // Stripe 
        Stripe::setApiKey($this->getParameter('app.stripe_api_key'));

        $session = Session::create([
            'line_items' => [
                $productLine
            ],
            'customer_email' => 'customer.one@xyz.com',
            'client_reference_id' => 1,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url,Response::HTTP_SEE_OTHER);
    }

    #[Route('/payment-success', name: 'app_payment_success', methods:['GET'])]
    public function success(): Response
    {   
        Stripe::setApiKey($this->getParameter('app.stripe_api_key'));
        $session = Session::all(['limit' => 1]);
        
        // Cache Saving
        $cacheItem = $this->cache->getItem('payment.id.'.$session->data[0]['id']);

        if (!$cacheItem->isHit()) {
            $cacheItem->set(
                [
                    'uuid' => $session->data[0]['payment_intent'],
                    'status' => $session->data[0]['payment_status'],
                ]
            );
            $cacheItem->expiresAfter(3600);
            $this->cache->save($cacheItem);
        }

        return $this->render('payment/success.html.twig',[
            'datas' => $session,
        ]);
    }

    #[Route('/payment-error', name: 'app_payment_error', methods:['GET'])]
    public function error(): Response
    {
        Stripe::setApiKey($this->getParameter('app.stripe_api_key'));
        $session = Session::all(['limit' => 1]);
        // Cache Saving
        $cacheItem = $this->cache->getItem('payment.id.'.$session->data['id']);

        if (!$cacheItem->isHit()) {
            $cacheItem->set(
                [
                    'uuid' => $session->data['payment_intent'],
                    'status' => $session->data['payment_status'],
                ]
            );
            $cacheItem->expiresAfter(3600);
            $this->cache->save($cacheItem);
        }

        //
        return $this->render('payment/error.html.twig',
            ['datas' => $session],
        );
    }

    #[Route('/cache', name: 'app_cache', methods:['GET'])]
    public function getCache(): Response
    {
        return $this->render('cart/index.html.twig',
            ['datas' => $this->cache->getItem('payment.id.'),]
        );
    }
}
