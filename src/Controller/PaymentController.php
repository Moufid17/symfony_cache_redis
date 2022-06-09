<?php

namespace App\Controller;

use App\Entity\Payment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class PaymentController extends AbstractController
{
    // /**
    //  * @var CacheItemPoolInterface
    //  */
    // private $cache;
    // private $paymentIDS;

    public function __construct(CacheItemPoolInterface $cache){
        $this->cache = $cache;
    }

    #[Route('/payment', name: 'app_payment', methods:['GET'])]
    public function index(): Response
    {
        // $cacheItems = [];
        // foreach($this->paymentIDS as $id){
        //     $cacheItems[$id] = $this->cache->getItem('payment.id.' .$id);
        // }

        return $this->render('payment/index.html.twig',[
                'datas' => $this->cache->getItem('payment.id.2'),
            ]   
        );
    }
    
    
    #[Route('/payment/{payment}', name: 'app_add_payment', methods:['GET'])]
    public function add(String $payment, EntityManagerInterface $em): Response
    {

        $paymentInstance = [
            'id' => $payment,
            'datas' => [
                'amount' =>141, 
                'methods' =>'card', 
                'response' => 'success',
            ],
            'userFrom' => 'Moufid',
            'userTo' => 'Mouhite',
        ];

        $item = $this->cache->getItem('payment.id.' . $payment);

        $itemCameFromCache = true;
        if (!$item->isHit()) {
            $itemCameFromCache = false;
            $item->set($paymentInstance);
            $item->expiresAfter(3600);
            $this->cache->save($item);
        }

        return $this->render('payment/index.html.twig', [
            'isCached' => $itemCameFromCache ? 'true' : 'false',
            'data' => $item,
        ]);
    }
}
