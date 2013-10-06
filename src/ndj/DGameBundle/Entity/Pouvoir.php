<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pouvoir
 *
 * @ORM\Table(name="POUVOIR")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\PouvoirRepository")
 */
class Pouvoir
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=6, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTION", type="string", length=45, nullable=false)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="CAT", type="string", nullable=false)
     */
    private $cat;



    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Pouvoir
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Pouvoir
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return Pouvoir
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set cat
     *
     * @param string $cat
     * @return Pouvoir
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
    
        return $this;
    }

    /**
     * Get cat
     *
     * @return string 
     */
    public function getCat()
    {
        return $this->cat;
    }
}