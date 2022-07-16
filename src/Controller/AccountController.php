<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\GroupSelectType;
use App\Form\ChangeHandleType;
use App\Form\ChangePassFormType;
use App\Entity\Group;
use Symfony\Component\Security\Core\Security;
use App\Service\UserService;
use App\Service\GroupService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    public function __construct(Security $security, UserService $userService, GroupService $groupService)
    {
        $this->security = $security;
        $this->userService = $userService;
        $this->groupService = $groupService;
    }
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request): Response
    {
        $new_group = New Group();
        $grp_form = $this->createForm(GroupSelectType::class, $new_group);
        $handle_form = $this->createForm(ChangeHandleType::class, null);
        $change_pass_form = $this->createForm(ChangePassFormType::class,null);
        
        return $this->renderForm('account/index.html.twig', [
            'grp_form'=>$grp_form,
            'handle_form'=>$handle_form,
            'pass_form'=>$change_pass_form,
            'msg'=> ''
        ]);
    }

    /**
     * @Route("/account/save/group", name="save_group")
     */
    public function saveGroup(Request $request): Response
    {
        $user = $this->security->getUser();
        $new_group = New Group();
        $form = $this->createForm(GroupSelectType::class, $new_group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getName()=='--'){
                $group = null;
            }
            else{
                $group = $this->groupService->get($form->getData()->getName());
            }
            
            
            $user->setGrp($group);
            $this->userService->save($user);
            return $this->redirectToRoute('account');
        }

        return $this->redirectToRoute('account');
    }

    /**
     * @Route("/account/save/handle", name="save_handle")
     */
    public function saveHandle(Request $request): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(ChangeHandleType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()['handle']==''){
                $handle = null;
            }
            else{
                $handle = $form->getData()['handle'];             
            }
            
            
            $user->setHandle($handle);
            $this->userService->save($user);
            return $this->redirectToRoute('account');
        }

        return $this->redirectToRoute('account');
    }

    /**
     * @Route("/account/save/pass", name="save_pass")
     */
    public function savePass(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->security->getUser();
        $new_group = New Group();
        $grp_form = $this->createForm(GroupSelectType::class, $new_group);
        $handle_form = $this->createForm(ChangeHandleType::class, null);
        $change_pass_form = $this->createForm(ChangePassFormType::class,null);
        $change_pass_form->handleRequest($request);
        

        if ($change_pass_form->isSubmitted() && $change_pass_form->isValid()){
            $oldPass = $change_pass_form->getData()['oldPlainPass'];
            $msg = "Vanha salasana väärin! Salasanaa ei vaihdettu.";
            if ($userPasswordHasher->isPasswordValid($user,$oldPass)){ //salasana mätsää nykyiseen
                $msg = "Salasana päivitetty!";
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $change_pass_form->get('password')->getData()
                        )
                    );
                $this->userService->save($user);
            }
            
            return $this->renderForm('account/index.html.twig', [
                'grp_form'=>$grp_form,
                'handle_form'=>$handle_form,
                'pass_form'=>$change_pass_form,
                'msg'=> $msg
            ]);
        }

        return $this->renderForm('account/index.html.twig', [
            'grp_form'=>$grp_form,
            'handle_form'=>$handle_form,
            'pass_form'=>$change_pass_form,
            'msg'=> ''
        ]);
    }
}
