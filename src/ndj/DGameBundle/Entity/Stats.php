<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stats
 *
 * @ORM\Table(name="STATS")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\TalentRepository")
 */
class Stats
{
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
     * @ORM\Column(name="USR", type="string", length=10, nullable=false)
     */
    private $usr;

    /**
     * @var string
     *
     * @ORM\Column(name="CLE", type="string", length=45, nullable=false)
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="VAL", type="string", length=45, nullable=false)
     */
    private $val;



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
     * Set usr
     *
     * @param string $usr
     * @return Stats
     */
    public function setUsr($usr)
    {
        $this->usr = $usr;
    
        return $this;
    }

    /**
     * Get usr
     *
     * @return string 
     */
    public function getUsr()
    {
        return $this->usr;
    }

    /**
     * Set cle
     *
     * @param string $cle
     * @return Stats
     */
    public function setCle($cle)
    {
        $this->cle = $cle;
    
        return $this;
    }

    /**
     * Get cle
     *
     * @return string 
     */
    public function getCle()
    {
        return $this->cle;
    }

    /**
     * Set val
     *
     * @param string $val
     * @return Stats
     */
    public function setVal($val)
    {
        $this->val = $val;
    
        return $this;
    }

    /**
     * Get val
     *
     * @return string 
     */
    public function getVal()
    {
        return $this->val;
    }
}