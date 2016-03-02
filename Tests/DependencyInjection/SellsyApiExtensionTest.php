<?php

namespace Sellsy\ApiBundle\Tests\DependencyInjection;
use Sellsy\ApiBundle\DependencyInjection\SellsyApiExtension;
use Sellsy\ApiBundle\Tests\Fixtures\Credentials;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * Class SellsyApiExtensionTest
 *
 * @package Sellsy\ApiBundle\Tests\DependencyInjection
 */
class SellsyApiExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessConsummerTokenIsSet()
    {
        $loader = new SellsyApiExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['consumer_token']);

        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessConsummerSecretIsSet()
    {
        $loader = new SellsyApiExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['consumer_secret']);

        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessUserTokenIsSet()
    {
        $loader = new SellsyApiExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['user_token']);

        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessUserSecretIsSet()
    {
        $loader = new SellsyApiExtension();
        $config = $this->getConfiguration();

        unset($config['authentication']['user_secret']);

        $loader->load(array($config), new ContainerBuilder());
    }

    public function testSellsyApiHasMapperService()
    {
        return $this->assertHasDefinition('sellsy_api.mapper');
    }

    public function testSellsyApiHasTransportService()
    {
        return $this->assertHasDefinition('sellsy_api.transport');
    }

    public function testSellsyApiHasAdapterBaseService()
    {
        return $this->assertHasDefinition('sellsy_api.adapters.base');
    }

    public function testSellsyApiHasAdapterMapperService()
    {
        return $this->assertHasDefinition('sellsy_api.adapters.mapper');
    }

    public function testSellsyApiHasClientService()
    {
        return $this->assertHasDefinition('sellsy_api.client');
    }

    public function testCompileContainer()
    {
        $configuration = new ContainerBuilder();
        $config = $this->getConfiguration();

        $loader = new SellsyApiExtension();
        $loader->load(array($config), $configuration);

        $configuration->compile();
    }

    /**
     * @expectedException \Sellsy\Exception\ServerException
     */
    public function testSellsyApiClientConnectError()
    {
        $configuration = new ContainerBuilder();
        $config = $this->getConfiguration();

        $config['authentication']['consumer_token'] = 'XXX';

        $loader = new SellsyApiExtension();
        $loader->load(array($config), $configuration);

        /** @var \Sellsy\Client $client */
        $client = $configuration->get('sellsy_api.client');
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
        $yaml  = sprintf('adapter: mapper%s', PHP_EOL);
        $yaml .= sprintf('authentication: %s', PHP_EOL);
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
        $configuration = new ContainerBuilder();
        $config = $this->getConfiguration();

        $loader = new SellsyApiExtension();
        $loader->load(array($config), $configuration);

        $this->assertTrue(($configuration->hasDefinition($id) ?: $configuration->hasAlias($id)));

        return $configuration->get($id);
    }
}