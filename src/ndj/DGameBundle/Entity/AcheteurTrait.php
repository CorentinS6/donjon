<?php

namespace ndj\DGameBundle\Entity;

//use ndj\DGameBundle\Entity\AchetableTrait;

trait AcheteurTrait {

    abstract public function getArgent();

    abstract public function setArgent($a);

    /**
     * Recette
     * @param double $somme
     */
    public function recette($somme) {
        $this->setArgent($this->getArgent() + $somme);
        return $this;
    }

    /**
     * Dépense
     * @param double $somme
     * @return boolean
     */
    public function depense($somme) {
        if ($somme > $this->getArgent())
            return false;
        $this->setArgent($this->getArgent() - $somme);
        return true;
    }

    public function achat(AchetableTrait $o) {
        if ($this->depense($o->calculerPrix())) {
            $o->setProprio($this);
            return true;
        }
        return false;
    }

}