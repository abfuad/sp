<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\UserInfoType;
use App\Helper\PrintHelper;
use App\Helper\UserHelper;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-info')]
class UserInfoController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_user_info_index', methods: ['GET','POST'])]
    public function index(PrintHelper $printHelper, UserHelper $userHelper, Request $request, UserInfoRepository $userInfoRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('vw_usr_lst');



        if ($request->request->get('cred')) {
            $this->denyAccessUnlessGranted('prnt_usr_pswrd');
            $user = $userInfoRepository->find($request->request->get('cred'));
            $password = UserHelper::getPassword();
            $user->getUser()->setPassword($userHelper->getHashedPassWord($user->getUser(), $password));
            $this->em->flush();
            $printHelper->print('user_info/print.html.twig', ["user" => $user, 'password' => $password], 'user credential', 'portrait', 'A5');
        }
        $form = $this->createFormBuilder()
            ->setMethod("GET")

            ->add("gender", ChoiceType::class, ["choices" => ["All" => null, "Male" => "M", "Female" => "F"]])
            ->add("status", ChoiceType::class, ["choices" => ["All" => null, "Active" => "1", "Deactive" => "0"]]);
        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $queryBuilder = $userInfoRepository->findUser($form->getData(), $this->getUser());
        } else
            $queryBuilder = $userInfoRepository->findUser(['name' => $request->request->get('name')]);


        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('user_info/index.html.twig', [
            'users' => $data,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_user_info_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository,UserHelper $userHelper,Request $request, UserInfoRepository $userInfoRepository): Response
    {
        $this->denyAccessUnlessGranted('ad_usr');
  
        $userInfo = new UserInfo();
        $form = $this->createForm(UserInfoType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=new User();
            $username=$userHelper->getUsername($userInfo->getFirstName(), $userInfo->getMiddleName());
            $password = UserHelper::getPassword();

            $user->setRoles($form->get('roles')->getData());
            // $user->setEmail($request->request->get('user_info')['email']);
             $user->setUsername($username);
             $user->setPassword($userHelper->getHashedPassWord($user, $password));

            $user->setIsActive(true);

           $this->em->persist($user);
           $this->em->flush();
            $userInfo->setUser($user);
           $this->em->persist($userInfo);
           $this->em->flush();
            $this->addFlash('success', "register Successfuly");
           
            

            return $this->redirectToRoute('app_user_info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_info/new.html.twig', [
            'user_info' => $userInfo,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_user_info_show', methods: ['GET'])]
    public function show(UserInfo $userInfo): Response
    {
        return $this->render('user_info/show.html.twig', [
            'user_info' => $userInfo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_info_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserInfo $userInfo, UserInfoRepository $userInfoRepository): Response
    {
        $form = $this->createForm(UserInfoType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfoRepository->save($userInfo, true);

            return $this->redirectToRoute('app_user_info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_info/edit.html.twig', [
            'user_info' => $userInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_info_delete', methods: ['POST'])]
    public function delete(Request $request, UserInfo $userInfo, UserInfoRepository $userInfoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userInfo->getId(), $request->request->get('_token'))) {
            $userInfoRepository->remove($userInfo, true);
        }

        return $this->redirectToRoute('app_user_info_index', [], Response::HTTP_SEE_OTHER);
    }
}
