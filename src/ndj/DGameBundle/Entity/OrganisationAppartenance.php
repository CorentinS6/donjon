<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organisation
 *
 * @ORM\Table(name="ORGANISATION_APPARTENANCE")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\OrganisationAppartenanceRepository")
 */
class OrganisationAppartenance {

    /**
     * @var \Aventurier
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\ManyToOne(targetEntity="Aventurier", inversedBy="organisationappartenances")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAVENTURIER", referencedColumnName="id")
     * })
     */
    private $idaventurier;

    /**
     * @var \Organisation
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="organisationappartenances")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idORGANISATION", referencedColumnName="id")
     * })
     */
    private $idorganisation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CACHER", type="boolean", nullable=false)
     */
    private $cacher;

    /**
     * @var string
     *
     * @ORM\Column(name="TITRE", type="string", length=45, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="DROITS", type="string", length=45, nullable=true)
     */
    private $droits;

    /**
     * @var string
     *
     * @ORM\Column(name="ETAT", type="string", nullable=false)
     */
    private $etat;

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    public function getIdaventurier() {
        return $this->idaventurier;
    }

    public function setIdaventurier(Aventurier $aventurier) {
        $this->idaventurier = $aventurier;
    }

    public function getIdorganisation() {
        return $this->idorganisation;
    }

    public function setIdorganisation(Organisation $organisation) {
        $this->idorganisation = $organisation;
    }

    public function getCacher() {
        return $this->cacher;
    }

