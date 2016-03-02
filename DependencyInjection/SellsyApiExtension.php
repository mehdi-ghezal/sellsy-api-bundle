<?php

namespace Sellsy\ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class SellsyApiExtension
 *
 * @package Sellsy\ApiBundle\DependencyInjection
 */
class SellsyApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('sellsy_api.authentication.consumer.token', $config['authentication']['consumer_token']);
        $container->setParameter('sellsy_api.authentication.consumer.secret', $config['authentication']['consumer_secret']);
        $container->setParameter('sellsy_api.authentication.user.token', $config['authentication']['user_token']);
        $container->setParameter('sellsy_api.authentication.user.secret', $config['authentication']['user_secret']);

        switch($config['adapter']) {
            case 'base':
                $container->setParameter('sellsy_api.adapter', new Reference('sellsy_api.adapters.base'));
                break;
            case 'mapper':
                $container->setParameter('sellsy_api.adapter', new Reference('sellsy_api.adapters.mapper'));
                break;
        }
    }
}
