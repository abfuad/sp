<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\AssetCategory;
use App\Entity\Measure;
use App\Entity\UserCard;
use App\Form\AssetType;
use App\Form\UserCardType;
use App\Helper\PrintHelper;
use App\Repository\AssetCategoryRepository;
use App\Repository\AssetRepository;
use App\Repository\UserCardRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/asset')]
class AssetController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_asset_index', methods: ['GET', "POST"])]
    public function index(PrintHelper $printHelper, AssetCategoryRepository $assetCategoryRepository,AssetRepository $assetRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $form = $this->createFormBuilder()
        ->setMethod("GET")
        ->add('measure', EntityType::class, [
            'class' => Measure::class,
            'placeholder' => 'Select Measurement',
            'required' => false
        ])

        
        ->add("type", ChoiceType::class, ["choices" => ["All" => null, "Fixed" => 1, "Non Fixed" => 0]]);


    $form = $form->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $queryBuilder = $assetCategoryRepository->filter($form->getData());
        $reportQuery = $assetCategoryRepository->filter($form->getData());
    } else {
        $queryBuilder = $assetCategoryRepository->filter(['name' => $request->request->get('name')]);
        $reportQuery = $assetCategoryRepository->filter(['name' => $request->request->get('name')]);
    }
    $data = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        18
    );
   
        return $this->render('asset/index.html.twig', [
          
            'datas' => $data,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_asset_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssetRepository $assetRepository): Response
    {
        $asset = new Asset();
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assetRepository->save($asset, true);

            return $this->redirectToRoute('app_asset_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset/new.html.twig', [
            'asset' => $asset,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asset_show', methods: ['GET'])]
    public function show(Asset $asset): Response
    {
        return $this->render('asset/show.html.twig', [
            'asset' => $asset,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asset_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Asset $asset, AssetRepository $assetRepository): Response
    {
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assetRepository->save($asset, true);

            return $this->redirectToRoute('app_asset_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('asset/edit.html.twig', [
            'asset' => $asset,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/transaction', name: 'app_asset_transaction', methods: ['GET', 'POST'])]
    public function transaction(Request $request, AssetCategory $category, AssetRepository $assetRepository,UserCardRepository $userCardRepository): Response
    {
        if($request->request->get('card_remove')){
            $id=$request->request->get('card_remove');
            $userCard=$userCardRepository->findOneBy(['id'=>$id]);
            $userCardRepository->remove($userCard,true);
            $this->addFlash('success', "Deleted Successfuly");
    
    
            return $this->redirectToRoute('app_asset_transaction',['id'=>$category->getId()]);
        }
        $userCard = new UserCard();
        $userCardForm = $this->createForm(UserCardType::class, $userCard);
        $userCardForm->handleRequest($request);

        if ($userCardForm->isSubmitted() && $userCardForm->isValid()) {
           $userCard->setAsset($category);
           $userCard->setIsReturned(0);
           $this->em->persist($userCard);
           $this->em->flush();
           $this->addFlash('success', "Registered Successfuly");
           return $this->redirectToRoute('app_asset_transaction',['id'=>$category->getId()]);
        }
        
        if($request->request->get('remove')){
        $id=$request->request->get('remove');
        $asset=$assetRepository->findOneBy(['id'=>$id]);
        $assetRepository->remove($asset,true);
        $this->addFlash('success', "Deleted Successfuly");


        return $this->redirectToRoute('app_asset_transaction',['id'=>$category->getId()]);
    }
    if($request->request->get('edit')){
              

        $id=$request->request->get('edit');
        $asset=$assetRepository->findOneBy(['id'=>$id]);
       

        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->em->flush();
            $this->addFlash('success', "Updated Successfuly");


            return $this->redirectToRoute('app_asset_transaction',['id'=>$category->getId()]);
        }
       
       
        return $this->render('asset/show.html.twig', [
            'category' => $category,
            'form' => $form,
            'edit'=>$id,
            
            'card_edit'=>false,


        ]);

    }
        $asset = new Asset();
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $asset->setCategory($category);
           
           $this->em->persist($asset);
           $this->em->flush();
           $this->addFlash('success', "Registered Successfuly");
            return $this->redirectToRoute('app_asset_transaction',['id'=>$category->getId()]);
        }

        return $this->render('asset/show.html.twig', [
            'category' => $category,
            'form'=>$form,
            'edit'=>false,
            'card_edit'=>false,
            'card_form'=>$userCardForm

          
        ]);
    }

    #[Route('/{id}', name: 'app_asset_delete', methods: ['POST'])]
    public function delete(Request $request, Asset $asset, AssetRepository $assetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$asset->getId(), $request->request->get('_token'))) {
            $assetRepository->remove($asset, true);
        }

        return $this->redirectToRoute('app_asset_index', [], Response::HTTP_SEE_OTHER);
    }
}
