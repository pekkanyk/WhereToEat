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
        $today->setTime(12,0);
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
                    $wte = $wteDb->findForGrpDate($user->getGrp(),$today);
                    $page['url'] = 'default/index_voted.html.twig';
                    $page['data']['voteCount'] = $this->voteCount($wte);
                    $page['data']['restaurants'] = $this->votes($wte);
                    $page['data']['wte'] = $wte;
                    $page['data']['members'] = count($user->getGrp()->getUsers());
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
        $today->setTime(12,0);

        if ($wteDb->findForGrpDate($user->getGrp(),$today) == null ){
            $wte = new WhereToEat();
            $wte->setDate($today);
            $wte->setDraw(false);
            $wte->setGrp($user->getGrp());
            $this->entityManager->persist($wte);
            $this->entityManager->flush();
        }

    }
    
    public function vote(User $user, $restaurant){
        $wteDb = $this->entityManager->getRepository(WhereToEat::class);
        $voteDb = $this->entityManager->getRepository(Vote::class);
        $restDb = $this->entityManager->getRepository(Restaurant::class);
        $today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $today->setTime(12,0);
        $wte = $wteDb->findForGrpDate($user->getGrp(),$today);
        
        if ($wte != null ){
            
            $vote = new Vote;
            $vote->setUserId($user);
            if ($restaurant!='--'){
                $vote->setRestaurantId($restDb->find($restaurant));
            }
            else{
                $vote->setRestaurantId(null);
            }
            $vote->setDate($today);
            $vote->setWhereToEat($wte);
            $this->entityManager->persist($vote);
            $this->entityManager->flush();
            $wte = $this->currentWinner($wte,$restaurant);
            //$vote->setWhereToEat($wte);    
            //$this->entityManager->persist($vote);
            //$this->entityManager->flush();
        }

    }

    private function currentWinner(WhereToEat $wte,$restId){
        $voteDb = $this->entityManager->getRepository(Vote::class);
        $restDb = $this->entityManager->getRepository(Restaurant::class);
        $topRestaurants = $voteDb->topVotes($wte);
        if ($topRestaurants != null){
        $topScore = $topRestaurants[0]['lkm'];
        $winnerId = 0;
        $drawCount = -1;
        $activateDice = false;
        for ($i=0;$i<count($topRestaurants);$i++){
            if ($topRestaurants[$i]['lkm']==$topScore){
                $drawCount++;
                if ($topRestaurants[$i]['rr']==$restId){
                    $activateDice = true;
                }
            }
        }
        
        if ($drawCount>0){
            if ($activateDice){
            $wte->setDraw(true);
            $winnerId = rand(0,$drawCount);
            $restaurant = $restDb->find($topRestaurants[$winnerId]['rr']);
            $wte->setWinner($restaurant);
            $this->entityManager->persist($wte);
            $this->entityManager->flush();
            }
        }
        
        else{
            $restaurant = $restDb->find($topRestaurants[0]['rr']);
            $wte->setWinner($restaurant);
            $wte->setDraw(false);
            $this->entityManager->persist($wte);
            $this->entityManager->flush();
        }
        }
        

        return $wte;
    }

    private function voteCount($wte){
        $voteDb = $this->entityManager->getRepository(Vote::class);
        $voteCount = $voteDb->voteCount($wte);
        

        return $voteCount;
    }
    private function votes($wte){
        $voteDb = $this->entityManager->getRepository(Vote::class);
        $restDb = $this->entityManager->getRepository(Restaurant::class);
        $votelist = $voteDb->topVotes($wte);
        $restaurants = [];
        for ($i=0;$i<count($votelist);$i++){
            $restaurants[] = ['votes'=>$votelist[$i]['lkm'], 'restaurant' => $restDb->find($votelist[$i]['rr'])];
        }
        return $restaurants;
    }

       
}