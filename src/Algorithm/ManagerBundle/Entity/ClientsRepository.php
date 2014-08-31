<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ClientsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientsRepository extends EntityRepository
{
    public function getAll()
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('c, u')
           ->from('AlgorithmManagerBundle:Clients', 'c')
           ->leftJoin('c.createur', 'u')
        ;
        
        return $em->getQuery()->getResult();
    }
    
    public function getDetails($id)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('c, a')
           ->from('AlgorithmManagerBundle:Clients', 'c')
           ->leftJoin('c.accounts', 'a')
           ->where('c.id = :id')
           ->setParameter('id', $id)
        ;
        
        return $em->getQuery()->getSingleResult();
    }
    
    public function getDashboardClients()
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('c')
           ->from('AlgorithmManagerBundle:Clients', 'c')
           ->setMaxResults(5)
        ;
        
        return $em->getQuery()->getResult();
    }
    
    public function getSearchClients($search)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('c')
           ->from('AlgorithmManagerBundle:Clients', 'c')
           ->where('c.search LIKE :param')
           ->setParameter('param', "%$search%")
        ;
        
        return $em->getQuery()->getResult();
    }
}