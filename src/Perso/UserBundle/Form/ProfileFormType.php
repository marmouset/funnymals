<?php

namespace Perso\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;


class ProfileFormType extends BaseType
{

    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        $builder
            ->add('first_name',null, array('label' => 'firstName', 'translation_domain' => 'FOSUserBundle'))
            ->add('last_name',null, array('label' => 'lastName', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getName()
    {
        return 'my_user_profile';
    }


}
