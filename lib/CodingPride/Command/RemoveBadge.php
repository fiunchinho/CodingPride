<?php
namespace CodingPride\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        $badge_name = $input->getArgument( 'badge_name' );

        $badge = $this
            ->dm
            ->getRepository( '\CodingPride\Document\Badge' )
            ->findOneByName( $badge_name );

        if ( null === $badge )
        {
            $output->writeln('<error>The badge \'' . $badge_name . '\' does not exist </error>');
            return;
        }

        $users = $this
            ->dm
            ->getRepository( '\CodingPride\Document\User' )
            ->findAll();

        foreach ( $users as $user )
        {
            $user->removeBadge( $badge );
        }

        $this->dm->remove( $badge );
        $this->dm->flush();

        $output->writeln('');
        $output->writeln('<comment>The badge \'' . $badge_name . '\' has been removed from the system</comment>');
    }
}