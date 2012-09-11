<?php
namespace CodingPride\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommits extends CodingPride
{
    protected function configure()
    {
        $this
            ->setName( 'badges:import' )
            ->setDescription( 'Import all the commits from your repository' )
            ->addOption( 'force', null, InputOption::VALUE_NONE, 'If you want to bypass the confirmation question' );
        ;
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $output->writeln('<info>This command will import the old commits from the repository that you specified in the config file.</info>');
        $output->writeln('<info>This is a time/resource consuming operation and it may not finish the first time you run it.</info>');
        $output->writeln('<comment>Please, visit https://github.com/fiunchinho/CodingPride for more information.</comment>');

        if ( !$input->getOption('force') )
        {
            $dialog = $this->getHelperSet()->get('dialog');
            if ( !$dialog->askConfirmation( $output, '<question>Continue with this action? y/n (default: no)</question>', false ) )
            {
                return;
            }
        }
        
        $output->writeln('');
        $output->writeln('<comment>Importing old commits... be patient :)</comment>');

        try
        {
            $this->config = $this->getConfig();
            $this->createDatabaseManager( $this->config );

            $repository = new $this->config['api_wrapper']( $this->dm, $this->config, new \Guzzle\Http\Client() );
            $repository->getCommits();

            $output->writeln('<info>The import process has finished. You are free to run the "badges:give" command and start giving badges to your colleagues! :)</info>');
            $output->writeln('<info>We recommend to use it as a cron job in your system.</info>');
        }
        catch( \CodingPride\Repository\ApiUsageLimitReachedException $e )
        {
            $output->writeln('<error>The script has reached the API limit usage. You\'ll have to wait until you can run this script again. Please, read your API conditions to know when you can run it. </error>');
        }
        
        $this->dm->flush();
    }
}