<?php
namespace CodingPride\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/OdmTestCase.php';


class UserRepositoryTest extends OdmTestCase
{
	public function testThatOnlyLooksForTheUserTheFirstTime()
	{
		$commit_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\User' );
		$commit_repository = $this->getMock( get_class( $commit_repository ), array( 'findOneBy' ), array(), '', false );
		$commit_repository->expects( $this->once() )->method( 'findOneBy' )->will( $this->returnValue( 'some user' ) );
		
		$this->assertEquals( 'some user', $commit_repository->create( 'username' ), 'The user created is not right' );
		$this->assertEquals( 'some user', $commit_repository->create( 'username' ), 'The user created is not right' );
	}
}