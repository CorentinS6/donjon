<?php

namespace ndj\DGameBundle\Controller;



use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use ndj\DGameBundle\Entity\Piece;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Etage;
use ndj\DGameBundle\Entity\Tools;

/**
 * 
 * @Route("/actions")
 */
class ActionsController extends Controller
{
	
	/**
	 * Enregistrer les actions d'une piece
	 *
	 * @Route("/saveActions/{id}/{actions}", name="piece_saveactions", options={"expose"=true})
	 * @Method("GET")
	 */
	public function saveActionsAction(Piece $piece, $actions)
	{
		$donjon = $this->get('gamesession')->getd();
		
		// action sous la forme A_a_a-x_y-x_y-x_y...
		$action = explode('-', $actions);
		$a = array_shift($action);
		$a = explode('_',$a);
		array_unshift($a,0,0);
		$args = $a;
		
		$em = $this->getDoctrine()->getManager();
		
		while(count($action)>0)
		{
			$coord = array_shift($action);
			if (is_null($coord) || strlen($coord)==0) continue;
			$coord = explode('_',$coord);
			$args[0] = $coord[0];
			$args[1] = $coord[1];
	
	
			try {
				$m = call_user_method_array('setAction',$piece,$args);
				foreach($m as $o) $em->persist($o);
			} catch (\Exception $e) {
				return new Response(
						'<p>'.$e->getMessage().'</p>'.
						'<p><a href="javascript:void(0);" onclick="loadInLayer(\''.$this->generateUrl('piece_interfaceediteurpieceactionlibs',array('id'=>$piece->getId())).'\',\'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6\')">Retour</a></p>'
				);
			}
		}
		 
		$em->flush();
		 
		return $this->forward('ndjDGameBundle:Piece:interfaceEditeurPieceActionLibs',array('id'=>$piece->getId()));
		 
	}
	
	/**
	 * @Route("/actionForm/{id}/{type}/{x}/{y}", name="piece_actionform", options={"expose"=true})
	 */
	public function actionFormAction(Piece $p, $type=null, $x=0, $y=0)
	{
		$donjon = $this->get('gamesession')->getd();
		
		return $this->render('ndjDGameBundle:Piece:action.new.form.html.twig',array(
				'piece'=>$p,
				'type'=>$type,
				'x'=>$x,
				'y'=>$y
		));
	}
        	
	/**
	 * @Route("/actionFormValid/{id}/{x}/{y}/{a}", name="piece_actionformvalid", options={"expose"=true})
	 */
	public function actionFormValidAction(Piece $piece, $x,$y,$a)
	{
		$donjon = $this->get('gamesession')->getd();
		 
		$_a = $this->getRequest()->query;
		 
		$args = array( 'x'=>$x,'y'=>$y,'a'=>$a );
		foreach($_a as $c=>$v)
			$args[$c] = $v;
		 
		$em = $this->getDoctrine()->getManager();
		 
		if ($args['a']=='PO')
		{
			$em->persist($donjon);
			$donjon->depense($args['somme']);
		}
		 
		try {
			$m = call_user_method_array('setAction',$piece,$args);
			foreach($m as $o) $em->persist($o);
		} catch (\Exception $e) {
			return new Response('<p> Impossible d\'ajouter la nouvelle action : '.$e->getMessage().'</p>');
		}
		 
		$em->flush();
		 
		return new Response('1');
	}
	
	
	
	/**
	 * @Route("/unsetAction/{id}/{x}/{y}/{a}", name="piece_unsetaction", options={"expose"=true})
	 * @todo supression d'un escalier = supprression de l'escalier correspondant dans le sens opposé
	 */
	public function unsetActionAction(Piece $piece, $x,$y,$a)
	{
		$donjon = $this->get('gamesession')->getd();
		 
		$_a = $this->getRequest()->query;
		 
		$args = array( 'x'=>$x,'y'=>$y,'a'=>$a );
		foreach($_a as $c=>$v)
			$args[$c] = $v;
		 
		 
		$em = $this->getDoctrine()->getManager();
		$_a = $piece->getAction($x,$y,$a);
		 
		try {
			if (!is_null($_a) && ($res = $piece->unsetAAction($_a))) {
				if ($res && $args['a']=='PO') {
					$donjon->recette($_a[3]);
					$em->persist($donjon);
				}
		   
				$em->persist($piece);
			} else {
				throw new \Exception('Echec ('.var_export($res, true).')');
			}
		} catch (\Exception $e) {
			return new Response(
					'<p>Impossible de supprimer cette action : </p>'.
					'<p><a href="javascript:void(0);" onclick="loadInLayer(\''.
					$this->generateUrl('piece_interfaceediteurpieceactionlibs',array('id'=>$piece->getId()))
					.'\',\'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6\')">Retour</a></p>'
			);
		}
		 
		$em->flush();
		 
		return new Response('<script>	map.build(); loadInLayer(\''.$this->generateUrl('piece_interfaceediteurpieceactionlibs',array('id'=>$piece->getId())).'\',\'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6\')</script>');
	}
	
	
		
	
	
	
	/**
	 * Ramasser l'or au sol
	 *
	 * @Route("/ramasseror/{idpiece}/{x}/{y}/{or}", name="economat_ramasseror", options={"expose"=true})
	 */
	public function ramasserOrAction($idpiece,$x,$y,$or)
	{
		$donjon = $this->get('gamesession')->getd();
	
		$m = 'Erreur';
		if ($donjon->action())
		{
			$em = $this->getDoctrine()->getManager();
			 
			$p = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($idpiece);
			 
			$p->setActions( str_replace('{'.$x.','.$y.',PO,'.$or.'}','',$p->getActions()) );
			if ($p->getActions()=='') $p->setActions(' ');
	
			$donjon->recette($or);
			 
			$em->persist($p);
			$em->persist($donjon);
			 
			$em->flush();
			$m = '';
		}
		return new Response($m);
	}
	
	/**
	 * Deposer de l'or au sol
	 *
	 * @Route("/deposeror/{idpiece}/{x}/{y}/{or}", name="economat_deposeror", options={"expose"=true})
	 */
	public function deposerOrAction($idpiece,$x,$y,$or)
	{
		$donjon = $this->get('gamesession')->getd();
	
		$m = '';
		if (is_numeric($or) && $donjon->depense($or))
		{
			$em = $this->getDoctrine()->getManager();
			$p = $this->getDoctrine()->getRepository('ndjDGameBundle:Piece')->find($idpiece);
			 
			// au dela des limites de la piece ?
			if (!$p->caseExist($x,$y))
			{
				$m = 'Impossible de déposer ici (au delà des limites de la salle) !';
			}
			elseif(!$donjon->action())
			{
				$m = 'Pas assez de points d\'action';
			}
			else
			{
				$p->setAction($x,$y,'PO',$or);
	
				$em->persist($p);
				$em->persist($donjon);
	
				$em->flush();
			}
		}
		else
		{
			$m = 'Tu ne peux pas déposer '.$or.' PO !';
		}
		return new Response($m);
	}
	
	
	/**
	 * load actions d'une piece
	 * @Route("/mapLoadActions/{id}", name="gamecommon_maploadactions", options={"expose"=true})
	 */
	public function mapLoadActionsAction(Piece $p)
	{
		if (!$this->get('gamesession')->check())
		{
			throw new AccessDeniedException('Impossible car hors-jeu !');
		}
		
		return new JsonResponse(Tools::explodex('}{',$p->getActions()));
	}
	
}
