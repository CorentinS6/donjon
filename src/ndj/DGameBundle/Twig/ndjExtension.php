<?php
// src/ndj/DGameBundle/Twig/ndjExtension.php
namespace ndj\DGameBundle\Twig;

use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Util\caracs;
use Symfony\Component\Config\Definition\IntegerNode;

class ndjExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
        	// Tools
        	'array_count_val'=> new \Twig_Filter_Method($this, 'array_count_valFilter'),
        	'str_troncate'=> new \Twig_Filter_Method($this, 'str_troncateFilter'),
        	'str_replace'=> new \Twig_Filter_Method($this, 'str_replaceFilter'),
        	'explodex'=> new \Twig_Filter_Method($this, 'explodexFilter'),
        	// Etats
        	'etatd'	=> new \Twig_Filter_Method($this, 'etatdFilter'),
        	// Caracs
            'or' => new \Twig_Filter_Method($this, 'orFilter'),
            'rarete' => new \Twig_Filter_Method($this, 'rareteFilter'),
            'carac' => new \Twig_Filter_Method($this, 'caracFilter'),
            'level' => new \Twig_Filter_Method($this, 'levelFilter'),
            'next_level' => new \Twig_Filter_Method($this, 'next_levelFilter'),
            'renommee' => new \Twig_Filter_Method($this, 'renommeeFilter'),
            'qualite' => new \Twig_Filter_Method($this, 'qualiteFilter'),
            'xp_place' => new \Twig_Filter_Method($this, 'xp_placeFilter'),
        );
    }
    
    
    public function array_count_valFilter($array, $key, $val)
    {
    	$cpt = 0;
    	foreach ($array as $item)
    	{	
    		$method = 'get'.ucfirst($key);
    		if (method_exists($item, $method))
    		{
    			$test_val = $item->$method();
    			if ($test_val==$val)
    			{   				
    				$cpt++;
    			}
    		}
    	}
        return $cpt;
    }
    
    public function str_replaceFilter($subject, $search, $replace)
    {
    	return str_replace($search, $replace, $subject);
    }
    public function str_troncateFilter($str, $limit)
    {
        return (strlen($str) <= $limit) ? $str : trim(substr($str,0,$limit)).'...';
    }

    public function etatdFilter($etat)
    {
    	
    	/*if ($etat >= 0) {
    		return Donjon::$_etat[$etat];
    	} else {
    		$ret = Donjon::$_etat[-1];
    		$ret = str_replace('{X}',$etat * -1, $ret);
    		return $ret;
    	}*/
    	return 'fixme';
    	/*
    	switch($etat) {
    		case 2 : return 'ouvert';
    			break;
    		case -3 :case -2 :case -1 : return 'ouvert (fermeture prochaine)';
    			break;
    		case 1 : return 'fermé';
    			break;
    	}
        return $etat;*/
    }

    public function caracFilter($point)
    {
        return caracs::getNiveauText($point);
    }

    public function levelFilter($xp)
    {
        return caracs::XP_getLevel($xp);
    }

    public function next_levelFilter($xp)
    {
        return caracs::XP_getNextLevel($xp);
    }
    
    public function renommeeFilter($pt)
    {
    	return caracs::getRenommeeText($pt);
    }
    
    public function qualiteFilter($pt)
    {
    	return caracs::getQualiteLabel($pt);
    }
    
    public function rareteFilter($rarete)
    {
    	return caracs::getRareteText($rarete);
    }
    
    public function orFilter($or, $end=' PO')
    {
		return money_format('%.2n'.$end, $or);    	
    }
    
    public function xp_placeFilter($xp)
    {
    	return caracs::XP_getPlace($xp);
    }
    
    public function explodexFilter($str,$chr)
    {
    	return Tools::explodex($chr, $str);
    }
    

    public function getName()
    {
        return 'ndj_extension';
    }
}