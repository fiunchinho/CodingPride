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

class ImportCommits extends Command
{
    protected function configure()
    {
        $this
            ->setName( 'badges:import' )
            ->setDescription( 'Import all the commits from your repository' )
        ;
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $config = json_decode( file_get_contents( __DIR__ . '/../config.json' ) , true );

        $output->writeln('<info>This command will import the old commits from the repository that you specified in the config file.</info>');
        $output->writeln('<info>This is a time/resource consuming operation and it may not finish the first time you run it.</info>');
        $output->writeln('<comment>Please, visit https://github.com/fiunchinho/CodingPride for more information.</comment>');

        /*
        $dialog = $this->getHelperSet()->get('dialog');
        if ( !$dialog->askConfirmation( $output, '<question>Continue with this action? y/n (default: no)</question>', false ) )
        {
            return;
        }
        */
        
        $output->writeln('');
        $output->writeln('<comment>Importing old commits... be patient :)</comment>');

        $this->createDatabaseManager( $config );

        try
        {
            $commits = $this->dm->getRepository( '\CodingPride\Document\Commit' )->findAll();
            if ( count( $commits ) == 0 )
            {
                $badge_factory= new BadgeFactory( $this->dm, $config['badges'] );
                $badges = $badge_factory->getInactiveBadges();
                foreach ( $badges as $badge )
                {
                    $badge->setActive( 1 );
                }
            }

            $repository = new $config['api_wrapper']( $this->dm, $config, new \Guzzle\Http\Client() );
            $repository->getCommits();

            $output->writeln('<info>The import process has finished. You are free to run the "badges:give" command and start giving badges to your colleagues! :)</info>');
            $output->writeln('<info>We recommend to use it as a cron job in your system.</info>');
        }
        catch( \Exception $e )
        {
            $output->writeln('<error>The script has reached the API limit usage. You\'ll have to wait until you can run this script again. Please, read your API conditions to know when you can run it. </error>');
        }
        
        $this->dm->flush();
    }

    protected function getCommitsOnBatches( $repository, $rate_limit )
    {
        $oldest_commit      = $this->dm->getRepository( '\CodingPride\Document\Commit' )->findBy( array(), array( 'date' => 'asc' ), 1 );
        $params             = array();

        if ( $oldest_commit->hasNext() ) // It's not the first time this script runs.
        {
            $params         = array( 'last_sha' => $oldest_commit->getNext()->getRevision() );    
        }

        do
        {
            $latest_commits     = $repository->getCommits( $params );

            $number_of_commits  = count( $latest_commits );
            $rate_limit         = $rate_limit - $number_of_commits - 1;
            if ( $number_of_commits > 0 )
            {
                $oldest_commit      = $latest_commits[$number_of_commits - 1];
                $params             = array( 'last_sha' => $oldest_commit->getRevision() );
            }
            
        } while( $rate_limit > 0 && $number_of_commits > 0 ) ;
    }

    private function createDatabaseManager( $config )
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
}