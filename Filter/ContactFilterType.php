<?php

namespace CULabs\ContactBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType;

class ContactFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('email', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('telefono', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('celular', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('ciudad', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
        ;
    }

    public function getName()
    {
        return 'culabs_contactbundle_contactfiltertype_filter';
    }
}
