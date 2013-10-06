<?php

namespace ndj\DAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObjetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('cat')
            ->add('description')
            ->add('bonus')
            ->add('prix')
            ->add('frequence')
            ->add('degat')
            ->add('bouclier')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ndj\DGameBundle\Entity\Objets'
        ));
    }

    public function getName()
    {
        return 'ndj_dgamebundle_objetstype';
    }
}
