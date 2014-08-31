<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ApplicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends EntityRepository
{
    public function getSearchApps($search)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('a, s')
           ->from('AlgorithmManagerBundle:Application', 'a')
           ->leftJoin('a.server', 's')
           ->where('a.name LIKE :param')
           ->setParameter('param', "%$search%")
        ;
        
        return $em->getQuery()->getResult();
    }
}
