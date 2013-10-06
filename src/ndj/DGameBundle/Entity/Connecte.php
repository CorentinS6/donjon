<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connecte
 *
 * @ORM\Table(name="CONNECTE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\ConnecteRepository")
 */
class Connecte {

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="LASTTIME", type="integer", nullable=false)
     */
    private $lasttime;

    /**
     * Get id
     *
     * @return string 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Connecte 
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Set lasttime
     *
     * @param integer $lasttime
     * @return Connecte
     */
    public function setLasttime($lasttime) {
        $this->lasttime = $lasttime;

        return $this;
    }

    /**
     * Get lasttime
     *
     * @return integer 
     */
    public function getLasttime() {
        return $this->lasttime;
    }

}