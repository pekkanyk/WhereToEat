<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddRestaurantType;
use App\Entity\Restaurant;
use App\Service\RestaurantService;

class RestaurantController extends AbstractController
{
    private RestaurantService $restaurantService;
    public function __construct(RestaurantService $restaurantService) {
        $this->restaurantService = $restaurantService;
    }

    /**
     * @Route("/restaurant", name="app_restaurant")
     */
    public function index(Request $request): Response
    {
        $new_restaurant = new Restaurant();
        $form = $this->createForm(AddRestaurantType::class, $new_restaurant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $new_restaurant->setName($form->getData()->getName());
            $new_restaurant->setOpens($form->getData()->getOpens());
            $new_restaurant->setUrl($form->getData()->getUrl());
            $this->restaurantService->add($new_restaurant);
            return $this->redirectToRoute('app_restaurant');
        }
        $restaurants = $this->restaurantService->findAll();

        return $this->renderForm('restaurant/index.html.twig', [
            'form' => $form,
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @Route("/restaurant/add", name="add_resturant")
     */
    public function add(): Response
    {
        return $this->redirectToRoute('app_restaurant');
    }
}
