<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use ndj\DGameBundle\Entity\Bestiaire;

use ndj\DGameBundle\Entity\Aventurier;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ndj\DGameBundle\Entity\Chatmsg;

/**
 * Chatmsg controller.
 *
 * @Route("/chatmsg")
 */
class ChatmsgController extends Controller
{
    
    /**
     * Reload the chat with time of the last message
     *
     * @Route("/reload/{lastmsg}", name="chatmsg_reload", options={"expose"=true})
     */
    public function reloadAction($lastmsg)
    {
    	if (!$this->get('gamesession')->check())
    		throw new AccessDeniedException('Aucune partie en cours !');
    		
    	$joueur = $this->get('gamesession')->get();
    	$mode = $this->get('gamesession')->getMode();
    	$typejoueur = $mode[0];
    	
    	$messages = $this->getDoctrine()->getManager()->getRepository('ndjDGameBundle:Chatmsg')->findAllWithTime($lastmsg,$joueur->getId(),$typejoueur);
    	
    	$evenements = $this->getDoctrine()->getManager()->getRepository('ndjDGameBundle:Evenement')->findBy( array(
    				'dest'=>$joueur->getId().':'.$typejoueur,
    				'lu'=>0
    			));
    	foreach($evenements as $e) {
    		$this->getDoctrine()->getManager()->persist($e->setLu(1));
    	}
    	$this->getDoctrine()->getManager()->flush();
    	
    	$connectes = $this->getDoctrine()->getManager()->getRepository('ndjDGameBundle:Connecte')->findById($joueur->getId().':'.$typejoueur);
    	
    	
    	return $this->render('ndjDGameBundle:Chatmsg:chatload.html.twig', array(
    			'messages'=>$messages,
    			'evenements'=>$evenements,
    			'connectes'=>$connectes,
    			'lastmsg'=>$lastmsg
    			));
    }
    
    /**
     * Send a command
     *
     * @Route("/send/{command}", name="chatmsg_send", options={"expose"=true})
     */
    public function sendAction($command)
    {
    	if (!$this->get('gamesession')->check())
    		throw new AccessDeniedException('Aucune partie en cours !');
    	
    	$cmd = $this->bindCommand($command);
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	if ($cmd['send'])
    	{
    		if (count($cmd['cssClass'])>0)
    		{
    			$cmd['msg'] = '<span class="'.implode(' ',$cmd['cssClass']).'">'.$cmd['msg'].'<span>';
    		}
    		
    		if (is_array($cmd['dest']))
    		{
    			$m = null;
    			foreach($cmd['dest'] as $d)
    			{
    				$m = new Chatmsg();
    				$m->setAuteur( $cmd['auteur'] );
    				$m->setDestinataire( $cmd['dest'] );
    				$m->setMtime( time() );
    				$m->setMessage( $cmd['msg'] );
    				 
    				if ($cmd['save'])
    					$em->persist($m);
    			}
    			$em->flush();
    			if (!$cmd['save'])
    			{
    				return $this->render('ndjDGameBundle:Chatmsg:chatmultiplelines.html.twig',array('message'=>$cmd['msg']));
    			}
    			else
    			{
    				return $this->render('ndjDGameBundle:GameCommon:blank.html.twig');
    			}
    		}
    		else
    		{
    			
    			$m = new Chatmsg();
    			$m->setAuteur( $cmd['auteur'] );
    			$m->setDestinataire( $cmd['dest'] );
    			$m->setMtime( time() );
    			$m->setMessage( $cmd['msg'] );
    			
    			if ($cmd['save']) {
    				$em->persist($m);
    				$em->flush();
    			} else {
    				return $this->render('ndjDGameBundle:Chatmsg:chatline.html.twig',array('message'=>$m));
    			}
    		}
    	}
    	else
    	{
    		return $this->render('ndjDGameBundle:Chatmsg:chatblankline.html.twig',array('message'=>$cmd['msg']));
    	}
    	
    	return $this->render('ndjDGameBundle:GameCommon:blank.html.twig');
    	
    }
    
