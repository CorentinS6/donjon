<?php

namespace ndj\DGameBundle\Entity;

trait OuvrableTrait {

    abstract public function setClose();

    abstract public function setOpen();

    abstract public function getEtat();

    abstract public function setEtat($e);


    /**
     *  valeur de ETAT :
     * -X: ferme dans X tours
     * -1: ferme au prochain tour
     * 0: désactivé
     * 1: en construction (PC, PA illimité)
     * 2: ouvert
     */
    /* static public $_etat = array(
      -1 => 'Fermé dans {X} tour(s)',
      0 => 'Désactivé',
      1 => 'En construction',
      2 => 'Ouvert aux aventuriers'
      ); */

    public function getEtats() {
        return array(
            -3 => 'Fermé dans 3 tour(s)',
            -2 => 'Fermé dans 2 tour(s)',
            -1 => 'Fermé dans 1 tour(s)',
            0 => 'Désactivé',
            1 => 'En construction',
            2 => 'Ouvert au aventurier'
        );
    }

    public function isClosingSoon() {
        return ($this->getETAT() < 0) ? $this->getETAT() : false;
    }

    public function isOpen() {
        return ($this->getETAT() == 2);
    }

    public function isBuilding() {
        return ($this->getETAT() == 1);
    }

    public function displayEtat() {
        //if ($this->getETAT() >= 0) {
        return $this->getEtats()[$this->getETAT()];
        /* } else {
          $ret = self::$_etat[-1];
          $ret = str_replace('{X}', $this->getETAT() * -1, $ret);
          return $ret;
          } */
    }

    public function setCloseDelay() {
        if ($this->isOpen()) {
            $this->setEtat(-3);
            return true;
        }
        return false;
    }

}
