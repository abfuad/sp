<?php

namespace App\Controller;

use App\Entity\AssetCategory;
use App\Entity\UserCard;
use App\Form\UserCardType;
use App\Helper\PrintHelper;
use App\Repository\AssetRepository;
use App\Repository\UserCardRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-card')]
class UserCardController extends AbstractController
{
   
    use BaseControllerTrait;
    #[Route('/', name: 'app_user_card_index', methods: ['GET', "POST"])]
    public function index(PrintHelper $printHelper,UserCardRepository $userCardRepository,AssetRepository $assetRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if($request->request->get('return')){
            $userCard=$userCardRepository->find($request->request->get('return'));
            $userCard->setIsReturned(true);
            $userCardRepository->save($userCard,true);
            $this->addFlash('success','successfuly returned');
        }
        $form = $this->createFormBuilder()
        ->setMethod("GET")
        ->add('category', EntityType::class, [
            'class' => AssetCategory::class,
            'placeholder' => 'Select category',
            'required' => false
        ])

        
        ->add("type", ChoiceType::class, ["choices" => ["All" => null, "Returned" => 1, "Not Returned" => 0]]);


    $form = $form->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $queryBuilder = $userCardRepository->filter($form->getData());
        $reportQuery = $userCardRepository->filter($form->getData());
    } else {
        $queryBuilder = $userCardRepository->filter(['name' => $request->request->get('name')]);
        $reportQuery = $userCardRepository->filter(['name' => $request->request->get('name')]);
    }
    $data = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        18
    );
   
        return $this->render('user_card/index.html.twig', [
          
            'datas' => $data,
            'form' => $form
        ]);
    }
    #[Route('/new', name: 'app_user_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserCardRepository $userCardRepository): Response
    {
        $userCard = new UserCard();
        $form = $this->createForm(UserCardType::class, $userCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCardRepository->save($userCard, true);

            return $this->redirectToRoute('app_user_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_card/new.html.twig', [
            'user_card' => $userCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_card_show', methods: ['GET'])]
    public function show(UserCard $userCard): Response
    {
        return $this->render('user_card/show.html.twig', [
            'user_card' => $userCard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserCard $userCard, UserCardRepository $userCardRepository): Response
    {
        $form = $this->createForm(UserCardType::class, $userCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCardRepository->save($userCard, true);

            return $this->redirectToRoute('app_user_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_card/edit.html.twig', [
            'user_card' => $userCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_card_delete', methods: ['POST'])]
    public function delete(Request $request, UserCard $userCard, UserCardRepository $userCardRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userCard->getId(), $request->request->get('_token'))) {
            $userCardRepository->remove($userCard, true);
        }

        return $this->redirectToRoute('app_user_card_index', [], Response::HTTP_SEE_OTHER);
    }
}
