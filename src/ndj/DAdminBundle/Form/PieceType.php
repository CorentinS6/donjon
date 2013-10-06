<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('iddonjon','entity',array(
            		'class'=>'ndjDGameBundle:donjon',
					'property' => 'nom',))
            ->add('idetage','entity',array(
            		'class'=>'ndjDGameBundle:Etage',
					'property' => 'nom',))
            ->add('nom')
            ->add('posx')
            ->add('posy')
            ->add('taillex')
            ->add('tailley')
            ->add('coucheSol')
            ->add('coucheSol2')
            ->add('coucheMobilier')
            ->add('actions')
            ->add('etat')
            ->add('lumiere')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Piece'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_piecetype';
    }
}
