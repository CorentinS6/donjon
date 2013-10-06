<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ndj\UserBundle\Entity;
use ndj\DGameBundle\Util\caracs;
use ndj\DGameBundle\Util\Tools;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Donjon
 *
 * @ORM\Table(name="DONJON")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\DonjonRepository")
 */
class Donjon {

    //use AcheteurTrait;

    //use SerialisableArrayTrait;

    //use LevelableTrait;

    //use JoueurActionTrait;

    //use OuvrableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_CREATION", type="date", nullable=false)
     * @Assert\Date()
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_OUVERTURE", type="date", nullable=true)
     * @Assert\Date()
     */
    private $dateOuverture;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=45, nullable=false)
     * @Assert\Length(min=5,max=45,minMessage="Le nom du donjon doit faire au moins 5 caractères.",maxMessage="Le nom du donjon doit faire au plus 45 caractères.")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="text", nullable=true)
     * @Assert\Length(min=10,minMessage="Merci de détailler davantage la description de votre donjon.")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="ARGENT", type="float", nullable=false)
     */
    private $argent;

    /**
     * @var integer
     *
     * @ORM\Column(name="ETAT", type="smallint", nullable=false)
     * @Assert\Range(min=-3,max=2)
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="PACT", type="smallint", nullable=false)
     */
    private $pact;

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
     * @ORM\Column(name="EXPERIENCE", type="integer", nullable=false)
     */
    private $experience;

    /**
     * @var \ndj\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\ndj\UserBundle\Entity\User",inversedBy="donjons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idMEMBRE", referencedColumnName="id")
     * })
     */
    private $idmembre;

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Inventaire", mappedBy="iddonjon")
     */
    private $inventaires;

    public function addInventaire(Inventaire $inventaire) {
        $this->inventaires[] = $inventaire;
        $inventaire->setIddonjon($this);
        return $this;
    }

    public function removeInventaire(Inventaire $inventaire) {
        $this->inventaires->removeElement($inventaire);
        $inventaire->setIddonjon(null);
    }

    public function hasInventaire(Inventaire $inventaire) {
        return $this->inventaires->contains($inventaire);
    }

    public function getInventaires() {
        return $this->inventaires;
    }

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Etage", mappedBy="iddonjon")
     */
    private $etages;

    public function addEtage(Etage $etage) {
        $this->etages[] = $etage;
        $etage->setIddonjon($this);
        return $this;
    }

    public function removeEtage(Etage $etage) {
        $this->etages->removeElement($etage);
        $etage->setIddonjon(null);
    }

    public function hasEtage(Etage $etage) {
        return $this->etages->contains($etage);
    }

    public function getEtages() {
        return $this->etages;
    }

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Bestiaire", mappedBy="iddonjon")
     */
    private $bestiaires;

    public function addBestiaire(Bestiaire $bestiaire) {
        $this->bestiaires[] = $bestiaire;
        $bestiaire->setIddonjon($this);
        return $this;
    }

    public function removeBestiaire(Bestiaire $bestiaire) {
        $this->bestiaires->removeElement($bestiaire);
        $bestiaire->setIddonjon(null);
    }

    public function hasBestiaire(Bestiaire $bestiaire) {
        return $this->bestiaires->ontains($bestiaire);
    }

    public function getBestiaires() {
        return $this->bestiaires;
    }

    // Pieces

    public function getPieces() {
        $_return = array();
        $etages = $this->getEtages();
        foreach ($etages as $e) {
            $pieces = $e->getPieces();
            foreach ($pieces as $p) {
                $_return[] = $p;
            }
        }
        return $_return;
    }

    public function getPiecesOpen() {
        $_return = array();
        $etages = $this->getEtages();
        foreach ($etages as $e) {
            $pieces = $e->getPieces();
            foreach ($pieces as $p) {
                if ($p->getEtat() == 2)
                    $_return[] = $p;
            }
        }
        return $_return;
    }

    public function hasPiece(Piece $piece) {
        return array_search($this->getPieces(), $piece, true);
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->inventaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bestiaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Donjon
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Set dateOuverture
     *
     * @param \DateTime $dateOuverture
     * @return Donjon
     */
    public function setDateOuverture($dateOuverture) {
        $this->dateOuverture = $dateOuverture;

        return $this;
    }

    /**
     * Get dateOuverture
     *
     * @return \DateTime 
     */
    public function getDateOuverture() {
        return $this->dateOuverture;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Donjon
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
     * Set description
     *
     * @param string $description
     * @return Donjon
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set argent
     *
     * @param float $argent
     * @return Donjon
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
     * Set etat
     *
     * @param integer $etat
     * @return Donjon
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
     * Set pact
     *
     * @param integer $pact
     * @return Donjon
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
     * Set renommee
     *
     * @param integer $renommee
     * @return Donjon
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
     * @return Donjon
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
     * Set experience
     *
     * @param integer $experience
     * @return Donjon
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
     * Set idmembre
     *
     * @param \ndj\UserBundle\Entity\User $idmembre
     * @return Donjon
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

    public function getSuperficie() {
        $pieces = $this->getPieces();
        $s = 0;
        foreach ($pieces as $p) {
            $s += $p->getSuperficie();
        }
        return $s;
    }

    public function getARGENT_AU_SOL() {
        $pieces = $this->getPieces();
        $s = 0;
        foreach ($pieces as $p) {
            $tmp = Tools::explodex('}{', $p->getActions());
            foreach ($tmp as $_a) {
                if (strlen($_a) > 0) {
                    $_a = explode(',', $_a);
                    if ($_a[2] == 'PO') {
                        $s+= $_a[3];
                    }
                }
            }
        }
        return $s;
    }

    public function getValeurMurs() {
        $s = 0;
        foreach ($this->getEtages() as $e) {
            $s += Etage::coutNouvelEtage($e->getTaille());
        }
        $s += $this->getSuperficie() * Piece::PRIX_CREATION_FACTOR;
        return $s;
    }

    public function getEtageSuperieur(Etage $etage) {
        $liste = $this->getEtages();
        $_result = array();
        foreach ($liste as $e) {
            if ($e->getNiveau() == $etage->getNiveau() + 1) {
                return $e;
            }
        }
        return null;
    }

    public function getEtageInferieur(Etage $etage) {
        $liste = $this->getEtages();
        $_result = array();
        foreach ($liste as $e) {
            if ($e->getNiveau() == $etage->getNiveau() - 1) {
                return $e;
            }
        }
        return null;
    }

    public function getRandomStartPosition() {
        $pieces = $this->getPiecesOpen();
        shuffle($pieces);
        // action contient "%,Z}%"
        $piece = null;
        foreach ($pieces as $p) {
            if (strpos($p->getActions(), ',Z}') !== false) {
                $piece = $p;
                break;
            }
        }

        if (is_null($piece))
            return false;

        $act = Tools::explodex('}{', $piece->getActions());
        $coord = array();
        foreach ($act as $v) {
            $v = explode(',', $v);
            if ($v[2] == 'Z') {
                $coord[] = $v[0] . ',' . $v[1];
            }
        }
        $xy = $coord[array_rand($coord)];

        return array($piece->getId(), '{' . $xy . '}');
    }

    public function setOpen() {

        if ($this instanceof Donjon)
            if (!$this->isBuilding())
                return false;

        $p = $this->getPieces();
        $check_salleopen = false;
        $check_start = false;
        $check_end = false;
        foreach ($p as $piece) {
            if ($piece->isOpen()) {
                $check_salleopen = true;

                if (!$check_start && $piece->actionExist('Z')) {
                    $check_start = true;
                }

                if (!$check_end && $piece->actionExist('F')) {
                    $check_end = true;
                }
            }
        }
        $check = ($check_salleopen && $check_start && $check_end);

        if ($check) {
            $this->setEtat(2);
        }
        return $check;
    }

    public function setClose() {
        $cptav = 0;
        $p = $this->getPieces();
        foreach ($p as $piece) {
            $cptav += count($piece->getAventuriers());
        }

        $check = ($cptav == 0);

        if ($check) {
            $this->setEtat(1);
        }

        return $check;
    }

    public function level_up($n = null) {
        $this->setPOINTS($this->getPOINTS() + caracs::POINTS_LEVELUP);

        // evenement::create($this, 'Tu es maintenant niveau '.caracs::XP_getLevel($this->EXPERIENCE).' !');
        // evenement::create($this, '',1);
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
    
    
    public function getEtats() {
        return array(
            -3 => 'Fermé dans 3 tour(s)',
            -2 => 'Fermé dans 2 tour(s)',
            -1 => 'Fermé dans 1 tour(s)',
            0 => 'Désactivé',
            1 => 'En construction',
            2 => 'Ouvert au aventurier'
        );
    }

    public function isClosingSoon() {
        return ($this->getETAT() < 0) ? $this->getETAT() : false;
    }

    public function isOpen() {
        return ($this->getETAT() == 2);
    }

    public function isBuilding() {
        return ($this->getETAT() == 1);
    }

    public function displayEtat() {
        //if ($this->getETAT() >= 0) {
        return $this->getEtats()[$this->getETAT()];
        /* } else {
          $ret = self::$_etat[-1];
          $ret = str_replace('{X}', $this->getETAT() * -1, $ret);
          return $ret;
          } */
    }

    public function setCloseDelay() {
        if ($this->isOpen()) {
            $this->setEtat(-3);
            return true;
        }
        return false;
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
}