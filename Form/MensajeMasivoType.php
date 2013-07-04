<?php

namespace CULabs\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MensajeMasivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto')
            ->add('body')
            ->add('grupo')
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CULabs\ContactBundle\Entity\MensajeMasivo'
        ));
    }

    public function getName()
    {
        return 'culabs_contactbundle_mensajemasivotype';
    }
}
