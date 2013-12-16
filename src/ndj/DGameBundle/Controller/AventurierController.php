<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityRepository;
use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Util\caracs;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Creature;
use ndj\DGameBundle\Entity\Evenement;
use ndj\DGameBundle\Entity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/aventurier")
 */
class AventurierController extends Controller {

    /**
     * Get fiche
     *
     * @Route("/displayfiche/{id}", name="aventurier_displayfiche", options={"expose"=true})
     */
    public function displayFicheAction(Aventurier $aventurier) {
        return $this->render('ndjDGameBundle:Aventurier:aventurier.publicfiche.html.twig', array('aventurier' => $aventurier));
    }

    /**
     * Création d'un aventurier
     * @Route("/creer", name="aventurier_creation")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function aventurierCreationAction(Request $request) {
        if (count($this->getUser()->getAventuriers()) >= $this->getUser()->limitAventurier() &&
                !$this->get('security.context')->isGranted('ROLE_USER_UNLIMITED')) {

            throw new AccessDeniedHttpException('Impossible de créer un nouvel aventurier !');
        }

        $av = new Aventurier();


        $creatures = $this->getDoctrine()->getManager()->getRepository('ndjDGameBundle:Creature')->findByIntelligente(1);

        $choices = array();
        foreach ($creatures as $cre) {
            $choices[$cre->getId()] = $cre;
        }

        $form = $this->createFormBuilder($av)
                ->add('idcreature', 'entity', array(
                    'class' => 'ndjDGameBundle:Creature',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->where('c.intelligente=1')
                                ->orderBy('c.nom', 'ASC');
                    },
                    'property' => 'nom',
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
                ))
                /* ->add('idcreature','entity',array(
                  'choices' => $choices,
                  'required'  => true,
                  'multiple'	=> false,
                  'expanded'	=> true
                  )) */
                ->add('nom', 'text')
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {

                //$av = $form->getData();
                /*

                  $crea = $this->getDoctrine()->getManager()->getRepository('ndjDGameBundle:Creature')->find( $av->getIdcreature() );

                  if (!$crea) {
                  throw $this->createNotFoundException('Unable to find Creature entity.');
                  } */

                $av->setIdmembre($this->getUser());

                $av->setAcrobatie($av->getIdcreature()->getAcrobatie());
                $av->setBagarre($av->getIdcreature()->getBagarre());
                $av->setCharme($av->getIdcreature()->getCharme());
                $av->setAcuite($av->getIdcreature()->getAcuite());

                $av->setPvieMax(caracs::jetDe($av->getIdcreature()->getVie()));
                $av->setPvie($av->getPvieMax());


                $av->setExperience(0);
                $av->setRenommee(0);
                $av->setRenommeeMax(0);
                $av->setAge(0);

                $av->setArgent(30);

                $av->setManaMax(0);
                $av->setMana(0);

                $av->setPact(10);
                $av->setPdep(10);
                $av->setPoints(0);
                $av->setEtat(1);

                // à la taverne
                $av->setPosition('{T}');

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($av);
                $em->flush();

                return $this->redirect($this->generateUrl('default_home'));
            }
        }



        return $this->render('ndjDGameBundle:Aventurier:form.creer.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * Aller à un donjon
     * @Route("/debutDonjon/{id}", name="aventurier_debutdonjon", options={"expose"=true})
     */
    public function debutDonjonAction(Donjon $donjon) {
        $aventurier = $this->get('gamesession')->geta();

        if ($donjon->isOpen()) {
            $init = $donjon->getRandomStartPosition();
            if ($init !== false && is_array($init)) {
                $piece = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($init[0]);

                // @todo entrer dans une piece
                $piece->addAventurier($aventurier);
                $aventurier->setPosition($init[1]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($aventurier);
                $em->persist($piece);
                $em->flush();

                return $this->forward('ndjDGameBundle:GameAventurier:interfaceCarte');
            }
        }

        return $this->forward('ndjDGameBundle:GameAventurier:interfaceTaverne');
    }

    /**
     * Quitter le donjon
     * @Route("/quitterDonjon", name="aventurier_quitterdonjon", options={"expose"=true})
     */
    public function quitterDonjonAction() {
        $aventurier = $this->get('gamesession')->geta();

        $aventurier->setIdpiece(null);
        $aventurier->setPosition('{T}');
        $em = $this->getDoctrine()->getManager();
        $em->persist($aventurier);
        $em->flush();

        return $this->forward('ndjDGameBundle:GameAventurier:interfaceTaverne');
        //$this->out .= '<script>Av_Reload_Interface()</script>';
    }

    /**
     * Terminer un donjon
     * @Route("/finDonjon", name="aventurier_findonjon", options={"expose"=true})
     */
    public function finDonjon() {
        $aventurier = $this->get('gamesession')->geta();

        // @todo check si bien sur une case FIN Donjon !
        // gain d'XP (2*XPBASE)
        $aventurier->gain_xp(caracs::XP_BASE * 2);
        // 5 points
        $aventurier->setPoints($aventurier->getPoints() + 5);

        // PV-MANA
        $aventurier->setPvie($aventurier->getPviemax());
        $aventurier->setPmana($aventurier->getPmanamax());

        // PA-PD		
        $aventurier->setPact($aventurier->getPact() + caracs::PA_TOUR);
        $aventurier->setPdep($aventurier->getPdep() + caracs::PD_TOUR);

        // OR (1% de l'or dans les coffres du Donjon)
        $donjon = $aventurier->getDonjon();
        $po = $donjon->getArgent() * caracs::PC_PO_FIN_DONJON;
        $donjon->depense($po);

        // @todo stats::add('PO_TRANS',$po,'game');
        $aventurier->setARGENT($aventurier->getARGENT() + $po);

        $aventurier->setPOSITION('{T}');
        $aventurier->setIdpiece(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($aventurier);
        $em->persist($donjon);
        $em->flush();

        return $this->forward('ndjDGameBundle:GameAventurier:interfaceTaverne');
        //$this->out .= '<script>Av_Reload_Interface()</script>';
    }

    /**
     * Ressusciter
     * @Route("/ressusciter", name="aventurier_ressusciter", options={"expose"=true})
     */
    public function ressusciterAction() {
        $aventurier = $this->get('gamesession')->geta();

        $aventurier->setPOSITION('{T}');
        $aventurier->setIdpiece(null);
        $aventurier->setPvie($aventurier->getPviemax());

        $em = $this->getDoctrine()->getManager();
        $em->persist($aventurier);
        $em->flush();

        return $this->forward('ndjDGameBundle:GameAventurier:interfaceTaverne');
    }

    /**
     * Déplacement
     * @Route("/deplacement/{x}/{y}", name="aventurier_deplacement", options={"expose"=true})
     */
    public function deplacementAction($x, $y) {
        $aventurier = $this->get('gamesession')->geta();

        $pos = Tools::explodex(',', $aventurier->getPOSITION());

        // triche avec déplacement en diag ou >1?
        if (abs($pos[0] - $x + $pos[1] - $y) != 1) {
            return new Response('Impossible de se déplacer comme ça !');
        }

        $piece = $aventurier->getIdpiece();

        $allowed = !$piece->isAction($x, $y, 'I') && !$piece->isAction($x, $y, 'W') && !$piece->isAction($x, $y, 'V') && !$piece->isPerso($x, $y) && $piece->caseExist($x, $y);

        if (!$allowed) {
            return new Response('Impossible de se déplacer ici !');
        }

        if (!$aventurier->deplacement()) {
            return new Response('Tu n\'as pas assez de points de déplacement');
        }

        $aventurier->setPosition('{' . $x . ',' . $y . '}');

        $em = $this->getDoctrine()->getManager();
        $em->persist($aventurier);
        $em->flush();

        return new Response('ok');
    }

    // @todo traduire à partir d'ici

    /**
     * @todo
     * Action P (passage/porte)
     * @Route("/actionP/{id}/{x}/{y}", name="aventurier_action_p", options={"expose"=true})
     */
    public function actionPAction(Piece $p, $x, $y) {
        $aventurier = $this->get('gamesession')->geta();

        $idPIECE = $_GET['idPIECE'];
        $x = $_GET['x'];
        $y = $_GET['y'];
        $p = explodex(',', $a->POSITION);
        //$check = db::get()->query('SELECT COUNT(idPIECE) FROM PIECE WHERE idPIECE='.$p[1].' AND Actions LIKE "%{'.$p[2].','.$p[3].',P,'.$idPIECE.','.$x.','.$y.'}%" ')->fetch(PDO::FETCH_NUM);
        $check = db::get()->query('SELECT COUNT(idPIECE) FROM PIECE WHERE idPIECE=' . $p[1] . ' AND Actions LIKE "%,P,' . $idPIECE . ',' . $x . ',' . $y . '}%" ')->fetch(PDO::FETCH_NUM);
        //echo 'SELECT COUNT(idPIECE) FROM PIECE WHERE idPIECE='.$p[1].' AND Actions LIKE "%{'.$p[2].','.$p[3].',P,'.$idPIECE.','.$x.','.$y.'}%" ';
        //echo '<br />';
        //var_dump($check);exit;
        if ($check[0] == 1) {
            $a->POSITION = '{' . $p[0] . ',' . $idPIECE . ',' . $x . ',' . $y . '}';
            $a->MiseAJour();
        }
        $this->interfaceCarte();
    }

    /**
     * @todo
     * Action E (Escalier M/D)
     * @Route("/actionE/{t}", name="aventurier_action_e", options={"expose"=true})
     */
    public function actionEAction($t) {
        $aventurier = $this->get('gamesession')->geta();

        $t = $_GET['t'];
        $x = null;
        $y = null;

        $s = $this->av->getPiece()->getStrairs();
        foreach ($s as $_a) {
            if ($this->av->a_porte_coord($_a[0], $_a[1])) {
                $x = $_a[0] + $this->av->getPiece()->PosX;
                $y = $_a[1] + $this->av->getPiece()->PosY;
                break;
            }
        }
        if (!is_null($x)) {
            $e = $this->av->getPiece()->getEtage();
            $e = ($t == 'M') ? $e->getSuperieur() : $e->getInferieur();
            $np = $e->getPiece($x, $y);
            if (!is_null($np)) {
                $this->av->POSITION = '{' . $np->idDONJON . ',' . $np->idPIECE . ',' . ($x - $np->PosX) . ',' . ($y - $np->PosY) . '}';
                $this->av->MiseAJour();
            }
        }
        $this->interfaceCarte();
    }

    /**
     * @todo
     * Action PO (Piece d'or)
     * @Route("/actionPO/{x}/{y}/{po}", name="aventurier_action_po", options={"expose"=true})
     */
    public function actionPOAction($x, $y, $po) {
        $aventurier = $this->get('gamesession')->geta();

        $p = Tools::explodex(',', $aventurier->getPOSITION());
        $diff = abs($x - $p[0] + $y - $p[1]);
        $action = '{' . $x . ',' . $y . ',PO,' . $po . '}';
        $check = db::get()->query('SELECT Actions FROM PIECE WHERE idPIECE=' . $p[1] . ' AND Actions LIKE "%' . $action . '%" ')->fetch(PDO::FETCH_NUM);
        //var_dump($check);
        // check distance, get[po] pour check et suppression ds action, puis crediter joueur
        if (count($check) == 1 && $diff <= 1) {
            if ($a->recette($po)) {
                // @todo caracs::add('PO_TRANS', $po, 'game');
                // @todo caracs::add('PO_LOST', $po, aventurier::getAventurier()->getDonjon());
                $actions = str_replace($action, '', $check[0]);
                db::get()->query('UPDATE PIECE SET Actions="' . $actions . '" WHERE idPIECE=' . $p[1]);
                $this->out .= $a->displayARGENT();
            }
        } else {
            $this->out = '';
        }
    }

    /**
     * @todo
     * Action D (Déclencheur)
     * @Route("/actionD", name="aventurier_action_d", options={"expose"=true})
     */
    public function actionDAction() {
        $aventurier = $this->get('gamesession')->geta();

        $p = Tools::explodex(',', $aventurier->getPOSITION());

        $piece = $av->getPiece();
        $a = $piece->getAction($p[0], $p[1], 'D');

        if (!is_null($a)) {

            $_up = false;
            $rythme = $a[3];
            // si pas illimité
            if ($rythme != '0') {
                $rythme = explode('/', $rythme);
                // si vide, impossible
                if ($rythme[0] == 0) {
                    $this->out = 'vide';
                    return;
                } else {
                    $_up = true;
                    $rythme[0] = $rythme[0] - 1;
                    $rythme = implode('/', $rythme);
                }
            }

            $_op = array('+', '-');
            $actions = Tools::explodex('][', $a[4]);
            $modification = array();
            foreach ($actions as $act) {
                $op = null;
                // quelle opérateur ? +,-,...
                foreach ($_op as $op) {
                    if (strpos($act, $op) !== false)
                        break;
                }

                $_actions = explode($op, $act);
                $modification [] = $_actions[0];
                switch ($op) {
                    case '+' :
                        $av->{$_actions[0]} = $av->{$_actions[0]} + $_actions[1];
                        break;
                    case '-' :
                        $a->{$_actions[0]} = $av->{$_actions[0]} - $_actions[1];
                        break;
                }
            }
            if ($_up) {
                $a[3] = $rythme;
                $piece->unsetAction($p[2], $p[3], 'D');
                $piece->Actions = trim($piece->Actions) . '{' . $p[2] . ',' . $p[3] . ',D,' . $a[3] . ',' . $a[4] . '}';
                $piece->miseAJour();
            }
            $av->corrigeMaximumCaracs($modification, false);
            $av->miseAJour();
            $this->out .= implode(',', $modification);
        } else {
            $this->out .= '0';
        }
    }

    /**
     * Attaque
     * @Route("/attaquer/{k}", name="aventurier_attaquer", options={"expose"=true})
     */
    public function attaquerAction($k) {
        $aventurier = $this->get('gamesession')->geta();

        if (!$aventurier->action()) {
            return new Response('Tu n\'as pas assez de points d\'action.');
        }

        $adv = $this->get('gamesession')->getObjectFromKey($k);
        $av_key = $this->get('gamesession')->getKey();
        // vérifier la distance
        if ($aventurier->a_porte_attaque($adv)) {
            // bagarre
            $msg = caracs::bagarre($aventurier, $adv);
            
            $em = $this->getDoctrine()->getManager();
            
            // messages
            $e = new Evenement();
            $e->setDest($av_key)->setCat(4)->setLu(0)->setDt(new \DateTime())->setContent(json_encode(array(
                "code" => 'info',
                "data" => $msg
            )));
            $em->persist($e);

            if ($adv instanceof Aventurier) {
                $ev = new Evenement();
                $ev->setDest($k)->setCat(4)->setLu(0)->setDt(new \DateTime())->setContent(json_encode(array(
                    "code" => 'info',
                    "data" => $msg
                )));
                $em->persist($ev);
            }
            
            // update des combattants
            $em->persist($aventurier);
            $em->persist($adv);
            
            $em->flush();

            return new Response('ok');
        } else {
            return new Response('Trop loin pour attaquer !');
        }
    }

    // FIN TRAD

    /**
     * Expérience
     * @Route("/getexperience", name="aventurier_getexperience", options={"expose"=true})
     */
    public function getExperienceAction() {
        $aventurier = $this->get('gamesession')->geta();

        $level = caracs::XP_getLevel($aventurier->getEXPERIENCE());
        $place = caracs::XP_getPlace($aventurier->getEXPERIENCE());
        return new Response($level . ',' . implode(',', $place) . ',' . $av->getEXPERIENCE());
    }

    /**
     * Attribuer un point
     * @Route("/attribuerpoints/{c}", name="aventurier_attribuerpoint", options={"expose"=true})
     */
    public function attribuerPointsAction($c) {
        $aventurier = $this->get('gamesession')->geta();

        $getter = 'get' . $c;
        if ($aventurier->getPOINTS() <= 0) {
            return new Response('Pas assez de points !');
        } elseif (method_exists($aventurier, $getter)) {
            $setter = 'set' . $c;
            // @todo : check un bon update (bagarre...) et pas du cheat dans d'autres caracs
            $aventurier->$setter($aventurier->$getter() + 1);
            $aventurier->setPOINTS($aventurier->getPOINTS() - 1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($aventurier);
            $em->flush();

            return new Response($aventurier->getPOINTS());
        } else {
            return new Response('Impossible, ' . $c . ' inexistant !');
        }
    }

}
