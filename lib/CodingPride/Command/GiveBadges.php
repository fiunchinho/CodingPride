<?php
namespace CodingPride\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use CodingPride\BadgeFactory;
use CodingPride\BadgeGiver;

class GiveBadges extends CodingPride
{
    protected function configure()
    {
        $this
            ->setName( 'badges:give' )
            ->setDescription( 'Give badges for latest commits in the database.' )
        ;
    }

    /**
     * Reads badges from the config file and insert them into the database.
     * Get latest commits from database and give badges for those commits.
     * Take badges that are out of date and go through the commits for those badges.
     *
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $this->config           = $this->getConfig();
        $this->createDatabaseManager( $this->config );
        $this->badge_factory    = new BadgeFactory( $this->dm, $this->config['badges'] );
        $this->badge_giver      = new BadgeGiver();

        $this->activateBadgesIfFirstTime();

        $commits = $this
            ->dm
            ->getRepository( '\CodingPride\Document\Commit' )
            ->findBy( array( 'in_game' => 0 ), array( 'date' => 'asc' ) );

        $this->badge_giver->giveBadges( $commits, $this->badge_factory->getBadges() );

        $this->updateBadgesOutOfDate();

        $this->dm->flush();
    }

    /**
     * Everytime we run this script, we check if we found a new badge in the config file.
     * If we did, we need to go through all the commits and check if that commit
     * has to be given. When we check all the commits, we can activate it so it is
     * calculated normally.
     *
     */
    protected function updateBadgesOutOfDate()
    {
        $inactive_badges = $this->badge_factory->getInactiveBadges();

        if ( !empty( $inactive_badges) )
        {
            $commits = $this
                ->dm
                ->getRepository( '\CodingPride\Document\Commit' )
                ->findBy( array( 'in_game' => 1 ), array( 'date' => 'asc' ) );

            $this->badge_giver->giveBadges( $commits, $inactive_badges );
            $inactive_badges->activateAll();
        }
    }

    /**
     * If it's the first time that we run the script, we don't have to wait for the recent added badges
     * to be updated. So we just put the badges active to start giving badges right away.
     *
     */
    protected function activateBadgesIfFirstTime()
    {
        if ( 0 == count( $this->dm->getRepository( '\CodingPride\Document\Commit' )->findAll() ) )
        {
            $badges = $this->badge_factory->getInactiveBadges()->activateAll();
        }
    }
}