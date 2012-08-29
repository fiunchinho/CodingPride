<?php
namespace CodingPride\Source;

class GithubApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME = '\CodingPride\Source\GithubApiToCommitConverter';
	
	protected function getCommitRevisionIterator()
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl() );
		$array = json_decode( $api_response_as_string, true );
		return new \ArrayIterator( $array );
	}

	protected function setUpHttp()
	{
		return true;
	}

	protected function getCommitListUrl()
	{
		return 'https://api.github.com/repos/' . $this->config['username'] . '/' . $this->config['repository'] . '/commits';
	}

	protected function getCommitDetailsUrl( $sha )
	{
		return 'https://api.github.com/repos/' . $this->config['username'] . '/' . $this->config['repository'] . '/commits/' . $sha;
	}

}
