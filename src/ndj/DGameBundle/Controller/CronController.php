<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 *
 * @author corentin
 *         @Route("/cron")
 */
class CronController extends Controller {
	
	/**
	 * Tâche CRON
	 * @Route("/cron/{hash}", name="gamecommon_cron", options={"expose"=true})
	 */
	public function cronAction($hash) {
		
		if (isset ( $_GET ['hash'] ) || true) {
			// $hash = $_GET['hash'];
			$hash = 1;
			// $_check = md5(self::CRON_KEY);
			$_check = self::CRON_KEY;
			if ($hash == $_check || true) {
				$sql = array ();
				echo "<hr /><i>" . date ( "Y-m-d H:m:s" ) . "</i>";
				$tour = (date ( 'H' ) == '20');
				
				// CRON TOUR
				if ($tour) {
					echo "<p><b>TOUR :</b></p>";
					// rajouter PA/PC/AGE aux joueurs / donjon
					$sql [] = 'UPDATE AVENTURIER SET AGE=AGE+1,PA=LEAST(PA+' . caracs::PA_TOUR . ',' . caracs::PA_MAX . '),PD=LEAST(PD+' . caracs::PD_TOUR . ',' . caracs::PD_MAX . ') WHERE ETAT=1';
					// rajouter de l'age au bestaire
					$sql [] = 'UPDATE BESTIAIRE SET AGE=AGE+1';
					// rajouter de l'age aux objets
					$sql [] = 'UPDATE INVENTAIRE SET AGE=AGE+1';
					// rajouter des PA/PC aux donjons
					$sql [] = 'UPDATE DONJON SET PA=LEAST(PA+' . caracs::PA_TOUR . ',' . caracs::PA_MAX . '),PC=LEAST(PC+' . caracs::PC_TOUR . ',' . caracs::PC_MAX . ')'; // WHERE
					                                                                                                                                      // ETAT=1';
					                                                                                                                                      
					// dépense entretien Donjon
					$sql [] = 'UPDATE DONJON D SET D.ARGENT=D.ARGENT-(SELECT SUM(B.COUT) FROM BESTIAIRE B WHERE B.idDONJON=D.idDONJON)';
					
					// parse ENVOUTEMENTs (aventurier, bestiaire)
					
					// RAZ des Déclencheur
					
					// fermeture / ouverture piece/donjon
					
					// action différé groupes
				
				}
				
				// CRON MAINTENANCE (chaque heure)
				echo "<p><b>MAINTENANCE (/heure) :</b></p>";
				
				// générer bestiaire/inventaire aléatoire et suppression du
				// marche de quelques inventaires/bestaires
				// self::cron_generateInventaire();
				
				// déplacement ordre bestiaire
				
				// clear connecte (30s)
				$sql [] = 'DELETE FROM CONNECTE WHERE ' . time () . '-lasttime>30';
				// clear chat (2h)
				$sql [] = 'DELETE FROM CHATMSG WHERE ' . time () . '-time>' . (3600 * 2);
				// évènement à nettoyer (30j)
				$sql [] = 'DELETE FROM EVENEMENT WHERE `READ`=1 AND DATEDIFF(NOW(),DT)>30';
				
				// up MANA aventurier
				$sql [] = 'UPDATE AVENTURIER SET MANA=LEAST(MANA+1,MANA_MAX) WHERE ETAT=1';
				// évènement programmé
				
				// var_dump($sql);
				// do it SQL querys !
				
				echo "<p><b>Traitement SQL :</b></p>";
				foreach ( $sql as $s ) {
					echo "<p> Requete [{$s}] ";
					$res = db::get ()->query ( $s );
					echo $res->rowCount ();
					echo "</p>";
				}
			} else {
				$this->out .= '<p>Bad way !</p>';
			}
		}
	}
	
