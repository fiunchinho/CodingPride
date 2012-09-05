<?php
namespace CodingPride\Repository;

class BitbucketApi extends AbstractApi
{
	const CONVERTER_CLASS_NAME 	= '\CodingPride\Repository\BitbucketConverter';
	const API_RATE_LIMIT		= 150;

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

		return new \ArrayIterator( array_reverse( $array['changesets'] ) );
	}

	protected function getCommitDetailsApiResponse( $sha )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $sha ) );
		$request->setAuth( $this->config['login'], $this->config['password'] );
		$response 				= $request->send();

		return $response->getBody( true );
	}

	protected function getParamsForApiPagination( $last_commit )
	{
		return array( 'start' => $last_commit->getRevision() );
	}

	protected function getCommitListUrl( $params )
	{
		$query_string = '?limit=50&';
		foreach ( $params as $param => $value )
		{
			$query_string .= $param . '=' . $value;
		}

		return $this->config['username'] . '/' . $this->config['repository'] . '/changesets/' . $query_string;
	}

	protected function getCommitDetailsUrl( $sha )
	{
		return $this->config['username'] . '/' . $this->config['repository'] . '/changesets/' . $sha;
	}

}
