<?php

namespace Sellsy\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Sellsy\ApiBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('sellsy_api');
        $rootNode
            ->children()
                ->scalarNode('adapter')->defaultValue('mapper')
                    ->validate()
                        ->ifNotInArray(array('base', 'mapper'))
                        ->thenInvalid('Configuration "adapter" is required and can have one of the following values: base, mapper')
                    ->end()
                ->end()
                ->arrayNode('authentication')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->children()
                        ->scalarNode('consumer_token')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('user_token')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('user_secret')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
