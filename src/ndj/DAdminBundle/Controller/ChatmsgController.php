<?php

namespace ndj\DAdminBundle\Controller;

use ndj\DGameBundle\Entity\Aventurier;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Chatmsg;
use ndj\DAdminBundle\Form\ChatmsgType;

/**
 * Chatmsg controller.
 *
 * @Route("/chatmsg")
 */
class ChatmsgController extends Controller
{
    /**
     * Lists all Chatmsg entities.
     *
     * @Route("/", name="chatmsg")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ndjDGameBundle:Chatmsg')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Chatmsg entity.
     *
     * @Route("/", name="chatmsg_create")
     * @Method("POST")
     * @Template("ndjDAdminBundle:Chatmsg:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Chatmsg();
        $form = $this->createForm(new ChatmsgType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chatmsg_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Chatmsg entity.
     *
     * @Route("/new", name="chatmsg_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Chatmsg();
        $form   = $this->createForm(new ChatmsgType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Chatmsg entity.
     *
     * @Route("/{id}", name="chatmsg_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Chatmsg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chatmsg entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Chatmsg entity.
     *
     * @Route("/{id}/edit", name="chatmsg_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Chatmsg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chatmsg entity.');
        }

        $editForm = $this->createForm(new ChatmsgType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Chatmsg entity.
     *
     * @Route("/{id}", name="chatmsg_update")
     * @Method("PUT")
     * @Template("ndjDAdminBundle:Chatmsg:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ndjDGameBundle:Chatmsg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chatmsg entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChatmsgType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chatmsg_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Chatmsg entity.
     *
     * @Route("/{id}", name="chatmsg_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ndjDGameBundle:Chatmsg')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chatmsg entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chatmsg'));
    }

    /**
     * Creates a form to delete a Chatmsg entity by id.
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
