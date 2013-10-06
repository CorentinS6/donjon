<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AventurierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idmembre','entity',array(
            		'class'=>'ndjUserBundle:User',
					'property' => 'username',))
            ->add('idcreature','entity',array(
            		'class'=>'ndjDGameBundle:Creature',
					'property' => 'nom',))
            ->add('nom')
            ->add('acrobatie')
            ->add('bagarre')
            ->add('charme')
            ->add('acuite')
            ->add('age')
            ->add('experience')
            ->add('renommee')
            ->add('renommeeMax')
            ->add('pvie')
            ->add('pvieMax')
            ->add('argent')
            ->add('mana')
            ->add('manaMax')
            ->add('pact')
            ->add('pdep')
            ->add('position')
            ->add('pouvoirs')
            ->add('talents')
            ->add('envoutement')
            ->add('points')
            ->add('etat')
            ->add('idorganisation')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Aventurier'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_aventuriertype';
    }
}
