<?php

namespace CULabs\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'required' => true,
            ))
            ->add('email', null, array(
                'required' => true,
            ))
            ->add('telefono', null, array(
                'required' => false,
            ))
            ->add('celular', null, array(
                'required' => false,
            ))
            ->add('ciudad', null, array(
                'required' => false,
            ))
            ->add('grupo', null, array(
                'required' => true,
                'expanded' => true,
            ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CULabs\ContactBundle\Entity\Contact'
        ));
    }

    public function getName()
    {
        return 'culabs_contactbundle_contacttype';
    }
}
