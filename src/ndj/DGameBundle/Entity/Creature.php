<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Creature
 *
 * @ORM\Table(name="CREATURE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\CreatureRepository")
 */
class Creature
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
     * @ORM\Column(name="DESCRIPTION", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="INTELLIGENTE", type="boolean", nullable=false)
     */
    private $intelligente;

    /**
     * @var integer
     *
     * @ORM\Column(name="ACROBATIE", type="smallint", nullable=false)
     */
    private $acrobatie;

    /**
     * @var integer
     *
     * @ORM\Column(name="BAGARRE", type="smallint", nullable=false)
     */
    private $bagarre;

    /**
     * @var integer
     *
     * @ORM\Column(name="CHARME", type="smallint", nullable=false)
     */
    private $charme;

    /**
     * @var integer
     *
     * @ORM\Column(name="ACUITE", type="smallint", nullable=false)
     */
    private $acuite;

    /**
     * @var string
     *
     * @ORM\Column(name="VIE", type="string", length=45, nullable=false)
     */
    private $vie;

    /**
     * @var string
     *
     * @ORM\Column(name="DEGAT", type="string", length=45, nullable=false)
     */
    private $degat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="UNIQUE", type="boolean", nullable=false)
     */
    private $unique;

    /**
     * @var string
     *
     * @ORM\Column(name="POUVOIRS", type="string", length=45, nullable=true)
     */
    private $pouvoirs;

    /**
     * @var float
     *
     * @ORM\Column(name="PRIX_ACHAT", type="float", nullable=false)
     */
    private $prixAchat;

    /**
     * @var float
     *
     * @ORM\Column(name="PRIX_ENTRETIEN", type="float", nullable=false)
     */
    private $prixEntretien;

    /**
     * @var \Creature
     *
     * @ORM\ManyToOne(targetEntity="Creature")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPARENT", referencedColumnName="id")
     * })
     */
    private $idparent;

    
    
    
    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Aventurier", mappedBy="idcreature")
     */
    private $aventuriers;
    
    public function hasAventurier(Aventurier $aventurier)
    {
    	return $this->aventuriers->contains($aventurier);
    }
    public function addAventurier(Aventurier $aventurier)
    {
    	$this->aventuriers[] = $aventurier;
    	$aventurier->setIdobjets($this);
    	return $this;
    }
    
    public function removeAventurier(Aventurier $aventurier)
    {
    	$this->aventuriers->removeElement($aventurier);
    	$aventurier->setIdobjets(null);
    }
    
    public function getAventuriers()
    {
    	return $this->aventuriers;
    }
    
    
    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Bestiaire", mappedBy="idcreature")
     */
    private $bestiaires;
    
    
    public function hasBestiaire(Bestiaire $bestiaire)
    {
    	return $this->bestiaires->contains($bestiaire);
    }
    
    public function addBestiaire(Bestiaire $bestiaire)
    {
    	$this->bestiaires[] = $bestiaire;
    	$bestiaire->setIdobjets($this);
    	return $this;
    }
    
    public function removeBestiaire(Bestiaire $bestiaire)
    {
    	$this->bestiaires->removeElement($bestiaire);
    	$bestiaire->setIdobjets(null);
    }
    
    public function getBestiaires()
    {
    	return $this->bestiaires;
    }
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->aventuriers = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->bestiaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Creature
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
     * Set description
     *
     * @param string $description
     * @return Creature
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
     * Set intelligente
     *
     * @param boolean $intelligente
     * @return Creature
     */
    public function setIntelligente($intelligente)
    {
        $this->intelligente = $intelligente;
    
        return $this;
    }

    /**
     * Get intelligente
     *
     * @return boolean 
     */
    public function getIntelligente()
    {
        return $this->intelligente;
    }

    /**
     * Set acrobatie
     *
     * @param integer $acrobatie
     * @return Creature
     */
    public function setAcrobatie($acrobatie)
    {
        $this->acrobatie = $acrobatie;
    
        return $this;
    }

    /**
     * Get acrobatie
     *
     * @return integer 
     */
    public function getAcrobatie()
    {
        return $this->acrobatie;
    }

    /**
     * Set bagarre
     *
     * @param integer $bagarre
     * @return Creature
     */
    public function setBagarre($bagarre)
    {
        $this->bagarre = $bagarre;
    
        return $this;
    }

    /**
     * Get bagarre
     *
     * @return integer 
     */
    public function getBagarre()
    {
        return $this->bagarre;
    }

    /**
     * Set charme
     *
     * @param integer $charme
     * @return Creature
     */
    public function setCharme($charme)
    {
        $this->charme = $charme;
    
        return $this;
    }

    /**
     * Get charme
     *
     * @return integer 
     */
    public function getCharme()
    {
        return $this->charme;
    }

    /**
     * Set acuite
     *
     * @param integer $acuite
     * @return Creature
     */
    public function setAcuite($acuite)
    {
        $this->acuite = $acuite;
    
        return $this;
    }

    /**
     * Get acuite
     *
     * @return integer 
     */
    public function getAcuite()
    {
        return $this->acuite;
    }

    /**
     * Set vie
     *
     * @param string $vie
     * @return Creature
     */
    public function setVie($vie)
    {
        $this->vie = $vie;
    
        return $this;
    }

    /**
     * Get vie
     *
     * @return string 
     */
    public function getVie()
    {
        return $this->vie;
    }

    /**
     * Set degat
     *
     * @param string $degat
     * @return Creature
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
     * Set unique
     *
     * @param boolean $unique
     * @return Creature
     */
    public function setUnique($unique)
    {
        $this->unique = $unique;
    
        return $this;
    }

    /**
     * Get unique
     *
     * @return boolean 
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * Set pouvoirs
     *
     * @param string $pouvoirs
     * @return Creature
     */
    public function setPouvoirs($pouvoirs)
    {
        $this->pouvoirs = $pouvoirs;
    
        return $this;
    }

    /**
     * Get pouvoirs
     *
     * @return string 
     */
    public function getPouvoirs()
    {
        return $this->pouvoirs;
    }

    /**
     * Set prixAchat
     *
     * @param float $prixAchat
     * @return Creature
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;
    
        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return float 
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set prixEntretien
     *
     * @param float $prixEntretien
     * @return Creature
     */
    public function setPrixEntretien($prixEntretien)
    {
        $this->prixEntretien = $prixEntretien;
    
        return $this;
    }

    /**
     * Get prixEntretien
     *
     * @return float 
     */
    public function getPrixEntretien()
    {
        return $this->prixEntretien;
    }

    /**
     * Set idparent
     *
     * @param \ndj\DGameBundle\Entity\Creature $idparent
     * @return Creature
     */
    public function setIdparent(\ndj\DGameBundle\Entity\Creature $idparent = null)
    {
        $this->idparent = $idparent;
    
        return $this;
    }

    /**
     * Get idparent
     *
     * @return \ndj\DGameBundle\Entity\Creature 
     */
    public function getIdparent()
    {
        return $this->idparent;
    }
    
    
    public function getTypeLabel()
    {
    	return ($this->getIntelligente()=='1') ? 'peuple': 'creature';
    }
    
    public function isImg() {
    	return file_exists($this->getImg());
    }
    public function getImg() {
    	return 'bundles/ndjdgame/images/creatures/'.$this->getId().'.png';
    }
    
    
    
    public function toArray()
    {
    	$_return = array();
    	foreach($this as $key=>$val)
    	{
    		$_return[$key] = $val;
    	}
    	return $_return;
    }
}