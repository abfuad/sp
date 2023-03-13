<?php

namespace App\Controller;

use App\Entity\AssetCategory;
use App\Form\AssetCategoryType;
use App\Repository\AssetCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/asset-category')]
class AssetCategoryController extends AbstractController
{
   
    use BaseControllerTrait;
    #[Route('/', name: 'app_asset_category_index', methods: ['GET','POST'])]
    public function index(AssetCategoryRepository $assetCategoryRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $assetCategory=$assetCategoryRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(AssetCategoryType::class, $assetCategory);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_asset_category_index');
                }
                $queryBuilder=$assetCategoryRepository->filter($request->request->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('asset_category/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'asset_category'
                ]);
    
            }
            $assetCategory = new AssetCategory();
            $form = $this->createForm(AssetCategoryType::class, $assetCategory);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($assetCategory);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_asset_category_index');
            }
            $queryBuilder=$assetCategoryRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('asset_category/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'asset_category'
            ]);
        
       
    }

    #[Route('/new', name: 'app_asset_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssetCategoryRepository $assetCategoryRepository): Response
    {
        $assetCategory = new AssetCategory();
        $form = $this->createForm(AssetCategoryType::class, $assetCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assetCategoryRepository->save($assetCategory, true);

            return $this->redirectToRoute('app_asset_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset_category/new.html.twig', [
            'asset_category' => $assetCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asset_category_show', methods: ['GET'])]
    public function show(AssetCategory $assetCategory): Response
    {
        return $this->render('asset_category/show.html.twig', [
            'asset_category' => $assetCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asset_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AssetCategory $assetCategory, AssetCategoryRepository $assetCategoryRepository): Response
    {
        $form = $this->createForm(AssetCategoryType::class, $assetCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assetCategoryRepository->save($assetCategory, true);

            return $this->redirectToRoute('app_asset_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset_category/edit.html.twig', [
            'asset_category' => $assetCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asset_category_delete', methods: ['POST'])]
    public function delete(Request $request, AssetCategory $assetCategory, AssetCategoryRepository $assetCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assetCategory->getId(), $request->request->get('_token'))) {
            $assetCategoryRepository->remove($assetCategory, true);
        }

        return $this->redirectToRoute('app_asset_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
