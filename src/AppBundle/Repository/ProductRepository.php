<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 28/02/2018
 * Time: 18:25
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findProductsByName($text = '')
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p');
        $qb->where($qb->expr()->like('p.name', ':text'));
        $qb->setParameter('text', '%' . $text . '%');

        return $qb->getQuery()->getResult();
    }

    public function findProductsByNameAndCategory($text = '', $category)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p');
        $qb->join('p.category', 'c');
        $qb->where($qb->expr()->like('p.name', ':text'));
        $qb->andWhere('c.name = :category');
        $qb->setParameter('text', '%' . $text . '%');
        $qb->setParameter('category', $category);

        return $qb->getQuery()->getResult();
    }

    public function findProductsByCategory($category)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p');
        $qb->join('p.category', 'c');
        $qb->where('c.name = :category');
        $qb->setParameter('category', $category);

        return $qb->getQuery()->getResult();
    }
}