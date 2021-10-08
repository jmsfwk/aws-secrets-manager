# AWS Secrets Manager

The PHP AWS SDK doesn't provide a caching system for Secret Manager calls. This package provides a very simple class that will use a
PSR-16 Simple Cache to cache string secrets.

## Setup

Install with Composer

```shell
$ composer require jmsfwk/aws-secrets-manager
```

Then create a `SecretsManager` instance. 

```php
<?php

use Aws\SecretsManager\SecretsManagerClient;
use jmsfwk\AwsSecretsManager\SecretsManager;
use Psr\SimpleCache\CacheInterface;

/** @var CacheInterface $cache */

$secretsManager = new SecretsManager($cache, new SecretsManagerClient(/* aws options */));
```
