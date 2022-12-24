<?php

namespace App\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/nuestra-carta', name: 'app_website_menu')]
    public function index(): Response
    {
        return $this->render('website/menu/index.html.twig');
    }
}
