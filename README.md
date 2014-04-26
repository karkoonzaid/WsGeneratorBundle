WsGeneratorBundle
=====================

The `WsGeneratorBundle` extends the default Symfony2 command line
interface by providing new interactive and intuitive commands for generating
code skeletons like bundles, form classes, or CRUD controllers based on a
Doctrine 2 schema.

## Prerequisites

This version of the bundle requires Symfony 2.3+.

## Installation

Installation is a quick 4 step process:

1. Download WsGeneratorBundle using composer
2. Enable the Bundle
3. Generate your Entity class
4. Generate CRUD for your Entity class

### Step 1: Download WsGeneratorBundle using composer

Add WsGeneratorBundle in your composer.json:

```js
{
    "require": {
        "web-solution/generator-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update web-solution/generator-bundle
```

Composer will install the bundle to your project's `vendor/web-solution` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        $bundles[] = new Ws\Bundle\GeneratorBundle\WsGeneratorBundle();
    }
}
```

### Step 3: Generate your Entity class

``` bash
$ php app/console ws:generate:entity
```

Add the constructor in your Entity class:

``` php
<?php
// Entity/Post.php

function __construct()
{
    $this->token = base_convert(sha1(uniqid(mt_rand(1, 999) . new \DateTime(), true)), 16, 36);
}
```

### Step 4: Generate CRUD for your Entity class

``` bash
$ php app/console ws:generate:crud
```

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/ceif-khedhiri/WsGeneratorBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.