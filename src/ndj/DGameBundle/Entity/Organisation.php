<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ndj\DGameBundle\Entity\OrganisationAppartenance;
use ndj\DGameBundle\Entity\Aventurier;

/**
 * Organisation
 *
 * @ORM\Table(name="ORGANISATION")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\OrganisationRepository")
 */
class Organisation {

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
     * @ORM\Column(name="CAT", type="string", nullable=false)
     */
    private $cat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PUBLIC", type="boolean", nullable=false)
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=45, nullable=false)     
     * @Assert\Length(min=5,minMessage="Le nom doit être d'au moins 5 caratères.",max=45,maxMessage="Le nom doit être d'au plus 45 caratères.")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="text", nullable=false)
     * @Assert\Length(min=10,minMessage="Le nom doit être d'au moins 10 caratères.")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="CHARTE", type="text", nullable=true)
     */
    private $charte;

    /**
     * @var string
     *
     * @ORM\Column(name="BLASON", type="string", length=45, nullable=true)
     */
    private $blason;

    /**
     * @var string
     *
     * @ORM\Column(name="GESTION", type="string", nullable=false)
     */
    private $gestion;

    /**
     * @var integer
     *
     * @ORM\Column(name="NOMBRE_MB_MAX", type="integer", nullable=false)
     * @Assert\Range(min=0)
     */
    private $nombreMbMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRIX_IN", type="integer", nullable=true)
     * @Assert\Range(min=0)
     */
    private $prixIn;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRIX_COT", type="integer", nullable=true)
     * @Assert\Range(min=0)
     */
    private $prixCot;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIONS_DIFF", type="string", length=45, nullable=true)
     */
    private $actionsDiff;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_CREA", type="date", nullable=false)
     * @Assert\Date()
     */
    private $dateCrea;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Aventurier", mappedBy="idorganisation")
     */
    //private $idaventurier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrganisationAppartenance", mappedBy="idorganisation", cascade={"remove"})
     */
    private $organisationappartenances;

    public function addOrganisationAppartenance(OrganisationAppartenance $oa) {
        $this->organisationappartenances[] = $oa;
        $oa->setIdorganisation($this);
        return $this;
    }

    public function removeOrganisationAppartenance(OrganisationAppartenance $oa) {
        $this->organisationappartenances->removeElement($oa);
        $oa->setIdorganisation(null);
    }

    public function hasOrganisationAppartenance(OrganisationAppartenance $oa) {
        return $this->organisationappartenances->contains($oa);
    }

    public function getOrganisationAppartenances() {
        return $this->organisationappartenances;
    }

    /**
     * 
     * @param Aventurier $a
     * @return OrganisationAppartenance
     */
    public function getOrganisationAppartenance(Aventurier $a) {
        $liste = $this->getOrganisationAppartenances();
        foreach ($liste as $app) {
            if ($app->getIdAventurier() == $a)
                return $app;
        }
        return null;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // $this->idaventurier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organisationappartenance = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cat
     *
     * @param string $cat
     * @return Organisation
     */
    public function setCat($cat) {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return string 
     */
    public function getCat() {
        return $this->cat;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Organisation
     */
    public function setPublic($public) {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic() {
        return $this->public;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Organisation
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
     * @return Organisation
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
     * Set charte
     *
     * @param string $charte
     * @return Organisation
     */
    public function setCharte($charte) {
        $this->charte = $charte;

        return $this;
    }

    /**
     * Get charte
     *
     * @return string 
     */
    public function getCharte() {
        return $this->charte;
    }

    /**
     * Set blason
     *
     * @param string $blason
     * @return Organisation
     */
    public function setBlason($blason) {
        $this->blason = $blason;

        return $this;
    }

    /**
     * Get blason
     *
     * @return string 
     */
    public function getBlason() {
        return $this->blason;
    }

    /**
     * Set gestion
     *
     * @param string $gestion
     * @return Organisation
     */
    public function setGestion($gestion) {
        $this->gestion = $gestion;

        return $this;
    }

    /**
     * Get gestion
     *
     * @return string 
     */
    public function getGestion() {
        return $this->gestion;
    }

    /**
     * Set nombreMbMax
     *
     * @param integer $nombreMbMax
     * @return Organisation
     */
    public function setNombreMbMax($nombreMbMax) {
        $this->nombreMbMax = $nombreMbMax;

        return $this;
    }

    /**
     * Get nombreMbMax
     *
     * @return integer 
     */
    public function getNombreMbMax() {
        return $this->nombreMbMax;
    }

    /**
     * Set prixIn
     *
     * @param integer $prixIn
     * @return Organisation
     */
    public function setPrixIn($prixIn) {
        $this->prixIn = $prixIn;

        return $this;
    }

    /**
     * Get prixIn
     *
     * @return integer 
     */
    public function getPrixIn() {
        return $this->prixIn;
    }

    /**
     * Set prixCot
     *
     * @param integer $prixCot
     * @return Organisation
     */
    public function setPrixCot($prixCot) {
        $this->prixCot = $prixCot;

        return $this;
    }

    /**
     * Get prixCot
     *
     * @return integer 
     */
    public function getPrixCot() {
        return $this->prixCot;
    }

    /**
     * Set actionsDiff
     *
     * @param string $actionsDiff
     * @return Organisation
     */
    public function setActionsDiff($actionsDiff) {
        $this->actionsDiff = $actionsDiff;

        return $this;
    }

    /**
     * Get actionsDiff
     *
     * @return string 
     */
    public function getActionsDiff() {
        return $this->actionsDiff;
    }

    /**
     * Set dateCrea
     *
     * @param \DateTime $dateCrea
     * @return Organisation
     */
    public function setDateCrea($dateCrea) {
        $this->dateCrea = $dateCrea;

        return $this;
    }

    /**
     * Get dateCrea
     *
     * @return \DateTime 
     */
    public function getDateCrea() {
        return $this->dateCrea;
    }

    /**
     * Add idaventurier
     *
     * @param \ndj\DGameBundle\Entity\Aventurier $idaventurier
     * @return Organisation
     */
    /* public function addIdaventurier(\ndj\DGameBundle\Entity\Aventurier $idaventurier)
      {
      $this->idaventurier[] = $idaventurier;

      return $this;
      } */

    /**
     * Remove idaventurier
     *
     * @param \ndj\DGameBundle\Entity\Aventurier $idaventurier
     */
    /* public function removeIdaventurier(\ndj\DGameBundle\Entity\Aventurier $idaventurier)
      {
      $this->idaventurier->removeElement($idaventurier);
      } */

    /**
     * Get idaventurier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    /* public function getIdaventurier()
      {
      return $this->idaventurier;
      } */


    public function getChef() {
        $liste = $this->getOrganisationAppartenances();
        foreach ($liste as $app) {
            if ($app->is('chef'))
                return $app;
        }
        return null;
    }

    public function getAdministrateur() {
        return $this->get('administrateur');
    }

    public function get($droit) {
        $liste = $this->getOrganisationAppartenances();
        $return = array();
        foreach ($liste as $app) {
            if ($app->is($droit))
                $return [] = $app;
        }
        return $return;
    }

    public function isChef(Aventurier $a) {
        return $a == $this->getChef();
    }

    public function isAdministrateur(Aventurier $a) {
        return $this->is($a, 'admininstrateur');
    }

    public function is(Aventurier $a, $droit) {
        return (array_search($a, $this->get($droit)) !== false);
    }

    public function isAllowed(Aventurier $a, $action) {
        return in_array($action, $a->getOrganisationAppartenance($this)->getActionsPossibles());
    }
    
    public function isMembreActif(Aventurier $a) {
        $app = $a->getOrganisationAppartenance($this);
        if (is_null($app))
            return false;
        
        return $app->isActif();
    }

    public function isMembre(Aventurier $a) {
        return !is_null($a->getOrganisationAppartenance($this));
    }

    public function getMembresActif() {
        $liste = $this->getOrganisationAppartenances();
        $liste2 = array();
        foreach ($liste as $app) {
            if ($app->isActif())
                $liste2[] = $app;
        }
        return $liste2;
    }
    
    public function getMembresInactif() {
        $liste = $this->getOrganisationAppartenances();
        $liste2 = array();
        foreach ($liste as $app) {
            if (!$app->isActif())
                $liste2[] = $app;
        }
        return $liste2;
    }

    public function getDroitsPossibles() {
        return OrganisationAppartenance::getDroitsPossiblesKey();
    }

}