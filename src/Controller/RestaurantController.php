<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RestaurantService;
use App\Service\GroupService;
use App\Entity\Group;
use App\Entity\Restaurant;
use App\Form\AddRestaurantType;
use Symfony\Component\Security\Core\Security;

class RestaurantController extends AbstractController
{
    private RestaurantService $restaurantService;
    
    public function __construct(RestaurantService $restaurantService, GroupService $groupService, Security $security) {
        $this->restaurantService = $restaurantService;
        $this->groupService = $groupService;
        
        $this->security = $security;
    }

    
    
    


    /**
     * @Route("/restaurants/", name="restaurants")
     */
    public function restaurants(): Response
    {
        $usersGrp = $this->security->getUser()->getGrp();
        if ($usersGrp != null){
            $available_restaurants = $this->restaurantService->findNotIn($usersGrp);
        }
        else{
            $available_restaurants = $this->restaurantService->findAll();
        }
        
        /*
        if ($usersGrp == null){
            $group_restaurants = [];
        }
        else {
        $group_restaurants = $this->restaurantService->findIn($usersGrp);
        }
        */

        return $this->renderForm('restaurants/restaurant.html.twig', [
            'available' => $available_restaurants,
            'group' => $usersGrp
        ]);    
    }

    /**
     * @Route("/restaurants/add", name="add_restaurant_public")
     */
    public function add_restaurant_public(Request $request): Response
    {
        $new_restaurant = new Restaurant();
        $form = $this->createForm(AddRestaurantType::class, $new_restaurant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $new_restaurant->setName($form->getData()->getName());
            $new_restaurant->setOpens($form->getData()->getOpens());
            $new_restaurant->setUrl($form->getData()->getUrl());
            $this->restaurantService->add($new_restaurant);
            return $this->redirectToRoute('add_restaurant_public');
        }
        $restaurants = $this->restaurantService->findAll();

        return $this->renderForm('restaurants/restaurant_add.html.twig', [
            'form' => $form,
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @Route("/restaurants/group/{id}/restaurants/{r_id}/add/", name="add_r_to_group_public")
     */
    public function add_r_to_group(int $id,int $r_id): Response
    {
        $group = $this->groupService->get(intval($id));
        $restaurant = $this->restaurantService->get(intval($r_id));
        $group->addRestaurant($restaurant);
        $restaurant->addGroup($group);
        $this->groupService->save($group);
        $this->restaurantService->save($restaurant);

        
        return $this->redirectToRoute('restaurants');
    }
}
