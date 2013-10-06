<?php

namespace ndj\DGameBundle\Controller;

use ndj\DGameBundle\Entity\Inventaire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/inventaire")
 */
class InventaireController extends Controller {

    /**
     * Get fiche
     *
     * @Route("/displayfiche/{id}", name="inventaire_displayfiche", options={"expose"=true})
     */
    public function displayFicheAction(Inventaire $inventaire) {
        return $this->render('ndjDGameBundle:Inventaire:inventaire.publicfiche.html.twig', array('inventaire' => $inventaire));
    }

    /**
     * @Route("/inventaireVendre/{id}", name="economat_inventairevendre", options={"expose"=true})
     */
    public function inventaireVendreAction(Inventaire $i) {
        $donjon = $this->get('gamesession')->getd();

        if ($i->getOwner() !== $donjon) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Impossible !")</script>');
        } elseif (!$donjon->action()) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Pas assez de points d\'action")</script>');
        } else {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($i->getIdpiece())) {
                $em->persist($i->getIdpiece());
                $i->getIdpiece()->removeInventaire($i);
            }

            $i->setPOSITION('{V}');

            $em->persist($donjon);
            $em->persist($i);
            $em->flush();
        }

        return $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire');
    }

    /**
     * @Route("/inventaireRanger/{id}", name="economat_inventaireranger", options={"expose"=true})
     */
    public function inventaireRangerAction(Inventaire $i) {
        $donjon = $this->get('gamesession')->getd();

        if ($i->getOwner() !== $donjon) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Impossible !")</script>');
        } elseif (!$donjon->action()) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Impossible, pas assez de points d\'action")</script>');
        } else {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($i->getIdpiece())) {
                $em->persist($i->getIdpiece());
                $i->getIdpiece()->removeInventaire($i);
            }

            $i->setPOSITION('{I}');

            $em->persist($donjon);
            $em->persist($i);
            $em->flush();
        }

        return $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire');
    }

    /**
     * @Route("/inventaireDeposerForm/{id}", name="economat_inventairedeposerform", options={"expose"=true})
     */
    public function inventaireDeposerFormAction(Inventaire $i) {
        $donjon = $this->get('gamesession')->getd();

        return $this->render('ndjDGameBundle:Inventaire:economat.inventaire.salle.form.html.twig', array(
                    'donjon' => $this->donjon,
                    'inventaire' => $i
        ));
    }

    /**
     * @todo : vérifier case vide avant de placer
     * @Route("/inventaireDeposer/{id}/{idpiece}/{x}/{y}", name="economat_inventairedeposer", options={"expose"=true})
     */
    public function inventaireDeposerAction(Inventaire $i, $idpiece, $x, $y) {
        $donjon = $this->get('gamesession')->getd();

        $p = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($idpiece);

        if ($i->getOwner() !== $donjon) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Impossible !")</script>');
        } elseif (!$p->caseExist($x, $y)) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Impossible de place l\'objet \'' . $i->getNom() . '\' en ' . $x . ',' . $y . ' dans \'' . $p->getNom() . '\'")</script>');
        } elseif (!$donjon->action()) {
            $interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire')->getContent();
            return new Response($interface . '<script>set_error("Impossible, pas assez de points d\'action")</script>');
        } else {
            $em = $this->getDoctrine()->getManager();

            $p->addInventaire($i);
            $i->setPosition('{' . $x . ',' . $y . '}');

            $em->persist($donjon);
            $em->persist($i);
            $em->persist($p);

            $em->flush();
        }

        return $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatInventaire');
    }

    /**
     * Jeter un objet
     * @Route("/drop/{id}", name="inventaire_drop", options={"expose"=true})
     */
    public function dropInventaire(Inventaire $i) {
        $aventurier = $this->get('gamesession')->geta();

        if ($i->getIdAVENTURIER() === $aventurier) {

            $i->drop();

            $em = $this->getDoctrine()->getManager();
            $em->persist($i);
            $em->flush();
            // @todo caracs::add('INV_TRANS', 1, 'game');
            // @todo caracs::add('INV_DROP', 1, aventurier::getAventurier()->getDonjon());
            return new Response();
        }
        return new Response('Impossible de jeter cet objet !');
    }

    /**
     * Ramasser un objet
     * @Route("/ramasser/{id}", name="inventaire_ramasser", options={"expose"=true})
     */
    public function ramasserAction(Inventaire $i) {
        $aventurier = $this->get('gamesession')->geta();

        if ($aventurier->a_porte($i)) {
            $i->setIdpiece(null);
            $i->setIddonjon(null);
            $i->setIdcompte(null);
            $i->setIdbestiaire(null);
            $aventurier->addInventaire($i);
            $i->setPosition('{I}');

            // @todo caracs::add('INV_TRANS', 1, 'game');
            // @todo caracs::add('INV_LOST', 1, aventurier::getAventurier()->getDonjon());

            $em = $this->getDoctrine()->getManager();
            $em->persist($i);
            $em->flush();

            return new Response('1');
        }

        return new Response('0');
    }

}
