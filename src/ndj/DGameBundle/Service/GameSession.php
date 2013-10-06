<?php

namespace ndj\DGameBundle\Service;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use ndj\DGameBundle\Entity\Aventurier;
use ndj\DGameBundle\Entity\Donjon;
use ndj\DGameBundle\Entity\Connecte;

use ndj\UserBundle\Entity\User;

class GameSession extends Controller {

    /**
     * @var SessionInterface
     */
    protected $session = null;

    //protected $container = null;

    /**
     * @var Donjon|Aventurier
     */
    protected $joueur = null;

    /**
     * Le joueur est-il chargé ?
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Mode de jeu (gardien ou aventurier)
     */
    protected $mode = '';

    public function __construct(Container $container) {
        $this->container = $container;
        $this->session = $this->container->get('session');
    }

    /**
     * @return SessionInterface
     */
    protected function getSession() {
        if (is_null($this->session) || !($this->session instanceof SessionInterface)) {
            $this->session = $this->container->get('session');
        }
        return $this->session;
    }

    /**
     * @return Aventurier|Donjon|null
     */
    protected function load_joueur() {
        if (!$this->loaded && is_null($this->joueur)) {
            $this->loaded = true;

            if ($this->getSession()->has('idaventurier') && !is_null($this->getSession()->get('idaventurier'))) {
                $this->joueur = $this->container->get('doctrine')->getRepository('ndjDGameBundle:Aventurier')->find($this->getSession()->get('idaventurier'));
                $this->mode = 'aventurier';
            } elseif ($this->getSession()->has('iddonjon') && !is_null($this->getSession()->get('iddonjon'))) {
                $this->joueur = $this->container->get('doctrine')->getRepository('ndjDGameBundle:Donjon')->find($this->getSession()->get('iddonjon'));
                $this->mode = 'donjon';
            }
        }
        return $this->joueur;
    }

    /**
     * @return boolean
     */
    public function check($mode = null) {
        return !is_null($this->load_joueur()) &&
                (is_null($mode) || $mode == $this->mode);
    }

    /**
     * Mode de jeu
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @return Aventurier|Donjon|null|RedirectResponse
     */
    public function get($failed = 'redirect') {
        if ($this->check()) {
            return $this->joueur;
        } elseif ($failed == 'redirect') {
            return $this->redirect($this->generateUrl('home'));
        } else {
            return null;
        }
    }
    
    public function getj() {
        if ($this->check())
            return $this->get();
        else
            throw new AccessDeniedException("Aucune partie n'est commencée !");
    }

    public function geta() {
        if ($this->check('aventurier'))
            return $this->get();
        else
            throw new AccessDeniedException("Aucune partie en mode 'aventurier' n'est commencée !");
    }

    public function getd() {
        if ($this->check('donjon'))
            return $this->get();
        else
            throw new AccessDeniedException("Aucune partie en mode 'donjon' n'est commencée !");
    }

    public function clear() {
        $this->mode = '';
        $this->loaded = false;
        // reset
        $this->getSession()->remove('iddonjon');
        $this->getSession()->remove('idaventurier');
    }

    public function close() {
        $this->clear();
    }

    /**
     * Oouvre une session de jeu
     * @param string $mode
     * @param int $id
     * @return string
     */
    public function start($mode, $id) {
        $this->clear();

        if ($mode == 'donjon') {
            $url = 'game_donjon';
            $this->getSession()->set('iddonjon', $id);
        } elseif ($mode == 'aventurier') {
            $url = 'game_aventurier';
            $this->getSession()->set('idaventurier', $id);
        } else {
            $url = 'home';
        }

        return $url;
    }

    /**
     * Vérifie la présence en session de l'objet $object
     * @param mixed $object
     * @return boolean
     */
    public function has($object) {
        return (!is_null($this->get(null)) &&
                $object === $this->get(null));
    }

    public function getKey() {
        if (!$this->check())  throw new AccessDeniedException("Aucune partie n'est commencée !");

        return $this->joueur->getId() . ':' . $this->mode[0];
    }

    public function setConnected() {
        if (!$this->check()) throw new AccessDeniedException("Aucune partie n'est commencée !");
        
        $repo = $this->container->get('doctrine')->getRepository('ndjDGameBundle:Connecte');
        $em = $this->container->get('doctrine')->getManager();
        $key = $this->getKey();
        $con = $repo->find($key);
        // si pas déjà en ligne
        if (is_null($con)) {
            $con = new Connecte();
            $con->setId($key);
        }
        $con->setLasttime(time());
        $em->persist($con);
        $em->flush();
    }

    public function unsetConnected() {
        if (!$this->check()) throw new AccessDeniedException("Aucune partie n'est commencée !");
    }
    

    public function getConnected() {
        $_liste = $this->container->get('doctrine')->getRepository('ndjDGameBundle:Connecte')->findAll();
        $liste = Array();
        $limite = time() - 180; // 3 minutes
        foreach ($_liste as $o) {
            // pas trop ancien ?
            if ($o->getLasttime() > $limite) {
                $liste[] = $this->getObjectFromKey($o->getId());
            }
        }
        return $liste;
    }

    public function getObjectFromKey($k) {
        list($id, $type) = explode(':', $k);
        switch($type) {
            case 'a' : $class = 'Aventurier'; break;
            case 'd' : $class = 'Donjon'; break;
            case 'b' : $class = 'Bestiaire'; break;
            case 'i' : $class = 'Inventaire'; break;
            case 'o' : $class = 'Organisation'; break;
        }
        return $this->container->get('doctrine')->getRepository('ndjDGameBundle:' . $class)->find($id);
    }

    public function getKeyFromObject($o) {
        $_temp = explode('\\', get_class($o));
        $class = array_pop($_temp);
        return $o->getId() . ':' . strtolower($class[0]);
    }

}