Synopsys
=========

Symfony 3 Bundle for Google Recaptcha with Proxy configuration.

Step 1: Setting up the bundle
=============================
### 1) Add GoogleRecaptchaBundle to your project
 
 ```bash
 composer require kleegroup/google-recaptcha-bundle
 ```
 
 ### 2) Enable the bundle
 
 Enable the bundle in the kernel:
 
 ```php
 // app/AppKernel.php
 
 public function registerBundles()
 {
     $bundles = array(
         // ...
         new KleeGroup\GoogleReCaptchaBundle\GoogleReCaptchaBundle(),
     );
 }
 ```
 
 Step 2: Configure the bundle
 =============================
 
 ```yml
 // config.yml
 [...]
 google_re_captcha:
    site_key: [Google_site_key]
    secret_key: [Google_secret_key]
    enabled: true/false
    ajax: true/false
    locale_key: [locale_key]
    http_proxy:
        host: [IP or hostname]
        port: [Port]
 
 ```
 
Step 3: Usage
=============================
 ```php
    public function buildForm( FormBuilderInterface $builder, array $options)
    {
        [...]
        $builder
            ->add('recaptcha', ReCaptchaType::class, 
                    [
                          'mapped'      => false,
                          'constraints' => [
                              new ReCaptcha(),
                          ],
                    ]
            );
        [...]
        
    }
 
 ```