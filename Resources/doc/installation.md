Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require symfony2bundles/crawler-bundle "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new S2b\CrawlerBundle\S2bCrawlerBundle(),
        );

        // ...
    }

    // ...
}
```

Extend bundle:

```bash
$ php app/console sonata:easy-extends:generate S2bCrawlerBundle
```

And enable extended bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Application\S2b\CrawlerBundle\ApplicationS2bCrawlerBundle(),
        );

        // ...
    }

    // ...
}
```

Configuring dependencies
------------------------

```yaml
# app/config/config.yml
s2b_crawler:
    filters:
        - http://url-to-include/one
        - http://url-to-include/another
        - http://url-to-include/third
```