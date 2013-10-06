<?php

namespace ndj\DGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chatmsg
 *
 * @ORM\Table(name="CHATMSG")
 * @ORM\Entity(repositoryClass="ndj\DGameBundle\Repository\ChatmsgRepository")
 */
class Chatmsg
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
     * @var integer
     *
     * @ORM\Column(name="MTIME", type="integer", nullable=false)
     */
    private $mtime;

    /**
     * @var string
     *
     * @ORM\Column(name="AUTEUR", type="string", length=10, nullable=true)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="DESTINATAIRE", type="string", length=10, nullable=true)
     */
    private $destinataire;

    /**
     * @var string
     *
     * @ORM\Column(name="MESSAGE", type="string", length=255, nullable=false)
     */
    private $message;



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
     * Set mtime
     *
     * @param integer $mtime
     * @return Chatmsg
     */
    public function setMtime($mtime)
    {
        $this->mtime = $mtime;
    
        return $this;
    }

    /**
     * Get mtime
     *
     * @return integer 
     */
    public function getMtime()
    {
        return $this->mtime;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Chatmsg
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set destinataire
     *
     * @param string $destinataire
     * @return Chatmsg
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;
    
        return $this;
    }

    /**
     * Get destinataire
     *
     * @return string 
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Chatmsg
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Get the type of the chat message
     * 
     * @return string type of message 
     */
    public function getType()
    {
    	$auteur = $this->getAuteur();
    	$destinataire = $this->getDestinataire();
    	if ($auteur=='system') return 'system';
    	if ($auteur=='admin') return 'admin';
    	if (is_numeric($auteur[0]) && $destinataire=='') return 'public';
    	if (is_numeric($auteur[0]) && is_numeric($destinataire[0])) return 'prive';
    }
}