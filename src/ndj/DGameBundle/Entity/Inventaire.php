<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ndj\DGameBundle\Entity\Piece;
use ndj\DGameBundle\Util\Tools;
use ndj\DGameBundle\Util\caracs;

/**
 * Inventaire
 *
 * @ORM\Table(name="INVENTAIRE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\InventaireRepository")
 */
class Inventaire {

  // use PositionnableTrait;
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
     * @ORM\Column(name="NOM", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="AGE", type="smallint", nullable=false)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="BONUS", type="string", length=255, nullable=true)
     */
    private $bonus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="USURE", type="boolean", nullable=false)
     */
    private $usure;

    /**
     * @var boolean
     *
     * @ORM\Column(name="QUALITE", type="boolean", nullable=false)
     */
    private $qualite;

    /**
     * @var string
     *
     * @ORM\Column(name="POSITION", type="string", length=45, nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="ENVOUTEMENT", type="string", length=255, nullable=true)
     */
    private $envoutement;

    /**
     * @var \Objets
     *
     * @ORM\ManyToOne(targetEntity="Objets", inversedBy="inventaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idOBJETS", referencedColumnName="id")
     * })
     */
    private $idobjets;

    /**
     * @var \Donjon
     *
     * @ORM\ManyToOne(targetEntity="Donjon", inversedBy="inventaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDONJON", referencedColumnName="id")
     * })
     */
    private $iddonjon;

    /**
     * @var \Aventurier
     *
     * @ORM\ManyToOne(targetEntity="Aventurier", inversedBy="inventaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAVENTURIER", referencedColumnName="id")
     * })
     */
    private $idaventurier;

    /**
     * @var \Bestiaire
     *
     * @ORM\ManyToOne(targetEntity="Bestiaire", inversedBy="inventaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idBESTIAIRE", referencedColumnName="id")
     * })
     */
    private $idbestiaire;

    /**
     * @var \Banque
     *
     * @ORM\ManyToOne(targetEntity="Banque", inversedBy="inventaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCOMPTE", referencedColumnName="id")
     * })
     */
    private $idcompte;

    /**
     * @var \Piece
     *
     * @ORM\ManyToOne(targetEntity="Piece", inversedBy="inventaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPIECE", referencedColumnName="id")
     * })
     */
    private $idpiece;

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
     * @return Inventaire
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
     * Set age
     *
     * @param integer $age
     * @return Inventaire
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
     * Set bonus
     *
     * @param string $bonus
     * @return Inventaire
     */
    public function setBonus($bonus) {
        $this->bonus = $bonus;

        return $this;
    }

    /**
     * Get bonus
     *
     * @return string 
     */
    public function getBonus() {
        return $this->bonus;
    }

    /**
     * Set usure
     *
     * @param boolean $usure
     * @return Inventaire
     */
    public function setUsure($usure) {
        $this->usure = $usure;

        return $this;
    }

    /**
     * Get usure
     *
     * @return boolean 
     */
    public function getUsure() {
        return $this->usure;
    }

    /**
     * Set qualite
     *
     * @param boolean $qualite
     * @return Inventaire
     */
    public function setQualite($qualite) {
        $this->qualite = $qualite;

        return $this;
    }

    /**
     * Get qualite
     *
     * @return boolean 
     */
    public function getQualite() {
        return $this->qualite;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Inventaire
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
     * Set envoutement
     *
     * @param string $envoutement
     * @return Inventaire
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
     * Set idobjets
     *
     * @param \ndj\DGameBundle\Entity\Objets $idobjets
     * @return Inventaire
     */
    public function setIdobjets(\ndj\DGameBundle\Entity\Objets $idobjets = null) {
        $this->idobjets = $idobjets;

        return $this;
    }

    /**
     * Get idobjets
     *
     * @return \ndj\DGameBundle\Entity\Objets 
     */
    public function getIdobjets() {
        return $this->idobjets;
    }

    /**
     * Set iddonjon
     *
     * @param \ndj\DGameBundle\Entity\Donjon $iddonjon
     * @return Inventaire
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
     * Set idaventurier
     *
     * @param \ndj\DGameBundle\Entity\Aventurier $idaventurier
     * @return Inventaire
     */
    public function setIdaventurier(\ndj\DGameBundle\Entity\Aventurier $idaventurier = null) {
        $this->idaventurier = $idaventurier;

        return $this;
    }

    /**
     * Get idaventurier
     *
     * @return \ndj\DGameBundle\Entity\Aventurier 
     */
    public function getIdaventurier() {
        return $this->idaventurier;
    }

    /**
     * Set idbestiaire
     *
     * @param \ndj\DGameBundle\Entity\Bestiaire $idbestiaire
     * @return Inventaire
     */
    public function setIdbestiaire(\ndj\DGameBundle\Entity\Bestiaire $idbestiaire = null) {
        $this->idbestiaire = $idbestiaire;

        return $this;
    }

    /**
     * Get idbestiaire
     *
     * @return \ndj\DGameBundle\Entity\Bestiaire 
     */
    public function getIdbestiaire() {
        return $this->idbestiaire;
    }

    /**
     * Set idcompte
     *
     * @param \ndj\DGameBundle\Entity\Banque $idcompte
     * @return Inventaire
     */
    public function setIdcompte(\ndj\DGameBundle\Entity\Banque $idcompte = null) {
        $this->idcompte = $idcompte;

        return $this;
    }

    /**
     * Get idcompte
     *
     * @return \ndj\DGameBundle\Entity\Banque 
     */
    public function getIdcompte() {
        return $this->idcompte;
    }

    /**
     * Set idpiece
     *
     * @param \ndj\DGameBundle\Entity\Piece $idpiece
     * @return Inventaire
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

    /**
     * Calcul le prix de l'objet
     * @return double
     */
    public function calculerPrix() {
        return round($this->getIdobjets()->getPrix() * caracs::getQualiteFactor($this->getQualite()), 2);
    }

    public function setProprio(Acheteur $j) {
        $type = explode("\\", get_class($j));
        $type = array_pop($type);
        $this->setIdaventurier(null);
        $this->setIddonjon(null);
        $this->setIdbestiaire(null);
        $this->setIdcompte(null);

        $method = 'setId' . $type;
        $this->$method($j);

        $this->setPosition('{I}');
    }

    public function getProprio() {
        if (!is_null($this->getIdaventurier()))
            return $this->getIdaventurier();
        if (!is_null($this->getIddonjon()))
            return $this->getIddonjon();
    }

    public function displayPOSITION() {
        $pos = $this->getPosition();
        if ($pos == '{V}') {
            return 'En vente';
        }
        if ($pos == '{I}') {
            return 'Dans tes coffres';
        }
        if ($pos[1] == 'E') {
            return 'Equipé';
        }
        $pos = Tools::explodex(',', $pos);
        if (count($pos) == 2) {
            $piece = $this->getIdpiece();
            return 'Dans "' . $piece->getNom() . '" en (' . $pos[0] . ',' . $pos[1] . ')';
        }
    }

    public function drop() {
        if (!is_null($a = $this->getIdaventurier())) {
            $a->removeInventaire($this);
            $a->getIdpiece()->addInventaire($this);
            $this->setIddonjon($a->getIddonjon());
            $this->setPOSITION($a->getPOSITION());
        } elseif (!is_null($b = $this->getIdbestiaire())) {
            $b->removeInventaire($this);
            $b->getIdpiece()->addInventaire($this);
            $this->setIddonjon($b->getIddonjon());
            $this->setPOSITION($b->getPOSITION());
        }
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
     * Vérifie si l'objet est équipé
     * @retrun boolean
     */
    public function isEquipe() {
        return isset($this->position[1]) && $this->position[1]=='E';
    }

}