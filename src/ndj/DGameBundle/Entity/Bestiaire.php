<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Util\caracs;

/**
 * Bestiaire
 *
 * @ORM\Table(name="BESTIAIRE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\BestiaireRepository")
 */
class Bestiaire {

   // use LevelableTrait;
  //  use PositionnableTrait;
  //  use AchetableTrait;
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
     * @ORM\Column(name="PRENOM", type="string", length=45, nullable=false)
     */
    private $prenom;

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
     * @var boolean
     *
     * @ORM\Column(name="REPOS", type="boolean", nullable=false)
     */
    private $repos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="A_VENDRE", type="boolean", nullable=false)
     */
    private $aVendre;

    /**
     * @var float
     *
     * @ORM\Column(name="COUT", type="decimal", nullable=false)
     */
    private $cout;

    /**
     * @var string
     *
     * @ORM\Column(name="POSITION", type="string", length=45, nullable=true)
     */
    private $position;

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
     * @var string
     *
     * @ORM\Column(name="ORDRE", type="string", length=255, nullable=true)
     */
    private $ordre;

    /**
     * @var integer
     *
     * @ORM\Column(name="POINTS", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var \Donjon
     *
     * @ORM\ManyToOne(targetEntity="Donjon",  inversedBy="bestiaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDONJON", referencedColumnName="id")
     * })
     */
    private $iddonjon;

    /**
     * @var \Creature
     *
     * @ORM\ManyToOne(targetEntity="Creature", inversedBy="bestiaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCREATURE", referencedColumnName="id")
     * })
     */
    private $idcreature;

    /**
     * @var \Piece
     *
     * @ORM\ManyToOne(targetEntity="Piece", inversedBy="bestiaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPIECE", referencedColumnName="id")
     * })
     */
    private $idpiece;

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Inventaire", mappedBy="idbestiaire")
     */
    private $inventaires;

    public function addInventaire(Inventaire $inventaire) {
        $this->inventaires[] = $inventaire;
        $inventaire->setIdbestiaire($this);
        return $this;
    }

    public function removeInventaire(Inventaire $inventaire) {
        $this->inventaires->removeElement($inventaire);
        $inventaire->setIdbestiaire(null);
    }

    public function hasInventaire(Inventaire $inventaire) {
        return $this->inventaires->contains($inventaire);
    }

    public function getInventaires() {
        return $this->inventaires;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->inventaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set prenom
     *
     * @param string $prenom
     * @return Bestiaire
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom() {
        return $this->prenom;
    }

    public function getNom() {
        return $this->getPrenom();
    }

    /**
     * Set acrobatie
     *
     * @param integer $acrobatie
     * @return Bestiaire
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
     * @return Bestiaire
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
     * @return Bestiaire
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
     * @return Bestiaire
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
     * @return Bestiaire
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
     * @return Bestiaire
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
     * @return Bestiaire
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
     * Set pvie
     *
     * @param integer $pvie
     * @return Bestiaire
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
     * @return Bestiaire
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
     * Set repos
     *
     * @param boolean $repos
     * @return Bestiaire
     */
    public function setRepos($repos) {
        $this->repos = $repos;

        return $this;
    }

    /**
     * Get repos
     *
     * @return boolean 
     */
    public function getRepos() {
        return $this->repos;
    }

    /**
     * Set aVendre
     *
     * @param boolean $aVendre
     * @return Bestiaire
     */
    public function setAVendre($aVendre) {
        $this->aVendre = $aVendre;

        return $this;
    }

    /**
     * Get aVendre
     *
     * @return boolean 
     */
    public function getAVendre() {
        return $this->aVendre;
    }

    /**
     * Set cout
     *
     * @param float $cout
     * @return Bestiaire
     */
    public function setCout($cout) {
        $this->cout = $cout;

        return $this;
    }

    /**
     * Get cout
     *
     * @return float 
     */
    public function getCout() {
        return $this->cout;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Bestiaire
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
     * Set pouvoirs
     *
     * @param string $pouvoirs
     * @return Bestiaire
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
     * @return Bestiaire
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
     * @return Bestiaire
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
     * Set ordre
     *
     * @param string $ordre
     * @return Bestiaire
     */
    public function setOrdre($ordre) {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return string 
     */
    public function getOrdre() {
        return $this->ordre;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Bestiaire
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
     * Set iddonjon
     *
     * @param \ndj\DGameBundle\Entity\Donjon $iddonjon
     * @return Bestiaire
     */
    public function setIddonjon(\ndj\DGameBundle\Entity\Donjon $iddonjon = null) {
        $this->iddonjon = $iddonjon;

        return $this;
    }

    /**
     * Get iddonjon
     *
     * @return \ndj\DGameBundle\Entity\Donjon 
     */
    public function getIddonjon() {
        return $this->iddonjon;
    }

    /**
     * Set idcreature
     *
     * @param \ndj\DGameBundle\Entity\Creature $idcreature
     * @return Bestiaire
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
     * @return Bestiaire
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

    public function getEtat() {
        if ($this->getAVendre() == 1)
            return 'vente';
        if ($this->getRepos() == 1)
            return 'repos';
        if (!is_null($this->getIdpiece()))
            return 'salle';
    }

    /**
     * Calcule le prix
     * @return double
     */
    public function calculerPrix() {
        // PRIX d'achat * BONUS expÃ©rience * BONUS renomme * MALUS vie *
        // @TODO : AGEÂ ?
        //
    		 
   		// prix de base
        $p = $this->getIdcreature()->getPrixAchat();
        $n = caracs::XP_getLevel($this->getExperience());
        // calcule du prix
        $prix = $p + ( $p * 0.05 * ($n + exp($n / 10) - 1) )  // bonus expeience
                + ( $p * 0.10 * $this->getRenommee() )  // bonus renommee
                - ( $p * ($this->getPvieMax() - min($this->getPvie(), $this->getPvieMax())) / ($this->getPvieMax())); // malus vie
        //echo ($this->PV_MAX - min($this->PV, $this->PV_MAX))/($this->PV_MAX);
        //echo $prix;
        return round($prix, 2);
    }

    public function setProprio(Acheteur $j) {
        $this->setIdpiece(null);
        $this->setIddonjon($j);
        $this->setPosition('{I}');
    }

    public function displayPOSITION() {
        $p = '?';
        if ($this->getAVENDRE() == 1) {
            return 'En vente';
        }
        if ($this->getREPOS() == 1) {
            return 'Au repos';
        }

        $pos = Tools::explodex(',', $this->getPOSITION());
        if (count($pos) == 2) {
            return 'Dans "' . $this->getIdpiece()->getNom() . '" en (' . $pos[0] . ',' . $pos[1] . ')';
        }
    }

    public function getProprio() {
        return $this->getIddonjon();
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

    public function level_up() {

        $this->setPOINTS($this->getPOINTS() + caracs::POINTS_LEVELUP);
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
    
    /**
     * Calcul la défense d'un bestiaire
     * @return int
     */
    public function getDEFENSE() {
        $defense = 0;
        // parcours de l'inventaire pour ajouter les points de défense des objets équipés
        foreach($this->inventaires as $i) {
            // si l'objet est équipe, on ajoute ses points de défense
            if ($i->isEquipe()) {
                $defense += caracs::jetDe($i->getIdobjets()->getDefense());
            }
        }
        return $defense;
    }

    /**
     * Calcul le dégat d'un bestiaire
     * @return int
     */
    public function getDEGAT() {
        $degat = $this->getIdcreature()->getDegat();
        // parcours de l'inventaire pour ajouter les points d'attaque des objets équipes
        foreach($this->inventaires as $i) {
            // si l'objet est équipe, on ajoute ses points de dégat
            if ($i->isEquipe()) {
                $degat += caracs::jetDe($i->getIdobjets()->getDegat());
            }
        }
        return $degat;
    }

    /**
     * Infligue des dégats, retour vrai si le perso est mort.
     * @param int $degat
     * @return boolean
     */
    public function blessure($degat) {

        $nouvo = $this->getPvie() - $degat;
        $r = false;
        // mort ?pt de vie négatif ou nul ?
        if ($nouvo <= 0) {
            // pouvoir héro ?
            if ($this->hasPouvoir('hero')) {
                if ($this->getPvie() == 0) // si déjà KO, alors mort !
                    $r = $this->mort();
                else
                    $degat -= $this->getPvie();
            } else {
                $r = $this->mort();
            }
        }
        $this->setPvie($this->getPvie()-$degat);
        return $r;
    }
	
    public function mort(){
        //stats::add('MORT', 1, $this);
        // TODO : ce qui se passe lorsque l'on meurt
        //evenement::create($this, '<b>Tu es mort !</b>');
        //evenement::create($this, '<b>Tu es mort !</b>',2);
        $this->setPosition('{M}');
        return true;
    }
    
    /* en attendant les pouvoirs */
    public function hasPouvoir($p) { return false; }

}