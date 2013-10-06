<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="EVENEMENT")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\EvenementRepository")
 */
class Evenement {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="DEST", type="string", length=10, nullable=false)
     */
    private $dest;

    /**
     * @var string
     *
     * @ORM\Column(name="CONTENT", type="text", nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT", type="datetime", nullable=false)
     */
    private $dt;

    /**
     * @var integer
     *
     * @ORM\Column(name="LU", type="integer", nullable=false)
     */
    private $lu;

    /**
     * @var integer
     *
     * @ORM\Column(name="CAT", type="integer", nullable=false)
     */
    private $cat;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dest
     *
     * @param string $dest
     * @return Evenement
     */
    public function setDest($dest) {
        $this->dest = $dest;

        return $this;
    }

    /**
     * Get dest
     *
     * @return string 
     */
    public function getDest() {
        return $this->dest;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Evenement
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set dt
     *
     * @param \DateTime $dt
     * @return Evenement
     */
    public function setDt($dt) {
        $this->dt = $dt;

        return $this;
    }

    /**
     * Get dt
     *
     * @return \DateTime 
     */
    public function getDt() {
        return $this->dt;
    }

    /**
     * Set lu
     *
     * @param integer $lu
     * @return Evenement
     */
    public function setLu($lu) {
        $this->lu = $lu;

        return $this;
    }

    /**
     * Get lu
     *
     * @return integer 
     */
    public function getLu() {
        return $this->lu;
    }

    /**
     * Set cat
     *
     * @param integer $cat
     * @return Evenement
     */
    public function setCat($cat) {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return integer 
     */
    public function getCat() {
        return $this->cat;
    }

    /* CAT :
      0:message
      1:level up
      2:mort
      3:reload interface
      4:jso
     */
}