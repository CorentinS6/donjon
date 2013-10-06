<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BestiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idcreature','entity',array(
            		'class'=>'ndjDGameBundle:Creature',
					'property' => 'nom',))
            ->add('iddonjon','entity',array(
            		'class'=>'ndjDGameBundle:Donjon',
					'property' => 'nom',))
            ->add('prenom')
            ->add('acrobatie')
            ->add('bagarre')
            ->add('charme')
            ->add('acuite')
            ->add('age')
            ->add('experience')
            ->add('renommee')
            ->add('pvie')
            ->add('pvieMax')
            ->add('repos')
            ->add('aVendre')
            ->add('cout')
            ->add('position')
            ->add('pouvoirs')
            ->add('talents')
            ->add('envoutement')
            ->add('ordre')
            ->add('points')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Bestiaire'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_bestiairetype';
    }
}
