<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddRestaurantType;
use App\Entity\Restaurant;
use App\Service\RestaurantService;
use App\Entity\Group;
use App\Service\GroupService;
use App\Form\AddGroupType;

class AdminController extends AbstractController
{
    private RestaurantService $restaurantService;
    private GroupService $groupService;
    public function __construct(RestaurantService $restaurantService, GroupService $groupService) {
        $this->restaurantService = $restaurantService;
        $this->groupService = $groupService;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/restaurant", name="add_restaurant")
     */
    public function add_restaurant(Request $request): Response
    {
        $new_restaurant = new Restaurant();
        $form = $this->createForm(AddRestaurantType::class, $new_restaurant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $new_restaurant->setName($form->getData()->getName());
            $new_restaurant->setOpens($form->getData()->getOpens());
            $new_restaurant->setUrl($form->getData()->getUrl());
            $this->restaurantService->add($new_restaurant);
            return $this->redirectToRoute('add_restaurant');
        }
        $restaurants = $this->restaurantService->findAll();

        return $this->renderForm('admin/restaurant.html.twig', [
            'form' => $form,
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @Route("/admin/restaurant/del/{id}", name="del_restaurant")
     */
    public function del_resturant(int $id): Response
    {
        $this->restaurantService->del(intval($id));
        return $this->redirectToRoute('add_restaurant');
    }

    /**
     * @Route("/admin/group", name="add_group")
     */
    public function add_group(Request $request): Response
    {
        $new_group = new Group();
        $form = $this->createForm(AddGroupType::class, $new_group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $new_group->setName($form->getData()->getName());
            $this->groupService->add($new_group);
            return $this->redirectToRoute('add_group');
        }
        $groups = $this->groupService->findAll();

        return $this->renderForm('admin/group.html.twig', [
            'form' => $form,
            'groups' => $groups
        ]);
    }
    
    /**
     * @Route("/admin/group/del/{id}", name="del_group")
     */
    public function del_gropu(int $id): Response
    {
        $this->groupService->del(intval($id));
        return $this->redirectToRoute('add_group');
    }

}
