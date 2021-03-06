<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Stats;
use ndj\DAdminBundle\Form\StatsType;

/**
 * Stats controller.
 *
 * @Route("/stats")
 */
class StatsController extends Controller
{
    /**
     * Lists all Stats entities.
     *
     * @Route("/", name="stats")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Stats')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Stats entity.
     *
     * @Route("/", name="stats_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Stats:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Stats();
        $form = $this->createForm(new StatsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stats_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Stats entity.
     *
     * @Route("/new", name="stats_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Stats();
        $form   = $this->createForm(new StatsType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Stats entity.
     *
     * @Route("/{id}", name="stats_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Stats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stats entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Stats entity.
     *
     * @Route("/{id}/edit", name="stats_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Stats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stats entity.');
        }

        $editForm = $this->createForm(new StatsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Stats entity.
     *
     * @Route("/{id}", name="stats_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Stats:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Stats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stats entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StatsType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stats_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Stats entity.
     *
     * @Route("/{id}", name="stats_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Stats')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Stats entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stats'));
    }

    /**
     * Creates a form to delete a Stats entity by id.
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
