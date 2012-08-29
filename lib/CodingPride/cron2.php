<?php
require_once '../../vendor/autoload.php';

use Doctrine\MongoDB\Connection;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use CodingPride\Source\GithubCommitApiWrapper;
use CodingPride\BadgeFactory;
use CodingPride\Gamificator;

AnnotationDriver::registerAnnotationClasses();

$config = new Configuration();
$config->setProxyDir( __DIR__ . '/Proxy' );
$config->setProxyNamespace( 'Proxy' );
$config->setHydratorDir( __DIR__ . '/Hydrator' );
$config->setHydratorNamespace( 'Hydrator') ;
$config->setMetadataDriverImpl( AnnotationDriver::create('.') );
$config->setDefaultDB('codingPride');

$document_manager 	= DocumentManager::create( new Connection(), $config );
$http 				= new CodingPride\Http();
$config 			= json_decode( file_get_contents( 'config.json' ) , true );
$api 				= new $config['api_wrapper']( $document_manager, $config, $http );
$badge_factory		= new BadgeFactory( $document_manager, $config['badges'] );
//$pagination_param 	= $document_manager->getRepository( 'CodingPride\Document\Badge' )->getLastPaginationParam();
$gamificator 		= new Gamificator( $api, $badge_factory->getBadges() );


$gamificator->gamify();

$document_manager->flush();





































/*
$badges = array(
	'BraveCommit' => array(
		'name'			=> 'BraveCommit',
		'description'	=> 'Commiting after 16h',
		'conditions'	=> array(
			'LateCommitCondition',
			'PhpFileExtensionCondition'
		)
	),
	'EarlyBirdCommit' => array(
		'name'			=> 'EarlyBirdCommit',
		'description'	=> 'Commiting after 16h',
		'conditions'	=> array(
			'EarlyCommitCondition'
		)
	),
	'PhpContributor' => array(
		'name'			=> 'PhpContributor',
		'description'	=> 'Commiting after 16h',
		'conditions'	=> array(
			'PhpFileExtensionCondition'
		)
	),
	'JsContributor' => array(
		'name'			=> 'JsContributor',
		'description'	=> 'Commiting after 16h',
		'conditions'	=> array(
			'JsFileExtensionCondition'
		)
	),
	'Handyman' => array(
		'name'			=> 'Handyman',
		'description'	=> 'Commiting after 16h',
		'conditions'	=> array(
			'PhpFileExtensionCondition',
			'JsFileExtensionCondition'
		)
	)
);
*/
//echo json_encode( $badges );die;