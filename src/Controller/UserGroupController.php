<?php

namespace App\Controller;

use App\Entity\UserGroup;
use App\Form\UserGroupType;
use App\Repository\PermissionRepository;
use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-group')]
class UserGroupController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_user_group_index', methods: ['GET','POST'])]
    public function index(UserGroupRepository $userGroupRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        $this->denyAccessUnlessGranted('vw_usr_grp');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $userGroup=$userGroupRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(UserGroupType::class, $userGroup);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    $this->denyAccessUnlessGranted('edt_usr_grp');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_user_group_index');
                }
                $queryBuilder=$userGroupRepository->findUserGroup($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('user_group/index.html.twig', [
                    'user_groups' => $data,
                    'form' => $form->createView(),
                    'edit'=>$id
                ]);
    
            }
            $userGroup = new UserGroup();
            $form = $this->createForm(UserGroupType::class, $userGroup);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                 $this->denyAccessUnlessGranted('ad_usr_grp');

                
               $this->em->persist($userGroup);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_user_group_index');
            }
            $queryBuilder=$userGroupRepository->findUserGroup($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('user_group/index.html.twig', [
                'user_groups' => $data,
                'form' => $form,
                'edit'=>false
            ]);
        
       
    }

    #[Route('/{id}/users', name: 'user_group_users', methods: ['GET', 'POST'])]

    public function user( UserGroup $userGroup,Request $request,UserRepository $userRepository){
       $this->denyAccessUnlessGranted('ad_usr_t_grp');

      if($request->request->get('usergroupuser')){
          $users=$userRepository->findAll();
             foreach ($users as $user) {
           $userGroup->removeUser($user);
          }
          $users=$userRepository->findBy(['id'=>$request->request->all()['user']]);
          foreach ($users as $user) {
           $userGroup->addUser($user);
          }
       
          $this->em->flush();
      }
       return $this->render('user_group/user.html.twig', [
           'user_group' => $userGroup,
           'users' => $userRepository->findForUserGroup($userGroup->getUsers()),
          
       ]);


}
    
    #[Route('/{id}/permission', name: 'user_group_permission', methods: ['GET', 'POST'])]
   public function permission(  UserGroup $userGroup,Request $request,PermissionRepository $permissionRepository){
        $this->denyAccessUnlessGranted('ad_prmsn_t_grp');

      if($request->request->get('usergrouppermission')){
          $permissions=$permissionRepository->findAll();
             foreach ($permissions as $permission) {
           $userGroup->removePermission($permission);
          }
          $permissions=$permissionRepository->findBy(['id'=>$request->request->all()['permission']]);
          foreach ($permissions as $permission) {
           $userGroup->addPermission($permission);
          }

          $this->em->flush();
      }
       return $this->render('user_group/permission.html.twig', [
           'user_group' => $userGroup,
           'permissions' => $permissionRepository->findForUserGroup($userGroup->getPermission()),
          
       ]);


}


   

    #[Route('/{id}', name: 'app_user_group_delete', methods: ['POST'])]
    public function delete(Request $request, UserGroup $userGroup, UserGroupRepository $userGroupRepository): Response
    {
        $this->denyAccessUnlessGranted('dlt_usr_grp');

        if ($this->isCsrfTokenValid('delete'.$userGroup->getId(), $request->request->get('_token'))) {
            $userGroupRepository->remove($userGroup, true);
        }

        return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
