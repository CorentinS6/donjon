<?php

namespace ndj\DGameBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BestiaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BestiaireRepository extends EntityRepository
{
	public function findByIdDonjon($iddonjon)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT b FROM ndjDGameBundle:Bestiaire b 
				WHERE b.iddonjon = :id'
			)->setParameter('id', $iddonjon);
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
	
	public function findCommerceBoutique()
	{
		$query = $this->getEntityManager()->createQuery(' SELECT b FROM ndjDGameBundle:Bestiaire b  WHERE b.iddonjon IS NULL ' );
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
	
	public function findCommerceOccasion()
	{
		$query = $this->getEntityManager()->createQuery(' SELECT b FROM ndjDGameBundle:Bestiaire b WHERE b.aVendre=1 AND b.iddonjon IS NOT NULL ' );
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
	
	
	
}
