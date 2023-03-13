<?php

namespace App\Controller;

use App\Entity\Measure;
use App\Form\MeasureType;
use App\Repository\MeasureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/measure')]
class MeasureController extends AbstractController
{
    
    use BaseControllerTrait;
    #[Route('/', name: 'app_measure_index', methods: ['GET','POST'])]
    public function index(MeasureRepository $measureRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $measure=$measureRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(MeasureType::class, $measure);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_measure_index');
                }
                $queryBuilder=$measureRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('measure/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'measure'
                ]);
    
            }
            $measure = new Measure();
            $form = $this->createForm(MeasureType::class, $measure);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($measure);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_measure_index');
            }
            $queryBuilder=$measureRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('measure/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'measure'
            ]);
        
       
    }
   
   
    #[Route('/{id}', name: 'app_measure_delete', methods: ['POST'])]
    public function delete(Request $request, Measure $measure, MeasureRepository $measureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measure->getId(), $request->request->get('_token'))) {
            $measureRepository->remove($measure, true);
        }

        return $this->redirectToRoute('app_measure_index', [], Response::HTTP_SEE_OTHER);
    }
}
