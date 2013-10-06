<?php

namespace ndj\DGameBundle\Controller;


use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Entity\Etage;
use ndj\DGameBundle\Entity\Piece;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Evenement;
use ndj\DGameBundle\Entity\Inventaire;
use ndj\DGameBundle\Entity\Bestiaire;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ndj\DGameBundle\Form\EvenementType;

/**
 * GameDonjon controller.
 *
 * @Route("/game_donjon")
 */
class GameDonjonController extends Controller
{
	/**
	 * @Route("/", name="game_donjon")
	 * @Template()
	 */
	public function indexAction()
	{
		$donjon = $this->get('gamesession')->getd();
		
		return $this->render('ndjDGameBundle:GameDonjon:index.html.twig',array('donjon' => $donjon));
	}
	
	
	/**
	 * Get interface of a given tab
	 *
	 * @Route("/getinterface/{interface}", name="gamedonjon_getinterface", options={"expose"=true})
	 */
	public function getInterfaceAction($interface)
	{
		$donjon = $this->get('gamesession')->getd();
		
		$method_name = 'interface'.$interface.'Action';
		//$params = $this->getRequest()->request->all();
		$params = $this->getRequest()->query->all();
		if (method_exists($this, $method_name))
		{
			return $this->$method_name($params);
		}
		else
		{
			throw new \Exception('Unable to find the "'.$interface.'" interface !');
		}
	}
	
	/**
	 * Appel de l'interface de l'éditeur demandé (donjon, étage ou piece)
	 */
	public function interfaceEditeurAction($params)
	{
		$donjon = $this->get('gamesession')->getd();
		
		if (isset($params['e']))
		{
			return $this->forward('ndjDGameBundle:Etage:interfaceEditeurEtage',array('params'=>$params));
			// return $this->interfaceEditeurEtageAction($params);
		}
		elseif (isset($params['p']))
		{
			//return $this->interfaceEditeurPieceAction($params);
			return $this->forward('ndjDGameBundle:Piece:interfaceEditeurPiece',array('params'=>$params));
		}
		else 
		{
			return $this->interfaceEditeurDonjonAction($params);
		}
	}
	
	
	public function interfaceEditeurDonjonAction($params)
	{
		$donjon = $this->get('gamesession')->getd();
		
		$etages = $this->getDoctrine()->getRepository('ndjDGameBundle:Etage')->findBy(array('iddonjon'=>$donjon->getId()),array('niveau'=>'DESC'));
		return $this->render('ndjDGameBundle:Donjon:layer_editeur.donjon.html.twig',array('etages'=>$etages));
	}
	
	
	public function interfaceEconomatAction($params)
	{
		$donjon = $this->get('gamesession')->getd();
		
		$content ='';
		$p = isset($params['p'])?$params['p']:'preview';
		return $this->render('ndjDGameBundle:GameDonjon:layer_economat.html.twig',array('content'=>$content,'p'=>$p));
	}
	
	/**
	 * 
	 */
	public function interfaceYeuxAction($params)
	{
		$donjon = $this->get('gamesession')->getd();
		
		return $this->render('ndjDGameBundle:GameDonjon:layer_yeux.html.twig',array('donjon'=>$donjon));
	}
	/**
	 * 
	 */
	public function interfaceBureauAction($params)
	{
		$donjon = $this->get('gamesession')->getd();
		
		$aventurier_in = $this->getDoctrine()->getRepository('ndjDGameBundle:Aventurier')->findInDonjon($donjon->getId());
		
		$creature_badway = $this->getDoctrine()->getRepository('ndjDGameBundle:Bestiaire')->findBy(
				array(
						'repos'=>0,
						'aVendre'=>0,
						'iddonjon'	=> $donjon->getId()
				),
				array('pvie'=>'desc'),
				10,
				0
		);
		
		$evenements = $this->getDoctrine()->getRepository('ndjDGameBundle:Evenement')->findListByPlayer($donjon->getId().':d');
		
		
		return $this->render('ndjDGameBundle:GameDonjon:layer_bureau.html.twig',array(
				'donjon' => $donjon,
				'aventuriers_in' =>$aventurier_in,
				'creature_badway'=>$creature_badway,
				'evenements'=>$evenements
		));
	}
	
	
	
	
	/**
	 * Get interface of a ressource
	 *
	 * @Route("/getEconomatInterface/{interface}", name="economat_getinterface", options={"expose"=true})
	 */
	public function getEconomatInterfaceAction($interface)
	{
		$method_name = 'interfaceEconomat'.$interface.'Action';
		if (method_exists($this, $method_name))
		{
			return $this->$method_name();
		}
		else
		{
			throw new \Exception('Unable to find the "'.$interface.'" interface !');
		}
	}
	
