<?php

/*
 * This file is part of the Concise CLI package.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Concise;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Application extends BaseApplication
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new Command\Shorten;
        $commands[] = new Command\Expand;
        $commands[] = new Command\Validate;

        return $commands;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();

        $definition->addOption(new InputOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'Specify a custom location for the configuration file'
        ));

        $definition->addOption(new InputOption(
            'profile',
            'p',
            InputOption::VALUE_REQUIRED,
            'Use a profile other than the default',
            'default'
        ));

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultHelperSet()
    {
        $helperSet = parent::getDefaultHelperSet();

        $helperSet->set(new Helper\Concise);
        $helperSet->set(new Helper\Config);

        return $helperSet;
    }
}
