<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\UserType;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class UserController extends AbstractController
{
    use BaseControllerTrait;
 

   
    #[Route("/{id}/activate", name:"user_action", methods:["POST"])]
   
    public function action(User $user, Request $request, ManagerRegistry $doctrine)
    {
        

        $user->setIsActive($request->request->get('activateUser'));
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_user_info_index');
    }


    #[Route('/create', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function create(Request $request, UserPasswordHasherInterface $userPasswordEncoderInterface, UserRepository $userRepository, UserInfoRepository $userInfoRepository): Response
    {

        if ($userRepository->countUser()['count']>0)
         $this->denyAccessUnlessGranted('act_nw');
         $userInfo = new UserInfo();


        $form = $this->createForm(UserType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $user = new User();

            $user->setRoles(['rlspad']);
          
            $user->setUserName($form->get('username')->getData());

            $password = $form->get('password')->getData();
            // dd($password);
            $user->setPassword($userPasswordEncoderInterface->hashPassword($user, $password));
       
            $user->setIsActive(true);
            
            $this->em->persist($user);
            $this->em->flush();
            $userInfo->setUser($user);
         
            $this->em->persist($userInfo);
            $this->em->flush();
           
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/new.html.twig', [

            'form' => $form,
        ]);
    }


   
}
