<?php

namespace CULabs\ContactBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CULabs\ContactBundle\Entity\MensajeMasivo;
use CULabs\ContactBundle\Form\MensajeMasivoType;
use CULabs\ContactBundle\Filter\MensajeMasivoFilterType;

/**
 * MensajeMasivo controller.
 *
 * @Route("/admin/mensajemasivo")
 */
class MensajeMasivoCRUDController extends CRUDController
{
    /**
     * Lists all MensajeMasivo entities.
     *
     * @Route("", name="admin_mensajemasivo")
     * @Template()
     */
    public function indexAction()
    {        
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_LIST')) {
            throw new AccessDeniedException();
        }
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CULabsContactBundle:MensajeMasivoCRUD:list.html.twig', array(
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
     * Filter MensajeMasivo entities.
     *
     * @Route("/filter", name="admin_mensajemasivo_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_mensajemasivo'));
        }        
        $filter_form = $this->get('form.factory')->create(new MensajeMasivoFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_mensajemasivo'));
        }        
        return $this->render('CULabsContactBundle:MensajeMasivo:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
            'sort'   => $this->getSort(),
        ));
    }
    
    /**
     * Finds and displays a MensajeMasivo entity.
     *
     * @Route("/{id}/show", name="admin_mensajemasivo_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_SHOW')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:MensajeMasivo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MensajeMasivo entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new MensajeMasivo entity.
     *
     * @Route("/new", name="admin_mensajemasivo_new")
     * @Template()
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_NEW')) {
            throw new AccessDeniedException();
        }
        $entity = new MensajeMasivo();
        $form   = $this->createForm(new MensajeMasivoType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity is saved.');
                return $this->redirect($this->generateUrl('admin_mensajemasivo_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing MensajeMasivo entity.
     *
     * @Route("/{id}/edit", name="admin_mensajemasivo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_EDIT')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:MensajeMasivo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MensajeMasivo entity.');
        }
        $form = $this->createForm(new MensajeMasivoType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity is saved.');
                return $this->redirect($this->generateUrl('admin_mensajemasivo_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a MensajeMasivo entity.
     *
     * @Route("/{id}/delete", name="admin_mensajemasivo_delete")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_DELETE')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:MensajeMasivo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MensajeMasivo entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity is deleted.');
        return $this->redirect($this->generateUrl('admin_mensajemasivo'));
    }
    
    /**
     * Batch actions for MensajeMasivo entity.
     *
     * @Route("/batch", name="admin_mensajemasivo_batch")
     */
    public function batchAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_DELETE')) {
            throw new AccessDeniedException();
        }

        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_mensajemasivo'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_mensajemasivo'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_mensajemasivo'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CULabsContactBundle:MensajeMasivo')->createQueryBuilder('MensajeMasivo');
        $qb->delete()->where($qb->expr()->in('MensajeMasivo.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_mensajemasivo_changemaxperpage")
     */
    public function changeMaxPerPageAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_LIST')) {
            throw new AccessDeniedException();
        }
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_mensajemasivo'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_mensajemasivo_sort")
     */
    public function sortAction($field, $order)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_LIST')) {
            throw new AccessDeniedException();
        }
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_mensajemasivo'));
    }
    /**
     * Activar Mensaje Masivo
     *
     * @Route("/{id}/activar", name="admin_mensajemasivo_activar")
     */
    public function activarAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_MENSAJEMASIVO_ACTIVAR')) {
            throw new AccessDeniedException();
        }
        $entity = $this->getRepository('CULabsContactBundle:MensajeMasivo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MensajeMasivo entity.');
        }
        if ($entity->getStatus() !== MensajeMasivo::STATUS_NUEVO) {
            throw $this->createNotFoundException('Solo se puede activar mensajes nuevos.');
        }
        $entity->setStatus(MensajeMasivo::STATUS_LISTO);
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
        $this->setFlash('notice', 'El mensaje ha sido activado');
        return $this->redirect($this->generateUrl('admin_mensajemasivo_show', array(
            'id' => $entity->getId(),
        )));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CULabsContactBundle:MensajeMasivo')
                   ->createQueryBuilder('MensajeMasivo')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('MensajeMasivo.%s %s', $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new MensajeMasivoFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
