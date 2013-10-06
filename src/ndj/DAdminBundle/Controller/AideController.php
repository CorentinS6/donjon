<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Aide;
use ndj\DAdminBundle\Form\AideType;

/**
 * Aide controller.
 *
 * @Route("/aide")
 */
class AideController extends Controller
{
    /**
     * Lists all Aide entities.
     *
     * @Route("/", name="aide")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Aide')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Aide entity.
     *
     * @Route("/", name="aide_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Aide:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Aide();
        $form = $this->createForm(new AideType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aide_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Aide entity.
     *
     * @Route("/new", name="aide_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Aide();
        $form   = $this->createForm(new AideType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Aide entity.
     *
     * @Route("/{id}", name="aide_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Aide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aide entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Aide entity.
     *
     * @Route("/{id}/edit", name="aide_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Aide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aide entity.');
        }

        $editForm = $this->createForm(new AideType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Aide entity.
     *
     * @Route("/{id}", name="aide_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Aide:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Aide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aide entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AideType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aide_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Aide entity.
     *
     * @Route("/{id}", name="aide_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Aide')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Aide entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('aide'));
    }

    /**
     * Creates a form to delete a Aide entity by id.
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
