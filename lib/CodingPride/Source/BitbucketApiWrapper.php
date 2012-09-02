<?php
namespace CodingPride\Source;

class BitbucketApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME 	= '\CodingPride\Source\BitbucketApiToCommitConverter';
	const API_RATE_LIMIT		= 1000;

	protected function getBaseUrl()
	{
		return 'https://api.bitbucket.org/1.0/repositories/';
	}

	protected function getCommitRevisionIterator( $params )
	{
		$request				= $this->http->get( $this->getCommitListUrl( $params ) );
		$request->setAuth( $this->config['login'], $this->config['password'] );
		$response 				= $request->send();
		$array 					= json_decode( $response->getBody( true ), true );

		return new \ArrayIterator( $array['changesets'] );
	}

	protected function getCommitDetailsApiResponse( $sha )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $sha ) );
		$request->setAuth( $this->config['login'], $this->config['password'] );
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

		return $this->config['username'] . '/' . $this->config['repository'] . '/changesets' . $query_string;
	}

	protected function getCommitDetailsUrl( $sha )
	{
		return $this->config['username'] . '/' . $this->config['repository'] . '/changesets/' . $sha;
	}

}
