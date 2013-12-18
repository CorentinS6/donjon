<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Objets
 *
 * @ORM\Table(name="OBJETS")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\ObjetsRepository")
 */
class Objets
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
     * @ORM\Column(name="NOM", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="CAT", type="string", nullable=false)
     */
    private $cat;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="BONUS", type="string", length=255, nullable=false)
     */
    private $bonus;

    /**
     * @var float
     *
     * @ORM\Column(name="PRIX", type="float", nullable=false)
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="FREQUENCE", type="integer", nullable=false)
     */
    private $frequence;

    /**
     * @var string
     *
     * @ORM\Column(name="DEGAT", type="string", length=10, nullable=false)
     */
    private $degat;

    /**
     * @var string
     *
     * @ORM\Column(name="BOUCLIER", type="string", length=10, nullable=false)
     */
    private $bouclier;

    
    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Inventaire", mappedBy="idobjets")
     */
    private $inventaires;
    
    public function addInventaire(Inventaire $inventaire)
    {
    	$this->inventaires[] = $inventaire;
    	$inventaire->setIdobjets($this);
    	return $this;
    }
    
    public function hasInventaire(Inventaire $inventaire)
    {
    	return $this->inventaires->contains($inventaire);
    }
    
    public function removeInventaire(Inventaire $inventaire)
    {
    	$this->inventaires->removeElement($inventaire);
    	$inventaire->setIdobjets(null);
    }
    
    public function getInventaires()
    {
    	return $this->inventaires;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->inventaires = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set nom
     *
     * @param string $nom
     * @return Objets
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set cat
     *
     * @param string $cat
     * @return Objets
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
     * Set description
     *
     * @param string $description
     * @return Objets
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set bonus
     *
     * @param string $bonus
     * @return Objets
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    
        return $this;
    }

    /**
     * Get bonus
     *
     * @return string 
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set prix
     *
     * @param float $prix
     * @return Objets
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    
        return $this;
    }

    /**
     * Get prix
     *
     * @return float 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set frequence
     *
     * @param integer $frequence
     * @return Objets
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;
    
        return $this;
    }

    /**
     * Get frequence
     *
     * @return integer 
     */
    public function getFrequence()
    {
        return $this->frequence;
    }

    /**
     * Set degat
     *
     * @param string $degat
     * @return Objets
     */
    public function setDegat($degat)
    {
        $this->degat = $degat;
    
        return $this;
    }

    /**
     * Get degat
     *
     * @return string 
     */
    public function getDegat()
    {
        return $this->degat;
    }

    /**
     * Set bouclier
     *
     * @param string $bouclier
     * @return Objets
     */
    public function setBouclier($bouclier)
    {
        $this->bouclier = $bouclier;
    
        return $this;
    }

    /**
     * Get bouclier
     *
     * @return string 
     */
    public function getBouclier()
    {
        return $this->bouclier;
    }
    
    
    
    public function isImg() {
    	return file_exists($this->getImg());
    }
    public function getImg() {
    	return 'bundles/ndjdgame/images/objets/'.$this->getId().'.png';
    }
    
    // @todo
    public function getDefense() {
        // return $this->getBouclier();
        return 0;
    }
}