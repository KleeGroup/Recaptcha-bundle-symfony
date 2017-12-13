<?php

namespace KleeGroup\GoogleReCaptchaBundle\Validator\Constraints;

use KleeGroup\GoogleReCaptchaBundle\RequestMethod\CurlPost;
use ReCaptcha\ReCaptcha as GoogleReCaptcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * ReCaptchaValidator: Validator for Google Recaptcha.
 *
 * @package KleeGroup\GoogleReCaptchaBundle\Validator\Constraints
 */
class ReCaptchaValidator extends ConstraintValidator
{
    /**
     * Enabled ReCaptcha?
     *
     * @var boolean
     */
    protected $enabled;

    /**
     * Google ReCaptcha Private key
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Request Stack
     *
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * HTTP proxy configuration
     *
     * @var array;
     */
    protected $httpProxy;
    
    /**
     * Curl operators for proxy.
     *
     * @var array
     */
    protected $curlOptProxy;

    /**
     * ReCaptchaValidator constructor.
     *
     * @param boolean      $enabled
     * @param string       $secretKey
     * @param RequestStack $requestStack
     * @param array        $httpProxy
     */
    public function __construct($enabled, $secretKey, RequestStack $requestStack, array $httpProxy)
    {
        $this->enabled      = $enabled;
        $this->secretKey    = $secretKey;
        $this->requestStack = $requestStack;
        $this->httpProxy    = $httpProxy;
    
        $this->curlOptProxy = [];
        if ( $this->httpProxy[ 'host' ] ) {
            $this->curlOptProxy[ CURLOPT_PROXY ] = $this->httpProxy[ 'host' ];
        
        }
        if ( $this->httpProxy[ 'port' ] ) {
            $this->curlOptProxy[ CURLOPT_PROXYPORT ] = $this->httpProxy[ 'port' ];
        }
    
        if ( $this->httpProxy[ 'host' ] || $this->httpProxy[ 'port' ] ) {
            $this->curlOptProxy[ CURLOPT_HTTPPROXYTUNNEL ] = true;
        }
    }
    
    /**
     * Checks if the passed captcha's value is valid.
     *
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReCaptcha) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ReCaptcha');
        }

        if (!$this->enabled) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $requestValue = $request->request->get('g-recaptcha-response');

        if (!$requestValue) {
            $this->context->buildViolation($constraint->emptyMessage)
                ->addViolation();
            return;
        }
    
        $reCaptcha = new GoogleReCaptcha($this->secretKey, new CurlPost(null, $this->curlOptProxy));

        if (!$reCaptcha->verify($requestValue, $request->getClientIp())->isSuccess()) {
            $this->context->buildViolation($constraint->invalidMessage)
                ->addViolation();
            return;
        }
    }
}