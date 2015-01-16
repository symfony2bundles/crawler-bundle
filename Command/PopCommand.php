<?php

namespace S2b\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class PopCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('s2b:crawler:pop')
            ->setDescription('Crawl last [limit] uncrawled pages from queue')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Number of Pages to crawl', 10)
            // ->addOption('parse', 'p', InputOption::VALUE_NONE, 'Parse crawled page')
        ;
    }

    /**
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lockHandler = new LockHandler('crawler-pop.lock');
        if (!$lockHandler->lock()) {
            $output->writeln("<error>Crawler already running</error>");

            return 0;
        }

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $pageRepository = $doctrine->getRepository('S2bCrawlerBundle:Page');

        $limit = $input->getOption('limit');
        // $parse = $input->getOption('parse');

        $output->writeln('');
        // if ($parse) $output->writeln('Running with automatic parsing mode');

        do {
            $page = $pageRepository->findOneNotCrawled();

            if (!$page) {
                $output->writeln("<error>Empty queue</error>");

                return;
            }

            $output->writeln('Found ' . $page->getUrl());

            $input = [
                'command' => 's2b:crawler:crawl',
                'id'=>$page->getId(),
                '--verbose'=>$output->getVerbosity()
            ];

            if ($output->isVerbose()) {
                $output->writeln('Running ' . $input['command'] . ' with id ' . $input['id']);
            }

            $this->getApplication()
                ->find('s2b:crawler:crawl')
                ->run(new ArrayInput($input), $output);

            // Parse crawled page
            // if ($parse) {
            //     $input = [
            //         'command' => 's2b:parser:parse',
            //         'id'=>$page->getId(),
            //         '--verbose'=>$output->getVerbosity()
            //     ];

            //     if ($output->isVerbose()) {
            //         $output->writeln('Running ' . $input['command'] . ' with id ' . $input['id']);
            //     }

            //     $this->getApplication()
            //         ->find('s2b:parser:parse')
            //         ->run(new ArrayInput($input), $output);
            // }

            $output->writeln('');

        } while (--$limit > 0);

        $output->writeln('Crawling finished');
    }
}
