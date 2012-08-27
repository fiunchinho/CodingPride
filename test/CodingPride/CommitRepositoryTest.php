<?php
namespace CodingPride\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/OdmTestCase.php';

class CommitRepositoryTest extends \CodingPride\Tests\OdmTestCase
{
	/*
    public function setUp()
    {
    	AnnotationDriver::registerAnnotationClasses();

		$config = new Configuration();
		$config->setProxyDir('.');
		$config->setProxyNamespace('Proxies');
		$config->setHydratorDir('.');
		$config->setHydratorNamespace('Hydrators');
		$config->setMetadataDriverImpl( AnnotationDriver::create('.') );
		//$config->setDefaultDB('codingPride');
		//$config->addFilter('testFilter', 'Doctrine\ODM\MongoDB\Tests\Query\Filter\Filter');
        $conn 		= new Connection( null, array(), $config );
        $this->_dm 	= DocumentManager::create($conn, $config);



        $config 		= new Configuration();
        $config->setProxyDir( __DIR__ . '/../../Proxies' );
        $config->setProxyNamespace( 'Doctrine\ODM\MongoDB\Tests\Proxies' );
        $config->setMetadataDriverImpl( AnnotationDriver::create('.') );

        $this->_dm		= DocumentManagerMock::create( new ConnectionMock(), $config );
    }*/


	public function testCreatingCommit()
	{
		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getAuthorUsername', 'setAuthor' ) );
		$commit->expects( $this->once() )->method( 'getAuthorUsername' )->will( $this->returnValue( $username = 'username' ) );
		$commit->expects( $this->once() )->method( 'setAuthor' );

		$converter = $this->getMock( '\CodingPride\Source\ConverterInterface', array( 'convert', 'getRevision' ) );
		$converter->expects( $this->once() )->method( 'convert' )->will( $this->returnValue( $commit ) );

		//$repository         = $this->getMock( '\CodingPride\UserRepository', array( 'create' ), array(), '', false );
		//$repository->expects( $this->once() )->method( 'create' );

		//$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		//$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );

		$commit_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Commit' );
		//$commit_repository->setDocumentManager( $database );
		//$commit_repository->dm = $database ;
		
		$commit_repository->create( array(), $converter );
	}

	public function testCreatingCommitReturnsFalseWhenTheCommitAlreadyExists()
	{
		$revision = 23;
		$commit_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Commit' );

		$converter = $this->getMock( '\CodingPride\Source\ConverterInterface', array( 'convert', 'getRevision' ) );
		$converter->expects( $this->once() )->method( 'getRevision' )->will( $this->returnValue( $revision ) );

		$commit_repository         = $this->getMock( get_class( $commit_repository ), array( 'findOneBy' ), array(), '', false );
		$commit_repository->expects( $this->once() )
						->method( 'findOneBy' )
						->with( array( 'revision' => $revision ) )
						->will( $this->returnValue( true ) );
		
		$this->assertFalse( $commit_repository->create( array(), $converter ), 'It must return false when the commit already exists' );
	}
}