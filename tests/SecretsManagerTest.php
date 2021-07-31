<?php

namespace Tests\AwsSecretsManager;

use Aws\SecretsManager\SecretsManagerClient;
use jmsfwk\AwsSecretsManager\SecretsManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class SecretsManagerTest extends TestCase
{
    /** @var MockObject|CacheInterface */
    private $cache;
    /** @var SecretsManagerClient */
    private $client;
    /** @var SecretsManager */
    private $secretsManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->secretsManager = new SecretsManager(
            $this->cache = $this->createMock(CacheInterface::class),
            $this->client = $this->getMockBuilder(SecretsManagerClient::class)
                ->addMethods(['getSecretValue'])
                ->disableOriginalConstructor()
                ->getMock()
        );
    }

    /** @test */
    public function get_returns_cached_item()
    {
        $this->cache->method('has')->willReturn(true);
        $this->cache->method('get')->willReturn('::value::');

        $this->client->expects(self::never())->method('getSecretValue');

        self::assertEquals('::value::', $this->secretsManager->get(''));
    }

    /** @test */
    public function get_caches_item_that_are_not_cached()
    {
        $this->cache->method('has')->willReturn(false);

        $this->cache->expects(self::once())->method('set')->with(self::anything(), '::value::');
        $this->client->expects(self::once())
            ->method('getSecretValue')
            ->willReturn(new \Aws\Result([
                'SecretString' => '::value::',
            ]));

        self::assertEquals('::value::', $this->secretsManager->get(''));
    }

}
