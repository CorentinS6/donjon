<?php

namespace ndj\DGameBundle\Controller;

use ndj\DGameBundle\Util\caracs;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Donjon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * GameAventurier controller.
 *
 * @Route("/game_aventurier")
 */
class GameAventurierController extends Controller {

    /**
     * @Route("/", name="game_aventurier")
     */
    public function indexAction() {
        $aventurier = $this->get('gamesession')->geta();

        $panels = array(
            array(
                'code' => 'fiche',
                'label' => 'Fiche',
                'pos' => 0
            ),
            array(
                'code' => 'inventaire',
                'label' => 'Inventaire',
                'pos' => 1,
            ),
            array(
                'code' => 'relations',
                'label' => 'Relations',
                'pos' => 2
            ),
            array(
                'code' => 'organisations',
                'label' => 'Organisations',
                'pos' => 3
            ),
            array(
                'code' => 'stats',
                'label' => 'Statistiques',
                'pos' => 4
            ),
        );

        $xp = caracs::XP_getPlace($aventurier->getExperience());
        return $this->render('ndjDGameBundle:GameAventurier:index.html.twig', array(
                    'aventurier' => $aventurier,
                    'panels' => $panels,
                    'xp' => $xp,
        ));
    }

    /**
     * Affichage du PAD donjon
     */
    public function interfaceDonjonAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:donjon.html.twig', array());
    }

    /**
     * Get interface of a given tab
     *
     * @Route("/interfaceResume", name="gameaventurier_interfaceresume", options={"expose"=true})
     */
    public function interfaceResumeAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:resume.html.twig', array(
                    'aventurier' => $aventurier
        ));
    }

    /**
     * Get interface of a given tab
     *
     * @Route("/interfaceMain", name="gameaventurier_interfacemain", options={"expose"=true})
     */
    public function interfaceMainAction() {
        $aventurier = $this->get('gamesession')->geta();

        // @todo $this->addHeaderJs('$(function(){ updateAffichageDonneeNoVal("POSITION"); });');

        if ($aventurier->isOnMap()) {
            return $this->interfaceCarteAction();
        } elseif ($aventurier->isMort()) {
            return $this->interfaceMortAction();
        } else {
            return $this->interfaceTaverneAction();
        }
    }

    public function interfaceTaverneAction() {
        $aventurier = $this->get('gamesession')->geta();

        $params = array('aventurier' => $aventurier);
        $repo = $this->getDoctrine()->getRepository('ndjDGameBundle:Donjon');

        $params['liste_nouv'] = $repo->findBy(
                array('etat' => '2'), array('dateOuverture' => 'DESC'), 3, 0
        );
        $params['liste_pvisite'] = $repo->findByStats('AV_IN');
        $params['liste_pnote'] = $repo->findByStats('NOTE_AVG');
        $params['liste_tous'] = $repo->findByEtat('2');

        return $this->render('ndjDGameBundle:GameAventurier:taverne.html.twig', $params);
    }

    public function interfaceMortAction() {
        $aventurier = $this->get('gamesession')->geta();
        /*
          @todo : Gestion de la mort :
          - ne paie rien :
          - perte de 50% or et un objet au pif.

          - paie :
          - 50% (min 30 PO) : rÃ©cupÃ¨re son objet.
         */

        return $this->render('ndjDGameBundle:GameAventurier:mort.html.twig', array(
                    'aventurier' => $aventurier
        ));
    }

    public function interfaceCarteAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:carte.html.twig', array('aventurier' => $aventurier));
    }

    public function interfaceCaracPanelAction() {
        $aventurier = $this->get('gamesession')->geta();

        $panels = array(
            array(
                'code' => 'inventaire',
                'label' => 'Inventaire',
                'pos' => 1,
            ),
            array(
                'code' => 'relations',
                'label' => 'Relations',
                'pos' => 2
            ),
            array(
                'code' => 'stats',
                'label' => 'Statistiques',
                'pos' => 3
            ),
            array(
                'code' => 'fiche',
                'label' => 'Fiche',
                'pos' => 0
            ),
        );
        return $this->render('ndjDGameBundle:GameAventurier:caracpanel.html.twig', array(
                    'aventurier' => $aventurier,
                    'panels' => $panels
        ));
    }

    public function interfaceStatsAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:panel.stats.html.twig', array('aventurier' => $aventurier));
    }

    public function interfaceRelationsAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:panel.relations.html.twig', array('aventurier' => $aventurier));
    }

    public function interfaceOrganisationsAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:panel.organisations.html.twig', array('aventurier' => $aventurier));
    }

    /**
     * 
     * @Route("/interfaceInventaire", name="gameaventurier_interfaceinventaire", options={"expose"=true})
     */
    public function interfaceInventaireAction() {
        $aventurier = $this->get('gamesession')->geta();

        $params = array('aventurier' => $aventurier);

        $mode = array('et', 'ec', 'ead', 'eca', 'eag', 'emd', 'ecc', 'emg', 'ep', 'i', 'jet', 'g', 'v');
        $rep = $this->getDoctrine()->getRepository('ndjDGameBundle:Inventaire');
        foreach ($mode as $m) {
            $params['liste_' . $m] = $rep->findBy(array(
                'idaventurier' => $aventurier->getId(),
                'position' => '{' . strtoupper($m) . '}'
            ));
        }


        return $this->render('ndjDGameBundle:GameAventurier:panel.inventaire.html.twig', $params);
    }

    public function listeInventaireAction($mode = 'I') {
        $aventurier = $this->get('gamesession')->geta();

        if (strlen($mode) > 3)
            throw new \Exception('Ce mode n\'est pas possible !');

        $liste = $this->getDoctrine()->getRepository('ndjDGameBundle:Inventaire')->findBy(array(
            'idaventurier' => $aventurier->getId(),
            'position' => '{' . $mode . '}'
        ));

        return $this->render('ndjDGameBundle:GameAventurier:panel.inventaire.liste.html.twig', array(
                    'liste' => $liste,
                    'mode' => $mode
        ));
    }

    /**
     *
     * @Route("/interfaceFiche", name="gameaventurier_interfacefiche", options={"expose"=true})
     */
    public function interfaceFicheAction() {
        $aventurier = $this->get('gamesession')->geta();

        return $this->render('ndjDGameBundle:GameAventurier:panel.fiche.html.twig', array('aventurier' => $aventurier));
    }

}