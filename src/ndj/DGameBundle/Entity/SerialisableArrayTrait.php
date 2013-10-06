<?php

namespace ndj\DGameBundle\Entity;

trait SerialisableArrayTrait {

    abstract public function toArray();

    /*public function __toJson() {
        return json_encode($this->toArray());
    }*/

}
