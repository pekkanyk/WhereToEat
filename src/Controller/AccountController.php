<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\GroupSelectType;
use App\Entity\Group;
use Symfony\Component\Security\Core\Security;
use App\Service\UserService;
use App\Service\GroupService;

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
        $form = $this->createForm(GroupSelectType::class, $new_group);
        
        return $this->renderForm('account/index.html.twig', [
            'form'=>$form,
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
}
