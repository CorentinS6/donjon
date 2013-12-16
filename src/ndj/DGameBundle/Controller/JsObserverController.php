<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use ndj\DGameBundle\Entity\Evenement;
use ndj\DGameBundle\Util\Tools;

/**
 * Js Observer Controller
 * 
 * @Route("/jso")
 */
class JsObserverController extends Controller {
    /**
     * Appels spéciaux pour certaines caractéristiques
     * @var array 
     */
    static private $_special_call = array(
        'xpbar' => 'xp_bar',
    );
    
    /**
     * Tableau des évenements perso à renvoyer au joueur
     * @var array
     */
    private $_for_me = array();
    
    
    protected function getData($o, $data) {
        if (in_array($data, self::$_special_call)) {
            return self::$_special_call[$data](func_get_args());
        }

        $method1 = 'display' . ucfirst(strtolower($data));
        $method = 'get' . ucfirst(strtolower($data));
        if (method_exists($o, $method1)) {
            $result = $o->$method1();
        } elseif (method_exists($o, $method)) {
            $result = $o->$method();
        } else {
            throw new ResourceNotFoundException('Attribut "' . $data . '" introuvable !');
        }

        return $result;
    }

    /**
     * Affiche une donnée d'un objet
     * @Route("/gd/{objet}/{id}/{data}", name="jso_getdata", options={"expose"=true})
     */
    public function getDataAction($object, $id, $data) {
        return new Response($this->getData(new $object($id), $data));
    }

    /**
     * GetDonnee
     * @Route("/gj/{data}", name="jso_getj", options={"expose"=true})
     */
    public function getDataJoueurAction($data) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }
        $j = $this->get('gamesession')->get();

        return new Response($this->getData($j, $data));
    }

    /**
     * Effectue une transaction
     * @Route("/t", defaults={"q" = null}, name="jso_transaction", options={"expose"=true})
     * @Route("/t/{q}", name="jso_transaction", options={"expose"=true})
     */
    public function transactionAction($q = null) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }
        $j = $this->get('gamesession')->get();
        $key = $this->get('gamesession')->getKey();
        
        // mise à jour dans la liste des connectés
        $this->get('gamesession')->setConnected();
        
        // récupération des evenements à envoyer au joueur        
        $event = $this->getDoctrine()->getRepository('ndjDGameBundle:Evenement')->findBy(array(
            'dest' => $key,
            'cat' => 4,
        ));
        // var_dump($event);
        if (count($event) > 0) {
            $em = $this->getDoctrine()->getManager();

            foreach ($event as $e) {
                $this->_for_me [] = json_decode($e->getContent());
                $em->remove($e);
            }
            $em->flush();
        }
        
        // traitement de la file
        if (!is_null($q)) {
            $q = json_decode($q);
            foreach($q as $obj) {
                $this->dispatch($obj);
            }
        }

        return new JsonResponse($this->_for_me);
    }
    

    /**
     * Dispatche un nouvel evenement
     * @Route("/d/{e}", name="jso_dispatch", options={"expose"=true})
     */
    public function dispatchAction($e) {

        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }
        $j = $this->get('gamesession')->get();
        
        return new JsonResponse(' @todo ');
    }
    
    /**
     * Dispatch un evenement $o {call:string, data:JsonString}
     * @param Object $o
     * @throws AccessDeniedException
     */
    protected function dispatch($o) {
        if (!$this->get('gamesession')->check()) {
            throw new AccessDeniedException('Impossible car hors-jeu !');
        }
        $j = $this->get('gamesession')->get();
        $mykey = $this->get('gamesession')->getKey();
        
        // formatage et récupération des informations de l'evenement
        list($code_objet, $code_id, $code_ns, $data) = $this->formatEvent($o);

        // récupération des destinataires
        // liste des destinataires de l'evenement
        $destinataires = array();
        
        // récuépration entité nécessaire au traitement, si besoin
        if ($code_objet == 'aventurier' || $code_objet == 'donjon' || $code_objet == 'inventaire' || $code_objet == 'bestiaire' || $code_objet == 'piece') {
            $entity = $this->getDoctrine()->getRepository('ndjDGameBundle:' . ucfirst($code_objet))->find($code_id);
            if (is_null($data)) {
                $this->hydrateEvent($o, $entity);
            }
        }
        if ($code_objet == 'aventurier' || $code_objet == 'donjon' || $code_objet == 'inventaire' || $code_objet == 'bestiaire') {
            if ($code_objet != 'donjon' && !is_null($entity->getIdpiece())) {
                $destinataires = $this->getDoctrine()->getRepository('ndjDGameBundle:Aventurier')->findByIdpiece($entity->getIdpiece()->getId());
                if (!is_null($entity->getIddonjon())) {
                    $destinataires [] = $entity->getIddonjon();
                }
            }
            
        } elseif ($code_objet == 'chat') {
            
        } elseif ($code_objet == 'piece') {
            $destinataires = Tools::collectionToArray($entity->getAventuriers());
            $destinataires[] = $entity->getIddonjon();
        }
        
        $base_event = $this->buildEvenement($o->code, $o->data);
        
        $em = $this->getDoctrine()->getManager();
        foreach($destinataires as $d) {
            $destkey = $this->get('gamesession')->getKeyFromObject($d);
            if ($destkey === $mykey) { // evite une ecriture dans la bdd
                $this->_for_me [] = json_decode($base_event->getContent());
            } else {
                $e = clone $base_event;
                $e->setDest($destkey);
                // $retour[] = $e->getContent();
                $em->persist($e);
            }
        }
        $em->flush();
    }
    
    protected function hydrateEvent(& $o, $entity) {
        if (is_null($o->data)) {
            try {
                $ev_ns = explode('.', $o->code);
                $o->data = $this->getData($entity, array_pop($ev_ns));
            } catch (ResourceNotFoundException $e) {
                
            }
        }
    }
    
    /**
     * Format un evenement
     * @param Object$o
     * @return array
     */
    protected function formatEvent(& $o) {
        $j = $this->get('gamesession')->get();
        $r = array(
          0 => null,
          1 => null,
          2 => null,
          3 => null,
        );
        // on créee data si inexistante
        if (!property_exists($o, 'data')) {
            $o->data = null;
        }
        $r[3] = $o->data;
        // on format l'evenement 
        $code = explode('.', $o->code);
        
        if (isset($code[1])) $r[2] = $code[1];
        
        $event = &$code[0];
        // traduction du code (ex: aventurier.carac en aventurier_#.carac)
        if ($event == 'aventurier' || $event=='donjon') {
            $event = $event.'_'.$j->getId();
            // $o->code = $code[0].(isset($code[1])?'.'.$code[1]:'');
        }
        $o->code = implode('.', $code);
        
        if (count($code) == 2) {
            $r[2] = $code[1];
        }
        $e = explode('_', $event);
        if (count($e) == 2) {
            list($r[0], $r[1]) = $e;
        } else {
            $r[0] = $event;
        }

        return $r;
        
    }
    
    protected function buildEvenement($ev, $data=null) {
        $e = new Evenement();
        return $e->setCat(4)
                ->setContent(json_encode(array(
                        "code" => $ev,
                        "data" => $data
                    )))
                ->setLu(0)
                ->setDt(new \DateTime());
        
    }

}
