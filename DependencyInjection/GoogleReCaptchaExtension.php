<?php

namespace KleeGroup\GoogleReCaptchaBundle\DependencyInjection;

use KleeGroup\GoogleReCaptchaBundle\Exception\NotSupportedException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class GoogleReCaptchaExtension
 *
 * @package KleeGroup\GoogleReCaptchaBundle\DependencyInjection
 */
class GoogleReCaptchaExtension extends ConfigurableExtension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function loadInternal( array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        foreach ($configs as $key => $value) {
            $container->setParameter('google_re_captcha.'.$key, $value);
        }

        $templatingEngines = $container->getParameter("templating.engines");

        if (in_array("twig", $templatingEngines)) {
            $formResource = "GoogleReCaptchaBundle:Form:google_re_captcha_widget.html.twig";
            $container->setParameter("twig.form.resources", array_merge(
                $container->getParameter("twig.form.resources"),
                array($formResource)
            ));
        } else {
            throw new NotSupportedException('Only twig templating engines is supported.');
        }
    }
}