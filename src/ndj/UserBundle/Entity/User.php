<?php

namespace ndj\UserBundle\Entity;

use ndj\DGameBundle\Util\caracs;

use ndj\DGameBundle\Entity\Donjon;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Entity\User as BaseUser;

/**
 * Membre
 *
 * @ORM\Table(name="MEMBRE")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * @ORM\ManyToMany(targetEntity="ndj\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
    
    public function limitDonjon() {
    	return caracs::NB_LIMIT_AVENTURIERS;
    }
    public function limitAventurier() {
    	return caracs::NB_LIMIT_DONJONS;
    }
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Donjon", mappedBy="idmembre")
     */
    protected $donjons;
    
    public function addDonjon(Donjon $donjon)
    {
    	$this->donjons[] = $donjon;
    	$donjon->setIdmembre($this);
    	return $this;
    }
    
    public function removeDonjon(Donjon $donjon)
    {
    	$this->donjons->removeElement($donjon);
    	$donjon->setidmembre(null);
    }
    
    public function hasDonjon(Donjon $donjon)
    {
    	return $this->donjons->contains($donjon);
    }
    
    public function getDonjons()
    {
    	return $this->donjons;
    }
    
    
    /**
     * @ORM\OneToMany(targetEntity="ndj\DGameBundle\Entity\Aventurier", mappedBy="idmembre")
     */
    protected $aventuriers;
    
    public function addAventurier(Aventurier $aventurier) {
    	$this->aventuriers[] = $aventurier;
    	$aventurier->setidmembre($this);
    	return $this;
    }
    
    public function removeAventurier(Aventurier $aventurier)  {
    	$this->aventuriers->removeElement($aventurier);
    	$aventurier->setidmembre(null);
    }
    
    public function hasAventurier(Aventurier $aventurier)  {
    	return $this->aventuriers->removeElement($aventurier);
    }
    
    public function getAventuriers() {
    	return $this->aventuriers;
    }
    
}