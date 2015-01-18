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

use Ivory\HttpAdapter\GuzzleHttpHttpAdapter;
use Symfony\Component\Console\Helper\InputAwareHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Concise extends InputAwareHelper
{
    /**
     * @var array
     */
    protected $providers = array(
        'bitly'  => 'Concise\Provider\Bitly',
        'google' => 'Concise\Provider\Google',
        'tinycc' => 'Concise\Provider\Tinycc',
    );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'concise';
    }

    /**
     * @return Concise
     */
    public function getConcise()
    {
        $config = $this->loadConfiguration($input);

        if (!$provider = $this->input->getParameterOption(['-p', '--provider'])) {
            $provider = 'default';
        }

        return $this->concise;
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
