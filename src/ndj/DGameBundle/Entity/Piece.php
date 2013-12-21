<?php

namespace ndj\DGameBundle\Entity;

use ndj\DGameBundle\Util\Tools;
use Doctrine\ORM\Mapping as ORM;
use ndj\DGameBundle\Entity\Inventaire;
use ndj\DGameBundle\Entity\Bestiaire;
use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Donjon;

/**
 * Piece
 *
 * @ORM\Table(name="PIECE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\PieceRepository")
 */
class Piece {

    //use SerialisableArrayTrait;
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
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="POSX", type="smallint", nullable=false)
     */
    private $posx;

    /**
     * @var integer
     *
     * @ORM\Column(name="POSY", type="smallint", nullable=false)
     */
    private $posy;

    /**
     * @var integer
     *
     * @ORM\Column(name="TAILLEX", type="smallint", nullable=false)
     */
    private $taillex;

    /**
     * @var integer
     *
     * @ORM\Column(name="TAILLEY", type="smallint", nullable=false)
     */
    private $tailley;

    /**
     * @var string
     *
     * @ORM\Column(name="COUCHE_SOL", type="text", nullable=false)
     */
    private $coucheSol;

    /**
     * @var string
     *
     * @ORM\Column(name="COUCHE_SOL2", type="text", nullable=false)
     */
    private $coucheSol2;

    /**
     * @var string
     *
     * @ORM\Column(name="COUCHE_MOBILIER", type="text", nullable=false)
     */
    private $coucheMobilier;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIONS", type="text", nullable=false)
     */
    private $actions;

    /**
     * @var integer
     *
     * @ORM\Column(name="ETAT", type="smallint", nullable=false)
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="LUMIERE", type="smallint", nullable=false)
     */
    private $lumiere;

    /**
     * @var \Etage
     *
     * @ORM\ManyToOne(targetEntity="Etage", inversedBy="pieces")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idETAGE", referencedColumnName="id")
     * })
     */
    private $idetage;

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Inventaire", mappedBy="idpiece")
     */
    private $inventaires;

    public function addInventaire(Inventaire $inventaire) {
        $this->inventaires[] = $inventaire;
        $inventaire->setIdpiece($this);
        return $this;
    }

    public function removeInventaire(Inventaire $inventaire) {
        $this->inventaires->removeElement($inventaire);
        $inventaire->setIdpiece(null);
    }

    public function hasInventaire(Inventaire $inventaire) {
        return $this->inventaires->contains($inventaire);
    }

    public function getInventaires() {
        return $this->inventaires;
    }

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Bestiaire", mappedBy="idpiece")
     */
    private $bestiaires;

    public function addBestiaire(Bestiaire $bestiaire) {
        $this->bestiaires[] = $bestiaire;
        $bestiaire->setIdpiece($this);
        return $this;
    }

    public function removeBestiaire(Bestiaire $bestiaire) {
        $this->bestiaires->removeElement($bestiaire);
        $bestiaire->setIdpiece(null);
    }

    public function hasBestiaire(Bestiaire $bestiaire) {
        return $this->bestiaires->contains($bestiaire);
    }

    public function getBestiaires() {
        return $this->bestiaires;
    }

    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Aventurier", mappedBy="idpiece")
     */
    private $aventuriers;

    public function addAventurier(Aventurier $aventurier) {
        $this->aventuriers[] = $aventurier;
        $aventurier->setIdpiece($this);
        return $this;
    }

    public function removeAventurier(Aventurier $aventurier) {
        $this->aventuriers->removeElement($aventurier);
        $aventurier->setIdpiece(null);
    }

    public function hasAventurier(Aventurier $aventurier) {
        return $this->aventuriers->contains($aventurier);
    }

    public function getAventuriers() {
        return $this->aventuriers;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->inventaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bestiaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aventuriers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Piece
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
     * Set posx
     *
     * @param integer $posx
     * @return Piece
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
     * @return Piece
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
     * Set taillex
     *
     * @param integer $taillex
     * @return Piece
     */
    public function setTaillex($taillex) {
        $this->taillex = $taillex;

        return $this;
    }

    /**
     * Get taillex
     *
     * @return integer 
     */
    public function getTaillex() {
        return $this->taillex;
    }

    /**
     * Set tailley
     *
     * @param integer $tailley
     * @return Piece
     */
    public function setTailley($tailley) {
        $this->tailley = $tailley;

        return $this;
    }

    /**
     * Get tailley
     *
     * @return integer 
     */
    public function getTailley() {
        return $this->tailley;
    }

    /**
     * Set coucheSol
     *
     * @param string $coucheSol
     * @return Piece
     */
    public function setCoucheSol($coucheSol) {
        $this->coucheSol = $coucheSol;

        return $this;
    }

    /**
     * Get coucheSol
     *
     * @return string 
     */
    public function getCoucheSol() {
        return $this->coucheSol;
    }

    /**
     * Set coucheSol2
     *
     * @param string $coucheSol2
     * @return Piece
     */
    public function setCoucheSol2($coucheSol2) {
        $this->coucheSol2 = $coucheSol2;

        return $this;
    }

    /**
     * Get coucheSol2
     *
     * @return string 
     */
    public function getCoucheSol2() {
        return $this->coucheSol2;
    }

    /**
     * Set coucheMobilier
     *
     * @param string $coucheMobilier
     * @return Piece
     */
    public function setCoucheMobilier($coucheMobilier) {
        $this->coucheMobilier = $coucheMobilier;

        return $this;
    }

    /**
     * Get coucheMobilier
     *
     * @return string 
     */
    public function getCoucheMobilier() {
        return $this->coucheMobilier;
    }

    /**
     * Set actions
     *
     * @param string $actions
     * @return Piece
     */
    public function setActions($actions) {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Get actions
     *
     * @return string 
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     * @return Piece
     */
    public function setEtat($etat) {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean 
     */
    public function getEtat() {
        return $this->etat;
    }

    /**
     * Set lumiere
     *
     * @param boolean $lumiere
     * @return Piece
     */
    public function setLumiere($lumiere) {
        $this->lumiere = $lumiere;

        return $this;
    }

    /**
     * Get lumiere
     *
     * @return boolean 
     */
    public function getLumiere() {
        return $this->lumiere;
    }

    /**
     * Set idetage
     *
     * @param \ndj\DGameBundle\Entity\Etage $idetage
     * @return Piece
     */
    public function setIdetage(\ndj\DGameBundle\Entity\Etage $idetage = null) {
        $this->idetage = $idetage;

        return $this;
    }

    /**
     * Get idetage
     *
     * @return \ndj\DGameBundle\Entity\Etage
     */
    public function getIdetage() {
        return $this->idetage;
    }

    /**
     * Get iddonjon
     *
     * @return \ndj\DGameBundle\Entity\Donjon 
     */
    public function getIddonjon() {
        return $this->getIdetage()->getIddonjon();
    }

    /**
     * Vérifie l'existence d'une action dans la piece
     * @param int $x
     * @param int $y
     * @param string $a
     * @return boolean
     */
    public function isAction($x, $y, $a) {
        $_a = '{' . $x . ',' . $y . ',' . $a;
        return (strpos($this->getActions(), $_a) !== false);
    }

    /**
     * Retour l'action demandée
     * @param int $x
     * @param int $y
     * @param string $a
     * @return string|NULL
     */
    public function getAction($x, $y, $a) {
        $_a = '{' . $x . ',' . $y . ',' . $a;
        if (strpos($this->getActions(), $_a) !== false) {
            $_tab = tools::explodex('}{', $this->getActions());
            foreach ($_tab as $k => $action) {
                $_action = explode(',', $action);
                if ($_action[0] == $x && $_action[1] == $y && $_action[2] == $a) {
                    return $_action;
                }
            }
        }
        return null;
    }

    // retire l'action Ã  partir des coordonnÃ©es et du type
    public function unsetAction($x, $y, $a) {
        $_a = $this->getAction($x, $y, $a);
        if (!is_null($_a)) {
            return $this->unsetAAction($_a);
        }
        return false;
    }

    /**
     * Retire l'action à partir d'un tableau contenant les coordonnées et le type (x,y,a)
     * @param array $a
     * @return \ndj\DGameBundle\Entity\Piece
     */
    public function unsetAAction(array $a) {
        if (is_array($a))
            $a = '{' . implode(',', $a) . '}';
        $this->setActions(str_replace($a, '', $this->getActions()));
        return $this;
    }

    /**
     * Retourne les escaliers de la pieces
     * @return array liste des action escaliers
     */
    public function getStrairs() {
        $l = array();
        $a = tools::explodex('}{', $this->getActions());
        foreach ($a as $_a) {
            $action = explode(',', $_a);
            if ($action[2] == 'EM' || $action[2] == 'ED')
                $l[] = $action;
        }
        return $l;
    }

    /**
     * Retourne le tableau des actions
     * @return array
     */
    public function getActionTab() {
        return Tools::explodex('}{', $this->getActions());
    }

    /**
     * Vérifie si une action du type $type existe dans la piece
     * @return boolean
     */
    public function actionExist($type) {
        $tab = $this->getActionTab();

        foreach ($tab as $a) {
            $a = explode(',', $a);
            if ($a[2] == $type)
                return true;
        }
        return false;
    }

    /**
     * Ajout une action à la piece
     * @param int $x
     * @param int $y
     * @param string $a
     * @param paramètres supplémentaires si nécessaire
     * @return \ndj\DGameBundle\Entity\Piece
     */
    public function setAction($x, $y, $a) {
        $args = func_get_args();

        if (!$this->caseExist($x, $y)) {
            throw new \Exception("L'action est en dehors de la salle, vérifiez les coordonnées !");
        }
        $return = array();
        $return[] = $this;
        switch ($a) {
            case 'PO' :
                $somme = $args[3];
                //	var_dump($args);
                //	exit;
                // cumul si déjà  de l'or sur cette case
                if ($this->isAction($x, $y, 'PO')) {
                    $l = $this->getActionTab();
                    foreach ($l as $cle => $act) {
                        if (strpos($act, $x . ',' . $y . ',PO,') !== false) {
                            // alors on additionne
                            $tmp = explode(',', $act);
                            $l[$cle] = $x . ',' . $y . ',PO,' . ($tmp[3] + $somme);
                            break;
                        }
                    }
                    $this->setActions('{' . implode('}{', $l) . '}');
                    // sinon, on ajoute directe
                } else {
                    $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',PO,' . $somme . '}');
                }

                break;

            case 'I' : case 'W' : case 'V' : case 'Z' : case 'S' : case 'F' :
                if (!$this->isAction($x, $y, 'I') && !$this->isAction($x, $y, 'Z') && !$this->isAction($x, $y, 'W') && !$this->isAction($x, $y, 'V')) {
                    $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',' . $a . '}');
                } else {
                    throw new \Exception('Cette action est impossible en ' . $x . ',' . $y . ' !');
                }
                break;

            // @todo setAction()/Action EM Escalier à refaire 
            case 'EM' :
                if ($this->isAction($x, $y, 'EM') || $this->isAction($x, $y, 'ED') || $this->isAction($x, $y, 'P')) {
                    throw new \Exception('Cette action est impossible en ' . $x . ',' . $y . ' !');
                }
                $sup = $this->getIdetage()->getSuperieur();
                if (is_null($sup)) {
                    throw new \Exception('Impossible de créer un escalier qui monte : aucun étage au dessus !');
                } else {
                    $psup = $sup->getPiece($x, $y);
                    if ($psup !== false) {
                        $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',EM}');
                        $psup->setActions(trim($psup->getActions()) . '{' . $x . ',' . $y . ',ED}');
                        $return[] = $psup;
                        $change = true; //$psup->MiseAJour();
                    } else {
                        $change = false;
                        $msg = 'Impossible de créer un escalier qui monte : aucune salle au dessus !';
                    }
                }
                break;

            // @todo setAction()/Action ED Escalier à refaire
            case 'ED' :
                if ($this->isAction($x, $y, 'EM') || $this->isAction($x, $y, 'ED') || $this->isAction($x, $y, 'P')) {
                    $change = false;
                    $msg = 'Cette action est impossible en ' . $x . ',' . $y . ' !';
                    break;
                }
                $sup = $this->getIdetage()->getInferieur();

                if (is_null($sup)) {
                    $change = false;
                    $msg = 'Impossible de créer un escalier qui descend : aucun étage en dessous !';
                } else {
                    $pinf = $sup->getPiece($x, $y);
                    if ($pinf !== false) {
                        $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',ED}');
                        $pinf->setActions(trim($pinf->getActions()) . '{' . $x . ',' . $y . ',EM}');
                        $change = true; //$pinf->MiseAJour();
                        $return[] = $pinf;
                    } else {
                        $change = false;
                        $msg = 'Impossible de créer un escalier qui descend : aucune salle en dessous !';
                    }
                }
                break;

            case 'P' :
                if ($this->isAction($x, $y, 'EM') || $this->isAction($x, $y, 'ED') || $this->isAction($x, $y, 'P')) {
                    throw new \Exception('Cette action est impossible en ' . $x . ',' . $y . ' !');
                }
                $pieced = new self($args[3]);
                $xto = $args[4];
                $yto = $args[5];

                if ($xto >= $pieced->getTaillex() || $yto >= $pieced->getTailley()) {
                    throw new \Exception('Coordonnées au delà des limites de la salle de destination !');
                } else {
                    $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',P,' . $pieced->idPIECE . ',' . $xto . ',' . $yto . '}');
                }
                break;

            case 'M' : case 'MS' :
                if ($this->isAction($x, $y, 'M') || $this->isAction($x, $y, 'MS')) {
                    throw new \Exception('Cette action est impossible en ' . $x . ',' . $y . ' !');
                }
                $message = trim($args[3]);
                $message = strip_tags($message);

                $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',' . $a . ',' . $message . '}');
                break;

            case 'D':
                if ($this->isAction($x, $y, 'D')) {
                    throw new \Exception('Cette action est impossible en ' . $x . ',' . $y . ' !');
                }
                $suite = '';

                // rythme du déclencheur (illimité ou X/tour)
                $rythme = $args[3];
                if ($rythme != 0) {
                    $suite .= $rythme . '/';
                }
                $suite .= $rythme . ',';
                // Actions

                $car = $args[4];
                $act = $args[5];
                $val = $args[6];
                foreach ($car as $k => $v) {
                    $suite .= '[' . $v . urlencode($act[$k]) . $val[$k] . ']';
                }
                //
                $this->setActions(trim($this->getActions()) . '{' . $x . ',' . $y . ',' . $a . ',' . $suite . '}');
                break;
        }
        return $return;
    }

    /**
     * Vérifie si les coordonnées x,y sont dans la piece
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function coordExist($x, $y) {
        return (0 <= $x && $x <= $this->getTaillex() && 0 <= $y && $y <= $this->getTailley());
    }

    /**
     * Vérifie si les coordonnées x,y désigne une case de la piece
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function caseExist($x, $y) {
        return (0 <= $x && $x < $this->getTaillex() && 0 <= $y && $y < $this->getTailley());
    }

    public function getSuperficie() {
        return $this->getTaillex() * $this->getTailley();
    }

    public static $_actions = array(
        'PO' => 'Pièce d\'or', // {X,Y,PO,5}	: 5 PO
        'I' => 'Case interdite', // {X,Y,I}		: Case interdit
        'W' => 'Case avec de l\'eau', // {X,Y,W}		: Case d'eau
        'V' => 'Case avec du vide', // {X,Y,V}		: Case avec du vide
        'EM' => 'Escalier qui monte', // {X,Y,EM}		: Escalier qui monte
        'ED' => 'Escalier qui descend', // {X,Y,ED}		: Escalier qui descent
        'P' => 'Passage (secret ou pas)', // {X,Y,P,idP,X,Y} : passage secret vers piece#idP en X,Y
        'M' => 'Message', // {X,Y,M,"Message"} : affiche le message "Message" lorsque le personnage arrive
        'MS' => 'Message survol', // {X,Y,MS,"Message"} : affiche le message "Message" lors du survol de la sourie
        'Z' => 'Zone de départ', // {X,Y,Z}	: zone de dÃ©part du donjon
        'S' => 'Zone de sortie', // {X,Y,S}	: zone de sortie du donjon
        'F' => 'Zone de fin', // {X,Y,F}	: zone de fin du donjon
        'D' => 'Déclencheur'    // {X,Y,D,[d],[a],[v]} : si le dÃ©clencheur d est activÃ©, on fait l'action a. [v] sont des variables.
    );

    static public function _getAvailableActions() {
        return self::$_actions;
    }

    public function getAvailableActions() {
        return self::_getAvailableActions();
    }

    const PRIX_CREATION_FACTOR = 0.05;

    public function getPrixCreation() {
        return ( $this->getTaillex() * $this->getTailley() ) * self::PRIX_CREATION_FACTOR;
    }

    // vÃ©rifier que deux pieces ne se chevauche pas
    static public function testCollision2(Piece $p1, Piece $p2) {
        $left1 = $p1->getPosx();
        $left2 = $p2->getPosx();
        $right1 = $p1->getPosx() + $p1->getTaillex() - 1;
        $right2 = $p2->getPosx() + $p2->getTaillex() - 1;
        $top1 = $p1->getPosY();
        $top2 = $p2->getPosY();
        $bottom1 = $p1->getPosY() + $p1->getTailley() - 1;
        $bottom2 = $p2->getPosY() + $p2->getTailley() - 1;

        if (($bottom1 < $top2) || ($top1 > $bottom2) || ($right1 < $left2) || ($left1 > $right2)) {
            return false;
        }

        return true;
    }

    public function testCollision($p) {
        return self::testCollision2($this, $p);
    }

    public function testCollisionEtage() {
        $liste = $this->getIdetage()->getPieces();

        foreach ($liste as $p) {
            //echo "<p>{$p->getId()}/{$p->getNom()} : ".$this->testCollision($p)."</p>";
            if ($this !== $p && $this->testCollision($p)) {
                return true;
            }
        }
        return false;
    }

    public function checkTailleMini() {
        return ($this->getTaillex() >= 3 && $this->getTailley() >= 3);
    }

    public function nouvellePiece(Etage $etage) {
        $etage->addPiece($this);
        $this->setNom('Nouvelle salle');
        $this->setETAT(1);
        $this->setLUMIERE(10);
        $this->setActions(' ');
        $this->setCoucheSol2(' ');
        $this->setCoucheMobilier(' ');
        $this->setCoucheSol('');

        //$line_head = '[' . implode(',', array_fill (0, $this->Taille_x, '1' )) . ']';

        $init_tile = '002094';
        $line = '[' . implode(',', array_fill(0, $this->getTaillex(), $init_tile)) . ']';

        $this->Sol = '';
        for ($y = 0; $y < $this->getTailley(); $y++) {
            $this->coucheSol .= $line;
        }
    }

    public function json() {
        return json_encode($this->toArray());
    }

    public function isPerso($x, $y) {
        $liste1 = $this->getAventuriers();
        foreach ($liste1 as $o)
            if ($o->getPosition() == '{' . $x . ',' . $y . '}')
                return true;
        $liste2 = $this->getBestiaires();
        foreach ($liste2 as $o)
            if ($o->getPosition() == '{' . $x . ',' . $y . '}')
                return true;
        return false;
    }

    public function setClose() {
        throw new \Exception("TODO");
    }

    public function setOpen() {
        throw new \Exception("TODO");
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
    
    
    protected function isFreeOf($x, $y, $class) {
        $_pos = '{'.$x.','.$y.'}';
        $_f = 'get'.$class.'s';
        $l = $this->$_f();
        foreach($l as $item) {
            if ($_pos == $item->getPosition()) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Vérifie si una venturier est présent en x y dans la piece
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function isFreeOfAventurier($x, $y) {
        return $this->isFreeOf($x, $y, 'aventurier');
    }
    
    /**
     * Vérifie si una venturier est présent en x y dans la piece
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function isFreeOfBestiaire($x, $y) {
        return $this->isFreeOf($x, $y, 'bestiaire');
    }
    
    /**
     * Vérifie si una venturier est présent en x y dans la piece
     * @param int $x
     * @param int $y
     * @return boolean
     */
    public function isFreeOfInventaire($x, $y) {
        return $this->isFreeOf($x, $y, 'inventaire');
    }

}