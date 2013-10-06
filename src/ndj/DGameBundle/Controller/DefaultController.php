<?php

namespace ndj\DGameBundle\Controller;


use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Creature;
use ndj\DGameBundle\Entity\caracs;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ndj\DGameBundle\Entity; 

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
	/**
	 * Affichage principale
 	 * @Route("/", name="default_home")
	 */
    public function indexAction()
    {
        return $this->render('ndjDGameBundle:Default:index.html.twig');
    }
    
    /**
     * tuto donjon
     * 
 	 * @Route("/donjon_ecole", name="default_donjon_ecole")
 	 * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ecoleDonjonAction()
    {
    	return $this->render('ndjDGameBundle:Default:ecoledonjon.html.twig');
    }
    
    
    /**
     * Chargement en session d'un donjon ou un aventurier
     * @param string $mode
     * @param integer $id
     * 
 	 * @Route("/load/{mode}/{id}", name="default_load")
 	 * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loadAction($mode, $id)
    {
    	$url = 'home';
    	
    	$url = $this->get('gamesession')->start($mode, $id);
    	
    	return $this->redirect($this->generateUrl($url));
    }
}
