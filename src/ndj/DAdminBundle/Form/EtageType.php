<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('iddonjon','entity',array(
            		'class'=>'ndjDGameBundle:Donjon',
					'property' => 'nom',))
            ->add('niveau')
            ->add('nom')
            ->add('taille')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Etage'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_etagetype';
    }
}
