<?php

namespace ndj\DGameBundle\Util;


/**
 * Carac
 *
 */
class caracs
{
	// point gagnÃ© lors d'un passage de niveau
	const POINTS_LEVELUP = 10 ;
	// base d'xp gagnÃ©s lors d'un coup rÃ©ussit
	const XP_BASE = 10 ;
	// pourcentage d'xp attribuÃ© au maitre lorsqu'une crÃ©ature gagne de l'xp
	const PC_XP_OWNER = 0.05 ;
	// PO gagnÃ© par un donjon lors d'un "level up"
	const DJ_PO_LVELUP = 1000;

	// pourcentage d'or du donjon aux aventuriers Ã  la fin
	const PC_PO_FIN_DONJON = 0.01;

	// gestio CRON des PA/PD
	const PA_TOUR = 5;
	const PA_MAX = 30;
	const PD_TOUR = 5;
	const PD_MAX = 30;
	const PC_TOUR = 5;
	const PC_MAX = 30;
	
	// nombre max d'aventuriers par membre
	const NB_LIMIT_AVENTURIERS = 3;
	// nombre max de donjons par membre
	const NB_LIMIT_DONJONS = 1;

	private static $_renommee = array(
			// Point_Minimum => Rang
			0 	=> 'Inconnu',
			10 	=> 'Rumeur',
			50	=> 'Connu',
			100	=> 'Renommé',
			250	=> 'Célèbre',
			500	=> 'Légendaire'
	);

	private static $_niveau = array(
			// Point_Minimum => Rang
			0	=> 'Nul',
			10	=> 'Pas très doué',
			50	=> 'Bon',
			100	=> 'Habile',
			250	=> 'Balaise',
			500	=> 'Super fortiche'
	);

	private static $_qualite = array(
			0 => array('Inutilisable / Raté', 0),
			1 => array('Usuel', 1),
			2 => array('Bonne qualité', 1.5),
			3 => array('Qualité supérieure', 2),
			4 => array('Chef-d\'oeuvre', 3),
			5 => array('Objet légendaire', 4),
			6 => array('Occasion', 0.5)
	);

	private static $_rarete = array(
			0 => 'objet unique',
			1 => 'objet introuvable',
			2 => 'objet très rare',
			3 => 'objet rare',
			4 => 'objet courant',
			5 => 'objet commun',
	);
	
	private static $tableau_test = array(
			/*		0		1		2		3		4		5		*/
			array(	/*0*/	0.5,	0.3,	0.1,	0.05,	0.01,	0.01	),
			array(	/*1*/	0.7,	0.5,	0.3,	0.1,	0.05,	0.01	),
			array(	/*2*/	0.9,	0.7,	0.5,	0.3,	0.1,	0.05	),
			array(	/*3*/	0.95,	0.9,	0.7,	0.5,	0.3,	0.1		),
			array(	/*4*/	0.99,	0.95,	0.9,	0.7,	0.5,	0.3		),
			array(	/*5*/	0.99,	0.99,	0.95,	0.9,	0.7,	0.5		)
	);


	// nombre de niveau au total
	const level_nb = 40 ;

	// nombre d'XP pour le dernier niveau
	const level_xp_max = 1000000 ;

	/* --------- LES BONUS/MALUS --------- //
	 //
	// Ã©criture :
	//	static (style "malus armure"), ex: {ACROBATIE,static,-1R} (-1 rang en acrobatie)		ou {ACROBATIE,static,-64p} (-64 points d'acrobatie)
	//	permanant (style "poison"), ex: {PV,perm,-1} (-1 piont de vie Ã  chaque tour)
	//  temporaire (style "sort", ou "alcool"), ex: {PV,temp-5,-2} (-2 point de vie Ã  chaque tour pendant 5 tour)
	//
	// ----------------------------------- */
	
	static public function getRareteText($niveau)
	{
		return self::$_rarete[$niveau];
	}
	
	static public function getRenommeeText($pt)
	{
		$ret = self::$_renommee[500];

		foreach(self::$_renommee as $k=>$v)
		{
			if ($pt >= $k) return $v;
			else $ret = $v;
		}

		return $ret;
	}

	static public function getNiveauOrdre($pt)
	{

		$i = 0;

		foreach(self::$_niveau as $k=>$v) {
			if ($pt < $k) break;
			else $i++;
		}

		return $i;
		/*
		 $ret = self::$_niveau[500];

		$i = 0;
		foreach(self::$_niveau as $k=>$v)
		{
		if ($pt >= $k) return $i;
		else $ret = $i;
		$i++;
		}

		return $ret;
		*/
	}

