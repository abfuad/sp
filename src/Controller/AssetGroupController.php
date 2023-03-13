<?php

namespace App\Controller;

use App\Entity\AssetGroup;
use App\Form\AssetGroupType;
use App\Repository\AssetGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/asset/group')]
class AssetGroupController extends AbstractController
{
    #[Route('/', name: 'app_asset_group_index', methods: ['GET'])]
    public function index(AssetGroupRepository $assetGroupRepository): Response
    {
        return $this->render('asset_group/index.html.twig', [
            'asset_groups' => $assetGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_asset_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssetGroupRepository $assetGroupRepository): Response
    {
        $assetGroup = new AssetGroup();
        $form = $this->createForm(AssetGroupType::class, $assetGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assetGroupRepository->save($assetGroup, true);

            return $this->redirectToRoute('app_asset_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset_group/new.html.twig', [
            'asset_group' => $assetGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asset_group_show', methods: ['GET'])]
    public function show(AssetGroup $assetGroup): Response
    {
        return $this->render('asset_group/show.html.twig', [
            'asset_group' => $assetGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asset_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AssetGroup $assetGroup, AssetGroupRepository $assetGroupRepository): Response
    {
        $form = $this->createForm(AssetGroupType::class, $assetGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assetGroupRepository->save($assetGroup, true);

            return $this->redirectToRoute('app_asset_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset_group/edit.html.twig', [
            'asset_group' => $assetGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asset_group_delete', methods: ['POST'])]
    public function delete(Request $request, AssetGroup $assetGroup, AssetGroupRepository $assetGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assetGroup->getId(), $request->request->get('_token'))) {
            $assetGroupRepository->remove($assetGroup, true);
        }

        return $this->redirectToRoute('app_asset_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
