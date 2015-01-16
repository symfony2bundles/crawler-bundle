<?php

namespace S2b\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageCrawled
 *
 * @ORM\Table(name="s2b_crawler_page_crawled")
 * @ORM\Entity(repositoryClass="\S2b\CrawlerBundle\Entity\PageCrawledRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PageCrawled
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
     * @ORM\OneToOne(targetEntity="Page", inversedBy="crawled")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     **/
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="headers", type="text", nullable=true)
     */
    private $headers;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * Links to another pages
     *
     * @var array
     *
     * @ORM\Column(name="links", type="array")
     */
    private $links;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set headers
     *
     * @param  string      $headers
     * @return PageCrawled
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get headers
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set content
     *
     * @param  string      $content
     * @return PageCrawled
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set links
     *
     * @param  array       $links
     * @return PageCrawled
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * Get links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime   $createdAt
     * @return PageCrawled
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
     * Set page
     *
     * @param  \S2b\CrawlerBundle\Entity\Page $page
     * @return PageCrawled
     */
    public function setPage(\S2b\CrawlerBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \S2b\CrawlerBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
