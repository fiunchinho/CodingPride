<?php
namespace CodingPride\Command;

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
use CodingPride\BadgeGiver;

class GiveBadges extends Command
{
    protected function configure()
    {
        $this
            ->setName( 'badges:give' )
            ->setDescription( 'Give badges for latest commits. It connects to the API and gives badges based on these latest commits.' )
        ;
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $config             = json_decode( file_get_contents( __DIR__ . '/../config.json' ) , true );
        $this->createDatabaseManager( $config );

        $api                = new $config['api_wrapper']( $this->dm, $config, new \Guzzle\Http\Client() );
        $this->badge_factory= new BadgeFactory( $this->dm, $config['badges'] );

        $this->badge_giver  = new BadgeGiver();
        
        $commits = $this->dm->getRepository( '\CodingPride\Document\Commit' )->findBy( array( 'in_game' => 0 ), array( 'date' => 'asc' ) );
        $this->badge_giver->giveBadges( $commits, $this->badge_factory->getBadges() );

        $this->updateBadgesOutOfDate();

        $this->dm->flush();
    }

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

    protected function updateBadgesOutOfDate()
    {
        $inactive_badges = $this->badge_factory->getInactiveBadges();

        if ( !empty( $inactive_badges) )
        {
            $commits = $this->dm->getRepository( '\CodingPride\Document\Commit' )->findBy( array( 'in_game' => 1 ), array( 'date' => 'asc' ) );
            $this->badge_giver->giveBadges( $commits, $inactive_badges );
            foreach ( $inactive_badges as $badge )
            {
                $badge->setActive( 1 );
            }
        }
    }
}