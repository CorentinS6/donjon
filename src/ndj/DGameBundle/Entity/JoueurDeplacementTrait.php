<?php

namespace ndj\DGameBundle\Entity;

trait JoueurDeplacementTrait {

    abstract public function getPdep();

    abstract public function setPdep($p);

    /**
     * Un dépalcement est-il possible (suffisamenet de point de déplacement) ?
     * @return boolean
     */
    public function deplacement($p = 1) {
        $p = (int) $p;
        if ($this->getPdep() >= $p) {
            $this->setPdep((int) $this->getPdep() - $p);
            return true;
        } else {
            return false;
        }
    }

}
