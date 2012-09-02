<?php
namespace CodingPride\Source;

class FisheyeApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME 	= '\CodingPride\Source\FisheyeApiToCommitConverter';
	const API_RATE_LIMIT		= 1000;

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

		return new \ArrayIterator( $array['csid'] );
	}

	protected function getCommitDetailsApiResponse( $revision )
	{
		$request				= $this->http->get( $this->getCommitDetailsUrl( $revision ) );
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

		return 'changesetList/' . $this->config['repository'] . '.json' . $query_string;
	}

	protected function getCommitDetailsUrl( $revision )
	{
		return 'changeset/' . $this->config['repository'] . '/' . $revision . '.json' ;
	}
}