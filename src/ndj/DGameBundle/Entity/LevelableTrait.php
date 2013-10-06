<?php

namespace ndj\DGameBundle\Entity;

use ndj\DGameBundle\Util\caracs;

trait LevelableTrait {

    abstract public function setEXPERIENCE($xp);

    abstract public function getEXPERIENCE();

    abstract public function level_up($n = null);


    public function gain_xp($xp) {
        $xp = ceil($xp);
        $next = caracs::XP_getNextLevel($this->getEXPERIENCE());
        $this->setEXPERIENCE($this->getEXPERIENCE() + $xp);
        if ($this->getEXPERIENCE() >= $next) {
            $this->level_up();
        }
        return $xp;
    }

}
