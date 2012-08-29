<?php
namespace CodingPride\Source;

class FisheyeApiWrapper extends AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME = '\CodingPride\Source\FisheyeApiToCommitConverter';

	protected function getCommitRevisionIterator()
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl() );
		$array = json_decode( $api_response_as_string, true );
		return new \ArrayIterator( $array['csid'] );
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
		return $this->config['host'] . '/rest-service-fe/revisionData-v1/changesetList/' . $this->config['repository'] . '.json' ;
	}

	protected function getCommitDetailsUrl( $revision )
	{
		return $this->config['host'] . '/rest-service-fe/revisionData-v1/changeset/' . $this->config['repository'] . '/' . $revision . '.json' ;
	}
}