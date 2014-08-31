<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ServerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServerRepository extends EntityRepository
{
    public function getDetails($id)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('s, a')
           ->from('AlgorithmManagerBundle:Server', 's')                
           ->leftJoin('s.applications', 'a')
           ->where('s.id = :id')
           ->setParameter('id', $id)
        ;
        
        return $em->getQuery()->getSingleResult();
    }
    
    public function getDashboardServers()
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('s')
           ->from('AlgorithmManagerBundle:Server', 's')
           ->setMaxResults(5)
        ;
        
        return $em->getQuery()->getResult();
    }
    
    public function getSearchServers($search)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('s')
           ->from('AlgorithmManagerBundle:Server', 's')
           ->where('s.search LIKE :param')
           ->setParameter('param', "%$search%")
        ;
        
        return $em->getQuery()->getResult();
    }
}
