<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Tools;
use ndj\DGameBundle\Entity\Piece;

/**
 * GameCommon controller.
 *
 * @Route("/game_common")
 */
class GameCommonController extends Controller {

    /**
     *
     * @Route("/getcurrentplayercarac/{carac}", name="game_getcurrentplayercarac")
     */
    public function getCurrentPlayerCaracAction($carac) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }

        $j = $this->get('gamesession')->get();

        return new Response($j->$carac());
    }

    /**
     * 
     * @Route("/getplayercaracfromcode/{code}/{carac}", name="game_getplayercaracfromcode")
     */
    public function getPlayerCaracFromCodeAction($code, $carac) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }

        list($id, $type) = explode(':', $code);
        if ($type == 'a')
            $class = 'Aventurier';
        elseif ($type == 'd')
            $class = 'Donjon';
        else
            throw new \Exception('Impossible de trouver un item avec ce code ! (' . $code . ')');

        return new Response($this->getDoctrine()->getRepository('ndjDGameBundle:' . $class)->find($code[0])->$carac());
    }

    /**
     * GetDonnee
     * @Route("/getdonnee/{data}", name="gamecommon_getdonnee", options={"expose"=true})
     */
    public function getDonneeAction($data) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }
        $j = $this->get('gamesession')->get();

        $method1 = 'display' . ucfirst(strtolower($data));
        $method = 'get' . ucfirst(strtolower($data));
        if (method_exists($j, $method1)) {
            $result = $j->$method1();
        } elseif (method_exists($j, $method)) {
            $result = $j->$method();
        } else {
            throw new ResourceNotFoundException('Attribut "' . $data . '" introuvable !');
        }
        return new Response($result);
    }

    /**
     * setVal
     * @Route("/setval/{obj}/{id}/{var}/{val}", name="gamecommon_setval", options={"expose"=true})
     */
    public function setValAction($obj, $id, $var, $val) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }

        $o = $this->getDoctrine()->getRepository('ndjDGameBundle:' . ucfirst(strtolower($obj)))->find($id);
        $setter = 'set' . ucfirst(strtolower($var));
        $o->$setter($val);
        $em = $this->getDoctrine()->getManager();
        $em->persist($o);
        $em->flush();

        return new Response($val);
    }

    /**
     * getval
     * @Route("/getval/{obj}/{id}/{var}", name="gamecommon_getval", options={"expose"=true})
     */
    public function getValAction($obj, $id, $var) {
        $o = $this->getDoctrine()->getRepository('ndjDGameBundle:' . ucfirst(strtolower($obj)))->find($id);
        $getter = 'get' . ucfirst(strtolower($var));
        return new Response($o->$getter());
    }

    /**
     * load Objets et Monstre d'une piece
     * @Route("/mapLoadObjetMonstre/{id}", name="gamecommon_maploadobjetmonstre", options={"expose"=true})
     */
    public function mapLoadObjetMonstreAction(Piece $p) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }
        $j = $this->get('gamesession')->get();

        $objets = $p->getInventaires();
        $aventuriers = $p->getAventuriers();
        $monstres = $p->getBestiaires();

        $final = array(
            'aventuriers' => array(),
            'bestiaires' => array(),
            'inventaires' => array(),
        );

        foreach ($objets as $o)
            $final['inventaires'] [] = $o->toArray();
        foreach ($aventuriers as $o)
            $final['aventuriers'] [] = $o->toArray();
        foreach ($monstres as $o)
            $final['bestiaires'] [] = $o->toArray();

        return new JsonResponse($final);
        
        /*
        foreach ($objets as $o) {
            $_f = $o->toArray();
            $_f = array_merge($_f, array('image' => 'images/objets/' . $o->getIdobjets()->getId() . '.png'));
            $final[] = $_f;
        }

        foreach ($aventuriers as $o) {
            $_f = $o->toArray();
            // si c'est le joueur courant
            if ($j instanceof Aventurier && $o->getId() == $j->getId()) {
                $_f = array_merge($_f, array('joueur' => 1));
            }
            $_f = array_merge($_f, array('image' => 'images/creatures/' . $o->getIdcreature()->getId() . '.png'));
            $final[] = $_f;
        }

        foreach ($monstres as $o) {
            $_f = $o->toArray();
            $_f = array_merge($_f, array('image' => 'images/creatures/' . $o->getIdcreature()->getId() . '.png'));
            $_f = array_merge($_f, array('nom' => $o->getPrenom()));
            $final[] = $_f;
        }


        // images
        foreach ($final as $c => $v) {
            $final[$c]['image'] = file_exists('bundles/ndjdgame/' . $final[$c]['image']) ? 'bundles/ndjdgame/' . $final[$c]['image'] : 'null';
        }

        return new JsonResponse($final);
        */
    }

}
