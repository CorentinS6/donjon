<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idparent','entity',array(
            		'class'=>'ndjDGameBundle:Creature',
					'property' => 'nom',))
            ->add('nom')
            ->add('description')
            ->add('intelligente')
            ->add('acrobatie')
            ->add('bagarre')
            ->add('charme')
            ->add('acuite')
            ->add('vie')
            ->add('degat')
            ->add('unique')
            ->add('pouvoirs')
            ->add('prixAchat')
            ->add('prixEntretien')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Creature'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_creaturetype';
    }
}
