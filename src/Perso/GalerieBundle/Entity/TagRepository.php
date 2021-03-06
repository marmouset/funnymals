<?php

namespace Perso\GalerieBundle\Entity;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    /*
    public function betterTags()
    {
        //on doit retourner les tags les plus utilis�s (dans photo donc)
        $qb = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('PersoGalerieBundle:Photo', 'p')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            //->groupBy('p.tags')
        ;


        return $qb->getQuery()->getResult();
    }
    */

    public function betterTags()
    {
        //on doit retourner les tags les plus utilis�s (dans photo donc)
        /*
        $qb = $this->createQueryBuilder('t')
            //->select('p')
            //->from('PersoGalerieBundle:Photo', 'p')
            ->leftJoin('p.tags', 't')
            //->addSelect('t')
        ;
        */

        /*
        $result = $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->from('PersoGalerieBundle:Tag' , 't')
            ->leftJoin('u.UserGroup','p')
            ->where('g.GroupId = :id')
            ->andWhere('g.UserId = :null')
            ->setParameter('id', 70)
            ->setParameter('null', null)
            ->getQuery()
        */

        $limit = 5;
        $qb = $this->createQueryBuilder('t');
        $qb->select('t.libTag, count(p.id) AS compte');
        $qb->leftJoin('t.photos','p');
        $qb->groupBy('t.id');
        $qb->setMaxResults($limit);
        $qb->orderBy('compte', 'DESC');


        //$qb->select('count(t.photos) as compte');
        //$qb->groupBy('t.photos');

        //$qb->groupBy('t.photos');
        //$qb->andHaving($qb->expr()->gt($qb->expr()->count('u.numChild'), 0))

        //$qb->setMaxResults(5);
        //$qb->orderBy('compte', 'DESC');

        /*
        $query = $this->createQueryBuilder('t');
        $query->select('t, COUNT(t.id) AS compte');
        //$query->where('s.challenge = :challenge')->setParameter('challenge', $challenge);
        $query->groupBy('s.user');
        $query->setMaxResults(5);
        $query->orderBy('compte', 'DESC');
        */

        /*
        $qb = $this->_em->createQueryBuilder()
            ->select('t')
            ->from('PersoGalerieBundle:Tag', 't')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            //->groupBy('p.tags')
        ;
        */
        return $qb->getQuery()->getResult();
    }
}
