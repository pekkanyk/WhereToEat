<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class UserService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function findAll(){
        $db = $this->entityManager->getRepository(User::class);
        $users = $db->findall();
        
        return $users;
    }

    public function save($user){
        $db = $this->entityManager->getRepository(User::class);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function get(int $id){
        $db = $this->entityManager->getRepository(User::class);
        return $db->find($id);
    }

    
}