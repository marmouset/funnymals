<?php

namespace Perso\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireDuelType extends AbstractType
{
    protected $name;

    public function __construct($name) // = 'testtype'
    {
        $this->name = 'perso_galeriebundle_' . $name;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('texte',      'textarea', array('label' => 'Un commentaire ?'))
            ->add('name', 'hidden', array(
            'mapped' => false,
            ))
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
            'data_class' => 'Perso\GalerieBundle\Entity\CommentaireDuel',
            'name' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        //return 'perso_galeriebundle_commentaireduel';
        return $this->name;
    }
}
