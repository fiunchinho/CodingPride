<?php
namespace CodingPride;

class Http
{

	public function __construct()
	{
		$this->context = stream_context_create();
	}

	public function get( $url )
	{
		return file_get_contents( $url, false, $this->context );
	}

	public function setContext( $headers )
	{
		$header  = implode( ',', $headers );
		$options = array(
			'http' => array(
		    	'method' => 'GET',
		    	'header' => $header
		  	)
		);

		$this->context = stream_context_create( $options );
	}
}