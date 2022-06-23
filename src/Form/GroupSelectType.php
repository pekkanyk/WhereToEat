<?php

namespace App\Form;

use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Service\GroupService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class GroupSelectType extends AbstractType
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
        $groups = $db->findall();
        $usersGroup = $this->security->getUser()->getGrp();
        
        if ($usersGroup==null){
            $usersGroupId = "--";
        }
        else{
            $usersGroupId = $usersGroup->getId();
        }
        
        $grpArr=[];
        $grpArr['--'] = "--";
        for ($i=0;$i<count($groups);$i++){
            $grpArr[$groups[$i]->getName()] = $groups[$i]->getId();
        }
        
        $builder
            ->add('name',ChoiceType::class,array(
                'expanded' =>false,
                'choices'=>$grpArr,
                'data'=>$usersGroupId
                ))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
