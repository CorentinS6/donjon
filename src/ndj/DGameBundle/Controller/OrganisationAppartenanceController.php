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
 * OrganistionAppartenance controller.
 *
 * @Route("/organisationappartenance")
 */
class OrganisationAppartenanceController extends Controller {

    /**
     * @Route("/inscrire/{id}/{orga}/{mode}", name="organisationapp_inscrire", options={"expose"=true})
     */
    public function inscrireAction(Aventrier $perso, Organisation $orga, $mode = 'demande') {
        $av = $this->get('gamesession')->geta();

        $app = new OrganisationAppartenance();

        $perso->addOrganisationAppartenance($app);
        $orga->addOrganisationAppartenance($app);

        $app->setCacher(0);
        $app->setTitre(null);
        $app->setDroits(null);
        $app->setEtat(($mode == 'invite') ? 'invite' : 'demande' );

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response('Votre demande a été enregistrée !');
    }

    //////////////////////////////////////////
    //                                      //
    // GESTION INTERNE DE L'ORGANISATION    //
    //                      		    //
    //////////////////////////////////////////
    /*
      0 => 'quitterOrga', // Quitter l’organisation
      1 => 'listeMembre', // Voir les membres
      2 => 'listeInventaire', // Voir l'inventaire
      3 => 'listeArgent', // Voir l'argent
      4 => 'bannirMembre', // Bannir un membre*
      5 => 'inviterMembre', // Inviter un membre
      6 => 'validerMembre', // Valider l'inscription d'un membre
      7 => 'gererInventaire', // Gérer l'inventaire
      8 => 'gererArgent', // Gérer l'argent
      9 => 'gererTitre', // Gérer les titre des membres*
      10 => 'gererDroits', // Gérer les droits des membres*
      11 => 'dissoudre', // Dissoudre l'organisation
      12 => 'nommerchef', // Nommer un successeur
     */

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
     * Lister les membres d'une organisation
     *
     * @Route("/listemembre/{id}/{actif}", name="organisationapp_listemembre", options={"expose"=true})
     */
    public function listeMembreAction(Organisation $orga, $actif = true) {
        $av = $this->get('gamesession')->geta();

        $this->check($orga, 'listeMembre');

        return $this->render('ndjDGameBundle:Organisation:listemembre.html.twig', array(
                    'orga' => $orga,
                    'ctrl' => $this,
                    'aventurier' => $av,
                    'actif' => $actif,
                    'liste' => (($actif === true) ? $orga->getMembresActif() : $orga->getMembresInactif()),
        ));
    }

