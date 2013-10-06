<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DonjonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idmembre','entity',array(
            		'class'=>'ndjUserBundle:User',
					'property' => 'username',))
            ->add('dateCreation')
            ->add('dateOuverture')
            ->add('nom')
            ->add('description')
            ->add('argent')
            ->add('etat')
            ->add('pact')
            ->add('renommee')
            ->add('renommeeMax')
            ->add('experience')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Donjon'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_donjontype';
    }
}