    /**
     * Bind the chat command
     * 
     * @param string $command
     * @return mixed
     */
    private function bindCommand($command)
    {
    	$return = array(
    		'auteur'=>null,
    		'dest'=>null,
    		'msg'=>null,
    		'save'=>true,
    		'send'=>true,
    		
    		'cssClass'=>array()
    	);
    	
    	// récupération du joueur
    	
    	if (!$this->get('gamesession')->check())
    		throw new AccessDeniedException('Aucune partie en cours !');
    	
    	//$type = $this->get('session')->get('idtype');
    	$type = $this->get('gamesession')->getMode();
    	//$id = $this->get('session')->get('id'.$type);
    	$moi = $this->get('gamesession')->get();
    	$id = $moi->getId();
    	
    	$cmd = stripslashes(strip_tags(trim($command)));
    	
    	// commande systÃ¨me, commenÃ§ant par un double slash (//)
    	if ($cmd[0]=='/' && $cmd[1]=='/'  && false !== $this->get('security.context')->isGranted('ROLE_MODO'))
    	{
    		// type : //cmd params1 params2
    		$params = explode(' ', $cmd);
    		$_cmd = array_shift($params);
    		switch($_cmd)
    		{
    			case '//tuer' : case '//t' :
    				// tuer un joueur !
    				// #t NomDuJoueur	ou #t idJOUEUR
    				$a = new Aventurier($params[0]);
    				//$a->mort();
    				break;
    	
    			case '//tuer-creature' : case '//tc' :
    				// tuer une creature !
    				// //tc idBESTIAIRE
    				$b = new Bestiaire($params[0]);
    				//$b->mort();
    				break;
    	
    			case '//destruire-creature' : case '//dc' :
    				// dÃ©truire une creature !
    				// //dc idBESTIAIRE
    				break;
    	
    			case '//destruire-objet' : case '//do' :
    				// tuer une creature !
    				// //do idINVENTAIRE
    				break;
    	
    			case '//get' :
    				$_save = false;
    				$on = $params[0];
    				$o = new $on($params[1]);
    				$aut = 'system';
    				$msg = $on.'#'.$params[1].'.'.$params[2].' = '.$o->{$params[2]};
    					
    				break;
    					
    			case '//set' :
    				// modifier une caractÃ©ristique d'un Ã©lÃ©ment
    				/* exemples :
    				 * //set donjon 1 NOM = TAGADA
    				 */
    				$on = $params[0];
    				$o = new $on($params[1]);
    				$o->{$params[2]} = $params[3];
    				$o->MiseAJour();
    					
    				$_send = false;
    				$_save = false;
    				break;
    	
    			case '//mod-player' : case '//mp' :
    				// modifier une caractÃ©ristique d'un joueur
    				/* exemples :
    				 * //mp NomDuJoueur ACROBIATIE p 5
    				 * //mp idJOUEUR BAGARRE = 400
    				 * //mp NomDuJoueur ACROBIATIE m 5
    				 */
    				$a = new Aventurier(trim($params[0]));
    				switch(trim($params[2])) {
    					case 'p' : $a->{$params[1]} = $a->{$params[1]} + $params[3];
    					$a->MiseAJour();
    					break;
    					case '=' : $a->{$params[1]} = $params[3];
    					$a->MiseAJour();
    					break;
    					case 'm' : $a->{$params[1]} = $a->{$params[1]} - $params[3];
    					$a->MiseAJour();
    					break;
    					default :
    						$aut = 'system';
    						$msg = 'Modification impossible avec les paramÃ©tres '.implode(', ',$params).' !';
    						break;
    				}
    				$_send = false;
    				$_save = false;
    				break;
    	
    			case '//mod-objet' : case '//mo' :
    				// modifier une caractÃ©ristique d'un joueur
    				/* exemples :
    				 * //mo idINVENTAIRE DEGAT=2D10
    				 * //mo idINVENTAIRE QUALITE+400
    				 */
    				break;
    	
    			default :
    				$aut = 'system';
    				$msg = 'Commande "'.$_cmd.'" inconnue !';
    				break;
    		}
    	}
    	// commande joueur, commenÃ§ant par un slash (/)
    	elseif ($cmd[0]=='/')
    	{
    		// type : #cmd params1 params2
    		$params = explode(' ', $cmd);
    		$_cmd = array_shift($params);
    		switch($_cmd)
    		{
    			case '/cri' :
    				$return['cssClass'][]='bolder';
    				$return['auteur'] = $moi->getID().':'.$type[0];
    				$return['msg'] = implode(' ',$params);
    				break;
    	
    			case '/bouder' :  case '/bou' :
    				$return['cssClass'][]='bolder';
    				$return['auteur'] = $moi->getID().':'.$type[0];
    				$return['msg'] = 'boude...';
    				break;
    	
    			case '/chut' :
    				$cssClass[]='smaller2 o60';
    				$return['auteur'] = $moi->getID().':'.$type[0];
    				$return['msg'] = implode(' ',$params);
    				break;
    					
    			case '/help' : case '/h' :
    				$return['send'] = false;
    				$return['save'] = false;
    				$help = '@todo get Help';
    				$return['msg'] = <<<MSGHELP
<div id="chat-help" class="hide">$help</div>
<script>
//$(document).ready(function(){
	$("#chat-help").dialog({
		title:"Aide pour le dialogue en directe",
		width:650,
		maxWidth:650,
		closeOnEscape:true,
		height:400,
		maxHeight:400,
		close: function( event, ui ) {
			$(this).dialog("destroy");
			$("#chat-help").remove();
		}
	});
//});
</script>
MSGHELP;
    					break;
    					
    				case '/clear' :
    						$return['send'] = false; 	$return['save'] = false; 	$return['msg'] = '<script>$("#chat-mainwrapper .chat-line-system,#chat-mainwrapper .chat-line-public,#chat-mainwrapper .chat-line-prive").remove();</script>';
    					break;
    					
    				case '/clear-system' :
    						$return['send'] = false; 	$return['save'] = false; 	$return['msg'] = '<script>$("#chat-mainwrapper .chat-line-system").remove()</script>';
    					break;
    					
    				case '/clear-public' :
    						$return['send'] = false; 	$return['save'] = false; 	$return['msg'] = '<script>$("#chat-mainwrapper .chat-line-public").remove()</script>';
    					break;
    							
    				case '/clear-prive' :
    						$return['send'] = false; 	$return['save'] = false; 	$return['msg'] = '<script>$("#chat-mainwrapper .chat-line-prive").remove()</script>';
    					break;
    	
    				default :
	    					$return['auteur'] = 'system';
	    					$return['save'] = false;
	    					$return['msg'] = 'Commande "'.$_cmd.'" inconnue !';
    					break;
    			}
    		}
    		elseif (substr($cmd,0,7)=='@donjon' || substr($cmd,0,3)=='@dj')
    		{
    			if ($type!='aventurier' || $moi->getIdpiece()==null)
    			{
    				$return['auteur'] = 'system';
    				$return['save'] = false;
    				$return['msg'] = 'Commande <i>@donjon</i> impossible.';
    			}
    			else
    			{
    				$return['auteur'] = $id.':'.$type;
    				$return['dest'] = $moi->getPiece()->getEtage()->getIddonjon().':d';
    				if (substr($cmd,0,3)=='@dj') $i = 3;
    				else $i=7;
    				$return['msg'] = substr($cmd, $i);
    			}
    	}
    	elseif (substr($cmd,0,7)=='@aventurier' || substr($cmd,0,3)=='@av')
    	{
    		if ($type!='donjon')
    		{
    				$return['auteur'] = 'system';
    				$return['save'] = false;
    				$return['msg'] = 'Commande <i>@aventurier</i> impossible.';
    		}
    		else
    		{
    			$return['auteur'] = $id.':'.$type;
    			$liste = $this->getDoctrine()->getRepository('ndjDGameBundle:Aventurier')->findInDonjon($id);
    			$return['dest']=array();
    			foreach($liste as $a)
    			{
    				$return['dest'][] = $a->getId().':a';
    			}
    			if (substr($cmd,0,3)=='@av') $i = 3;
    			else $i=7;
    			$return['msg'] = substr($cmd, $i);
    		}
    	}
    	// message privÃ©
    	// @Nom Salut !
    	elseif ($cmd[0]=='@')
    	{
    		// on rÃ©cupÃ¨re le nom
    		$_quote_1 = strpos($cmd, '"');
    		$_quote_2 = strpos($cmd, '"', $_quote_1+1);
    			
    		if ($_quote_1===false || $_quote_2===false) {
    			$return['auteur'] = 'system';
    			$return['save'] = false;
    			$return['msg'] = 'Format requis : @"Nom du Joueur" Message...';
    		}
    		else
    		{
    			$name = substr($cmd, $_quote_1+1, $_quote_2-$_quote_1-1);
    			//echo "$name = ".membre::getPlayer()->getNOM().'<br>';
    			if (trim(strtolower($name)) == trim(strtolower($moi->getNom())))
    			{
    				$return['auteur'] = 'system';
    				$return['save'] = false;
    				$return['msg'] = 'Tu te parles à toi même...';
    			} else {
    				$reqA = $liste = $this->getDoctrine()->getRepository('ndjDGameBundle:Aventurier')->findOneByNom($name);
    				if (count($reqA)==1) {
    					$return['auteur'] = $id.':'.$type;
    					$return['dest'] = $reqA->getId().':a';
    					$return['msg'] = substr($cmd, $_quote_2+1);
    				} else {
    				$reqD = $liste = $this->getDoctrine()->getRepository('ndjDGameBundle:Donjon')->findOneByNom($name);
    					if(count($reqD)==1) {
    						$return['auteur'] = $id.':'.$type;
    						$return['dest'] = $reqD->getId().':d';
    						$return['msg'] = substr($cmd, $_quote_2+1);
    					} else {
    						$return['auteur'] = 'system';
    						$return['save'] = false;
    						$return['msg'] = '"'.$name.'" n\'existe pas !';
    					}
    				}
    			}
    		}
    	}
    	elseif($cmd=='')
    	{
    			
    	}
    	// message chat classic
    	// type : Salut toi !
    	else
    	{
    		$return['auteur'] = $id.':'.$type;
    		$return['msg'] = $cmd;
    	}
    	
    	
    	
    	return $return;
    }
   
}