	static public function cron_generateInventaire() {
		$cpta = db::get ()->query ( 'SELECT COUNT(idAVENTURIER) FROM AVENTURIER' )->fetch ( PDO::FETCH_NUM );
		$cptd = db::get ()->query ( 'SELECT COUNT(idDONJON) FROM DONJON' )->fetch ( PDO::FETCH_NUM );
		
		$cpt = $cpta [0] + $cptd [0];
		
		$plafond = $cpt * 20;
		
		$cpt = $cpt * 2;
		
		// on ajoute aux hasards
		while ( $cpt -- ) {
			
			// $base = db::get()->fetchAllInto('SELECT * FROM OBJETS WHERE
			// FREQUENCE>2','objets');
			// $o = $base[array_rand($base)];
			
			$r = rand ( 3, 5 );
			$obj = db::get ()->query ( 'SELECT idOBJETS FROM OBJETS WHERE FREQUENCE>' . $r . ' ORDER BY RAND() LIMIT 1' )->fetch ( PDO::FETCH_NUM );
			
			if ($obj === false) {
				$cpt ++;
				continue;
			}
			
			$o = new objets ( $obj [0] );
			$i = new inventaire ();
			$i->idOBJETS = $o->idOBJETS;
			$i->idDONJON = null;
			$i->idAVENTURIER = null;
			$i->idBESTIAIRE = null;
			$i->idCOMPTE = null;
			$i->NOM = $o->NOM;
			$i->AGE = rand ( 0, 10 );
			$i->BONUS = $o->BONUS;
			$i->USURE = rand ( 1, 5 );
			$i->QUALITE = (rand ( 0, 5 ) == 1) ? 2 : 1;
			$i->POSITION = 0;
			$i->ENVOUTEMENT = '';
			$i->miseAJour ();
			
			// bonus arme
			if ($o->CAT != 'ARME' && rand ( 1, 3 ) == 1)
				$cpt ++;
		}
		
		// plafond
		$reqt = db::get ()->query ( 'SELECT COUNT(idINVENTAIRE) FROM INVENTAIRE I, OBJETS O ' . ' WHERE O.idOBJETS=I.idOBJETS AND O.FREQUENCE>2' . ' AND idDONJON IS NULL AND idAVENTURIER IS NULL AND idCOMPTE IS NULL AND idBESTIAIRE IS NULL' )->fetch ( PDO::FETCH_NUM );
		
		if ($reqt [0] > $plafond) {
			$cpt = $reqt [0] - $plafond;
			while ( $cpt -- > 0 ) {
				$sql = 'DELETE FROM INVENTAIRE WHERE idOBJETS=(SELECT idOBJETS FROM OBJETS WHERE FREQUENCE>2 ORDER BY RAND() LIMIT 1)' . ' AND idDONJON is NULL AND idAVENTURIER IS NULL AND idCOMPTE IS NULL AND idBESTIAIRE IS NULL' . ' LIMIT 1';
				db::get ()->query ( $sql );
			}
		}
		
		// update bestiaire à vendre
		
		$cpt = $cptd [0] * 2;
		$plafond = $cpt * 20;
		
		while ( $cpt -- ) {
			$cre = db::get ()->query ( 'SELECT idCREATURE FROM CREATURE WHERE `UNIQUE`=0 ORDER BY RAND() LIMIT 1' )->fetch ( PDO::FETCH_NUM );
			
			if ($cre === false) {
				$cpt ++;
				continue;
			}
			$c = new creature ( $cre [0] );
			
			$b = new bestiaire ();
			
			$b->idCREATURE = $c->idCREATURE;
			$b->idDONJON = NULL;
			$b->PRENOM = $c->NOM;
			$b->BAGARRE = $c->BAGARRE;
			$b->ACROBATIE = $c->ACROBATIE;
			$b->CHARME = $c->CHARME;
			$b->ACUITE = $c->ACUITE;
			$b->AGE = rand ( 1, 10 );
			$b->EXPERIENCE = 0;
			$b->RENOMMEE = 0;
			$b->PV_MAX = caracs::jetDe ( $c->VIE );
			$b->PV = $b->PV_MAX;
			$b->REPOS = 0;
			$b->A_VENDRE = 1;
			$b->COUT = $c->PRIX_ENTRETIEN;
			$b->POSITION = '{V}';
			$b->POUVOIRS = $c->POUVOIRS;
			$b->TALENTS = '';
			$b->ENVOUTEMENT = '';
			$b->ORDRE = '';
			$b->POINTS = 0;
			$b->miseAJour ();
		}
		
		// plafond
		$reqt = db::get ()->query ( 'SELECT COUNT(idBESTIAIRE) FROM BESTIAIRE B, CREATURE C ' . ' WHERE C.idCREATURE=B.idCREATURE AND C.UNIQUE=0' . ' AND idDONJON IS NULL' )->fetch ( PDO::FETCH_NUM );
		
		if ($reqt [0] > $plafond) {
			$cpt = $reqt [0] - $plafond;
			while ( $cpt -- > 0 ) {
				$sql = 'DELETE FROM BESTIAIRE WHERE idCREATURE=(SELECT idCREATURE FROM CREATURE WHERE `UNIQUE`=0 ORDER BY RAND() LIMIT 1)'
								.' AND idDONJON is NULL'
								.' LIMIT 1';
								db::get()->query($sql);
							}
						}
		
		
		
				}
}
