<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
     /**
     * @Route("/changeLocale", name="change_locale")
     */
    public function changeLocale(Request $request,RequestStack $requestStack)
    {

        $session = $requestStack->getSession();
        $language = ['en', 'am', 'or'];
        $lang = $request->request->get('lang');
      
        $response['success'] = false;
        if (in_array($lang, $language)) {
            $response['success'] = true;
            $session->set('_locale', $lang);
        }

        return new JsonResponse(['success' => true]);
    }
    #[Route('/home', name: 'app_home_index')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
