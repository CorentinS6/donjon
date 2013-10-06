<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Commerce controller.
 *
 * @Route("/commerce")
 */
class CommerceController extends Controller
{
	
    /**
     * 
     *
     * @Route("/index", name="commerce_index", options={"expose"=true})
     */
    public function indexAction()
    {
		if (!$this->get('gamesession')->check())
		{
			throw new AccessDeniedException('Impossible car hors-jeu !');
		}
		$j = $this->get('gamesession')->get();
    	
    	$invrep = $this->getDoctrine()->getRepository('ndjDGameBundle:Inventaire');
    	$bestrep = $this->getDoctrine()->getRepository('ndjDGameBundle:Bestiaire');
    	
    	$liste_forge = $invrep->findCommerceBoutique(array('ARME','ARME DE JET','BOUCLIER','ARMURE'));
    	$liste_herbo = $invrep->findCommerceBoutique(array('POTION','OBJET MAGIQUE'));
    	$liste_bazar = $invrep->findCommerceBoutique(array('VETEMENT','PETIT MATERIEL','DENREE','LIVRE','MATERIEL CHASSE'));
    	 
    	$liste_monstre = $bestrep->findCommerceBoutique(); 
    	
    	$liste_occas_inv = $invrep->findCommerceOccasion();
    	$liste_occas_bes = $bestrep->findCommerceOccasion();
    	
    	$classe_array = explode("\\",get_class($j));
    	return $this->render('ndjDGameBundle:Commerce:index.html.twig',array(
    				'joueur'=>$j,
    				'type'=> strtolower(array_pop($classe_array)),
    				'listeforge' => $liste_forge,
    				'listeherbo' => $liste_herbo,
    				'listebazar' => $liste_bazar,
    				'listemonstre' => $liste_monstre,
    				'listeoccasion' => array_merge($liste_occas_inv, $liste_occas_bes)
    			));
    }

    
    
    
    
    
    /**
     * Description d'un objet du amrché
     * @Route("/description/{class}/{mode}/{type}/{button}/{id}", name="commerce_description", options={"expose"=true})
     */
    public function descriptionAction($class,$mode,$type,$button,$id)
    {
		if (!$this->get('gamesession')->check())
		{
			throw new AccessDeniedException('Impossible car hors-jeu !');
		}
		
		$j = $this->get('gamesession')->get();
		
    	$o = $this->getDoctrine()->getRepository('ndjDGameBundle:'.ucfirst($class))->find($id);
    	
    	switch($type)
    	{
    		case 'json' :
    			return new JsonResponse($o);
    			break;
    		case 'xml' :
    			return new Response('commerce->descrption()->XML : TODO');
    			break;
    
    		case 'fiche' :
    
    			return $this->render('ndjDGameBundle:'.ucfirst($class).':'.$class.'.commercefiche.html.twig',array(
    				'o'=>$o,
    				'joueur'=>$j,
    				'mode'=>$mode,
    				'type'=>$type,
    				'button'=>$button,
    				'id'=>$id
    			));
    
		    	break;
    	}
    }
    
    
    /**
     * Description d'un objet du amrché
     * @Route("/achat/{type}/{id}", name="commerce_achat", options={"expose"=true})
     */
    public function achatAction($id, $type)
    {
		if (!$this->get('gamesession')->check())
		{
			throw new AccessDeniedException('Impossible car hors-jeu !');
		}
		
		$j = $this->get('gamesession')->get();
    	
    	$em = $this->getDoctrine()->getManager();
    
    	$o = $this->getDoctrine()->getRepository('ndjDGameBundle:'.ucfirst($type))->find($id);
    	
    	// @TODO : occasion -> PO vers autre jouer et non dans le vide
    	if ($j->action() && $j->achat($o))
    	{
    		//$tps = ($o instanceof bestaire) ? 'BEST':'INV';
    		// @TODO stats::add($tps.'_TRANS', 1 , 'game');
    		// @TODO stats::add('PO_OUT', $o->getPrix() , 'game');
    		$em->persist($j);
    		$em->persist($o);
    		$em->flush();
    		return new Response('1');
    	}
    	else
    	{
    		return new Response('Ton achat a échoué !');
    	}
    }
    
    
}
