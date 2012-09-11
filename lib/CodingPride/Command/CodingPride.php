<?php
namespace CodingPride\Command;

use Symfony\Component\Console\Command\Command;

use Doctrine\MongoDB\Connection;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

abstract class CodingPride extends Command
{
	protected function createDatabaseManager( $config )
    {
        AnnotationDriver::registerAnnotationClasses();

        $doctrine_config = new Configuration();
        $doctrine_config->setProxyDir( __DIR__ . '/../Proxy' );
        $doctrine_config->setProxyNamespace( 'Proxy' );
        $doctrine_config->setHydratorDir( __DIR__ . '/../Hydrator' );
        $doctrine_config->setHydratorNamespace( 'Hydrator' ) ;
        $doctrine_config->setMetadataDriverImpl( AnnotationDriver::create('.') );
        $doctrine_config->setDefaultDB( $config['mongo']['options']['db'] );
        $connection = new Connection( $config['mongo']['server'] );

        $this->dm = DocumentManager::create( $connection, $doctrine_config );
    }

    protected function getConfig()
    {
    	return json_decode( file_get_contents( __DIR__ . '/../config.json' ) , true );
    }
}