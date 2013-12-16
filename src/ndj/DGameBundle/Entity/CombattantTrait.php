<?php

namespace ndj\DGameBundle\Entity;

//use ndj\DGameBundle\Entity\AcheteurTrait;

trait AchetableTrait {

        public function getBAGARRE() {}
	
	public function getACROBATIE(){}
	
	public function getDEFENSE(){}
	
	public function getDEGAT(){}
	
	public function blessure($degat){}
	
	public function mort(){}
	
	public function gain_xp($xp){}
	
	public function level_up(){}
	
	public function getPlayerKey(){}

}
