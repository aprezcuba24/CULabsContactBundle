<?php

namespace CULabs\ContactBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType;
use CULabs\ContactBundle\Entity\MensajeMasivo;

class MensajeMasivoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('status', 'filter_choice', array(
                'choices' => MensajeMasivo::getStatusType(),
            ))
            ->add('grupo', 'filter_entity', array(
                'class' => '\CULabs\ContactBundle\Entity\Grupo',
                'em'    => $options['em'],
            ))
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'em' => 'default',
        ));
    }
    public function getName()
    {
        return 'culabs_contactbundle_mensajemasivofiltertype_filter';
    }
}
