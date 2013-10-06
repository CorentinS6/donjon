<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idaventurier1','entity',array(
            		'class'=>'ndjDGameBundle:Aventurier',
					'property' => 'nom',))
            ->add('idaventurier2','entity',array(
            		'class'=>'ndjDGameBundle:Aventurier',
					'property' => 'nom',))
            ->add('cat')
            ->add('cout')
            ->add('dateRelation')
            ->add('fin')
            ->add('etat1')
            ->add('etat2')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Relation'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_relationtype';
    }
}
