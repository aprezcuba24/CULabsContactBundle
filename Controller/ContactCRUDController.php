<?php

namespace CULabs\ContactBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use CULabs\ContactBundle\Entity\Contact;
use CULabs\ContactBundle\Form\ContactType;
use CULabs\ContactBundle\Filter\ContactFilterType;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactCRUDController extends CRUDController
{
    /**
     * Lists all Contact entities.
     *
     * @Route("", name="admin_contact")
     * @Template()
     * @Secure(roles="ROLE_CONTACT_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CULabsContactBundle:ContactCRUD:list.html.twig', array(
                'pager' => $pager,
                'sort'  => $this->getSort(),
            ));
        }
        $filter_form = $this->getFilterForm();
        return array(
            'pager'  => $pager,
            'filter' => $filter_form->createView(),
            'sort'   => $this->getSort(),
        );
    }
    
    /**
     * Filter Contact entities.
     *
     * @Route("/filter", name="admin_contact_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_contact'));
        }        
        $filter_form = $this->get('form.factory')->create(new ContactFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_contact'));
        }        
        return $this->render('CULabsContactBundle:Contact:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
            'sort'   => $this->getSort(),
        ));
    }
    
    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}/show", name="admin_contact_show")
     * @Template()
     * @Secure(roles="ROLE_CONTACT_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CULabsContactBundle:Contact')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="admin_contact_new")
     * @Template()
     * @Secure(roles="ROLE_CONTACT_NEW")
     */
    public function newAction()
    {
        $entity = new Contact();
        $form   = $this->createForm(new ContactType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity is saved.');
                return $this->redirect($this->generateUrl('admin_contact_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="admin_contact_edit")
     * @Template()
     * @Secure(roles="ROLE_CONTACT_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CULabsContactBundle:Contact')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }
        $form = $this->createForm(new ContactType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity is saved.');
                return $this->redirect($this->generateUrl('admin_contact_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}/delete", name="admin_contact_delete")
     * @Secure(roles="ROLE_CONTACT_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CULabsContactBundle:Contact')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity is deleted.');
        return $this->redirect($this->generateUrl('admin_contact'));
    }
    
    /**
     * Batch actions for Contact entity.
     *
     * @Route("/batch", name="admin_contact_batch")
     * @Secure(roles="ROLE_CONTACT_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_contact'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_contact'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_contact'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CULabsContactBundle:Contact')->createQueryBuilder('Contact');
        $qb->delete()->where($qb->expr()->in('Contact.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_contact_changemaxperpage")
     * @Secure(roles="ROLE_CONTACT_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_contact'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_contact_sort")
     * @Secure(roles="ROLE_CONTACT_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_contact'));
    }
    /**
     * Import contactos.
     *
     * @Route("/import", name="admin_contact_import")
     * @Template()
     * @Secure(roles="ROLE_IMPORT_CONTACT")
     */
    public function importAction()
    {
        $import_service = $this->get('cu_labs_contact.import');
        $form = $import_service->getForm();
        $request = $this->getRequest();        
        if ($request->isMethod('POST')) {
            $form->bindRequest($request);
            if ($form->isValid()) {                
                try {                    
                    $text = $import_service->processFrom($form);
                    $this->setFlash('notice_import', $text);
                    return $this->redirect($this->generateUrl('admin_contact_import_after'));
                } catch (\Exception $e) {
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        return array(
            'form' => $form->createView(),
            'help' => $import_service->getHelp(),
        );
    }
    /**
     * Import contactos.
     *
     * @Route("/import/after", name="admin_contact_import_after")
     * @Template()
     * @Secure(roles="ROLE_IMPORT_CONTACT")
     */
    public function importAfterAction()
    {
        $mensajes = unserialize($this->getRequest()->getSession()->getFlash('notice_import'));
        return array(
            'mensajes' => $mensajes,
        );
    }
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CULabsContactBundle:Contact')
                   ->createQueryBuilder('Contact')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('Contact.%s %s', $sort['field'], $sort['order']));
        }
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filter_form, $qb);
        $pager = $this->get('knp_paginator')->paginate(
            $qb->getQuery(),
            $this->getPage(),
            $this->getMaxPerPage()
        );        
        return $pager;
    }
    
    protected function getFilterForm()
    {
        $filter_form = $this->get('form.factory')->create(new ContactFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }  
}
