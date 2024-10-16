<?php

namespace Hch\ElasticMLBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('elastic_ml');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->scalarNode('host')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
