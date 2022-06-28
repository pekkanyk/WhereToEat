<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\WhereToEat;
use App\Entity\Vote;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class DefaultService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getPage(bool $loggedIn){
        $today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $today->setTime(0,0);
        $page['data'] = ['today' => $today];

        if ($loggedIn){
            $user = $this->security->getUser();
            $wteDb = $this->entityManager->getRepository(WhereToEat::class);
            if ($user->getGrp()== null){
                $page['url'] = 'default/index_not_in_group.html.twig';
            }
            elseif ( $wteDb->findForGrpDate($user->getGrp(),$today) == null ){
                $page['url'] = 'default/index_not_started.html.twig';
                $page['data']['formClass'] = 'StartNewType';
            }
            else {
                $voteDb = $this->entityManager->getRepository(Vote::class);
                if ($voteDb->hasUserVotedDate($user,$today) == null ){
                    $page['url'] = 'default/index_not_voted.html.twig';
                    $page['data']['formClass'] = 'VoteType';
                }
                else {
                    $page['url'] = 'default/index_voted.html.twig';
                }
            }
        }
        else {
            $page['url'] = 'default/index.html.twig';
        }

        return $page;
    }

    public function newWte(User $user){
        $wteDb = $this->entityManager->getRepository(WhereToEat::class);
        $today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $today->setTime(0,0);

        if ($wteDb->findForGrpDate($user->getGrp(),$today) == null ){
            $wte = new WhereToEat();
            $wte->setDate($today);
            $wte->setGrp($user->getGrp());
            $this->entityManager->persist($wte);
            $this->entityManager->flush();
        }

    }
    
    public function vote(User $user, int $restaurant){
        $wteDb = $this->entityManager->getRepository(WhereToEat::class);
        $voteDb = $this->entityManager->getRepository(Vote::class);
        $restDb = $this->entityManager->getRepository(Restaurant::class);
        $today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $today->setTime(0,0);
        $wte = $wteDb->findForGrpDate($user->getGrp(),$today);
        
        if ($wte != null ){
            $vote = new Vote;
            $vote->setUserId($user);
            $vote->setRestaurantId($restDb->find($restaurant));
            $vote->setDate($today);
            $vote->setWhereToEat($wte);    
            $this->entityManager->persist($vote);
            $this->entityManager->flush();
        }

    }
       
}