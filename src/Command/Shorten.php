<?php

/*
 * This file is part of the Concise CLI package.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Concise\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Shorten extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('shorten')
            ->setDescription('Shorten a URL')
            ->addArgument('url', InputArgument::REQUIRED, 'What URL do you want to shorten?');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        $concise = $this->getApplication()->getConcise();

        $shortenedUrl = $concise->shorten($url);

        $output->writeln($shortenedUrl);
    }
}