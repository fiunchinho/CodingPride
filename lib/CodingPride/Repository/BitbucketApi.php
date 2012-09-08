<?php
namespace CodingPride\Repository;

class BitbucketApi extends AbstractApi
{
	const CONVERTER_CLASS_NAME 	= '\CodingPride\Repository\BitbucketConverter';
	const API_RATE_LIMIT		= 1000;
	const NUMBER_OF_API_CALLS_NEEDED_TO_GET_COMMIT_LIST = 1;

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

		//var_dump($array);
		if ( count( $array ) > 0 )
		{
			return new \ArrayIterator( array_reverse( $array['changesets'] ) );
		}

		return new \ArrayIterator();
	}

	protected function getCommitDetailsApiResponse( $sha )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $sha ) );
		$request->setAuth( $this->config['login'], $this->config['password'] );
		$response 				= $request->send();

		return $response->getBody( true );
	}

	protected function getParamsForApiPagination( $latest_commits )
	{
		$params = array();
		$number_of_commits = count( $latest_commits );

		if ( $number_of_commits > 0 )
        {
            $oldest_commit = $latest_commits[$number_of_commits - 1];
			$params = array( 'start' => $oldest_commit->getRevision() );
		}

		return $params;
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
