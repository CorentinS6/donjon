<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Connecte;
use ndj\DAdminBundle\Form\ConnecteType;

/**
 * Connecte controller.
 *
 * @Route("/connecte")
 */
class ConnecteController extends Controller
{
    /**
     * Lists all Connecte entities.
     *
     * @Route("/", name="connecte")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Connecte')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Connecte entity.
     *
     * @Route("/", name="connecte_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Connecte:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Connecte();
        $form = $this->createForm(new ConnecteType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('connecte_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Connecte entity.
     *
     * @Route("/new", name="connecte_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Connecte();
        $form   = $this->createForm(new ConnecteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Connecte entity.
     *
     * @Route("/{id}", name="connecte_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Connecte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Connecte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Connecte entity.
     *
     * @Route("/{id}/edit", name="connecte_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Connecte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Connecte entity.');
        }

        $editForm = $this->createForm(new ConnecteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Connecte entity.
     *
     * @Route("/{id}", name="connecte_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Connecte:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Connecte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Connecte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ConnecteType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('connecte_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Connecte entity.
     *
     * @Route("/{id}", name="connecte_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Connecte')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Connecte entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('connecte'));
    }

    /**
     * Creates a form to delete a Connecte entity by id.
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
