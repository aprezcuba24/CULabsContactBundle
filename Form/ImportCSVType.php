<?php

namespace CULabs\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\File;

class ImportCSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'required' => true,
            ))
            ->add('separador', 'choice', array(
                'choices' => array(';' => ';', ',' => ','),
            ))
            ->add('delimitador', 'choice', array(
                'choices' => array('\'\'' => '\'\'', '\'' => '\''),
            ))
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'file' => new File(array(
                'mimeTypes' => array('text/plain', 'text/csv'),
            )),
            'separador' => new Choice(array(
                'choices' => array(';', ',')
            )),
            'delimitador' => new Choice(array(
                'choices' => array('\'\'', '\'')
            )),
        ));
        $resolver->setDefaults(array(
            'validation_constraint' => $collectionConstraint
        ));
    }
    public function getName() 
    {
        return 'import_contact_csv';
    }
}