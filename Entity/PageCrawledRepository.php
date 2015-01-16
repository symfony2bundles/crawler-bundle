<?php

namespace S2b\CrawlerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 */
class PageCrawledRepository extends EntityRepository
{

    /**
     *
     */
    public function countPagesCrawled() {
        return $this
            ->createQueryBuilder('pc')
            ->select('count(pc.id)')
                ->getQuery()
                    ->getSingleScalarResult();
    }

}
