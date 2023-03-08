<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(TranslatorInterface $translator): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => $translator->trans('Hello!'),
        ]);
    }
}
