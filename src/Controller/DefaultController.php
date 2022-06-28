<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DefaultService;
use App\Form\StartNewType;
use App\Form\VoteType;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    private DefaultService $defaultService;
    public function __construct(DefaultService $defaultService,
                                Security $security)
    {
        $this->defaultService = $defaultService;
        $this->security = $security;
    }
    /**
     * @Route("/", name="app_default")
     */
    public function index(): Response
    {
        $page = $this->defaultService->getPage($this->isGranted('ROLE_USER'));
        if (array_key_exists('formClass',$page['data'])){
            if ($page['data']['formClass']=='StartNewType'){
                $page['data']['form'] = $this->createForm(StartNewType::class);
            }
            else{
                $page['data']['form'] = $this->createForm(VoteType::class);
            }
        }

        return $this->renderForm($page['url'],$page['data']);
    }

    /**
     * @Route("/wte/new/", name="new_wte")
     */
    public function new_wte(Request $request): Response
    {
        $form = $this->createForm(StartNewType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->security->getUser();
            $this->defaultService->newWte($user);
            return $this->redirectToRoute('app_default');
            
        }
        return $this->redirectToRoute('account');
        
    }

    /**
     * @Route("/wte/vote/", name="vote_wte")
     */
    public function vote_wte(Request $request): Response
    {
        $form = $this->createForm(VoteType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->security->getUser();
            $this->defaultService->vote($user,$form->getData()['restaurant_id']);
            return $this->redirectToRoute('app_default');
            
        }
        return $this->redirectToRoute('account');
        
    }
}
