<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018–2024 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('itk_dev_config');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->variableNode('form_types')->defaultValue([])->end()
            ->variableNode('locales')->defaultValue([])->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
