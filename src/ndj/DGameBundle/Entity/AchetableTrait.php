<?php

namespace ndj\DGameBundle\Entity;

//use ndj\DGameBundle\Entity\AcheteurTrait;

trait AchetableTrait {

    abstract public function calculerPrix();

    abstract public function setProprio(AcheteurTrait $o);
    
    abstract public function getProprio();
}
