<?php
namespace CodingPride;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\MongoDB\Connection;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use CodingPride\Source\GithubCommitApiWrapper;
use CodingPride\BadgeFactory;
use CodingPride\Gamificator;

//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;

class ImportCommits extends Command
{
    protected function configure()
    {
        $this
            ->setName( 'badges:install' )
            ->setDescription( 'Import all the commits from your repository' )
        ;
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $config             = json_decode( file_get_contents( __DIR__ . '/config.json' ) , true );

        echo "Importando los commits viejos... \n\n";

        AnnotationDriver::registerAnnotationClasses();

        $doctrine_config = new Configuration();
        $doctrine_config->setProxyDir( __DIR__ . '/Proxy' );
        $doctrine_config->setProxyNamespace( 'Proxy' );
        $doctrine_config->setHydratorDir( __DIR__ . '/Hydrator' );
        $doctrine_config->setHydratorNamespace( 'Hydrator' ) ;
        $doctrine_config->setMetadataDriverImpl( AnnotationDriver::create('.') );
        $doctrine_config->setDefaultDB( $config['mongo']['options']['db'] );
        $connection         = new Connection( $config['mongo']['server'] );

        $document_manager   = DocumentManager::create( $connection, $doctrine_config );
        $http               = new Http();
        $api                = new $config['api_wrapper']( $document_manager, $config, $http );

        $oldest_commit      = $document_manager->getRepository( '\CodingPride\Document\Commit' )->findBy( array(), array( 'date' => 'asc' ), 1 );
        $params             = array();

        if ( $oldest_commit->hasNext() ) // It's not the first time this script runs.
        {
            $params         = array( 'last_sha' => $oldest_commit->getNext()->getRevision() );    
        }
        
        $rate_limit         = \CodingPride\Source\GithubApiWrapper::API_RATE_LIMIT;

        do
        {
            $latest_commits     = $api->getLatestCommits( $params );
            $number_of_commits  = count( $latest_commits );
            $rate_limit         = $rate_limit - $number_of_commits - 1;
            
            if ( $number_of_commits > 0 )
            {
                $oldest_commit      = $latest_commits[$number_of_commits - 1];
                $params             = array( 'last_sha' => $oldest_commit->getRevision() );
            }
        } while( $rate_limit > 0 && $number_of_commits > 0 ) ;

        if ( 0 >= $rate_limit )
        {
            echo "Todavía no he terminado, pero he llegado al límite de la API. La siguiente ejecución de este cron seguiré.";
        }
        
        echo "Termine de importar los commits \n\n";
    }
}