    /**
     * Inviter une novueau membre
     * 
     * @Route("/inviter/{id}/{orga}", name="organisationapp_inviter", options={"expose"=true})
     */
    public function inviterAction(Aventurier $a, Organisation $orga) {
        $av = $this->get('gamesession')->geta();

        $this->check($orga, 'inviterMembre');
// @todo re-inviter 
        $app = new OrganisationAppartenance();
        $orga->addOrganisationAppartenance($app);
        $a->addOrganisationAppartenance($app);
        $app->setCacher(0);
        $app->setEtat('invite');
        $app->setTitre('Invité');
        $app->setDroits(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response('Invitation envoyée !');
    }

    /**
     * Accepter une invitation
     * 
     * @Route("/accepterinvitation/{orga}/{id}/{valid}", name="organisationapp_accepterinvitation", options={"expose"=true})
     */
    public function accepterInvitationAction(Organisation $orga, Aventurier $a, $valid = 0) {
        $av = $this->get('gamesession')->geta();

        $app = $orga->getOrganisationAppartenance($a);

        if (!is_null($app) && $app->getEtat() == 'invite' && $a == $av) {
            $app->setEtat(($valid == 0) ? 'refus' : 'actif' );

            if ($valid)
                $app->setTitre('Nouveau');

            $em = $this->getDoctrine()->getManager();
            $em->persist($app);
            $em->flush();

            return new Response('Inscription mise à jour avec succès !');
        } else {
            throw new AccessDeniedException("Vous ne pouvez pas avoir accès à cette option !");
        }
    }

    /**
     * Valider une demande d'adhésion
     *
     * @Route("/validerdemande/{orga}/{id}/{valid}", name="organisationapp_validerdemande", options={"expose"=true})
     */
    public function validerInscriptionAction(Organisation $orga, Aventurier $a, $valid = 0) {
        $av = $this->get('gamesession')->geta();

        $this->check($orga, 'validerMembre');

        $app = $orga->getOrganisationAppartenance($a);

        // dans la cas d'une demande, on valide ou non
        if ($app->getEtat() == 'demande') {
            $app->setEtat(($valid == 0) ? 'refus' : 'actif' );
            if ($valid)
                $app->setTitre('Nouveau');
        } elseif ($app->getEtat() == 'invite' && $valid == 0) {
            // on peut refuser une invitation en cours !
            $app->setEtat('refus');
        } else {
            throw new AccessDeniedException("Vous ne pouvez pas avoir accès à cette option !");
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response('Inscription mise à jour avec succès !');
    }

    /**
     * @Route("/settitre/{orga}/{id}/{titre}", name="organisationapp_settitre", options={"expose"=true})
     */
    public function setTitreAction(Organisation $orga, Aventurier $a, $titre = null) {
        $av = $this->get('gamesession')->geta();

        $this->check($orga, 'gererTitre');

        if (!$av->getOrganisationAppartenance($orga)->isAutorite($a)) {
            throw new AccessDeniedException("Vous ne pouvez modifier le titre de ce membre !");
        }

        $app = $orga->getOrganisationAppartenance($a);
        
        if (!is_null($titre) && strlen(trim($titre))==0)
            $titre = null;

        $app->setTitre($titre);

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response("Titre mis à jour !");
    }

    /**
     * @Route("/setdroit/{orga}/{id}/{droit}/{etat}", name="organisationapp_setdroit", options={"expose"=true})
     */
    public function setDroitAction(Organisation $orga, Aventurier $a, $droit, $etat) {
        $av = $this->get('gamesession')->geta();

        $this->check($orga, 'gererDroits');

        if (!$av->getOrganisationAppartenance($orga)->isAutorite($a)) {
            throw new AccessDeniedException("Vous ne pouvez modifier les droits de ce membre !");
        }

        $app = $orga->getOrganisationAppartenance($a);

        if ($etat == 1)
            $app->addDroit($droit);
        else
            $app->delDroit($droit);

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response("Droit mis à jour !");
    }

    /**
     * @Route("/setcacher/{orga}/{cacher}", name="organisationapp_setcacher", options={"expose"=true})
     */
    public function setCacherAction(Organisation $orga, $cacher) {
        
        $av = $this->get('gamesession')->geta();
        
        $cacher = (int)$cacher;
        if ($cacher != 1 && $cacher != 0)
            throw new \InvalidArgumentException("Valeur de l'argument 'cacher' invalide (".var_export($cacher, true).") !");

        $app = $orga->getOrganisationAppartenance($av);
        
        $app->setCacher($cacher);

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response("Visibilité mis à jour !");
    }

    /**
     * Bannir un membre
     *
     * @Route("/bannir/{id}/{orga}", name="organisationapp_bannir", options={"expose"=true})
     */
    public function bannirAction(Aventurier $a, Organisation $orga) {
        $av = $this->get('gamesession')->geta();

        $this->check($orga, 'bannirMembre');

        if (!$av->getOrganisationAppartenance($orga)->isAutorite($a)) {
            throw new AccessDeniedException("Vous ne pouvez bannir ce membre !");
        }

        $app = $a->getOrganisationAppartenance($orga);

        $app->setEtat('exclu');

        $em = $this->getDoctrine()->getManager();
        $em->persist($app);
        $em->flush();

        return new Response($a->getNom() . " est bannit de  " . $orga->getNom() . "!");
    }

    /**
     * Quitter une organisation
     *
     * @Route("/quitter/{id}", name="organisationapp_quitter", options={"expose"=true})
     */
    public function quitterAction(Organisation $o) {
        $av = $this->get('gamesession')->geta();

        $app = $av->getOrganisationAppartenance($o);

        $this->check($app->getIdorganisation(), 'quitterOrga');

        if (!$app->is('chef')) {
            $app->setEtat('ancien');
            $em = $this->getDoctrine()->getManager();
            $em->persist($app);
            $em->flush();
            return new Response("Vous avez quitter " . $o->getNom() . " !");
        } else {
            throw new \Exception("Impossible de quitter une organisation dont vous êtes le chef. Vous devez en premier lieu nommer un successeur.");
        }
    }

    /**
     * Radier une membre (suppression de l'objet OrgasniationAppartenance)
     *
     * @Route("/radier/{orga}/{id}", name="organisationapp_radier", options={"expose"=true})
     */
    public function radierAction(Organisation $o, Aventurier $a) {
        $av = $this->get('gamesession')->geta();

        $this->check($o, 'gererDroits');

        $app = $a->getOrganisationAppartenance($o);

        if (!$av->getOrganisationAppartenance($o)->isAutorite($a) || $app->isActif()) {
            throw new AccessDeniedException("Vous ne pouvez radier ce membre !");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($app);
        $em->flush();

        return new Response("Vous avez radié " . $a->getNom() . " !");
    }

    /**
     * Nommer un chef
     *
     * @Route("/nommerchef/{id}/{orga}", name="organisationapp_nommerchef", options={"expose"=true})
     */
    public function nommerChefAction(Aventurier $a, Organisation $o) {
        $av = $this->get('gamesession')->geta();

        $this->check($o, 'nommerchef');

        $myapp = $av->getOrganisationAppartenance($o);
        $otherapp = $a->getOrganisationAppartenance($o);

        if (!$otherapp->isActif())
            throw new \Exception("Impossible de nommer ce chef : il est inactif !");


        $myapp->dellDroit('chef');
        $otherapp->addDroit('chef');

        $em = $this->getDoctrine()->getManager();
        $em->persist($myapp);
        $em->persist($otherapp);
        $em->flush();

        return new Response("Vous avez nommé " . $a->getNom() . " chef !");
    }

}
