<?php

namespace App\Controller\AdminPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/admin-panel', name: 'app_admin-panel')]
    public function index(): Response
    {
        return $this->render('admin_panel/home/index.html.twig', [
            'controller_name' => 'AdminPanelController',
        ]);
    }
}