    public function setCacher($cacher) {
        $this->cacher = $cacher;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($Titre) {
        $this->titre = $Titre;
    }

    public function getDroits() {
        return $this->droits;
    }

    public function setDroits($droits) {
        $this->droits = $droits;
    }

    public function getEtat() {
        return $this->etat;
    }

    public function setEtat($etat) {
        $this->etat = $etat;
    }

    /**
     * Vérifie si un membre est actif dans l'organisation
     * @return boolean
     */
    public function isActif() {
        return ($this->getEtat() == 'actif');
    }

    /**
     * Vérifie si un membre possède le droit donné
     * @param string $droit
     * @return boolean
     */
    public function is($droit) {
        return in_array($droit, explode(',', $this->getDroits()));
    }

    /**
     * Liste des actions
     * @var array
     */
    public static $actions = array(
        0 => 'quitterOrga', // Quitter l’organisation
        1 => 'listeMembre', // Voir les membres
        2 => 'listeInventaire', // Voir l'inventaire
        3 => 'listeArgent', // Voir l'argent
        4 => 'bannirMembre', // Bannir un membre*
        5 => 'inviterMembre', // Inviter un membre
        6 => 'validerMembre', // Valider l'inscription d'un membre
        7 => 'gererInventaire', // Gérer l'inventaire
        8 => 'gererArgent', // Gérer l'argent
        9 => 'gererTitre', // Gérer les titre des membres*
        10 => 'gererDroits', // Gérer les droits des membres*
        11 => 'dissoudre', // Dissoudre l'organisation
        12 => 'nommerchef', // Nommer un successeur
    );
    
    private static $_actions_label = array(
        0 => 'Quitter l’organisation',
        1 => 'voir les membres', // Voir les membres
        2 => 'Voir l\'inventaire', // Voir l'inventaire
        3 => 'Voir l\'argent', // Voir l'argent
        4 => 'Bannir un membre', // Bannir un membre*
        5 => 'Inviter un membre', // Inviter un membre
        6 => 'Valider la demande d\'inscription d\'un membre', // Valider l'inscription d'un membre
        7 => 'Gérer l\'inventaire', // Gérer l'inventaire
        8 => 'Gérer l\'argent', // Gérer l'argent
        9 => 'Gérer les titre des membres', // Gérer les titre des membres*
        10 => 'Gérer les droits des membres', // Gérer les droits des membres*
        11 => 'Dissoudre l\'organisation', // Dissoudre l'organisation
        12 => 'Nommer un successeur', // Nommer un successeur
    );
    
    public static function getActionLabel($action) {
        return self::$_actions_label[array_search($action,self::$actions)];
    }
    
    /**
     * Liste des droits et actions associées
     * @var array
     */
    private static $_droits = array(
        'chef' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
        'administrateur' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
        'gdroits' => array(10),
        'gargent' => array(8),
        'ginventaire' => array(7),
        'gliste' => array(4, 5, 6),
        'argent' => array(3),
        'inventaire' => array(2),
        'liste' => array(1),
        'invite' => array(5),
    );
    
    private static $_droits_label = array(
        'chef' => 'chef',
        'administrateur' => 'administrateur',
        'gdroits' => 'Gestion des droits',
        'gargent' => 'Gestion des finances',
        'ginventaire' => "Gestion de l'inventaire",
        'gliste' => 'Gestion des membres',
        'argent' => 'Voir l\'argent',
        'inventaire' => 'Voir l\'inventaire',
        'liste' => 'Voir les membres',
        'invite' => 'Inviter un membre',
    );


    public static function getDroitLabel($droit) {
        return self::$_droits_label[$droit];
    }

    /**
     * Retourne la liste de tous droits possibles et leurs actions associées
     * @return array
     */
    static public function getDroitsPossibles() {
        return self::$_droits;
    }

    /**
     * La liste des droits possibles
     * @return array
     */
    static public function getDroitsPossiblesKey() {
        return array_keys(self::$_droits);
    }

    /**
     * Retourne la liste des actions autorisées pour le membre
     * @return array 
     */
    public function getActionsPossibles() {
        $actions = array();
        $d = explode(',', $this->getDroits());
        foreach ($d as $_d) {
            foreach (self::$_droits[$_d] as $na) {
                $actions[] = self::$actions[$na];
            }
        }

        return array_unique($actions);
    }

    /**
     * Vérifie si un action est autorisée
     * @param string $action
     * @return boolean
     */
    public function actionPossible($action) {
        return ( array_search($action, $this->getActionsPossibles()) !== false );
    }

    /**
     * Vérifie si l'aventurier a autorité sur un autre dans les droits de l'organisation
     * @param Aventurier $a
     * @return boolean
     */
    public function isAutorite(Aventurier $a) {
        return ($a != $this->getIdaventurier() && $this->_getDroitsMaxKey() > $a->getOrganisationAppartenance($this->getIdorganisation())->_getDroitsMaxKey());
    }

    /**
     * Retourne l'indice maximum des droits
     * @return integer
     */
    protected function _getDroitsMaxKey() {
        $max = 0;
        $droits = explode(',', $this->getDroits());
        foreach ($droits as $d) {
            if (trim($d)!='')
                $max = max(array($max,max(self::$_droits[$d])));
        }
        return $max;
    }

    /**
     * Ajout un droit au membre
     * @param string $droit
     * @throws \UnexpectedValueException
     */
    public function addDroit($droit) {
        if (array_key_exists($droit, self::$_droits) === false)
            throw new \UnexpectedValueException("'$droit' n'est pas une valeur valide !");

        if (!$this->is($droit)) {
            $droits = explode(',', $this->getDroits());
            $droits[] = $droit;
            $this->setDroits(join(',', $droits));
        }
    }

    /**
     * Retire un droit au membre
     * @param string $droit
     * @throws \UnexpectedValueException
     */
    public function delDroit($droit) {
        if (array_key_exists($droit, self::$_droits) === false)
            throw new \UnexpectedValueException("'$droit' n'est pas une valeur valide !");

        if ($this->is($droit)) {
            $droits = explode(',', $this->getDroits());
            $nouveau = array();
            foreach ($droits as $d) {
                if ($d != $droit)
                    $nouveau [] = $d;
            }
            $this->setDroits(join(',', $nouveau));
        }
    }

}