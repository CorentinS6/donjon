<?php

namespace ndj\DAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$ctrl = array(
    			'Aide',
    			'Aventurier',
    			'Banque',
    			'Bestiaire',
    			'Chatmsg',
    			'Connecte',
    			'Creature',
    			'Donjon',
    			'Etage',
    			'Evenement',
    			'Inventaire',
    			'Objets',
    			'Organisation',
    			'Piece',
    			'Pouvoir',
    			'Relation',
    			'Stats',
    			'Talent',
    		);
        return $this->render('ndjDAdminBundle:Default:index.html.twig',array(
        		'ctrl'=>$ctrl
        	));
    }
}
