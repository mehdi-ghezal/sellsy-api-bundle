Prerequisites
-------------

This version of the bundle requires Symfony 2.1+ and have been tested with Symfony 3.0

Installation
------------

1. Download SellsyApiBundle using composer
2. Enable the Bundle
3. Configure the SellsyApiBundle

Step 1: Download SellsyApiBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require mehdi-ghezal/sellsy-api-bundle "dev-master"

Composer will install the bundle to your project's ``vendor/mehdi-ghezal/SellsyApiBundle`` directory.

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel::

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Sellsy\SellsyApiBundle\SellsyApiBundle(),
            // ...
        );
    }

Step 5: Configure the SellsyApiBundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the following configuration to your ``config.yml`` file

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        sellsy_api:
            authentication:
                consumer_token:     "your_consumer_token"   # Required
                consumer_secret:    "your_consumer_secret"  # Required
                user_token:         "your_user_token"       # Required
                user_secret:        "your_user_secret"      # Required