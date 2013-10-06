<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BanqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idaventurier','entity',array(
            		'class'=>'ndjDGameBundle:Aventurier',
					'property' => 'nom',))
            ->add('tailleCoffre')
            ->add('argent')
            ->add('argentMax')
            ->add('cout')
            ->add('etat')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Banque'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_banquetype';
    }
}
