<?php

namespace App\Service;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;


class GroupService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function findAll(){
        $db = $this->entityManager->getRepository(Group::class);
        $groups = $db->findall();
        
        return $groups;
    }

    public function get(int $id){
        $db = $this->entityManager->getRepository(Group::class);
        return $db->find($id);
    }

    public function add(Group $group){
        $db = $this->entityManager->getRepository(Group::class);
        $this->entityManager->persist($group);
        $this->entityManager->flush();

    }
    
    public function del(int $id){
        $db = $this->entityManager->getRepository(Group::class);
        $tobeDeleted = $db->find($id);
        if ($tobeDeleted != null){
            //sql poistaa myös kaikki WTE:t
            //muuta kaikkien käyttäjien ryhmä null:ksi
            $this->entityManager->remove($tobeDeleted);
            $this->entityManager->flush();
        }
    }

    public function save(Group $group){
        $db = $this->entityManager->getRepository(Group::class);
        $this->entityManager->persist($group);
        $this->entityManager->flush();

    }

    
}