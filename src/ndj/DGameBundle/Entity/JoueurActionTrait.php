<?php

namespace ndj\DGameBundle\Entity;

trait JoueurActionTrait {
    
    abstract public function getPact();

    abstract public function setPact($p);

    /**
     * Une action est-elle possible ?
     * @return boolean
     */
    public function action($p = 1) {
        $p = (int) $p;
        if ($this->getPact() >= $p) {
            $this->setPact((int) $this->getPact() - $p);
            return true;
        } else {
            return false;
        }
    }

}
