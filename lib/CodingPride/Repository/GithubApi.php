<?php
namespace CodingPride\Repository;

class GithubApi extends AbstractApi
{
	const CONVERTER_CLASS_NAME  = '\CodingPride\Repository\GithubConverter';
	const API_RATE_LIMIT		= 5000;
	const NUMBER_OF_API_CALLS_NEEDED_TO_GET_COMMIT_LIST = 1;
	
	public function getBaseUrl()
	{
		return 'https://api.github.com/repos/';
	}

	protected function getCommitRevisionIterator( $params )
	{
		$request 				= $this->http->get( $this->getCommitListUrl( $params ) );
		$response 				= $request->send();
		$array 					= json_decode( $response->getBody( true ), true );

		if ( count( $array ) > 0 )
		{
			return new \ArrayIterator( $array );	
		}

		return new \ArrayIterator();
	}

	protected function getCommitDetailsApiResponse( $sha )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $sha ) );
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
			$params = array( 'last_sha' => $oldest_commit->getRevision() );
		}

		return $params;
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