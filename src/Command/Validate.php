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

use RomaricDrigon\MetaYaml\MetaYaml;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Validate extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('validate')
            ->setDescription('Validate configuration');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getHelper('config')->getConfiguration();

        $loader = new YamlLoader;
        $array = $loader->loadFromFile(__DIR__.'/../../resources/schema.yml');

        $schema = new MetaYaml($array);

        $schema->validate($config);
        $output->writeln('<info>Configuration is valid</info>');
    }
}
