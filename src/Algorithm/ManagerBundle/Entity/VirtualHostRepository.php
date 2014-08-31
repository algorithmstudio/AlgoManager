<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VirtualHostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VirtualHostRepository extends EntityRepository
{
    public function getSearchHost($search)
    {
        $em = $this->_em->createQueryBuilder();
        
        $em->select('v, s')
           ->from('AlgorithmManagerBundle:VirtualHost', 'v')
           ->leftJoin('v.server', 's')
           ->where('v.name LIKE :param')
           ->setParameter('param', "%$search%")
        ;
        
        return $em->getQuery()->getResult();
    }
}