	/**
	 * Preview ressources
	 *
	 * @Route("/economatPreview", name="economat_preview", options={"expose"=true})
	 */
	public function interfaceEconomatPreviewAction()
	{
		$donjon = $this->get('gamesession')->getd();
	
		$eco = array();
	
	
		$pieces = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->findByIdDonjon($donjon->getId());
	
		$eco['or_in'] = $donjon->getARGENT();
		$eco['or_out'] = 0;
		foreach($pieces as $p)
		{
			$tmp = Tools::explodex('}{',$p->getActions());
			foreach($tmp as $_a) {
				if(strlen($_a)>0) {
					$_a = explode(',', $_a);
					if ($_a[2]=='PO') {
						$eco['or_out'] += $_a[3];
					}
				}
			}
		}
		$eco['or_sum'] = $eco['or_in'] + $eco['or_out'] ;
	
		// Objets
		$inventaire = $this->getDoctrine()->getRepository('ndjDGameBundle:Inventaire')->findByIdDonjon($donjon->getId());
	
		$eco['inv_in'] = $eco['inv_out'] = 0;
		$eco['inv_cpt_in'] = $eco['inv_cpt_out']  = 0;
		foreach($inventaire as $i)
		{
			if ($i->getPOSITION()=='{I}' || $i->getPOSITION()=='{V}' || $i->getPOSITION()=='')
			{
				$eco['inv_in'] += $i->calculerPrix();
				$eco['inv_cpt_in']++;
			}
			else
			{
				$eco['inv_out'] += $i->calculerPrix();
				$eco['inv_cpt_out']++;
			}
		}
		$eco['inv_sum'] = $eco['inv_in']+$eco['inv_out'];
		$eco['inv_cpt_sum'] = $eco['inv_cpt_in']+$eco['inv_cpt_out'];
	
		// Creatures
		$bestiaire = $this->getDoctrine()->getRepository('ndjDGameBundle:Bestiaire')->findByIdDonjon($donjon->getId());
	
		$eco['bes_in'] = $eco['bes_out'] = 0;
		$eco['bes_cpt_in'] = $eco['bes_cpt_out']  = 0;
		foreach($bestiaire as $b)
		{
			if ($b->getRepos()==1)
			{
				$eco['bes_in'] += $b->calculerPrix();
				$eco['bes_cpt_in']++;
			}
			else
			{
				$eco['bes_out'] += $b->calculerPrix();
				$eco['bes_cpt_out']++;
			}
		}
		$eco['bes_sum'] = $eco['bes_in']+$eco['bes_out'];
		$eco['bes_cpt_sum'] = $eco['bes_cpt_in']+$eco['bes_cpt_out'];
	
		$eco['sum_in'] = $eco['or_in'] + $eco['inv_in'] + $eco['bes_in'];
		$eco['sum_out'] = $eco['or_out'] + $eco['inv_out'] + $eco['bes_out'];
		$eco['total'] = $eco['sum_in'] + $eco['sum_out'];
	
		$eco['val_mur'] = $donjon->getValeurMurs();
		$eco['big_total'] = $eco['total'] + $eco['val_mur'];
	
		$sal=0;
		foreach($bestiaire as $b) $sal+=$b->getCout();
		$eco['salaires'] = $sal;
	
		/*
			$val_mur = 	etage::coutNouvelEtage(60) 	* $this->dj->getNbEtage()
		+ 	$this->dj->getSuperficie()		* piece::PRIX_CREATION_FACTOR ;
	
		$big_total = $total + $val_mur;
	
		$sql = 'SELECT SUM(COUT) FROM BESTIAIRE WHERE idDONJON='.$this->dj->idDONJON;
		$res = db::get()->query($sql)->fetch(PDO::FETCH_NUM);
		$salaires = $res[0];
	
		$salaires = caracs::display_or($salaires);
		*/
	
	
		return $this->render('ndjDGameBundle:GameDonjon:economat.preview.html.twig',array(
				'economat' => $eco,
		));
	}
	
	/**
	 * Or ressources
	 *
	 * @Route("/economatOr", name="economat_or")
	 */
	public function interfaceEconomatOrAction()
	{
		$donjon = $this->get('gamesession')->getd();
	
		$pieces = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->findByIdDonjon($donjon->getId());
	
		$or_in = $donjon->getARGENT();
		$or_out = 0;
		$salles = array();
	
		foreach($pieces as $p)
		{
			$tmp = Tools::explodex('}{', $p->getActions());
			foreach($tmp as $a) {
				if(strlen($a)>0) {
					$_a = explode(',',$a);
					if ($_a[2]=='PO') {
						$salles[] = array(
								'piece' => $p,
								'x'=>$_a[0],
								'y'=>$_a[1],
								'or'=> $_a[3]
						);
						$or_out += $_a[3];
					}
				}
			}
		}
		return $this->render('ndjDGameBundle:Piece:economat.or.html.twig',array(
				'pieces'=>$pieces,
				'pieces_or'=>$salles,
				'or_in'=>$or_in,
				'or_out'=>$or_out
		));
	}
	
	/**
	 * Inventaire
	 *
	 * @Route("/economatInventaire", name="economat_inventaire")
	 */
	public function interfaceEconomatInventaireAction()
	{
		$donjon = $this->get('gamesession')->getd();
	
		$inventaire = $donjon->getInventaires();
		return $this->render('ndjDGameBundle:Inventaire:economat.inventaire.html.twig',array(
				'inventaire'=>$inventaire
		));
	
	}
	
	/**
	 * Bestiaire
	 *
	 * @Route("/economatBestiaire", name="economat_bestiaire")
	 */
	public function interfaceEconomatBestiaireAction()
	{
		$donjon = $this->get('gamesession')->getd();
	
		$bestiaire = $donjon->getBestiaires();
		return $this->render('ndjDGameBundle:Bestiaire:economat.bestiaire.html.twig',array(
				'bestiaire'=>$bestiaire
		));
	}
	
	/**
	 * Or ressources
	 *
	 * @Route("/economatMarche", name="economat_marche")
	 */
	public function interfaceEconomatMarcheAction()
	{
		$donjon = $this->get('gamesession')->getd();
	
		return $this->forward('ndjDGameBundle:Commerce:index');
		//commerce_index
		//return $this->render('ndjDGameBundle:Commerce:economat.marche.html.twig');
	}
}