<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * projectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{
    public function getAll()
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('p, u, t')
           ->from('AlgorithmManagerBundle:Project', 'p')
           ->leftJoin('p.createur', 'u')
           ->leftJoin('p.tasks', 't')
        ;
        
        return $em->getQuery()->getResult();
    }
    
    public function getDetails($id)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('p, t, c')
           ->from('AlgorithmManagerBundle:Project', 'p')
           ->leftJoin('p.tasks', 't')
           ->leftJoin('p.createur', 'c')
           ->where('p.id = :id')
           ->setParameter('id', $id)
        ;
        
        return $em->getQuery()->getSingleResult();
    }
}
