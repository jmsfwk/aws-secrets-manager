<?php

namespace jmsfwk\AwsSecretsManager;

use Aws\SecretsManager\SecretsManagerClient;
use Psr\SimpleCache\CacheInterface;

class SecretsManager
{
    /** @var CacheInterface */
    protected $cache;
    /** @var SecretsManagerClient */
    protected $client;

    public function __construct(CacheInterface $cache, SecretsManagerClient $client)
    {
        $this->cache = $cache;
        $this->client = $client;
    }

    public function get(string $key)
    {
        $cacheKey = $this->cacheKey($key);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $result = $this->client->getSecretValue([
            'SecretId' => $key,
        ]);

        $secret = $result['SecretString'];

        $this->cache->set($cacheKey, $secret);

        return $secret;
    }

    private function cacheKey(string $key): string
    {
        return sprintf('%s::%s', self::class, $key);
    }
}
