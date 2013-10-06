<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Creature;
use ndj\DAdminBundle\Form\CreatureType;

/**
 * Creature controller.
 *
 * @Route("/creature")
 */
class CreatureController extends Controller
{
    /**
     * Lists all Creature entities.
     *
     * @Route("/", name="creature")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Creature')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Creature entity.
     *
     * @Route("/", name="creature_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Creature:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Creature();
        $form = $this->createForm(new CreatureType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('creature_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Creature entity.
     *
     * @Route("/new", name="creature_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Creature();
        $form   = $this->createForm(new CreatureType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Creature entity.
     *
     * @Route("/{id}", name="creature_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Creature')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creature entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Creature entity.
     *
     * @Route("/{id}/edit", name="creature_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Creature')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creature entity.');
        }

        $editForm = $this->createForm(new CreatureType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Creature entity.
     *
     * @Route("/{id}", name="creature_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Creature:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Creature')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creature entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CreatureType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('creature_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Creature entity.
     *
     * @Route("/{id}", name="creature_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Creature')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Creature entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('creature'));
    }

    /**
     * Creates a form to delete a Creature entity by id.
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
