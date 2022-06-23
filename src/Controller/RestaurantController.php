<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RestaurantService;

class RestaurantController extends AbstractController
{
    private RestaurantService $restaurantService;
    public function __construct(RestaurantService $restaurantService) {
        $this->restaurantService = $restaurantService;
    }

    
    
    


    /**
     * @Route("/restaurant/", name="resturants")
     */
    public function restaurants(): Response
    {
        return $this->redirectToRoute('app_restaurant');
    }
}
