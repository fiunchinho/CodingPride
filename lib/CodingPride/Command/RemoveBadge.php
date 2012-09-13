<?php
namespace CodingPride\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use CodingPride\BadgeFactory;

class RemoveBadge extends CodingPride
{
    protected function configure()
    {
        $this
            ->setName( 'badges:remove' )
            ->setDescription( 'Remove a badge from the system, taking it away from users that earned it.' )
            ->addArgument( 'badge_name', InputArgument::REQUIRED, 'The name of the badge to delete' )
        ;
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if ( !$dialog->askConfirmation( $output, '<question>Continue with this action? y/n (default: no)</question>', false ) )
        {
            return;
        }
        
        $this->config = $this->getConfig();
        $this->createDatabaseManager( $this->config );

        try
        {
            $badge_factory  = new BadgeFactory( $this->dm, $this->config['badges'] );
            $badge_factory->removeBadge( $input->getArgument( 'badge_name' ) );

            $output->writeln('');
            $output->writeln('<comment>The badge \'' . $input->getArgument( 'badge_name' ) . '\' has been removed from the system</comment>');
        }
        catch ( \InvalidArgumentException $e )
        {
            $output->writeln('<error>The badge \'' . $input->getArgument( 'badge_name' ) . '\' does not exist </error>');
            return;
        }
    }
}