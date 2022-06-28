<?php

namespace App\Form;

use App\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Group;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VoteType extends AbstractType
{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $db = $this->entityManager->getRepository(Group::class);
        $usersGroup = $this->security->getUser()->getGrp();
        $groupsRestaurants = $db->find($usersGroup->getId())->getRestaurants();
        $grpRestArr=[];
        for ($i=0;$i<count($groupsRestaurants);$i++){
            $grpRestArr[$groupsRestaurants[$i]->getName()] = $groupsRestaurants[$i]->getId();
        }

        $builder
            ->add('restaurant_id',ChoiceType::class,array(
                'expanded' =>false,
                'choices'=>$grpRestArr
                ))
            ->add('submit', SubmitType::class)
            ->setAction('/wte/vote/')
        ;
    }
    /*
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vote::class,
        ]);
    }
    */
}
