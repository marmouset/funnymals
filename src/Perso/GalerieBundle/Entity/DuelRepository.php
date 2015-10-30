<?php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * DuelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DuelRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllDuelsDesc($nombreParPage, $page)
    {
        if ($page < 1) {
            throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$page.'").');
        }

        $qb = $this ->createQueryBuilder('d')
            ->orderBy('d.dateFin', 'DESC');

        $query = $qb->getQuery();
        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return new Paginator($query);
    }
}
