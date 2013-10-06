<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Etage;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/donjon")
 */
class DonjonController extends Controller
{
	
	/**
	 * Get fiche
	 *
	 * @Route("/displayfiche/{id}", name="donjon_displayfiche", options={"expose"=true})
	 */
	public function displayFicheAction(Donjon $donjon)
	{
		return $this->render('ndjDGameBundle:Donjon:donjon.publicfiche.html.twig',array('donjon'=>$donjon));
	}
	
	
	/**
	 * Get json pieces
	 *
	 * @Route("/loadPieces/{id}", name="donjon_loadpieces", options={"expose"=true})
	 */
	public function loadPiecesAction(Etage $e)
	{
		return new JsonResponse( Tools::toArray( $e->getPieces() ) );
	}
	
	/**
	 * MAJ l'état du donjon
	 *
	 * @Route("/setEtat", name="donjon_setetat", options={"expose"=true})
	 */
	public function setEtatAction()
	{
		$donjon = $this->get('gamesession')->getd();
		
			
		$form = $this->createFormBuilder($donjon)
			->add('etat', 'choice', array(
					'choices'   => array('close3' => 'Fermer (dans 3 tours)', 'close' => 'Fermer', 'open'=>'Ouvrir'),
					'required'  => true,
				))
			->getForm();
		
		if ($this->getRequest()->getMethod() == 'POST')
		{
			//$_form = $this->getRequest()->request->get('form');
			//$etat = $_form['etat'];
			
			$etat = $this->getRequest()->request->get('etat');
			$check = false;
			
			if ($etat == 'close3')
				$check = $donjon->setcloseDelay();
			elseif ($etat == 'open')
				$check = $donjon->setOpen();
			elseif ($etat == 'close')
				$check = $donjon->setClose();
			
			if ($check)
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($donjon);
				$em->flush();
				
				return new Response('1');
			}
			else
			{
				return new Response('0');
			}
		}
		else
		{
			return $this->render('ndjDGameBundle:Donjon:form.etat.html.twig', array(
					'donjon' => $donjon,
					'form' => $form->createView()
			));
		}
	}
	
	
	
	/**
	 * Création d'un donjon
	 * @Route("/creer", name="donjon_creation")
	 */
	public function creerAction()
	{
		if ( count($this->getUser()->getDonjons()) >= $this->getUser()->limitDonjon() &&
				!$this->get('security.context')->isGranted('ROLE_USER_UNLIMITED') )  {
			
				throw new AccessDeniedHttpException('Impossible de créer un nouveau donjon !');
			}
			
		$donjon = new Donjon();
		$form = $this->createFormBuilder($donjon)
			->add('nom')
			->add('description')
			->getForm();
		
		$request = $this->get('request');
		
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			
			$donjon->setIdmembre( $this->getUser() );
			$donjon->setDateCreation(new \DateTime() );
			$donjon->setEtat(1);
			
			if ($form->isValid()) {
				
				$em = $this->getDoctrine()->getManager();
				
				$em->persist($donjon);
				
				$donjon->setPact(0);
				$donjon->setRenommee(0);
				$donjon->setRenommeeMax(0);
				$donjon->setExperience(0);
				
				$etage = $request->request->get('ETAGE');
				
				$taille = 80 - ($etage-3) * 10;
				$donjon->setArgent( 2000 + (18000 - $etage * sqrt($taille))/10 ) ;
				
				//caracs::add('PO_IN', $dj->ARGENT, 'game');
				
				$start = ($etage>3) ?  -1 : 0;
				$end = $etage - $start;
				
				for($i=$start;$i<$end;$i++) {
					$e = new Etage();
					$e->setIddonjon($donjon);
					$e->setNom( Etage::getDefaultNom($i) );
					$e->setNiveau( $i );
					$e->setTAILLE( $taille );
					
					$em->persist($e);
				}
				
				$em->flush();
		
				return $this->redirect($this->generateUrl('default_home'));
			}
		}
		
		// À ce stade :
		// - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
		// - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
		
		return $this->render('ndjDGameBundle:Donjon:form.creer.html.twig', array(
				'donjon' => $donjon,
				'form' => $form->createView(),
		));
	}
	
}
