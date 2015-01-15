<?php

namespace S2b\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(
 *  name="s2b_crawler_page",
 *  indexes={
 *      @ORM\Index(name="url_idx", columns={"url"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="S2b\CrawlerBundle\Entity\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Page
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="depth", type="integer")
     */
    private $depth;

    /**
     * Part | List
     * 
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32, nullable=true)
     */
    private $type;
    
    /**
     * @ORM\OneToOne(targetEntity="PageCrawled", mappedBy="page", cascade={"persist"})
     **/
    private $crawled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="crawled_at", type="datetime", nullable=true)
     */
    private $crawledAt;

    /**
     * @ORM\OneToOne(targetEntity="Application\S2b\CrawlerBundle\Entity\PageParsed", mappedBy="page", cascade={"persist"})
     **/
    private $parsed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="parsed_at", type="datetime", nullable=true)
     */
    private $parsedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Are page already crawled
     */
    public function isCrawled() {
        return $this->getCrawledAt();
    }

    /**
     * Are page already parsed
     */
    public function isParsed() {
        return $this->getParsedAt();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Page
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set crawled
     *
     * @param \S2b\CrawlerBundle\Entity\PageCrawled $crawled
     * @return Page
     */
    public function setCrawled(\S2b\CrawlerBundle\Entity\PageCrawled $crawled = null)
    {
        $this->crawled = $crawled;

        return $this;
    }

    /**
     * Get crawled
     *
     * @return \S2b\CrawlerBundle\Entity\PageCrawled 
     */
    public function getCrawled()
    {
        return $this->crawled;
    }

    /**
     * Set parsed
     *
     * @param \Application\S2b\CrawlerBundle\Entity\PageParsed $parsed
     * @return Page
     */
    public function setParsed(\Application\S2b\CrawlerBundle\Entity\PageParsed $parsed = null)
    {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * Get parsed
     *
     * @return \Application\S2b\CrawlerBundle\Entity\PageParsed 
     */
    public function getParsed()
    {
        return $this->parsed;
    }

    /**
     * Set depth
     *
     * @param integer $depth
     * @return Page
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth
     *
     * @return integer 
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set crawledAt
     *
     * @param \DateTime $crawledAt
     * @return Page
     */
    public function setCrawledAt($crawledAt)
    {
        $this->crawledAt = $crawledAt;

        return $this;
    }

    /**
     * Get crawledAt
     *
     * @return \DateTime 
     */
    public function getCrawledAt()
    {
        return $this->crawledAt;
    }

    /**
     * Set parsedAt
     *
     * @param \DateTime $parsedAt
     * @return Page
     */
    public function setParsedAt($parsedAt)
    {
        $this->parsedAt = $parsedAt;

        return $this;
    }

    /**
     * Get parsedAt
     *
     * @return \DateTime 
     */
    public function getParsedAt()
    {
        return $this->parsedAt;
    }
}
