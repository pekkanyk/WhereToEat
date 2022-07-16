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
use App\Entity\User;
use App\Service\GroupService;
use App\Service\UserService;
use App\Form\AddGroupType;

class AdminController extends AbstractController
{
    private RestaurantService $restaurantService;
    private GroupService $groupService;
    private UserService $userService;
    public function __construct(RestaurantService $restaurantService, GroupService $groupService, UserService $userService) {
        $this->restaurantService = $restaurantService;
        $this->groupService = $groupService;
        $this->userService = $userService;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            
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
     * @Route("/admin/restaurant/{id}/del/", name="del_restaurant")
     */
    public function del_resturant(int $id): Response
    {
        $this->restaurantService->del(intval($id));
        return $this->redirectToRoute('add_restaurant');
    }

    /**
     * @Route("/admin/restaurant/{id}/edit/", name="edit_restaurant")
     */
    public function edit_restaurant(int $id, Request $request): Response
    {

        $restaurant = $this->restaurantService->get(intval($id));
        $form = $this->createForm(AddRestaurantType::class, $restaurant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $restaurant->setName($form->getData()->getName());
            $restaurant->setOpens($form->getData()->getOpens());
            $restaurant->setUrl($form->getData()->getUrl());
            $this->restaurantService->add($restaurant);
            return $this->redirectToRoute('add_restaurant');
        }

        return $this->renderForm('admin/restaurantEdit.html.twig', [
            'form' => $form
        ]);
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
     * @Route("/admin/group/{id}/restaurants/", name="edit_group")
     */
    public function edit_group(int $id): Response
    {
        $group = $this->groupService->get(intval($id));
        $available_restaurants = $this->restaurantService->findNotIn($group);
        
        return $this->renderForm('admin/groupEdit.html.twig', [
            'available' => $available_restaurants,
            'group' => $group
        ]);
    }

    /**
     * @Route("/admin/group/{id}/restaurants/{r_id}/add/", name="add_r_to_group")
     */
    public function add_r_to_group(int $id,int $r_id): Response
    {
        $group = $this->groupService->get(intval($id));
        $restaurant = $this->restaurantService->get(intval($r_id));
        $group->addRestaurant($restaurant);
        $restaurant->addGroup($group);
        $this->groupService->save($group);
        $this->restaurantService->save($restaurant);

        
        return $this->redirect("/admin/group/".intval($id)."/restaurants/");
    }

    /**
     * @Route("/admin/group/{id}/restaurants/{r_id}/remove/", name="remove_r_from_group")
     */
    public function remove_r_from_group(int $id,int $r_id): Response
    {
        $group = $this->groupService->get(intval($id));
        $restaurant = $this->restaurantService->get(intval($r_id));
        $group->removeRestaurant($restaurant);
        $restaurant->removeGroup($group);
        $this->groupService->save($group);
        $this->restaurantService->save($restaurant);

        
        return $this->redirect("/admin/group/".intval($id)."/restaurants/");
    }
    
    /**
     * @Route("/admin/group/{id}/del/", name="del_group")
     */
    public function del_group(int $id): Response
    {
        $this->groupService->del(intval($id));
        return $this->redirectToRoute('add_group');
    }

    /**
     * @Route("/admin/user", name="add_user")
     */
    public function add_user(): Response
    {
        $users = $this->userService->findAll();

        return $this->renderForm('admin/user.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * @Route("/admin/user/{id}/grant", name="grant_user")
     */
    public function grant_user(int $id): Response
    {
        $user = $this->userService->get(intval($id));
        $roles = $user->getRoles();
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);
        $this->userService->save($user);

        return $this->redirectToRoute('add_user');
    }

}
