<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relation
 *
 * @ORM\Table(name="RELATION")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\RelationRepository")
 */
class Relation
{
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
     * @ORM\Column(name="CAT", type="string", nullable=false)
     */
    private $cat;

    /**
     * @var float
     *
     * @ORM\Column(name="COUT", type="float", nullable=false)
     */
    private $cout;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_RELATION", type="date", nullable=false)
     */
    private $dateRelation;

    /**
     * @var integer
     *
     * @ORM\Column(name="FIN", type="smallint", nullable=false)
     */
    private $fin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ETAT1", type="boolean", nullable=false)
     */
    private $etat1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ETAT2", type="boolean", nullable=false)
     */
    private $etat2;

    /**
     * @var \Aventurier
     *
     * @ORM\ManyToOne(targetEntity="Aventurier", inversedBy="relations1")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAVENTURIER1", referencedColumnName="id")
     * })
     */
    private $idaventurier1;

    /**
     * @var \Aventurier
     *
     * @ORM\ManyToOne(targetEntity="Aventurier", inversedBy="relations2")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAVENTURIER2", referencedColumnName="id")
     * })
     */
    private $idaventurier2;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cat
     *
     * @param string $cat
     * @return Relation
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
    
        return $this;
    }

    /**
     * Get cat
     *
     * @return string 
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Set cout
     *
     * @param float $cout
     * @return Relation
     */
    public function setCout($cout)
    {
        $this->cout = $cout;
    
        return $this;
    }

    /**
     * Get cout
     *
     * @return float 
     */
    public function getCout()
    {
        return $this->cout;
    }

    /**
     * Set dateRelation
     *
     * @param \DateTime $dateRelation
     * @return Relation
     */
    public function setDateRelation($dateRelation)
    {
        $this->dateRelation = $dateRelation;
    
        return $this;
    }

    /**
     * Get dateRelation
     *
     * @return \DateTime 
     */
    public function getDateRelation()
    {
        return $this->dateRelation;
    }

    /**
     * Set fin
     *
     * @param integer $fin
     * @return Relation
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    
        return $this;
    }

    /**
     * Get fin
     *
     * @return integer 
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set etat1
     *
     * @param boolean $etat1
     * @return Relation
     */
    public function setEtat1($etat1)
    {
        $this->etat1 = $etat1;
    
        return $this;
    }

    /**
     * Get etat1
     *
     * @return boolean 
     */
    public function getEtat1()
    {
        return $this->etat1;
    }

    /**
     * Set etat2
     *
     * @param boolean $etat2
     * @return Relation
     */
    public function setEtat2($etat2)
    {
        $this->etat2 = $etat2;
    
        return $this;
    }

    /**
     * Get etat2
     *
     * @return boolean 
     */
    public function getEtat2()
    {
        return $this->etat2;
    }

    /**
     * Set idaventurier1
     *
     * @param \ndj\DGameBundle\Entity\Aventurier $idaventurier1
     * @return Relation
     */
    public function setIdaventurier1(\ndj\DGameBundle\Entity\Aventurier $idaventurier1 = null)
    {
        $this->idaventurier1 = $idaventurier1;
    
        return $this;
    }

    /**
     * Get idaventurier1
     *
     * @return \ndj\DGameBundle\Entity\Aventurier 
     */
    public function getIdaventurier1()
    {
        return $this->idaventurier1;
    }

    /**
     * Set idaventurier2
     *
     * @param \ndj\DGameBundle\Entity\Aventurier $idaventurier2
     * @return Relation
     */
    public function setIdaventurier2(\ndj\DGameBundle\Entity\Aventurier $idaventurier2 = null)
    {
        $this->idaventurier2 = $idaventurier2;
    
        return $this;
    }

    /**
     * Get idaventurier2
     *
     * @return \ndj\DGameBundle\Entity\Aventurier 
     */
    public function getIdaventurier2()
    {
        return $this->idaventurier2;
    }
}