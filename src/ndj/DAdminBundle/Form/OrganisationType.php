<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cat')
            ->add('public')
            ->add('nom')
            ->add('description')
            ->add('charte')
            ->add('blason')
            ->add('gestion')
            ->add('nombreMbMax')
            ->add('prixIn')
            ->add('prixCot')
            ->add('actionsDiff')
            ->add('dateCrea')
            ->add('idaventurier','entity',array(
            		'class'=>'ndjDGameBundle:Aventurier',
					'property' => 'nom',))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Organisation'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_organisationtype';
    }
}
