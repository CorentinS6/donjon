<?php

namespace ndj\DGameBundle\Controller;


use ndj\DGameBundle\Entity\Etage;

use ndj\DGameBundle\Entity\Piece;

use ndj\DGameBundle\Entity\Donjon;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Etage controller.
 *
 * @Route("/etage")
 */
class EtageController extends Controller
{	
	/**
	 * Add new etage
	 *
	 * @Route("/add/{taille}/{o}", name="etage_add", options={"expose"=true})
	 * @Method("POST")
	 */
	public function addAction($taille, $o)
	{
		$donjon = $this->get('gamesession')->getd();
	
		$cout = Etage::coutNouvelEtage((int)$taille);
			
		if (!$donjon->depense($cout))
		{
			return new Response("Tu n'as pas assez d'argent pour créer un nouvel étage !\nTu n'as que ".$this->donjon->getArgent()." PO...");
		}
		elseif (!$donjon->action())
		{
			return new Response('Pas assez de points d\'action !');
		}
		else
		{
			$niveau = $this->getDoctrine()->getRepository('ndjDGameBundle:Etage')->getNextNiveau($donjon, $o);
	
				
	
			// @todo caracs::add('PO_OUT', $cout, 'game');
				
			$etage = new Etage();
			$this->donjon->addEtage($etage);
			$etage->setNiveau($niveau['n']);
			$etage->setNom('Nouveau niveau');
			$etage->setTaille($taille);
	
			$em = $this->getDoctrine()->getManager();
			$em->persist($etage);
			$em->persist($donjon);
			$em->flush();
				
	
			return new Response('1:'.$etage->getId().':'.$etage->getNiveau().':'.$etage->getNom());
			// @todo : script dans evenement !
		}
	}
	
	/**
	 * Modifier nom etage
	 *
	 * @Route("/setNom/{id}/{nom}", name="etage_setnom", options={"expose"=true})
	 * @Method("POST")
	 */
	public function setNomAction(Etage $e, $nom)
	{
		$donjon = $this->get('gamesession')->getd();
		
		$e->setNom( strip_tags($nom) );
		$em = $this->getDoctrine()->getManager();
		$em->persist($e);
		$em->flush();
		return new Response('1:'.$e->getNom());
	}
	
	
	public function interfaceEditeurEtageAction($params)
	{
		$donjon = $this->get('gamesession')->getd();
		
		$etage = $this->getDoctrine()->getRepository('ndjDGameBundle:Etage')->find($params['e']);
		return $this->render('ndjDGameBundle:Etage:layer_editeur.etage.html.twig',array('etage'=>$etage));
	}
	
}