	static public function getNiveauText($pt)
	{
		$i = 0;

		foreach(self::$_niveau as $k=>$v) {
			if ($pt < $k) break;
			else $i = $k;
			//if ($pt >= $k) return $v;
			//else $ret = $v;
		}

		return self::$_niveau[$i];

	}

	static public function getNextNiveau($pt) {
		$ret = 500;
		foreach(self::$_niveau as $k=>$v){
			if ($pt < $k) return $k;
			else $ret = $k;
		}
		return $ret;
	}

	/**
	 *	Retourne un jet de dÃ© en fonction d'une chaine entrÃ©e
	 * du type : 10+3D-2D5+6D15
	 */
	static public function jetDe($chr)
	{
		$part = preg_split("/(\+|-)/", $chr, null, PREG_SPLIT_DELIM_CAPTURE);
			
		$jet = 0;
			
		$nextcoef = 1;
			
		foreach ($part as $v)
		{

			if(is_numeric($v)) { // si c'est un nombre
				$jet += $v * $nextcoef;
			} elseif ($v == '-') { // si c'est + ou - on corrige le coÃ©ficient
				$nextcoef = -1;
			} elseif ($v == '+') {
				$nextcoef = 1;
			} else { // sinon, de la forme 1D10
				$v = explode('D',$v);
				$nb_jet = $v[0];
				$de = (isset($v[1]) && !is_null($v[1]) && $v[1]!='')? $v[1] : 10;
				// on effectue les jets
				for($i=$nb_jet ; $i-- ; ) {
					$jet += $nextcoef * rand(1, $de);
				}
			}
		}
		return $jet;
	}

	static public function getQualiteArray()
	{
		return self::$_qualite;
	}

	static public function getQualiteLabel($q)
	{
		return self::$_qualite[$q][0];
	}

	static public function getQualiteFactor($q)
	{
		return self::$_qualite[$q][1];
	}

	static public function display_or($or)
	{
		//return number_format($or, 2, ' ', ',') . ' PO';
		//return sprintf('%F PO',$or);
		return money_format('%.2n PO', $or);
	}


	/********************************
	 *								*
	*		TEST ETÂ BAGARRE			*
	*								*
	********************************/

	static public function get_test_ecart($car1, $car2)
	{
		return self::getNiveauOrdre($car1) - self::getNiveauOrdre($car2);
	}

	static public function get_test_diff($car1, $car2)
	{
		$nivo1 = self::getNiveauOrdre($car1);
		$nivo2 = self::getNiveauOrdre($car2);
		$pc = self::$tableau_test[$nivo1][$nivo2];
		return $pc;
	}

	static public function test_oppose($car1, $car2)
	{
		$nivo1 = self::getNiveauOrdre($car1);
		$nivo2 = self::getNiveauOrdre($car2);
		$pc = self::$tableau_test[$nivo1][$nivo2];
		$jet = rand(1,99) / 100;
		return ($jet <= $pc);
	}

	static public function test_simple($difficulte, $carac)
	{
		$nivo = self::getNiveauOrdre($carac);
		$pc = self::$tableau_test[$nivo][$difficulte];
		$jet = rand(1,99) / 100;
		return ($jet <= $pc);
	}

