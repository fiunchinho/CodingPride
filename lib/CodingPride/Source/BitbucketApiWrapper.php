<?php
namespace CodingPride\Source;

class BitbucketApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME = '\CodingPride\Source\BitbucketApiToCommitConverter';

	protected function getCommitRevisionIterator()
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl() );
		$array = json_decode( $api_response_as_string, true );
		return new \ArrayIterator( $array['changesets'] );
	}

	protected function setUpHttp()
	{
		$this->http->setContext(
			array(
				'Authorization: Basic ' . base64_encode( $this->config['login'] . ':' . $this->config['password'] )
			)
		);
		return $this->http;
	}

	protected function getCommitListUrl()
	{
		return 'https://api.bitbucket.org/1.0/repositories/' . $this->config['username'] . '/' . $this->config['repository'] . '/changesets';
	}

	protected function getCommitDetailsUrl( $sha )
	{
		return 'https://api.bitbucket.org/1.0/repositories/' . $this->config['username'] . '/' . $this->config['repository'] . '/changesets/' . $sha;
	}

}
