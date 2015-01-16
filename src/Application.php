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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Application extends BaseApplication
{
    /**
     * @var Concise
     */
    protected $concise;

    /**
     * List of available providers
     *
     * @var array
     */
    protected $providers = array(
        'google' => 'Concise\Provider\Google',
    );

    public function __construct()
    {
        parent::__construct('Concise', '0.1-dev');

        $this->add(new Command\Shorten);
        $this->add(new Command\Expand);
    }

    /**
     * @return Concise
     */
    public function getConcise()
    {
        return $this->concise;
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $config = $this->loadConfiguration($input);

        if (!isset($config['provider'])) {
            throw new \RuntimeException('No provider specified');
        }

        if (!isset($this->providers[$name])) {
            throw new \InvalidArgumentException(sprintf('Provider "%s" does not exists', $name));
        }

        $this->concise = Factory::create($config['provider']);

        return parent::doRun($input, $output);
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

        return $definition;
    }

    /**
     * Load configuration from file
     *
     * @param InputInterface $input
     *
     * @return array
     */
    public function loadConfiguration(InputInterface $input)
    {
        $paths = ['concise.yml','.concise.yml'];

        if ($customPath = $input->getParameterOption(['-c','--config'])) {
            if (!file_exists($customPath)) {
                throw new RuntimeException('Custom configuration file not found at '.$customPath);
            }

            $paths = [$customPath];
        }

        $config = [];

        foreach ($paths as $path) {
            if (file_exists($path) and $parsedConfig = Yaml::parse($path)) {
                $config = $parsedConfig;
                break;
            }
        }

        if ($homeDir = getenv('HOME')) {
            $localPath = $homeDir.'/.config.yml';

            if (file_exists($localPath) and $parsedConfig = Yaml::parse($localPath)) {
                $config = array_replace_recursive($parsedConfig, $config);
            }
        }

        return $config;
    }
}
