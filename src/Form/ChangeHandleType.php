<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;

class ChangeHandleType extends AbstractType
{
      
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usersHandle = $this->security->getUser()->getHandle();
        $builder
            ->add('handle', TextType::class, array('data'=>$usersHandle, 'required'=>false))
            ->add('submit', SubmitType::class)
        ;
    }

}
