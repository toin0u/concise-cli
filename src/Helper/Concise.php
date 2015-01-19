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

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Concise extends InputAwareHelper
{
    /**
     * @var array
     */
    protected $providers = [
        'bitly'  => 'Concise\Provider\Bitly',
        'google' => 'Concise\Provider\Google',
        'tinycc' => 'Concise\Provider\Tinycc',
    ];

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
        $config = $this->getHelperSet()->get('config')->getConfiguration();
        $arguments = [];

        $profile = $this->input->getOption('profile');

        if (!isset($config['profiles'])) {
            throw new \RuntimeException('No profiles found');
        } elseif (!isset($config['profiles'][$profile])) {
            throw new \RuntimeException(sprintf('Profile "%s" cannot be found', $profile));
        }

        $profileConfig = $config['profiles'][$profile];

        $class = $this->findProvider($config['profiles'][$profile]['provider']);

        $reflection = new \ReflectionClass($class);

        if (isset($config['profiles'][$profile]['arguments'])) {
            $arguments = $config['profiles'][$profile]['arguments'];
        }

        array_unshift($arguments, new GuzzleHttpHttpAdapter);

        $provider = $reflection->newInstanceArgs($arguments);

        return new \Concise\Concise($provider);
    }

    /**
     * Finds the provider in the map or as a class
     *
     * @param string $provider
     *
     * @return string
     */
    protected function findProvider($provider)
    {
        if (isset($this->providers[$provider])) {
            return $this->providers[$provider];
        } elseif (class_exists($provider)) {

            if (is_subclass_of($provider, 'Concise\Provider\HttpAdapterAware')) {
                return $provider;
            }

            throw new \InvalidArgumentException(sprintf('Provider "%s" must be a subclass of Concise\Provider\HttpAdapterAware', $provider));
        }

        throw new \RuntimeException(sprintf('Provider "%s" not found', $provider));
    }
}
