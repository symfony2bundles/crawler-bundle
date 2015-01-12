<?php

namespace S2b\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;

use S2b\CrawlerBundle\Entity\Page;
use S2b\CrawlerBundle\Entity\PageCrawled;
use S2b\CrawlerBundle\Entity\PageParsed;

/**
 * 
 */
class CrawlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('s2b:crawler:crawl')
            ->setDescription('Crawl single page with given [id]')
            ->addArgument('id', InputArgument::REQUIRED, 'Page Id to crawl')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $pageRepository = $this->getContainer()->get('doctrine')
            ->getRepository('S2bCrawlerBundle:Page');

        $id = $input->getArgument('id');
        $page = $pageRepository->findOneById($id);

        if (!$page) {
            $output->writeln("<error>Page not found</error>");
            return;
        }

        if ($page->isCrawled()) {
            $output->writeln("<error>Page already crawled</error>");
            return;
        }

        $client = new Client();
        try {
            $response = $client->get($page->getUrl());
        } catch (ClientException $e) {
            if (404 == $e->getResponse()->getStatusCode()) {

                $output->writeln('<error>Not found ' . $page->getUrl() . '</error>');

                $page->setCrawledAt(new \DateTime());
                $em->persist($page);
                $em->flush();

                return;
            }
        }
        
        $links = $this->parseLinks((string)$response->getBody(), $page->getUrl());
        $links = $this->filterLinks($links);

        $crawled = new PageCrawled();
        $crawled
            ->setPage($page)
            ->setContent((string)$response->getBody())
            //->setHeaders(...)
            ->setLinks($links)
        ;

        $page
            ->setCrawled($crawled)
            ->setCrawledAt(new \DateTime())
        ;
        
        if ($links) {
            $links_added = 0;
            foreach ($links as $i=>$link) {
                if ($pageRepository->findOneByUrl($link)) {
                    if ($output->isVeryVerbose()) {
                        $output->writeln("Skipped #" . $i . ' ' . $link . ' - already in database');
                    }
                    continue;
                }

                $linkPage = new Page();
                $linkPage
                    ->setUrl($link)
                    ->setDepth($page->getDepth() + 1)
                ;
                $em->persist($linkPage);
                $em->flush();

                $links_added++;

                if ($output->isVerbose()) {
                    $output->writeln("Added #" . $i . ' ' . $link);
                }
            }
            $output->writeln('Added ' . $links_added . '/' . count($links) . ' links');
        }

        $em->persist($crawled);
        $em->persist($page);

        $em->flush();

        $output->writeln('Crawled ' . $page->getUrl());
    }

    /**
     * 
     */
    protected function isCrawlable($uri) {
        if (empty($uri)) {
            return false;
        }

        $filters = $this->getContainer()->getParameter('s2b_crawler.filters');
        if ($filters) {
            foreach ($filters as $filter) {
                $filter = preg_quote($filter);
                if (preg_match("@^$filter.*@", $uri)) {
                    return true;
                }
            }

            return false;
        } else {
            $stop_links = array(
                '@^javascript\:void\(0\)$@',
                '@^#.*@',
            );

            foreach ($stop_links as $ptrn) {
                if (preg_match($ptrn, $uri)) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * 
     */
    protected function prepareLink ($link) {
        // $link = strtok($link, "#");
        list ($link) = explode('#', $link);
        return $link;
    }

    /**
     * 
     */
    protected function parseLinks ($html, $baseUrl) {
        
        $crawler = new Crawler($html, $baseUrl);

        return $crawler->filter( 'a' )->each( function ( Crawler $node, $i ) {
            return $node->link()->getUri();
        });
    }

    /**
     * 
     */
    protected function filterLinks ($links) {
        $result = [];
        foreach ($links as $link) {
            $link = $this->prepareLink($link);
            if (!$this->isCrawlable($link)) continue;

            $result[] = $link;
        }

        return $result;
    }
}
