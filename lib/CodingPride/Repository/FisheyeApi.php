<?php
namespace CodingPride\Repository;

class FisheyeApi extends AbstractApi
{
	const CONVERTER_CLASS_NAME 	= '\CodingPride\Repository\FisheyeConverter';
	const API_RATE_LIMIT		= 1000;
	const NUMBER_OF_API_CALLS_NEEDED_TO_GET_COMMIT_LIST = 1;

	protected function getBaseUrl()
	{
		return $this->config['host'] . '/rest-service-fe/revisionData-v1/';
	}

	protected function getCommitRevisionIterator( $params )
	{
		$request				= $this->http->get( $this->getCommitListUrl( $params ) );
		$request->setAuth( $this->config['login'], $this->config['password'] );
		$response 				= $request->send();
		$array 					= json_decode( $response->getBody( true ), true );

		if ( count( $array ) > 0 )
		{
			return new \ArrayIterator( $array['csid'] );	
		}
		
		return new \ArrayIterator();
	}

	protected function getCommitDetailsApiResponse( $revision )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $revision ) );
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
			$params = array( 'end' => $oldest_commit->getDate()->format( 'Y-m-d' ) );
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

		return 'changesetList/' . $this->config['repository'] . '.json' . $query_string;
	}

	protected function getCommitDetailsUrl( $revision )
	{
		return 'changeset/' . $this->config['repository'] . '/' . $revision . '.json' ;
	}
}