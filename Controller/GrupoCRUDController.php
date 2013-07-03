<?php

namespace CULabs\ContactBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CULabs\ContactBundle\Entity\Grupo;
use CULabs\ContactBundle\Form\GrupoType;
use CULabs\ContactBundle\Filter\GrupoFilterType;

/**
 * Grupo controller.
 *
 * @Route("/grupo")
 */
class GrupoCRUDController extends CRUDController
{
    /**
     * Lists all Grupo entities.
     *
     * @Route("", name="admin_contact_grupo")
     * @Template()
     */
    public function indexAction()
    {       
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_LIST')) {
            throw new AccessDeniedException();
        }
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CULabsContactBundle:GrupoCRUD:list.html.twig', array(
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
     * Filter Grupo entities.
     *
     * @Route("/filter", name="admin_contact_grupo_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_contact_grupo'));
        }        
        $filter_form = $this->get('form.factory')->create(new GrupoFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_contact_grupo'));
        }        
        return $this->render('CULabsContactBundle:Grupo:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
            'sort'   => $this->getSort(),
        ));
    }
    
    /**
     * Finds and displays a Grupo entity.
     *
     * @Route("/{id}/show", name="admin_contact_grupo_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_SHOW')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:Grupo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new Grupo entity.
     *
     * @Route("/new", name="admin_contact_grupo_new")
     * @Template()
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_NEW')) {
            throw new AccessDeniedException();
        }
        $entity = new Grupo();
        $form   = $this->createForm(new GrupoType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Grupo is saved.');
                return $this->redirect($this->generateUrl('admin_contact_grupo_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Grupo entity.
     *
     * @Route("/{id}/edit", name="admin_contact_grupo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_EDIT')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:Grupo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }
        $form = $this->createForm(new GrupoType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Grupo is saved.');
                return $this->redirect($this->generateUrl('admin_contact_grupo_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Grupo entity.
     *
     * @Route("/{id}/delete", name="admin_contact_grupo_delete")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_DELETE')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:Grupo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity Grupo is deleted.');
        return $this->redirect($this->generateUrl('admin_contact_grupo'));
    }
    
    /**
     * Batch actions for Grupo entity.
     *
     * @Route("/batch", name="admin_contact_grupo_batch")
     */
    public function batchAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_DELETE')) {
            throw new AccessDeniedException();
        }
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_contact_grupo'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_contact_grupo'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_contact_grupo'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CULabsContactBundle:Grupo')->createQueryBuilder('Grupo');
        $qb->delete()->where($qb->expr()->in('Grupo.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_contact_grupo_changemaxperpage")
     */
    public function changeMaxPerPageAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_LIST')) {
            throw new AccessDeniedException();
        }
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_contact_grupo'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_contact_grupo_sort")
     */
    public function sortAction($field, $order)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_GRUPO_LIST')) {
            throw new AccessDeniedException();
        }
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_contact_grupo'));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CULabsContactBundle:Grupo')
                   ->createQueryBuilder('Grupo')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('Grupo.%s %s', $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new GrupoFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
