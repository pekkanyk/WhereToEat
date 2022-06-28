<?php

namespace App\Service;

use App\Entity\Restaurant;
use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;


class RestaurantService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function findAll(){
        $db = $this->entityManager->getRepository(Restaurant::class);
        $restaurants = $db->findall();
        
        return $restaurants;
    }

    public function add(Restaurant $restaurant){
        $db = $this->entityManager->getRepository(Restaurant::class);
        $this->entityManager->persist($restaurant);
        $this->entityManager->flush();

    }
    
    public function del(int $id){
        $db = $this->entityManager->getRepository(Restaurant::class);
        $tobeDeleted = $db->find($id);
        if ($tobeDeleted != null){
            $this->entityManager->remove($tobeDeleted);
            $this->entityManager->flush();
        }
    }

    public function get(int $id){
        $db = $this->entityManager->getRepository(Restaurant::class);
        return $db->find($id);
    }

    public function findNotIn(Group $group){
        $db = $this->entityManager->getRepository(Restaurant::class);
        $restaurants = $db->findNotIn($group);
        
        return $restaurants;
    }

    public function save(Restaurant $restaurant){
        $db = $this->entityManager->getRepository(Restaurant::class);
        $this->entityManager->persist($restaurant);
        $this->entityManager->flush();

    }
    
    
}