<?php

namespace ndj\DGameBundle\Controller;

use ndj\DGameBundle\Entity\Bestiaire;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/bestiaire")
 */
class BestiaireController extends Controller
{
	/**
	 * Get fiche
	 *
	 * @Route("/displayfiche/{id}", name="bestiaire_displayfiche", options={"expose"=true})
	 */
	public function displayFicheAction(Bestiaire $bestiaire)
	{
		return $this->render('ndjDGameBundle:Bestiaire:bestiaire.publicfiche.html.twig',array('bestiaire'=>$bestiaire));
	}
	
	
	
	/**
	 *
	 * @Route("/bestiaireRepos/{id}", name="economat_bestiairerepos", options={"expose"=true})
	 */
	public function bestiaireReposAction(Bestiaire $i)
	{
		$donjon = $this->get('gamesession')->getd();
	
		if ($i->getOwner() !== $donjon)
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible !")</script>');
		}
		elseif(!$donjon->action())
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible, pas assez de points d\'action !")</script>');
		}
		else
		{
			$em = $this->getDoctrine()->getManager();
				
			if (!is_null($i->getIdpiece()))
			{
				$em->persist($i->getIdpiece());
				$i->getIdpiece()->removeBestiaire($i);
			}
				
			$i->setPOSITION(null);
			$i->setAVendre(0);
			$i->setRepos(1);
				
			$em->persist($donjon);
			$em->persist($i);
			$em->flush();
		}
	
		return $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire');
	}
	
	/**
	 *
	 * @Route("/bestiaireVendre/{id}", name="economat_bestiairevendre", options={"expose"=true})
	 */
	public function bestiaireVendreAction(Bestiaire $i)
	{
		$donjon = $this->get('gamesession')->getd();
	
		if ($i->getOwner() !== $donjon)
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible !")</script>');
		}
		elseif(!$donjon->action())
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible, pas assez de points d\'action !")</script>');
		}
		else
		{
			$em = $this->getDoctrine()->getManager();
				
			if (!is_null($i->getIdpiece()))
			{
				$em->persist($i->getIdpiece());
				$i->getIdpiece()->removeBestiaire($i);
			}
				
			$i->setPOSITION(null);
			$i->setAVendre(1);
			$i->setRepos(0);
				
				
			$em->persist($donjon);
			$em->persist($i);
			$em->flush();
		}
	
		return $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire');
	}
	
	/**
	 * @Route("/bestiaireSalleForm/{id}", name="economat_bestiairesalleform", options={"expose"=true})
	 */
	public function bestiaireSalleFormAction(Bestiaire $i)
	{
		$donjon = $this->get('gamesession')->getd();
	
		return $this->render('ndjDGameBundle:Bestiaire:economat.bestiaire.salle.form.html.twig',array(
				'donjon'=>$this->donjon,
				'bestiaire'=>$i
		));
	}
	
	/**
	 * @todo : vérifier case vide avant de placer
	 * @Route("/bestiaireSalle/{id}/{idpiece}/{x}/{y}", name="economat_bestiairesalle", options={"expose"=true})
	 */
	public function bestiaireSalleAction(Bestiaire $i, $idpiece, $x, $y)
	{
		$donjon = $this->get('gamesession')->getd();
	
		$p = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($idpiece);
	
		if ($i->getOwner() !== $donjon)
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible !")</script>');
		}
		elseif(!$p->caseExist($x,$y))
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible de place l\'objet \''.$i->getPRENOM().'\' en '.$x.','.$y.' dans \''.$p->getNom().'\'")</script>');
		}
		elseif(!$donjon->action())
		{
			$interface = $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire')->getContent();
			return new Response($interface.'<script>set_error("Impossible, pas assez de points d\'action !")</script>');
		}
		else
		{
			$p->addBestiaire($i);
			$i->setPOSITION('{'.$x.','.$y.'}');
			$i->setAVendre(0);
			$i->setRepos(0);
				
			$em = $this->getDoctrine()->getManager();
			$em->persist($donjon);
			$em->persist($i);
			$em->persist($p);
			$em->flush();
		}
	
		return $this->forward('ndjDGameBundle:GameDonjon:interfaceEconomatBestiaire');
	}
	
	
}
