<?php

namespace S2b\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BasePageParsed
 *
 * @ORM\Entity
 */
abstract class BasePageParsed
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
