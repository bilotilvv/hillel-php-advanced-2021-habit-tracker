<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HabitController extends AbstractController
{
    /**
     * @Route("/habits", name="app_habit")
     */
    public function index(): Response
    {
        return $this->render('habit/list.html.twig');
    }
}
