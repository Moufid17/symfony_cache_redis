<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    #[Route('/cache', name: 'app_cache', methods:['GET'])]
    public function index(): Response
    {

        $cache = new FilesystemAdapter();

        $cacheItem = $cache->getItem('user.account');
        // dd([$cache, $cacheItem]);
        if(!$cacheItem->isHit()){
            echo 'Miss :( <br>';
            // $apiKey = md5('foo');
            $account = [
                'id' => 17,
                'name' => 'Moufid',
            ];
            $cacheItem->set($account);
            $cache->save($cacheItem);
        } else {
            echo 'Hit! <br>';
            $account = $cacheItem->get();
        }

        return $this->render('cache/index.html.twig', [
            'controller_name' => 'CacheController',
        ]);
    }
}
