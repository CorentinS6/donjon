<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DAdminBundle\Form\DonjonType;

/**
 * Donjon controller.
 *
 * @Route("/donjon")
 */
class DonjonController extends Controller
{
    /**
     * Lists all Donjon entities.
     *
     * @Route("/", name="donjon")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Donjon')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Donjon entity.
     *
     * @Route("/", name="donjon_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Donjon:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Donjon();
        $form = $this->createForm(new DonjonType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('donjon_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Donjon entity.
     *
     * @Route("/new", name="donjon_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Donjon();
        $form   = $this->createForm(new DonjonType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Donjon entity.
     *
     * @Route("/{id}", name="donjon_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Donjon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Donjon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Donjon entity.
     *
     * @Route("/{id}/edit", name="donjon_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Donjon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Donjon entity.');
        }

        $editForm = $this->createForm(new DonjonType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Donjon entity.
     *
     * @Route("/{id}", name="donjon_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Donjon:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Donjon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Donjon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DonjonType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('donjon_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Donjon entity.
     *
     * @Route("/{id}", name="donjon_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Donjon')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Donjon entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('donjon'));
    }

    /**
     * Creates a form to delete a Donjon entity by id.
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
