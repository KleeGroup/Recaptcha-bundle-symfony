<?php

namespace KleeGroup\GoogleReCaptchaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReCaptchaType
 *
 * @package KleeGroup\GoogleReCaptchaBundle\Form\Type
 */
class ReCaptchaType extends AbstractType
{
    /**
     * ReCaptcha public key
     *
     * @var string
     */
    protected $siteKey;

    /**
     * Enabled ReCaptcha?
     *
     * @var boolean
     */
    protected $enabled;

    /**
     * ReCaptchaType constructor.
     *
     * @param $siteKey
     * @param $enabled
     */
    public function __construct($siteKey, $enabled)
    {
        $this->siteKey = $siteKey;
        $this->enabled = $enabled;
    }
    
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView( FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'site_key' => $this->siteKey,
            'enabled'  => $this->enabled,
        ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions( OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'enabled' => true,
            'site_key' => null,
            'attr' => [
                'options' => [
                    'theme' => 'light',
                    'size' => 'normal',
                    'type' => 'image',
                ],
            ],
        ]);
    }
    
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'google_re_captcha';
    }
}