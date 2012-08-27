<?php
require __DIR__ . '/../../vendor/autoload.php';

class FileTest extends PHPUnit_Framework_TestCase
{
	public function testPathIsSettedCorrectly()
	{
		$path = '/var/www/index.html';
		$file = new \CodingPride\File( $path );
		$this->assertEquals( $file->getPath(), $path, 'The path is not correct' );
	}

	public function testGetExtensionFromRegularFile()
	{
		$path = '/var/www/index.html';
		$file = new \CodingPride\File( $path );
		$this->assertEquals( $file->getExtension(), 'html', 'The extension is not correct' );
	}

	public function testGetExtensionFromFolder()
	{
		$path = '/var/www/';
		$file = new \CodingPride\File( $path );
		$this->assertFalse( $file->getExtension(), 'The extension is not correct when the file is a folder.' );
	}

	public function testGetExtensionFromFileWithoutExtension()
	{
		$path = '/etc/fstab';
		$file = new \CodingPride\File( $path );
		$this->assertFalse( $file->getExtension(), 'The extension is not correct when the file has no extension.' );
	}
}