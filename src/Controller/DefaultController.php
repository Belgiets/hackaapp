<?php

namespace App\Controller;


use App\Repository\PersonRepository;
use App\Service\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new Response('<html><body>Default page!</body></html>');
    }

    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    /**
     * @Route("/admin/test")
     */
    public function testAction(PersonRepository $repository, Notification $notification)
    {
        $person = $repository->findOneBy(['id' => 1]);

        $test = $notification->notify('Hey-hey!', $person);

        return new Response("<html><body>Result {$test['code']}<br>{$test['message']}</body></html>");
    }
}