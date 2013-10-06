<?php

namespace ndj\DGameBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ChatmsgRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChatmsgRepository extends EntityRepository
{
	public function findAllWithTime($lstmsg, $idjoueur, $typejoueur)
	{
		$dql = "SELECT m FROM ndjDGameBundle:Chatmsg m WHERE m.id > :id "
				. " AND (m.auteur IN ('system','admin', :codejoueur1 ) "
				. " OR m.destinataire IN ('', :codejoueur2 ) ) "
				. " ORDER BY m.mtime DESC";
		
		$codejoueur = $idjoueur.':'.$typejoueur ;
		
		return $this->_em
			->createQuery($dql)
			->setParameters(array(
					'id' => $lstmsg,
					'codejoueur1' => $codejoueur,
					'codejoueur2' => $codejoueur
				))
			->getResult();
	}
}