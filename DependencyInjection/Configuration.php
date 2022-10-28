<?php

namespace KleeGroup\GoogleReCaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder("google_re_captcha");
        $rootNode = \method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('google_re_captcha');

        $rootNode
            ->children()
                ->scalarNode("site_key")->isRequired()->end()
                ->scalarNode("secret_key")->isRequired()->end()
                ->booleanNode("enabled")->defaultTrue()->end()
                ->booleanNode("ajax")->defaultFalse()->end()
                ->scalarNode("locale_key")->defaultValue("%kernel.default_locale%")->end()
                ->arrayNode("http_proxy")
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode("host")->defaultValue(null)->end()
                        ->scalarNode("port")->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
