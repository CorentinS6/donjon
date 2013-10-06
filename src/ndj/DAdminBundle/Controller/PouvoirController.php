<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Pouvoir;
use ndj\DAdminBundle\Form\PouvoirType;

/**
 * Pouvoir controller.
 *
 * @Route("/pouvoir")
 */
class PouvoirController extends Controller
{
    /**
     * Lists all Pouvoir entities.
     *
     * @Route("/", name="pouvoir")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Pouvoir')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Pouvoir entity.
     *
     * @Route("/", name="pouvoir_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Pouvoir:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Pouvoir();
        $form = $this->createForm(new PouvoirType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pouvoir_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Pouvoir entity.
     *
     * @Route("/new", name="pouvoir_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pouvoir();
        $form   = $this->createForm(new PouvoirType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Pouvoir entity.
     *
     * @Route("/{id}", name="pouvoir_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Pouvoir')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pouvoir entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Pouvoir entity.
     *
     * @Route("/{id}/edit", name="pouvoir_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Pouvoir')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pouvoir entity.');
        }

        $editForm = $this->createForm(new PouvoirType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Pouvoir entity.
     *
     * @Route("/{id}", name="pouvoir_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Pouvoir:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Pouvoir')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pouvoir entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PouvoirType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pouvoir_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Pouvoir entity.
     *
     * @Route("/{id}", name="pouvoir_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Pouvoir')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pouvoir entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('pouvoir'));
    }

    /**
     * Creates a form to delete a Pouvoir entity by id.
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
