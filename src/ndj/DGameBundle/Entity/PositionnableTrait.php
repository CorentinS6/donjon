<?php

namespace ndj\DGameBundle\Entity;

use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Entity\Piece;

trait PositionnableTrait {

    abstract public function displayPOSITION();

    abstract public function setPOSITION($p);

    abstract public function getPOSITION();

    abstract public function getIdpiece();
    
    /**
     * vérifie si un element $e est a porté de l'aventurier
     * @param object $e
     * @return boolean
     */
    public function a_porte(PositionnableTrait $e) {
        $p = Tools::explodex(',', $e->getPOSITION());
        return ($e->getIdpiece() === $this->getIdpiece() && $this->a_porte_coord($p[0], $p[1]));
    }

    /**
     * vérifie si les coordonnées $x et $y sont a porté de l'aventurier
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function a_porte_coord($x, $y) {
        $a = Tools::explodex(',', $this->getPosition());
        return (abs($x - $a[0] + $y - $a[1]) < 2);
    }

    // @TODO : prendre en compte da distance (les armes de jets)
    public function a_porte_attaque(PositionnableTrait $e) {
        return $this->a_porte($e);
    }

    public function deplacer(Piece $p, $x, $y, $force = false) {

        if (!$force && ($p !== $this->getIdpiece() || !$this->a_porte_coord($x, $y))) {
            throw new \UnexpectedValueException("Déplacement impossible !");
        }
/*
 * __CLASS__
        $class = str_replace('ndj\\DGameBundle\\Entity\\', '', get_class($this));
        $method_name = 'add' . ucfirst(strtolower($class));
        if (method_exists($p, $method_name)) {
            $p->$method_name($this);
        } else {
            throw new \UnexpectedValueException("Impossible de localiser un '" . $class . "' !");
        }
*/
        return $this->setPosition('{' . $x . '' . $y . '}');
    }

}