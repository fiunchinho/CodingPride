<?php
require_once '../../vendor/autoload.php';

use Doctrine\MongoDB\Connection;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

AnnotationDriver::registerAnnotationClasses();

$config = new Configuration();
$config->setProxyDir('.');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir('.');
$config->setHydratorNamespace('Hydrators');
$config->setMetadataDriverImpl(AnnotationDriver::create('.'));
$config->setDefaultDB('codingPride');

$document_manager = DocumentManager::create( new Connection(), $config );

$badge_factory		= new BadgeFactory();
$badge_scheduler 	= new BadgeScheduler( $document_manager );
$api 				= new FisheyeCommitApiWrapper( 'http://crucible.redtonic' );
$badge_giver 		= new BadgeGiver();

$badges_in_config	= $badge_factory->getBadges();

$badge_scheduler->insertNewBadgesInDb( $badges_in_config );

$badges_out_of_date = $badge_scheduler->getBadgesOutOfDate( $badges_in_config, new \Datetime( '2011-01-01' ) );
foreach ( $badges_out_of_date as $key => $badge )
{
	$start_period 	= $badge->getLast_date_checked();
	$end_period 	= clone( $start_period );
	$end_period->modify( '+60 days' );
	$commit_list 	= $api->getCommitList( $end_period, $start_period );
	$badge_giver->giveBadges( $commit_list, array( $badge->getName() => $badge ) );
	$badge->setLast_date_checked( $end_period );
	$document_manager->persist( $badge );
	$document_manager->flush();
	var_dump("Actualizar " . $badge->getName() . " a " . $end_period->format( 'Y-m-d' ));
}
$document_manager->flush();





// Este config viene de un JSON, o quizá debería venir de base de datos con los badges enabled = true.
$badges_available = array(
	'BraveCommit',
	'CoreContributor',
	'JsContributor'
);


/*
$badge_scheduler 	= new BadgeScheduler();
$last_date_checked 	= $badge_scheduler->getLastDateChecked();

$api 				= new FisheyeCommitApiWrapper();
$badge_giver 		= new BadgeGiver();
$commit_list 		= $api->getCommitList( $last_date_checked );
$badge_giver->giveBadges( $commit_list->commits, $badges_available );

$badge_scheduler->setLastDateChecked( $last_date_checked->modify( '+1 day' ) );
*/