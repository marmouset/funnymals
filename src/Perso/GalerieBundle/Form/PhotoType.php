<?php

namespace Perso\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('titre', 'text', array('label_attr' => array('class' => 'control-label')))
            ->add('legende',   'text', array('label_attr' => array('class' => 'myLabel')))
            ->add('descriptif',   'textarea', array('label_attr' => array('class' => 'myLabel')))
            ->add('file',       'file', array('label_attr' => array('class' => 'myLabel')))

            //->add('url',       'file', array('label_attr' => array('class' => 'myLabel'),'attr' => array('class' => 'myInputText')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Perso\GalerieBundle\Entity\Photo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'perso_galeriebundle_photo';
    }
}
