<?php
namespace CodingPride\Source;

class BitbucketApiWrapper extends AbstractApiWrapper
{

	public function getLatestCommits( $path = '/', $max = 1000 )
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl() );
		$api_response_array		= json_decode( $api_response_as_string, true );
		
		$commit_collection		= new \CodingPride\CommitList();
		$commit_repository		= $this->_dm->getRepository( 'CodingPride\Document\Commit' );

		foreach ( $api_response_array['changesets'] as $commit_info )
		{
			$commit_details 		= $this->http->get( $this->getCommitDetailsUrl() . $commit_info['raw_node'] );
			$commit 				= $commit_repository->create(
				json_decode( $commit_details, true ),
				$this->getConverter()
			);
			if ( $commit )
			{
				$commit_collection[] = $commit;	
			}
		}

		return $commit_collection;
	}

	protected function getConverter()
	{
		return new BitbucketApiToCommitConverter();
	}

	protected function setUpHttp()
	{
		//$http = new \CodingPride\Http();
		$this->http->setContext(
			array(
				'Authorization: Basic Zml1bmNoaW5obzpXQzNtNG40ZzNyIQ=='
			)
		);
		return $this->http;
	}

	protected function getCommitListUrl()
	{
		return 'https://api.bitbucket.org/1.0/repositories/' . $this->config['username'] . '/' . $this->config['repository'] . '/changesets';
	}

	protected function getCommitDetailsUrl()
	{
		return 'https://api.bitbucket.org/1.0/repositories/' . $this->config['username'] . '/' . $this->config['repository'] . '/changesets/';
	}

}
