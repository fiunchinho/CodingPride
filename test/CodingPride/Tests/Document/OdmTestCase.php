<?php
namespace CodingPride\Tests\Document;

use Doctrine\ODM\MongoDB\Configuration;

class OdmTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Creates an DocumentManager for testing purposes.
     *
     * @return Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function _getTestDocumentManager()
    {
        \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::registerAnnotationClasses();

        $config = new Configuration();
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());

        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('Proxies');

        $config->setHydratorDir(__DIR__ . '/Hydrators');
        $config->setHydratorNamespace('Hydrators');

        return \CodingPride\Tests\DocumentManagerMock::create( new \CodingPride\Tests\ConnectionMock(), $config );
    }
}