	static public function bagarre(/*icombattant*/ $j1, /*icombattant*/ $j2)
	{
		$msg = $j1->getNOM().' attaque '.$j2->getNOM();
		$d = array(0,false);
		$xp = 0;
		// difficultÃ© de l'attaque
		$d1 = self::get_test_diff($j1->getBAGARRE(), $j2->getBAGARRE());		// difficulte de la bagarre
		$d2 = self::get_test_diff($j1->getBAGARRE(), $j2->getACROBATIE());	// difficulte de l'attaque
		$d3 = self::get_test_diff($j2->getBAGARRE(), $j1->getACROBATIE());	// difficulte de l'attaque (du joueur attaquÃ© Ã  l'origine)
		// bonus j1
		$bonusa = (1-$d1 + 1-$d2)/2;
		// bonus j2
		$bonusd = ($d1 + 1-$d3)/2;
		$xpf = 0;
		if (self::test_oppose($j1->getBAGARRE(), $j2->getBAGARRE())) {
				
			// xp si attaque
			$mort = false;
			$xp += self::XP_BASE * $bonusa;
			if (self::test_oppose($j1->getBAGARRE(), $j2->getACROBATIE())) {
				$d = self::attaque($j1, $j2);
				if ($mort = $d[1]) $xp *= 0.5;
			} else {
				$xp *= 0.3;
			}
			$xpf = $j1->gain_xp($xp);
			$msg .= '. '.$j1->getNOM(). ' inflige <b>'.$d[0].' dÃ©gÃ¢ts</b> Ã  '.$j2->getNOM().' et gagne <b>'.$xpf.' XP</b>.';
			if ($mort) $msg .= $j2->getNOM().' est mort !';
				
		} else {

			// xp pour l'aversaire si attaque
			$mort = false;
			$xp += self::XP_BASE * $bonusd;
			if (self::test_oppose($j2->getBAGARRE(), $j1->getACROBATIE())) {
				$d = self::attaque($j2, $j1);
				if ($mort = $d[1]) $xp *= 0.5;
			} else {
				$xp *= 0.3;
			}
			$xpf = $j2->gain_xp($xp);
			$msg .= ' et rate...<br />'.$j2->getNOM(). ' inflige <b>'.$d[0].' dÃ©gÃ¢ts</b> Ã  '.$j1->getNOM().' et gagne <b>'.$xpf.' XP</b>.';
			if ($mort) $msg .= $j1->getNOM().' est mort !';
		}
		if ($j1 instanceof aventurier) evenement::create($j1,$msg);
		if ($j2 instanceof aventurier) evenement::create($j2,$msg);
	}

	static public function attaque(/*icombattant*/ $j1, /*icombattant*/ $j2)
	{
		$degat = $j1->getDEGAT() - $j2->getDEFENSE();
		// si les dÃ©gÃ¢ts sont supÃ©rieurs aux dÃ©fenses =>Â blessures
		$kill = false;
		if ($degat > 0) {
			$kill = $j2->blessure($degat);
		}
		return array($degat,$kill);
	}

	/********************************
	 *								*
	*	GESTIONÂ DEÂ L'EXPERIENCE		*
	*								*
	********************************/

	// retourne le niveau actuelle en fonction de $_xp
	static public function XP_getLevel($_xp)
	{
		$_level = 1;
		foreach(self::$_XpTable as $level=>$xp)
		{
			if ($_xp < $xp) return $_level;
			$_level = $level;
		}
	}
	// retourne le nombre d'xp de base du niveau
	static public function XP_getLevelXP($_xp)
	{
		return self::$_XpTable[self::XP_getLevel($_xp)];
	}
	// retourne le nombre d'xp avant le prochain niveau
	static public function XP_getNextLevel($_xp)
	{
		return self::$_XpTable[self::XP_getLevel($_xp)+1];
	}

	static public function XP_getPlace($_xp)
	{
		$c = self::XP_getLevelXP($_xp);
		$x = $_xp - $c;
		$n = self::XP_getNextLevel($_xp) - $c;
		return array($c,$x,$n);
	}

	static private function generateXpTable()
	{
		self::level_nb;
		self::level_xp_max;

		echo "1 => 0,<br>";
		for($n=1; $n<self::level_nb; $n++)
		{
			$l = round( 50 * pow( $n, 2 ) );
			echo ($n+1)." => ";
			echo $l;
			echo ",<br>";
		}
		exit;
	}




	private static $_XpTable = array(
			1 => 0,
			2 => 50,
			3 => 200,
			4 => 450,
			5 => 800,
			6 => 1250,
			7 => 1800,
			8 => 2450,
			9 => 3200,
			10 => 4050,
			11 => 5000,
			12 => 6050,
			13 => 7200,
			14 => 8450,
			15 => 9800,
			16 => 11250,
			17 => 12800,
			18 => 14450,
			19 => 16200,
			20 => 18050,
			21 => 20000,
			22 => 22050,
			23 => 24200,
			24 => 26450,
			25 => 28800,
			26 => 31250,
			27 => 33800,
			28 => 36450,
			29 => 39200,
			30 => 42050,
			31 => 45000,
			32 => 48050,
			33 => 51200,
			34 => 54450,
			35 => 57800,
			36 => 61250,
			37 => 64800,
			38 => 68450,
			39 => 72200,
			40 => 76050
	);

}
