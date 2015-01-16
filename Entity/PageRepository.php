<?php

namespace S2b\CrawlerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 */
class PageRepository extends EntityRepository
{
    /**
     *
     */
    public function countPages() {
        return $this
            ->createQueryBuilder('p')
            ->select('count(p.id)')
                ->getQuery()
                    ->getSingleScalarResult();
    }

    /**
     *
     */
    public function findOneNotCrawled()
    {
        return $this
            ->createQueryBuilder('p')
                ->where("p.crawledAt IS NULL")
                // ->addOrderBy('p.depth', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     *
     */
    public function findOneNotParsed()
    {
        return $this
            ->createQueryBuilder('p')
                ->where("p.crawledAt IS NOT NULL AND p.parsedAt IS NULL")
                // ->addOrderBy('p.depth', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                    ->getOneOrNullResult();
    }

}
