<?php

namespace Perso\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('legende',   'text', array('label' => 'form.legende','label_attr' => array('class' => 'myLabel')))
            ->add('descriptif',   'textarea', array('label' => 'form.desc','label_attr' => array('class' => 'myLabel')))
            ->add('file',       'file', array('label_attr' => array('class' => 'myLabel')))
            ->add('myTags', 'hidden', array(
                'mapped' => false,
            ))


            /*
            ->add('tags',   'collection', array('type'         => new TagType(),
                'allow_add'    => true,
                'allow_delete' => true))
            */

            //, array('placeholder' => 'Ajoutez des tags à votre photo, séparés par des virgules',)
            //->add('url',       'file', array('label_attr' => array('class' => 'myLabel'),'attr' => array('class' => 'myInputText')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
