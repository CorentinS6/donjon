<?php

namespace ndj\DGameBundle\Controller;

use ndj\DGameBundle\Entity\Evenement;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Evenement controller
 * 
 * @Route("/evenement")
 */
class EvenementController extends Controller
{
	/**
	 * 
	 * @Route("/listing/{user}/{offset}", name="evenement_listing", options={"expose"=true})
	 */
	public function listingAction($user, $offset)
	{
		$e = $this->getDoctrine()->getRepository('ndjDGameBundle:Evenement')->findListByPlayer($user,$offset);
		return $this->render('ndjDGameBundle:Evenement:listing.html.twig', array('evenements'=>$e));
	}
}
