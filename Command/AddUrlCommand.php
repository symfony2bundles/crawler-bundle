<?php

namespace S2b\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use S2b\CrawlerBundle\Entity\Page;

/**
 *
 */
class AddUrlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('s2b:crawler:add-url')
            ->setDescription('Add Url to crawler queue')
            ->addArgument('url', InputArgument::OPTIONAL, 'Page Url to crawl')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $pageRepository = $this->getContainer()->get('doctrine')
            ->getRepository('S2bCrawlerBundle:Page');

        $url = $input->getArgument('url');
        // $depth = $input->getOption('depth');

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            $output->writeln('<error>Invalid url</error>');

            return;
        }

        $page = $pageRepository->findOneByUrl($url);
        if ($page) {
            $output->writeln('<error>Url already in database</error>');

            return;
        }

        $page = new Page();
        $page->setUrl($url);
        $page->setDepth(0);
        $em->persist($page);
        $em->flush();

        $output->writeln('Url added successfully');
    }
}
