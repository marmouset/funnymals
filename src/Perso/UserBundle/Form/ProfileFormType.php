<?php

namespace Perso\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;


class ProfileFormType extends BaseType
{

    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        $builder
            ->add('first_name',null, array('label' => 'firstName', 'translation_domain' => 'FOSUserBundle'))
            ->add('last_name',null, array('label' => 'lastName', 'translation_domain' => 'FOSUserBundle'))
            ->add('ddn',null, array('label' => 'ddn', 'translation_domain' => 'FOSUserBundle'))
            ->add('current_password',null, array('label' => 'Mot de passe', 'translation_domain' => 'FOSUserBundle'))
        ;
        $builder->remove('username');
    }

    public function getName()
    {
        return 'my_user_profile';
    }


}
