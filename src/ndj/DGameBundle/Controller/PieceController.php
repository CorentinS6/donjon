<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ndj\DGameBundle\Entity\Piece;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Etage;

/**
 * Piece controller.
 *
 * @Route("/piece")
 */
class PieceController extends Controller
{	
    /**
     * Create an html selector
     *
     * @Route("/selector/{iddonjon}/{options}", name="piece_selector")
     */
    public function selectorAction(Donjon $iddonjon, $options='')
    {	
    	return $this->render('ndjDGameBundle:Piece:selector.html.twig',array('pieces'=>$iddonjon->getPieces(),'options'=>$options));
    }
    
    /**
     * Get piece information
     *
     * @Route("/getpieceinfo/{id}/{mode}", name="piece_getpieceinfo", options={"expose"=true})
     */
    public function getPieceInfoAction(Piece $p, $mode)
    {
    	$reponse = null;

    	if ($mode == 'json')
    		$reponse = new JsonResponse($p->toArray());
    	
    	return $reponse;
    }
    
    
    
    /**
     * Add new piece
     *
     * @Route("/addpiece/{id}/{posx}/{posy}/{taillex}/{tailley}", name="piece_add", options={"expose"=true})
     * @Method("POST")
     */
    public function addPieceAction(Etage $e, $posx,$posy,$taillex,$tailley)
    {
		$donjon = $this->get('gamesession')->getd();
    
    	$p = new Piece();
    
    	$p->setPosx($posx);
    	$p->setPosy($posy);
    	$p->setTaillex($taillex);
    	$p->setTailley($tailley);
    	$e->addPiece($p);
    
    	$somme = $p->getPrixCreation();
    
    	if ($p->checkTailleMini())
    	{
    		if ($somme > $donjon->getARGENT())
    		{
    			return new Response('Tu n\'as pas assez d\'argent pour créer une nouvelle salle (coÃ»t : '.$somme.'PO) !');
    		}
    		elseif($p->testCollisionEtage())
    		{
    			return new Response('Impossible de créer une salle ici ! (tu recouvres une salle existente)');
    		}
    		elseif(!$donjon->action())
    		{
    			return new Response('Tu n\'as pas assez de points d\'action !');
    		}
    		else
    		{
    			// @todo stats::add('PO_OUT', $somme, 'game');
    			$donjon->depense($somme);
    			$p->nouvellePiece($e);
    
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($p);
    			$em->persist($e);
    			$em->persist($donjon);
    			$em->flush();
    
    				
    			return new Response('Une nouvelle salle viens d\'être créée (coût : '.$somme.'PO) ! Doucle-cliquez dessus pour la modifier !');
    		}
    	}
    	else
    	{
    		return new Response('Une salle doit faire un minimum de 3x3 !');
    	}
    }
    
    
    public function interfaceEditeurPieceAction($params)
    {
		$donjon = $this->get('gamesession')->getd();
		
    	$piece = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($params['p']);
    	return $this->render('ndjDGameBundle:Piece:layer_editeur.piece.html.twig',array('piece'=>$piece));
    }
    
    
    
    /**
     *
     * @Route("/interfaceEditeurPieceActionLibs/{id}", name="piece_interfaceediteurpieceactionlibs", options={"expose"=true})
     */
    public function interfaceEditeurPieceActionLibsAction(Piece $p)
    {
		$donjon = $this->get('gamesession')->getd();
		
    	$a = $p->getActions();
    
    	return $this->render('ndjDGameBundle:Piece:layer_editeur.piece.actionslibs.html.twig',array(
    			'piece'=>$p,
    			'actionspossible'=>Piece::$_actions
    	));
    }
    
    
    /**
     * Modifier le nom d'une piece
     *
     * @Route("/setNom/{id}/{nom}", name="piece_setnom", options={"expose"=true})
     * @Method("POST")
     */
    public function setNomAction(Piece $piece, $nom)
    {
		$donjon = $this->get('gamesession')->getd();
		
    	$piece->setNom($nom);
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($piece);
    	$em->flush();
    	return new Response('1:'.$piece->getNom());
    }
    
    
    /**
     * Modifier la lumiere d'une piece
     *
     * @Route("/setEtat/{id}/{e}", name="piece_setetat", options={"expose"=true})
     * @Method("POST")
     */
    public function setEtatAction(Piece $piece, $e)
    {
		$donjon = $this->get('gamesession')->getd();
    
    	$up = true;
    	$msg = '';
    
    	switch($e) {
    		case '-3' : // fermeture prochaine
    			if ($piece->getIddonjon()->isOpen() && $piece->getIddonjon()->action() && $piece->isOpen()) {
    				$piece->setETAT(-3);
    				$msg='La salle va fermer dans 3 tours !';
    			}
    			else {
    				$up=false;
    				$msg = 'Impossible de choisir cette option !';
    			}
    			break;
    		case '1' : // en construction
    			if ($piece->getIddonjon()->isBuilding()) {
    				$piece->setETAT(1);
    				$msg='La salle est maintenant en construction !';
    			}
    			else { $up=false; $msg = 'Impossible de fermer la salle maintenant (choisissez "fermée dans 3 tours") !';
    			}
    			break;
    		case '2' : // ouverture
    			if ($piece->isBuilding()) {
    				$piece->setETAT(2); $msg='La salle est maintenant ouverte !';
    			}
    			else { $up=false; $msg = 'Pour ouvrir cette salle, il faut qu\'elle soit en construction !';
    			}
    			break;
    	}
    	if ($up)
    	{
    			
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($piece);
    		$em->flush();
    			
    		return new response('1:'.$msg);
    	} else {
    		return new response('0:'.$piece->getETAT().':'.$msg);
    	}
    }
    
    
    /**
     * Modifier la lumiere d'une piece
     *
     * @Route("/setLumiere/{id}/{l}", name="piece_setLumiere", options={"expose"=true})
     * @Method("POST")
     */
    public function setLumiereAction(Piece $p,$l)
    {
		$donjon = $this->get('gamesession')->getd();
		
    	$p->setLumiere($l);
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($p);
    	$em->flush();
    	return new Response('ok');
    }
    
    /**
     * Enregistrer la pièce
     *
     * @Route("/save/{id}/{sol}/{sol2}/{mobilier}", name="piece_save", options={"expose"=true})
     * @Method("POST")
     */
    public function saveAction(Piece $piece, $sol, $sol2='', $mobilier='')
    {
		$donjon = $this->get('gamesession')->getd();
    
    	//$piece = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($id);
    
    	if ($piece->getIddonjon() !== $donjon) {
    		return new Response('Echec critique !');
    	}
    
    	$piece->setCoucheSol($sol);
    	$piece->setCoucheMobilier($mobilier);
    
    	if ($piece->getCoucheMobilier()=='' || is_null($piece->getCoucheMobilier()))
    		$piece->setCoucheMobilier(' ');
    
    	$piece->setCoucheSol2($sol2);
    	if ($piece->getCoucheSol2()=='' || is_null($piece->getCoucheSol2()) )
    		$piece->setCoucheSol2(' ');
    
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($piece);
    	$em->flush();
    
    	return new Response('ok');
    }
    

    /**
     * Prévisualiser la pièce
     *
     * @Route("/apercu/{id}", name="piece_apercu", options={"expose"=true})
     */
    public function apercuAction(Piece $piece) {
        return $this->render('ndjDGameBundle:Piece:apercu.html.twig', array(
                    'piece' => $piece
        ));
    }

}
