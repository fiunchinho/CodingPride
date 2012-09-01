<?php
namespace CodingPride\Source;

class GithubApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME  = '\CodingPride\Source\GithubApiToCommitConverter';
	const API_RATE_LIMIT		= 1000;
	
	protected function getCommitRevisionIterator( $params )
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl( $params ) );
		$array = json_decode( $api_response_as_string, true );
		return new \ArrayIterator( $array );
	}

	protected function setUpHttp()
	{
		return true;
	}

	protected function getCommitListUrl( $params )
	{
		$query_string = '?';
		foreach ( $params as $param => $value )
		{
			$query_string .= $param . '=' . $value;
		}

		return 'https://api.github.com/repos/' . $this->config['username'] . '/' . $this->config['repository'] . '/commits' . $query_string;
	}

	protected function getCommitDetailsUrl( $sha )
	{
		return 'https://api.github.com/repos/' . $this->config['username'] . '/' . $this->config['repository'] . '/commits/' . $sha;
	}

}