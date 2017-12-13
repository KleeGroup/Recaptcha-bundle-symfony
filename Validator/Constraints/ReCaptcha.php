<?php

namespace KleeGroup\GoogleReCaptchaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class ReCaptcha extends Constraint
{
    /**
     * @var string
     */
    public $invalidMessage = 'google_re_captcha.validator.message.invalid';

    /**
     * @var string
     */
    public $emptyMessage = 'google_re_captcha.validator.message.empty';

    /**
     * @return string
     */
    public function getTargets()
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'google_re_captcha_validator';
    }
}