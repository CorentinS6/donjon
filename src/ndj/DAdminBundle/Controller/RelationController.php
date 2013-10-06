<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Relation;
use ndj\DAdminBundle\Form\RelationType;

/**
 * Relation controller.
 *
 * @Route("/relation")
 */
class RelationController extends Controller
{
    /**
     * Lists all Relation entities.
     *
     * @Route("/", name="relation")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Relation')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Relation entity.
     *
     * @Route("/", name="relation_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Relation:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Relation();
        $form = $this->createForm(new RelationType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('relation_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Relation entity.
     *
     * @Route("/new", name="relation_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Relation();
        $form   = $this->createForm(new RelationType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Relation entity.
     *
     * @Route("/{id}", name="relation_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Relation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Relation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Relation entity.
     *
     * @Route("/{id}/edit", name="relation_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Relation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Relation entity.');
        }

        $editForm = $this->createForm(new RelationType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Relation entity.
     *
     * @Route("/{id}", name="relation_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Relation:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Relation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Relation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RelationType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('relation_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Relation entity.
     *
     * @Route("/{id}", name="relation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Relation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Relation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('relation'));
    }

    /**
     * Creates a form to delete a Relation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
