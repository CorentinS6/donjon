<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Etage;
use ndj\DAdminBundle\Form\EtageType;

/**
 * Etage controller.
 *
 * @Route("/etage")
 */
class EtageController extends Controller
{
    /**
     * Lists all Etage entities.
     *
     * @Route("/", name="etage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Etage')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Etage entity.
     *
     * @Route("/", name="etage_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Etage:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Etage();
        $form = $this->createForm(new EtageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('etage_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Etage entity.
     *
     * @Route("/new", name="etage_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Etage();
        $form   = $this->createForm(new EtageType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Etage entity.
     *
     * @Route("/{id}", name="etage_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Etage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Etage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Etage entity.
     *
     * @Route("/{id}/edit", name="etage_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Etage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Etage entity.');
        }

        $editForm = $this->createForm(new EtageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Etage entity.
     *
     * @Route("/{id}", name="etage_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Etage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Etage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Etage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EtageType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('etage_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Etage entity.
     *
     * @Route("/{id}", name="etage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Etage')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Etage entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('etage'));
    }

    /**
     * Creates a form to delete a Etage entity by id.
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
