<?php

namespace Perso\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentaireType extends AbstractType
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Perso\GalerieBundle\Entity\Commentaire'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'perso_galeriebundle_commentaire';
    }
}
