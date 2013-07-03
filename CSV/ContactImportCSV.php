<?php

namespace CULabs\ContactBundle\CSV;

use Symfony\Component\Form\FormFactoryInterface;
use CULabs\ContactBundle\Form\ImportCSVType;
use CULabs\ContactBundle\Import\ContactImportInterface;
use Doctrine\ORM\EntityManager;
use CULabs\ContactBundle\Entity\Contact;

class ContactImportCSV implements ContactImportInterface
{
    protected $form_factory;
    protected $em;

    public function __construct(FormFactoryInterface $form_factory, EntityManager $em) 
    {
        $this->form_factory = $form_factory;
        $this->em           = $em;
    }
    public function getForm($type = null, $data = null, $options = array())
    {
        if (!$type) {
            $type = new ImportCSVType();
        }
        return $this->form_factory->create($type, $data, $options);
    }
    public function processFrom($form)
    {
        $mensajes = array();
        $data = $form->getData();        
        $csv = new CSVBY($data['file']->getRealPath(), $data['separador'], $data['delimitador'], '\\');
        $cant = 0;
        while ($arr_data = $csv->NextLine()) { 
            if (count($arr_data) >= 4){
                
                $contact = $this->em->getRepository('CULabsContactBundle:Contact')->findOneByEmail($arr_data[1]);
                if ($contact) {
                    $mensajes[] = sprintf('Ya existe un contacto con el correo "%s"', $arr_data[1]);
                    continue;
                }
                $contact = new Contact();
                $contact->setName($arr_data[0]);
                $contact->setEmail($arr_data[1]);
                $contact->setTelefono($arr_data[2]);
                $contact->setCelular($arr_data[3]);
                $contact->setCiudad($arr_data[4]);
                
                $this->em->persist($contact);
                $cant++;
            }
        }
        $this->em->flush();
        return serialize(array_merge(array(sprintf('Se registraron %s contactos', $cant)), $mensajes));
    }
    public function getHelp() 
    {
        return "Para importar contactos debe usar un formato csv exportado desde una hoja de cálculo, los campos deben de estar separados por el caracter ',' o ';' y el orden debe ser: \"nombre, email, teléfono, celular, ciudad\".";
    }
}