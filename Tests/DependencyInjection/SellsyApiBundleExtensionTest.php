<?php

namespace Sellsy\ApiBundle\Tests\DependencyInjection;
use Sellsy\ApiBundle\DependencyInjection\SellsyApiBundleExtension;
use Sellsy\ApiBundle\Tests\Fixtures\Credentials;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * Class SellsyApiBundleExtensionTest
 *
 * @package Sellsy\ApiBundle\Tests\DependencyInjection
 */
class SellsyApiBundleExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessConsummerTokenIsSet()
    {
        $loader = new SellsyApiBundleExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['consumer_token']);

        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessConsummerSecretIsSet()
    {
        $loader = new SellsyApiBundleExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['consumer_secret']);

        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessUserTokenIsSet()
    {
        $loader = new SellsyApiBundleExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['user_token']);

        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessUserSecretIsSet()
    {
        $loader = new SellsyApiBundleExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['user_secret']);

        $loader->load(array($config), new ContainerBuilder());
    }

    public function testSellsyApiHasParserService()
    {
        return $this->assertHasDefinition('sellsy_api.parser');
    }

    public function testSellsyApiHasCacheService()
    {
        return $this->assertHasDefinition('sellsy_api.cache');
    }

    public function testSellsyApiHasReaderService()
    {
        return $this->assertHasDefinition('sellsy_api.reader');
    }

    public function testSellsyApiHasMapperService()
    {
        return $this->assertHasDefinition('sellsy_api.mapper');
    }

    public function testSellsyApiHasTransportService()
    {
        return $this->assertHasDefinition('sellsy_api.transport');
    }

    public function testSellsyApiHasAdapterService()
    {
        return $this->assertHasDefinition('sellsy_api.adapter');
    }

    public function testSellsyApiHasClientService()
    {
        return $this->assertHasDefinition('sellsy_api.client');
    }

    /**
     * @expectedException \Sellsy\Exception\ServerException
     */
    public function testSellsyApiClientConnectError()
    {
        $this->configuration = new ContainerBuilder();
        $config = $this->getConfiguration();

        $config['authentication']['consumer_token'] = 'XXX';

        $loader = new SellsyApiBundleExtension();
        $loader->load(array($config), $this->configuration);

        /** @var \Sellsy\Client $client */
        $client = $this->configuration->get('sellsy_api.client');
        $client->getApiInfos();
    }

    /**
     * @depends testSellsyApiHasClientService
     */
    public function testSellsyApiClientConnect(\Sellsy\Client $client)
    {
        $this->assertEquals('ok', $client->getApiInfos()->status);
    }

    /**
     * @return mixed
     */
    protected function getConfiguration()
    {
        $yaml = sprintf('authentication: %s', PHP_EOL);

        $yaml .= sprintf('    consumer_token: "%s" %s', Credentials::$consumerToken, PHP_EOL);
        $yaml .= sprintf('    consumer_secret: "%s" %s', Credentials::$consumerSecret, PHP_EOL);
        $yaml .= sprintf('    user_token: "%s" %s', Credentials::$userToken, PHP_EOL);
        $yaml .= sprintf('    user_secret: "%s" %s', Credentials::$userSecret, PHP_EOL);

        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * @param $id
     * @return object
     */
    private function assertHasDefinition($id)
    {
        $this->configuration = new ContainerBuilder();
        $config = $this->getConfiguration();

        $loader = new SellsyApiBundleExtension();
        $loader->load(array($config), $this->configuration);

        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));

        return $this->configuration->get($id);
    }
}