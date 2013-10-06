<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banque
 *
 * @ORM\Table(name="BANQUE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\BanqueRepository")
 */
class Banque
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
     * @var integer
     *
     * @ORM\Column(name="TAILLE_COFFRE", type="integer", nullable=false)
     */
    private $tailleCoffre;

    /**
     * @var float
     *
     * @ORM\Column(name="ARGENT", type="float", nullable=false)
     */
    private $argent;

    /**
     * @var float
     *
     * @ORM\Column(name="ARGENT_MAX", type="float", nullable=false)
     */
    private $argentMax;

    /**
     * @var float
     *
     * @ORM\Column(name="COUT", type="float", nullable=false)
     */
    private $cout;

    /**
     * @var string
     *
     * @ORM\Column(name="ETAT", type="string", nullable=false)
     */
    private $etat;

    /**
     * @var \Aventurier
     *
     * @ORM\ManyToOne(targetEntity="Aventurier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAVENTURIER", referencedColumnName="id")
     * })
     */
    private $idaventurier;

    
    
    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Inventaire", mappedBy="idcompte")
     */
    private $inventaires;
    
    public function addInventaire(\Inventaire $inventaire)
    {
    	$this->inventaires[] = $inventaire;$inventaire->setidcompte($this);
    	return $this;
    }
    
    public function removeInventaire(\Inventaire $inventaire)
    {
    	$this->inventaires->removeElement($inventaire);$inventaire->setIdcompte(null);
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
     * Set tailleCoffre
     *
     * @param integer $tailleCoffre
     * @return Banque
     */
    public function setTailleCoffre($tailleCoffre)
    {
        $this->tailleCoffre = $tailleCoffre;
    
        return $this;
    }

    /**
     * Get tailleCoffre
     *
     * @return integer 
     */
    public function getTailleCoffre()
    {
        return $this->tailleCoffre;
    }

    /**
     * Set argent
     *
     * @param float $argent
     * @return Banque
     */
    public function setArgent($argent)
    {
        $this->argent = $argent;
    
        return $this;
    }

    /**
     * Get argent
     *
     * @return float 
     */
    public function getArgent()
    {
        return $this->argent;
    }

    /**
     * Set argentMax
     *
     * @param float $argentMax
     * @return Banque
     */
    public function setArgentMax($argentMax)
    {
        $this->argentMax = $argentMax;
    
        return $this;
    }

    /**
     * Get argentMax
     *
     * @return float 
     */
    public function getArgentMax()
    {
        return $this->argentMax;
    }

    /**
     * Set cout
     *
     * @param float $cout
     * @return Banque
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
     * Set etat
     *
     * @param string $etat
     * @return Banque
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    
        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set idaventurier
     *
     * @param \ndj\DGameBundle\Entity\Aventurier $idaventurier
     * @return Banque
     */
    public function setIdaventurier(\ndj\DGameBundle\Entity\Aventurier $idaventurier = null)
    {
        $this->idaventurier = $idaventurier;
    
        return $this;
    }

    /**
     * Get idaventurier
     *
     * @return \ndj\DGameBundle\Entity\Aventurier 
     */
    public function getIdaventurier()
    {
        return $this->idaventurier;
    }
}