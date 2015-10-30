<?php

namespace Perso\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireDuelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('texte',      'textarea', array('label' => 'Un commentaire ?'))
            //->add('createdAt',  'date')
        ;

        //$builder->remove('user') ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Perso\GalerieBundle\Entity\CommentaireDuel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'perso_galeriebundle_commentaireduel';
    }
}
