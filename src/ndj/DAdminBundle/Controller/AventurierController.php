<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DAdminBundle\Form\AventurierType;

/**
 * Aventurier controller.
 *
 * @Route("/aventurier")
 */
class AventurierController extends Controller
{
    /**
     * Lists all Aventurier entities.
     *
     * @Route("/", name="aventurier")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Aventurier')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Aventurier entity.
     *
     * @Route("/", name="aventurier_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Aventurier:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Aventurier();
        $form = $this->createForm(new AventurierType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aventurier_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Aventurier entity.
     *
     * @Route("/new", name="aventurier_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Aventurier();
        $form   = $this->createForm(new AventurierType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Aventurier entity.
     *
     * @Route("/{id}", name="aventurier_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Aventurier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aventurier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Aventurier entity.
     *
     * @Route("/{id}/edit", name="aventurier_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Aventurier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aventurier entity.');
        }

        $editForm = $this->createForm(new AventurierType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Aventurier entity.
     *
     * @Route("/{id}", name="aventurier_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Aventurier:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Aventurier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aventurier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AventurierType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aventurier_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Aventurier entity.
     *
     * @Route("/{id}", name="aventurier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Aventurier')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Aventurier entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('aventurier'));
    }

    /**
     * Creates a form to delete a Aventurier entity by id.
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
