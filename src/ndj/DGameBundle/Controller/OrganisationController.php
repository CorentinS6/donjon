<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Organisation;
use ndj\DGameBundle\Entity\OrganisationAppartenance;

/**
 * Organistion controller.
 *
 * @Route("/organisation")
 */
class OrganisationController extends Controller {

    /**
     * @Route("/creer/{cat}", name="organisation_creer", options={"expose"=true})
     */
    public function creerAction($cat = 'group') {
        $av = $this->get('gamesession')->geta();

        // si cat non autorisé
        if ($cat != 'group' && $cat != 'guilde')
            throw new \Exception("Impossible de traiter la demande :"
            . "la catégorie d'organisation proposée n'existe pas.");

        $request = $this->getRequest();

        $orga = new Organisation();
        $orga->setCat($cat);
        $orga->setPrixIn(0);
        $orga->setPrixCot(0);
        $orga->setNombreMbMax(($cat == 'group') ? 5 : 0);

        $form = $this->createFormBuilder($orga);

        $form->add('nom')
                ->add('description')
                ->add('nombrembmax', 'integer')
                ->add('cat', 'hidden');

        if ($cat == 'guilde') {
            $form->add('charte')
                    ->add('blason')
                    ->add('prixIn', 'integer')
                    ->add('prixCot', 'integer')
                    ->add('public', 'choice', array(
                        'choices' => array('1' => 'Publique', '0' => 'Cachée'),
                        'required' => true,
                        'multiple' => false,
                        'expanded' => false,
                    ))
                    ->add('gestion', 'choice', array(
                        'choices' => array('auto' => 'Automatique','joueur' => 'Manuelle'),
                        'required' => true,
                        'multiple' => false,
                        'expanded' => false,
            ));
        }

        $form = $form->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $orga->setDateCrea(new \DateTime());
                $orga->setCat($cat);
                if ($cat == 'group') {
                    $orga->setPublic(1);
                    $orga->setGestion('auto');
                }

                // sav
                $em = $this->getDoctrine()->getManager();
                $em->persist($orga);
                $em->flush();


                // création de l'appatrenance
                $app = new OrganisationAppartenance();
                $orga->addOrganisationAppartenance($app);
                $av->addOrganisationAppartenance($app);
                $app->setCacher(0);
                $app->setTitre('Fondateur');
                $app->setDroits('chef');
                $app->setEtat('actif');

                $em->persist($app);
                $em->flush();

                return new Reponse('Création terminée !');
            }
        }



        return $this->render('ndjDGameBundle:Organisation:form.creer.html.twig', array(
                    'orga' => $orga,
                    'cat' => $cat,
                    'form' => $form->createView(),
        ));
    }


    /**
     * Affiche les informations d'une gulide
     *
     * @Route("/displayfiche/{id}", name="organisation_displayfiche", options={"expose"=true})
     */
    public function displayficheAction(Organisation $o) {
        $av = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:Organisation:fiche.html.twig', array('orga' => $o,
            'aventurier' => $av,));
    }

    /**
     * Liste les organisations
     *
     * @Route("/liste/{cat}", name="organisation_liste", options={"expose"=true})
     */
    public function listeAction($cat = 'group') {
        $av = $this->get('gamesession')->geta();

        $liste = $this->getDoctrine()->getRepository('ndjDGameBundle:Organisation')->findBy(array(
            'cat' => $cat,
        ));

        return $this->render('ndjDGameBundle:Organisation:liste.html.twig', array(
            'liste' => $liste,
            'aventurier' => $av,
            ));
    }

    //////////////////////////////////////////
    //                                      //
    // GESTION INTERNE DE L'ORGANISATION    //
    //                      		    //
    //////////////////////////////////////////

    /**
     * Vérifie que le membre a les droits pour effectuer l'action donnée
     * @param \ndj\DGameBundle\Entity\Organisation $o
     * @param string $action
     * @throws AccessDeniedException
     */
    private function check(Organisation $o, $action) {
        $av = $this->get('gamesession')->geta();
        $app = $av->getOrganisationAppartenance($o);
        if ($app->isActif() && !$app->actionPossible($action)) {
            throw new AccessDeniedException("Impossible d'effectuer cette action : "
            . " il semble que vous n'ayez pas les droits d'accès suffisant "
            . " ou que vous soyez inactif dans l'organisation !");
        }
    }

    /**
     * Affiche les informations d'une gulide à destination d'un membre
     *
     * @Route("/fichedetaillee/{id}", name="organisation_fichedetaillee", options={"expose"=true})
     */
    public function ficheDetailleeAction(Organisation $o) {
        $av = $this->get('gamesession')->geta();

        if (!$o->isMembreActif($av))
               throw new AccessDeniedException("Accès impossible, vous n'êtes pas membre actif de cette organisation !");
        
        return $this->render('ndjDGameBundle:Organisation:fichedetaillee.html.twig', array(
            'orga' => $o,
            'aventurier'=>$av,
        ));
    }

    /**
     * Nommer un chef
     *
     * @Route("/dissoudre/{id}", name="organisation_dissoudre", options={"expose"=true})
     */
    public function dissoudreAction(Organisation $o) {
        $av = $this->get('gamesession')->geta();

        $this->check($o, 'dissoudre');

        $nom = $o->getNom();

        $em = $this->getDoctrine()->getManager();
        $em->remove($o);
        $em->flush();

        return new Response($nom . " n'exisite plus !");
    }

}
