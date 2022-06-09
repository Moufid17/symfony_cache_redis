<?php

namespace App\Controller;

use App\Entity\Payment;
use Doctrine\ORM\EntityManager;
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

    // public function __construct(CacheItemPoolInterface $cache){
    //     $this->cache = $cache;
    // }

    #[Route('/payment', name: 'app_payment', methods:['GET'])]
    public function index(CacheInterface $cache): Response
    {
        return $this->render('payment/index.html.twig',[
                'cache' => $cache->get('user.account', function (){}),
            ]   
        );
    }
    
    
    #[Route('/payment/{payment}', name: 'app_add_payment', methods:['GET'])]
    public function add(String $payment, EntityManagerInterface $em, CacheInterface $paymentCache, CacheItemPoolInterface $cache): Response
    {
        $paymentID = $payment;

        
        // Try to fetch item from cache
        // $item = $cache->getItem('user.account');
        // $paymentInstance = [
        //             'id' => $paymentID,
        //             'datas' => [
        //                 'amount' =>141, 
        //                 'methods' =>'card', 
        //                 'response' => 'success',
        //             ],
        //             'userFrom' => 'Moufid',
        //             'userTo' => 'Mouhite',
        //         ];
        // // Somehow it was not found in cache
        // $item->set($paymentInstance);
        // $cache->save($item);
        // if(!$item->isHit()) {
        //     // sleep(2);
        //     $item->set("paymentInstance");
        //     $cache->save($item);
        // }

        // if (!$this->cache->hasItem($paymentID)) {
        //     $item = $this->cache->getItem($paymentID);
        //     if ($item->isHit()) {
        //         $item->set($paymentInstance);
        //         $this->cachePool->save($item);
        //     }
        // }
        // if (!$item->isHit()) {
        //     $item->set($paymentInstance);
        //     $item->expiresAt(date_create('tomorrow')); // the item will be cached for 10 seconds
        //     $this->cache->save($item);
        // }
        // $cache = new FilesystemAdapter();

        // /** @var Payment $payment **/
        $payment = $paymentCache->get(strtolower($paymentID), function(ItemInterface $item) use ($paymentID, $em){
            // echo 'Miss :( <br>';

            // $item->expiresAfter(3600); //1 h
            $paymentInstance = [
                'id' => $paymentID,
                'datas' => [
                    'amount' =>141, 
                    'methods' =>'card', 
                    'response' => 'success',
                ],
                'userFrom' => 'Moufid',
                'userTo' => 'Mouhite',
            ];
            $item->set($paymentInstance);
            $item->expiresAt(date_create('tomorrow')); // midnight
            
            return $paymentInstance;
            // return $em->getRepository(Payment::class)->findOneBy(['id' => $paymentID]);
        });

        // dump($item);
        return $this->render('payment/index.html.twig', [
            'data' => $payment,
        ]);
    }
}
