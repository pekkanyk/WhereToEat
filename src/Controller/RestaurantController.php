<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RestaurantService;
use App\Service\GroupService;
use App\Entity\Group;
use Symfony\Component\Security\Core\Security;

class RestaurantController extends AbstractController
{
    private RestaurantService $restaurantService;
    
    public function __construct(RestaurantService $restaurantService, Security $security) {
        $this->restaurantService = $restaurantService;
        
        $this->security = $security;
    }

    
    
    


    /**
     * @Route("/restaurants/", name="resturants")
     */
    public function restaurants(): Response
    {
        $usersGrp = $this->security->getUser()->getGrp();
        if ($usersGrp == null){
            $group_restaurants = [];
        }
        else {
        $group_restaurants = $this->restaurantService->findIn($usersGrp);
        }
        
        return $this->renderForm('restaurants/restaurant.html.twig', [
            'restaurants' => $group_restaurants,
        ]);    }
}
