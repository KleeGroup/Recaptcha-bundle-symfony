<?php

namespace KleeGroup\GoogleReCaptchaBundle\RequestMethod;

use ReCaptcha\RequestMethod;
use ReCaptcha\RequestMethod\Curl;
use ReCaptcha\RequestParameters;

/**
 * Class CurlPost
 *
 * @package KleeGroup\GoogleReCaptchaBundle\RequestMethod
 */
class CurlPost implements RequestMethod
{
    /**
     * URL to which requests are sent via cURL.
     * @const string
     */
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Curl connection to the reCAPTCHA service
     * @var Curl
     */
    protected $curl;

    protected $options;
    
    /**
     * CurlPost constructor.
     *
     * @param Curl|NULL $curl
     * @param array     $options
     */
    public function __construct( Curl $curl = null, array $options = [])
    {
        if ($curl) {
            $this->curl = $curl;
        } else {
            $this->curl = new Curl();
        }

        if ($options) {
            $this->options = $options;
        }
    }

    /**
     * Submit the cURL request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the reCAPTCHA response
     */
    public function submit(RequestParameters $params)
    {
        $handle = $this->curl->init(static::SITE_VERIFY_URL);

        $options = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params->toQueryString(),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        );

        $this->curl->setoptArray($handle, array_merge($options + $this->options));
        $response = $this->curl->exec($handle);
        $this->curl->close($handle);
        
        return $response;
    }
}