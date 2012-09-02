<?php
namespace CodingPride\Source;

class GithubApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME  = '\CodingPride\Source\GithubApiToCommitConverter';
	const API_RATE_LIMIT		= 1000;
	
	public function getBaseUrl()
	{
		return 'https://api.github.com/repos/';
	}

	protected function getCommitRevisionIterator( $params )
	{
		$request 				= $this->http->get( $this->getCommitListUrl( $params ) );
		$response 				= $request->send();
		$array 					= json_decode( $response->getBody( true ), true );
		return new \ArrayIterator( $array );
	}

	protected function getCommitDetailsApiResponse( $sha )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $sha ) );
		$response 				= $request->send();
		
		return $response->getBody( true );
	}

	protected function getCommitListUrl( $params )
	{
		$query_string = '?';
		foreach ( $params as $param => $value )
		{
			$query_string .= $param . '=' . $value;
		}

		return $this->config['username'] . '/' . $this->config['repository'] . '/commits' . $query_string;
	}

	protected function getCommitDetailsUrl( $sha )
	{
		return $this->config['username'] . '/' . $this->config['repository'] . '/commits/' . $sha;
	}

}