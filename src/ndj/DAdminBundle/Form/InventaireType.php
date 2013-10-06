<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InventaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idobjets','entity',array(
            		'class'=>'ndjDGameBundle:Objets',
					'property' => 'nom',))
            ->add('iddonjon','entity',array(
            		'class'=>'ndjDGameBundle:Donjon',
					'property' => 'nom',))
            ->add('idaventurier','entity',array(
            		'class'=>'ndjDGameBundle:Aventurier',
					'property' => 'nom',))
            ->add('idbestiaire','entity',array(
            		'class'=>'ndjDGameBundle:Bestiaire',
					'property' => 'prenom',))
            ->add('idcompte','entity',array(
            		'class'=>'ndjDGameBundle:Banque',
					'property' => 'id',))
            ->add('nom')
            ->add('age')
            ->add('bonus')
            ->add('usure')
            ->add('qualite')
            ->add('position')
            ->add('envoutement')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Inventaire'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_inventairetype';
    }
}
