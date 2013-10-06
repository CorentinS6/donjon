<?php

namespace ndj\DGameBundle\Controller;

use ndj\DGameBundle\Entity\Creature;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/creature")
 */
class CreatureController extends Controller
{
	
	/**
	 * Get fiche
	 *
	 * @Route("/fiche/{id}", name="creature_fiche", options={"expose"=true})
	 */
	public function displayFicheAction(Creature $creature)
	{
		return $this->render('ndjDGameBundle:Creature:creature.publicfiche.html.twig',array('creature'=>$creature));
	}
}
