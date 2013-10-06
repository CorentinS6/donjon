<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Bestiaire;
use ndj\DAdminBundle\Form\BestiaireType;

/**
 * Bestiaire controller.
 *
 * @Route("/bestiaire")
 */
class BestiaireController extends Controller
{
    /**
     * Lists all Bestiaire entities.
     *
     * @Route("/", name="bestiaire")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Bestiaire')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Bestiaire entity.
     *
     * @Route("/", name="bestiaire_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Bestiaire:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Bestiaire();
        $form = $this->createForm(new BestiaireType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bestiaire_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Bestiaire entity.
     *
     * @Route("/new", name="bestiaire_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Bestiaire();
        $form   = $this->createForm(new BestiaireType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Bestiaire entity.
     *
     * @Route("/{id}", name="bestiaire_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Bestiaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bestiaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Bestiaire entity.
     *
     * @Route("/{id}/edit", name="bestiaire_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Bestiaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bestiaire entity.');
        }

        $editForm = $this->createForm(new BestiaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Bestiaire entity.
     *
     * @Route("/{id}", name="bestiaire_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Bestiaire:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Bestiaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bestiaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BestiaireType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bestiaire_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Bestiaire entity.
     *
     * @Route("/{id}", name="bestiaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Bestiaire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bestiaire entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bestiaire'));
    }

    /**
     * Creates a form to delete a Bestiaire entity by id.
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
