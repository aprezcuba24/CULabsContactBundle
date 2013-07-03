<?php

namespace CULabs\ContactBundle\Import;

interface ContactImportInterface
{
    public function getForm($type = null, $data = null, $options = array());
    public function processFrom($form);
    public function getHelp();
}