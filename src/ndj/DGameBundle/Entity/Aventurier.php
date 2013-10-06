<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ndj\UserBundle\Entity;
use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Util\caracs;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Aventurier
 *
 * @ORM\Table(name="AVENTURIER")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\AventurierRepository")
 */
class Aventurier {

   // use AcheteurTrait;
   // use PositionnableTrait;
   // use LevelableTrait;
   // use JoueurActionTrait;
  //  use JoueurDeplacementTrait;
  //  use SerialisableArrayTrait;

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
     * @ORM\Column(name="NOM", type="string", length=45, nullable=false)
     * @Assert\Length(min=5,minMessage="Le nom de l'aventurier doit faire au moins 5 caractères.",max=45,maxMessage="Le nom de l'aventurier doit faire au plus 45 caractères.")
     */
    private $nom;

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
     * @var integer
     *
     * @ORM\Column(name="AGE", type="smallint", nullable=false)
     */
    private $age;

    /**
     * @var integer
     *
     * @ORM\Column(name="EXPERIENCE", type="integer", nullable=false)
     */
    private $experience;

    /**
     * @var integer
     *
     * @ORM\Column(name="RENOMMEE", type="smallint", nullable=false)
     */
    private $renommee;

    /**
     * @var integer
     *
     * @ORM\Column(name="RENOMMEE_MAX", type="smallint", nullable=false)
     */
    private $renommeeMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="PVIE", type="smallint", nullable=false)
     */
    private $pvie;

    /**
     * @var integer
     *
     * @ORM\Column(name="PVIE_MAX", type="smallint", nullable=false)
     */
    private $pvieMax;

    /**
     * @var float
     *
     * @ORM\Column(name="ARGENT", type="float", nullable=false)
     */
    private $argent;

    /**
     * @var integer
     *
     * @ORM\Column(name="MANA", type="smallint", nullable=false)
     */
    private $mana;

    /**
     * @var integer
     *
     * @ORM\Column(name="MANA_MAX", type="smallint", nullable=false)
     */
    private $manaMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="PACT", type="smallint", nullable=false)
     */
    private $pact;

    /**
     * @var integer
     *
     * @ORM\Column(name="PDEP", type="smallint", nullable=false)
     */
    private $pdep;

    /**
     * @var string
     *
     * @ORM\Column(name="POSITION", type="string", length=45, nullable=true)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="POSX", type="integer", nullable=true)
     */
    private $posx;

    /**
     * @var integer
     *
     * @ORM\Column(name="POSY", type="integer", nullable=true)
     */
    private $posy;

    /**
     * @var string
     *
     * @ORM\Column(name="POUVOIRS", type="string", length=45, nullable=true)
     */
    private $pouvoirs;

    /**
     * @var string
     *
     * @ORM\Column(name="TALENTS", type="string", length=255, nullable=true)
     */
    private $talents;

    /**
     * @var string
     *
     * @ORM\Column(name="ENVOUTEMENT", type="string", length=255, nullable=true)
     */
    private $envoutement;

    /**
     * @var integer
     *
     * @ORM\Column(name="POINTS", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="ETAT", type="integer", nullable=false)
     */
    private $etat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Organisation", inversedBy="idaventurier")
     * @ORM\JoinTable(name="organisation_appartenance",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idAVENTURIER", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idORGANISATION", referencedColumnName="id")
     *   }
     * )
     */
    //private $idorganisation;

    /**
     * @var \ndj\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\ndj\UserBundle\Entity\User",inversedBy="aventuriers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idMEMBRE", referencedColumnName="id")
     * })
     */
    private $idmembre;

    /**
     * @var \Creature
     *
     * @ORM\ManyToOne(targetEntity="Creature", inversedBy="aventuriers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCREATURE", referencedColumnName="id")
     * })
     */
    private $idcreature;

    /**
     *
     * @var \Piece @ORM\ManyToOne(targetEntity="Piece",
     *      inversedBy="aventuriers")
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="idPIECE", referencedColumnName="id")
     *      })
     */
    private $idpiece;

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Relation", mappedBy="idaventurier1", cascade={"remove"})
     */
    private $relations1;

    public function addRelation1(Relation $relation) {
        $this->relations1 [] = $relation;
        $relation->setIdaventurier1($this);
        return $this;
    }

    public function removeRelation1(Relation $relation) {
        $this->relations1->removeElement($relation);
        $relation->setIdaventurier1(null);
    }

    public function hasRelation1(Relation $relation) {
        return $this->relations1->contains($relation);
    }

    public function getRelations1() {
        return $this->relations1;
    }

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Relation", mappedBy="idaventurier2", cascade={"remove"})
     */
    private $relations2;

    public function addRelation2(Relation $relation) {
        $this->relations2 [] = $relation;
        $relation->setIdaventurier2($this);
        return $this;
    }

    public function removeRelation2(Relation $relation) {
        $this->relations2->removeElement($relation);
        $relation->setIdaventurier2(null);
    }

    public function hasRelation2(Relation $relation) {
        $this->relations2->contains($relation);
    }

    public function getRelations2() {
        return $this->relations2;
    }

    public function hasRelation(Relation $relation) {
        return $this->hasRelation2($relation) || $this->hasRelation1($relation);
    }

    public function getRelationNumber(Relation $relation) {
        if ($this->hasRelation2($relation))
            return 2;
        elseif ($this->hasRelation1($relation))
            return 1;
        else
            return false;
    }

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Inventaire", mappedBy="idaventurier")
     */
    private $inventaires;

    public function addInventaire(Inventaire $inventaire) {
        $this->inventaires[] = $inventaire;
        $inventaire->setIdaventurier($this);
        return $this;
    }

    public function removeInventaire(Inventaire $inventaire) {
        $this->inventaires->removeElement($inventaire);
        $inventaire->setIdaventurier(null);
    }

    public function hasInventaire(Inventaire $inventaire) {
        return $this->inventaires->contains($inventaire);
    }

    public function getInventaires() {
        return $this->inventaires;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrganisationAppartenance", mappedBy="idaventurier", cascade={"remove"})
     */
    private $organisationappartenances;

    public function addOrganisationAppartenance(OrganisationAppartenance $oa) {
        $this->organisationappartenances[] = $oa;
        $oa->setIdaventurier($this);
        return $this;
    }

    public function removeOrganisationAppartenance(OrganisationAppartenance $oa) {
        $this->organisationappartenances->removeElement($oa);
        $oa->setIdaventurier(null);
    }

    public function hasOrganisationAppartenance(OrganisationAppartenance $oa) {
        return $this->organisationappartenances->contains($oa);
    }

    public function getOrganisationAppartenances() {
        return $this->organisationappartenances;
    }

    /**
     * 
     * @param Organisation $o
     * @return OrganisationAppartenance
     */
    public function getOrganisationAppartenance(Organisation $o) {
        $liste = $this->getOrganisationAppartenances();
        foreach ($liste as $app) {
            if ($app->getIdOrganisation() == $o)
                return $app;
        }
        return null;
    }

    /**
     * Constructor
     */
    public function __construct() {
        //$this->idorganisation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inventaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relations1 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relations2 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organisationappartenances = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Aventurier
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set acrobatie
     *
     * @param integer $acrobatie
     * @return Aventurier
     */
    public function setAcrobatie($acrobatie) {
        $this->acrobatie = $acrobatie;

        return $this;
    }

    /**
     * Get acrobatie
     *
     * @return integer 
     */
    public function getAcrobatie() {
        return $this->acrobatie;
    }

    /**
     * Set bagarre
     *
     * @param integer $bagarre
     * @return Aventurier
     */
    public function setBagarre($bagarre) {
        $this->bagarre = $bagarre;

        return $this;
    }

    /**
     * Get bagarre
     *
     * @return integer 
     */
    public function getBagarre() {
        return $this->bagarre;
    }

    /**
     * Set charme
     *
     * @param integer $charme
     * @return Aventurier
     */
    public function setCharme($charme) {
        $this->charme = $charme;

        return $this;
    }

    /**
     * Get charme
     *
     * @return integer 
     */
    public function getCharme() {
        return $this->charme;
    }

    /**
     * Set acuite
     *
     * @param integer $acuite
     * @return Aventurier
     */
    public function setAcuite($acuite) {
        $this->acuite = $acuite;

        return $this;
    }

    /**
     * Get acuite
     *
     * @return integer 
     */
    public function getAcuite() {
        return $this->acuite;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Aventurier
     */
    public function setAge($age) {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge() {
        return $this->age;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     * @return Aventurier
     */
    public function setExperience($experience) {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return integer 
     */
    public function getExperience() {
        return $this->experience;
    }

    /**
     * Set renommee
     *
     * @param integer $renommee
     * @return Aventurier
     */
    public function setRenommee($renommee) {
        $this->renommee = $renommee;

        return $this;
    }

    /**
     * Get renommee
     *
     * @return integer 
     */
    public function getRenommee() {
        return $this->renommee;
    }

    /**
     * Set renommeeMax
     *
     * @param integer $renommeeMax
     * @return Aventurier
     */
    public function setRenommeeMax($renommeeMax) {
        $this->renommeeMax = $renommeeMax;

        return $this;
    }

    /**
     * Get renommeeMax
     *
     * @return integer 
     */
    public function getRenommeeMax() {
        return $this->renommeeMax;
    }

    /**
     * Set pvie
     *
     * @param integer $pvie
     * @return Aventurier
     */
    public function setPvie($pvie) {
        $this->pvie = $pvie;

        return $this;
    }

    /**
     * Get pvie
     *
     * @return integer 
     */
    public function getPvie() {
        return $this->pvie;
    }

    /**
     * Set pvieMax
     *
     * @param integer $pvieMax
     * @return Aventurier
     */
    public function setPvieMax($pvieMax) {
        $this->pvieMax = $pvieMax;

        return $this;
    }

    /**
     * Get pvieMax
     *
     * @return integer 
     */
    public function getPvieMax() {
        return $this->pvieMax;
    }

    /**
     * Set argent
     *
     * @param float $argent
     * @return Aventurier
     */
    public function setArgent($argent) {
        $this->argent = $argent;

        return $this;
    }

    /**
     * Get argent
     *
     * @return float 
     */
    public function getArgent() {
        return $this->argent;
    }

    /**
     * Set mana
     *
     * @param integer $mana
     * @return Aventurier
     */
    public function setMana($mana) {
        $this->mana = $mana;

        return $this;
    }

    /**
     * Get mana
     *
     * @return integer 
     */
    public function getMana() {
        return $this->mana;
    }

    /**
     * Set manaMax
     *
     * @param integer $manaMax
     * @return Aventurier
     */
    public function setManaMax($manaMax) {
        $this->manaMax = $manaMax;

        return $this;
    }

    /**
     * Get manaMax
     *
     * @return integer 
     */
    public function getManaMax() {
        return $this->manaMax;
    }

    /**
     * Set pact
     *
     * @param integer $pact
     * @return Aventurier
     */
    public function setPact($pact) {
        $this->pact = $pact;

        return $this;
    }

    /**
     * Get pact
     *
     * @return integer 
     */
    public function getPact() {
        return $this->pact;
    }

    /**
     * Set pdep
     *
     * @param integer $pdep
     * @return Aventurier
     */
    public function setPdep($pdep) {
        $this->pdep = $pdep;

        return $this;
    }

    /**
     * Get pdep
     *
     * @return integer 
     */
    public function getPdep() {
        return $this->pdep;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Aventurier
     */
    public function setPosition($position) {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Set posx
     *
     * @param integer $posx
     * @return Aventurier
     */
    public function setPosx($posx) {
        $this->posx = $posx;

        return $this;
    }

    /**
     * Get posx
     *
     * @return integer 
     */
    public function getPosx() {
        return $this->posx;
    }

    /**
     * Set posy
     *
     * @param integer $posy
     * @return Aventurier
     */
    public function setPosy($posy) {
        $this->posy = $posy;

        return $this;
    }

    /**
     * Get posy
     *
     * @return integer 
     */
    public function getPosy() {
        return $this->posy;
    }

    /**
     * Set pouvoirs
     *
     * @param string $pouvoirs
     * @return Aventurier
     */
    public function setPouvoirs($pouvoirs) {
        $this->pouvoirs = $pouvoirs;

        return $this;
    }

    /**
     * Get pouvoirs
     *
     * @return string 
     */
    public function getPouvoirs() {
        return $this->pouvoirs;
    }

    /**
     * Set talents
     *
     * @param string $talents
     * @return Aventurier
     */
    public function setTalents($talents) {
        $this->talents = $talents;

        return $this;
    }

    /**
     * Get talents
     *
     * @return string 
     */
    public function getTalents() {
        return $this->talents;
    }

    /**
     * Set envoutement
     *
     * @param string $envoutement
     * @return Aventurier
     */
    public function setEnvoutement($envoutement) {
        $this->envoutement = $envoutement;

        return $this;
    }

    /**
     * Get envoutement
     *
     * @return string 
     */
    public function getEnvoutement() {
        return $this->envoutement;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Aventurier
     */
    public function setPoints($points) {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     * @return Aventurier
     */
    public function setEtat($etat) {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer 
     */
    public function getEtat() {
        return $this->etat;
    }

    /**
     * Add idorganisation
     *
     * @param \ndj\DGameBundle\Entity\Organisation $idorganisation
     * @return Aventurier
     */
    /* public function addIdorganisation(\ndj\DGameBundle\Entity\Organisation $idorganisation)
      {
      $this->idorganisation[] = $idorganisation;
      return $this;
      } */

    /**
     * Remove idorganisation
     *
     * @param \ndj\DGameBundle\Entity\Organisation $idorganisation
     */
    /* public function removeIdorganisation(\ndj\DGameBundle\Entity\Organisation $idorganisation)
      {
      $this->idorganisation->removeElement($idorganisation);
      } */

    /**
     * Get idorganisation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    /* public function getIdorganisation()
      {
      return $this->idorganisation;
      } */

    /**
     * Set idmembre
     *
     * @param \ndj\UserBundle\Entity\User $idmembre
     * @return Aventurier
     */
    public function setIdmembre(\ndj\UserBundle\Entity\User $idmembre = null) {
        $this->idmembre = $idmembre;

        return $this;
    }

    /**
     * Get idmembre
     *
     * @return \ndj\UserBundle\Entity\User
     */
    public function getIdmembre() {
        return $this->idmembre;
    }

    /**
     * Set idcreature
     *
     * @param \ndj\DGameBundle\Entity\Creature $idcreature
     * @return Aventurier
     */
    public function setIdcreature(\ndj\DGameBundle\Entity\Creature $idcreature = null) {
        $this->idcreature = $idcreature;

        return $this;
    }

    /**
     * Get idcreature
     *
     * @return \ndj\DGameBundle\Entity\Creature 
     */
    public function getIdcreature() {
        return $this->idcreature;
    }

    /**
     * Set idpiece
     *
     * @param \ndj\DGameBundle\Entity\Piece $idpiece
     * @return Aventurier
     */
    public function setIdpiece(\ndj\DGameBundle\Entity\Piece $idpiece = null) {
        $this->idpiece = $idpiece;

        return $this;
    }

    /**
     * Get idpiece
     *
     * @return \ndj\DGameBundle\Entity\Piece 
     */
    public function getIdpiece() {
        return $this->idpiece;
    }
    

    public function getIddonjon() {
        return (!is_null($this->getIdpiece())) ? $this->getIdpiece()->getIddonjon() : null;
    }

    public function getDonjon() {
        return $this->getIddonjon();
    }

    public function isOnMap() {
        return ($this->getPosition() != '' && $this->getPosition() != '{T}' && $this->getPosition() != '{M}' && !is_null($this->getPosition()));
    }

    public function isMort() {
        return ($this->getPosition() == '{M}');
    }

    public function displayPOSITION() {
        $p = '?';
        switch ($this->getPosition()) {
            case NULL: case '{T}' : $p = 'à la taverne';
                break;
            case '{M}' : $p = 'mort !';
                break;
            default :
                $pos = Tools::explodex(',', $this->getPosition());
                $piece = $this->getIdpiece();
                $etage = $piece->getIdetage();
                $donjon = $etage->getIddonjon();

                $p = 'dans le donjon<br />"<a href="javascript:void(0);" onclick="displayFiche(\'donjon\',' . $donjon->getId() . ')">' . $donjon->getNom() . '</a>" ';
                $p .= '(en ' . $pos[0] . ',' . $pos[1] . ' dans "' . $piece->getNom() . '", <span title="' . $etage->getnom() . '">niv. ' . $etage->getNiveau() . '</span>)';
                break;
        }
        return $p;
    }

      public function toArray() {
        $_return = array();
        foreach ($this as $key => $val) {
            if (is_object($val) && strpos(get_class($val), 'ndj\DGameBundle\Entity') !== false) {
                $_return[$key] = $val->getId();
            } else {
                $_return[$key] = $val;
            }
        }
        return $_return;
    }
    
    
    public function level_up($n = null) {
        $this->setPOINTS($this->getPOINTS() + caracs::POINTS_LEVELUP);

        // evenement::create($this, 'Tu es maintenant niveau '.caracs::XP_getLevel($this->EXPERIENCE).' !');
        // evenement::create($this, '',1);
    }
    
     /**
     * Recette
     * @param double $somme
     */
    public function recette($somme) {
        $this->setArgent($this->getArgent() + $somme);
        return $this;
    }

    /**
     * Dépense
     * @param double $somme
     * @return boolean
     */
    public function depense($somme) {
        if ($somme > $this->getArgent())
            return false;
        $this->setArgent($this->getArgent() - $somme);
        return true;
    }

    public function achat($o) {
        if ($this->depense($o->calculerPrix())) {
            $o->setProprio($this);
            return true;
        }
        return false;
    }
    
    /**
     * Un dépalcement est-il possible (suffisamenet de point de déplacement) ?
     * @return boolean
     */
    public function deplacement($p = 1) {
        $p = (int) $p;
        if ($this->getPdep() >= $p) {
            $this->setPdep((int) $this->getPdep() - $p);
            return true;
        } else {
            return false;
        }
    }
    
    
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
    
    
    public function gain_xp($xp) {
        $xp = ceil($xp);
        $next = caracs::XP_getNextLevel($this->getEXPERIENCE());
        $this->setEXPERIENCE($this->getEXPERIENCE() + $xp);
        if ($this->getEXPERIENCE() >= $next) {
            $this->level_up();
        }
        return $xp;
    }
    
    
    /**
     * vérifie si un element $e est a porté de l'aventurier
     * @param object $e
     * @return boolean
     */
    public function a_porte(PositionnableTrait $e) {
        $p = Tools::explodex(',', $e->getPOSITION());
        return ($e->getIdpiece() === $this->getIdpiece() && $this->a_porte_coord($p[0], $p[1]));
    }

    /**
     * vérifie si les coordonnées $x et $y sont a porté de l'aventurier
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function a_porte_coord($x, $y) {
        $a = Tools::explodex(',', $this->getPosition());
        return (abs($x - $a[0] + $y - $a[1]) < 2);
    }

    // @TODO : prendre en compte da distance (les armes de jets)
    public function a_porte_attaque(PositionnableTrait $e) {
        return $this->a_porte($e);
    }

    public function deplacer(Piece $p, $x, $y, $force = false) {

        if (!$force && ($p !== $this->getIdpiece() || !$this->a_porte_coord($x, $y))) {
            throw new \UnexpectedValueException("Déplacement impossible !");
        }
/*
 * __CLASS__
        $class = str_replace('ndj\\DGameBundle\\Entity\\', '', get_class($this));
        $method_name = 'add' . ucfirst(strtolower($class));
        if (method_exists($p, $method_name)) {
            $p->$method_name($this);
        } else {
            throw new \UnexpectedValueException("Impossible de localiser un '" . $class . "' !");
        }
*/
        return $this->setPosition('{' . $x . '' . $y . '}');
    }
}
