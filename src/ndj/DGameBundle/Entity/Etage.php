<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etage
 *
 * @ORM\Table(name="ETAGE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\EtageRepository")
 */
class Etage {

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
     * @ORM\Column(name="NIVEAU", type="smallint", nullable=false)
     */
    private $niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="TAILLE", type="integer", nullable=false)
     */
    private $taille;

    /**
     * @var \Donjon
     *
     * @ORM\ManyToOne(targetEntity="Donjon", inversedBy="etages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDONJON", referencedColumnName="id")
     * })
     */
    private $iddonjon;

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Piece", mappedBy="idetage")
     */
    private $pieces;

    public function addPiece(Piece $piece) {
        $this->pieces[] = $piece;
        $piece->setidetage($this);
        return $this;
    }

    public function removePiece(Piece $piece) {
        $this->pieces->removeElement($piece);
        $piece->setidetage(null);
    }

    public function hasPiece(Piece $piece) {
        return $this->pieces->contains($piece);
    }

    public function getPieces() {
        return $this->pieces;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->pieces = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set niveau
     *
     * @param boolean $niveau
     * @return Etage
     */
    public function setNiveau($niveau) {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return boolean 
     */
    public function getNiveau() {
        return $this->niveau;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Etage
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
     * Set taille
     *
     * @param integer $taille
     * @return Etage
     */
    public function setTaille($taille) {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return integer 
     */
    public function getTaille() {
        return $this->taille;
    }

    /**
     * Set iddonjon
     *
     * @param \ndj\DGameBundle\Entity\Donjon $iddonjon
     * @return Etage
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

    public function getSuperficie() {
        $pieces = $this->getPieces();
        $s = 0;
        foreach ($pieces as $p) {
            $s += $p->getSuperficie();
        }
        return $s;
    }

    // coef. pour le coput d'un nouvel étage (* taille * taille)

    const COUT_NOUV_ETAGE = 5;

    static public function coutNouvelEtage($t) {
        return $t * self::COUT_NOUV_ETAGE;
    }

    /**
     * Renvoie l'étage du dessus, null sinon
     * @return Etage|NULL
     */
    public function getSuperieur() {
        return $this->getIddonjon()->getEtageSuperieur($this);
    }

    /**
     * Renvoie l'étage du dessous, null sinon
     * @return Etage|NULL
     */
    public function getInferieur() {
        return $this->getIddonjon()->getEtageInferieur($this);
    }

    /**
     * trouver la piece se trouvant en x,y
     * @param int $x
     * @param int $y
     * @return Piece|boolean l'objet piece, ou false si pas de piece
     */
    public function getPiece($x, $y) {
        $liste = $this->getPieces();
        foreach ($liste as $p) {
            if ($p->getPosx() <= $x && $x <= ($p->getPosx() + $p->getTaillex()) &&
                    $p->getPosy() <= $y && $y <= ($p->getPosy() + $p->getTailley())) {
                return $p;
            }
        }
        return null;
    }

    /**
     * Vérifie la présence d'une pièce dans la position x,y
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function isTherePiece($x, $y) {
        $liste = $this->getPieces();
        foreach ($liste as $p) {
            if ($p->getPosx() <= $x && $x <= ($p->getPosx() + $p->getTaillex()) &&
                    $p->getPosy() <= $y && $y <= ($p->getPosy() + $p->getTailley())) {
                return true;
            }
        }
        return false;
    }

    /* nom par défaut en fonction du niveau */

    static protected $_noms = array(
        '-2' => 'eme sous-sol',
        '-1' => 'er sous-sol',
        '0' => 'Rez de chauss&eacute;',
        '1' => 'er  &eacute;tage',
        '2' => '&egrave;me &eacute;tage',
    );

    /**
     * Retourne le nom en fonction du niveau
     * @param int $niv
     * @return string nom
     */
    static public function getDefaultNom($niv) {
        $cle = '';
        if ($niv < -1)
            $cle = '-2';
        elseif ($niv == -1)
            $cle = '-1';
        elseif ($niv == 0)
            $cle = '0';
        elseif ($niv == 1)
            $cle = '1';
        elseif ($niv > 1)
            $cle = '2';

        return (($niv != 0) ? abs((int) $niv) : '' )
                . self::$_noms[$cle];
    }

}