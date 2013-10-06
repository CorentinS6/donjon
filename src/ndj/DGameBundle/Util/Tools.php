<?php

namespace ndj\DGameBundle\Util;

/**
 * Tools (classe utilitaire)
 *
 */
class Tools
{

	/**
	 * Retire et retourne le premier caractère d'une chaine
	 * @param string $str
	 * @return string
	 */
	static public function str_shift( &$str)
	{
		$return = $str[0];
		$str = substr($str, 1, strlen($str)-1);
		return $return;
	}
	
	/**
	 * Retire et retourne le dernier caractère d'une chaine
	 * @param string $str
	 * @return string
	 */
	static public function str_pop( &$str)
	{
		$return = $str[strlen($str)-1];
		unset($str[strlen($str)]);
		return $return;
	}
	
	/**
	 * Retourne une chaine diminuée de son premier et denier caractère
	 * @param string $str
	 * @return string
	 */
	static public function trimx ($str)
	{
		return substr($str, 1, strlen($str)-2);
	}
	
	/**
	 * Transforme un chaine en tableau en fonction du séparateur $sep, en retirant les deux caractères extrèmes
	 * @param string $sep
	 * @param string $str
	 * @return array 
	 */
	static public function explodex($sep, $str)
	{
		return explode($sep, self::trimx($str));
	}
	
	/**
	 * Concatène les tableaux passés en paramètre
	 * @param array
	 * @return array
	 */
	static public function array_concat()
	{
		$out = array();
		$arg_list = func_get_args();
		foreach($arg_list as $arg) {
			if (is_array($arg)){
				foreach($arg as $v) {
					$out[] = $v;
				}
			} else {
				$out[] = $arg;
			}
		}
		return $out;
	}
	
	/**
	 * Tronque une chaine en fonction d'une longueur maximum (ajout de ... à la fin)
	 * @param string $chaine
	 * @param int $lg_max
	 * @return string
	 */
	static public function str_troncate($chaine, $lg_max) {
		if (strlen($chaine) > $lg_max) {
			$chaine = substr($chaine, 0, $lg_max);
			$last_space = strrpos($chaine, " ");
			$chaine = substr($chaine, 0, $last_space)."...";
		}
		return $chaine;
	}
	
	static public function toArray($array)
	{
		$r = array();
		foreach($array as $o) $r[] = $o->toArray();
		return $r;
	}
        
        /**
         * @param $col
         * @return Array
         */
        static public function collectionToArray($col) {
		$r = Array();
		foreach($col as $o) $r[] = $o;
		return $r;
        }
	
}