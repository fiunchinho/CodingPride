<?php
namespace CodingPride\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/OdmTestCase.php';

class CommitRepositoryTest extends \CodingPride\Tests\OdmTestCase
{
	public function testCreatingCommitThatAlreadyExistsReturnsFalse()
	{
		$expected_revision = 23;

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getAuthorUsername', 'getRevision' ) );
		$commit->expects( $this->never() )->method( 'getAuthorUsername' );
		$commit->expects( $this->once() )->method( 'getRevision' )->will( $this->returnValue( $expected_revision ) );

		$commit_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Commit' );

		$commit_repository         = $this->getMock( get_class( $commit_repository ), array( 'findOneBy' ), array(), '', false );
		$commit_repository->expects( $this->once() )
						->method( 'findOneBy' )
						->with( array( 'revision' => $expected_revision ) )
						->will( $this->returnValue( $commit ) );

		$this->assertFalse( $commit_repository->create( $commit ), 'It must return false when the commit already existed' );
	}

	public function testUserRepositoryIsCalledWhenCommitDoesNotExists()
	{
		$commit_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Commit' );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getAuthorUsername', 'getRevision', 'setAuthor' ) );
		$commit->expects( $this->once() )->method( 'getAuthorUsername' );
		$commit->expects( $this->once() )->method( 'getRevision' )->will( $this->returnValue( 23 ) );
		$commit->expects( $this->once() )->method( 'setAuthor' );

		$this->assertSame( $commit, $commit_repository->create( $commit ), 'It must return the same commit' );
	}
}