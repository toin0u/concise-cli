<?php

/*
 * This file is part of the Concise CLI package.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Concise\Helper;

use Ivory\HttpAdapter\GuzzleHttpHttpAdapter;
use Symfony\Component\Console\Helper\InputAwareHelper;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Config extends InputAwareHelper
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var boolean
     */
    protected $isLoaded = false;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'config';
    }

    /**
     * Returns the configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        if (!$this->isLoaded) {
            $this->config = $config = $this->loadConfiguration();
            $this->isLoaded = true;
        }

        return $this->config;
    }

    /**
     * Load configuration from file
     *
     * @return array
     */
    protected function loadConfiguration()
    {
        $config = [];

        foreach ($this->getPaths() as $path) {
            if ($parsedConfig = $this->loadFile($path)) {
                $config = $parsedConfig;
                break;
            }
        }

        if ($homeDir = getenv('HOME')) {
            $localPath = $homeDir.'/.config.yml';

            if ($parsedConfig = $this->loadFile($localPath)) {
                $config = array_replace_recursive($parsedConfig, $config);
            }
        }

        return $config;
    }

    /**
     * Returns the possible paths
     *
     * @return array
     */
    protected function getPaths()
    {
        $paths = ['concise.yml','.concise.yml'];

        if ($customPath = $this->input->getParameterOption(['-c','--config'])) {
            if (!file_exists($customPath)) {
                throw new RuntimeException('Custom configuration file not found at '.$customPath);
            }

            $paths = [$customPath];
        }

        return $paths;
    }

    /**
     * Try to load a file
     *
     * @param string $path
     *
     * @return array|boolean
     */
    protected function loadFile($path)
    {
        if (file_exists($path) and $config = Yaml::parse($path)) {
            return $config;
        }

        return false;
    }
}
