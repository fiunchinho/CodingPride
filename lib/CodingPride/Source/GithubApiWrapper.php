<?php
namespace CodingPride\Source;

class GithubApiWrapper extends AbstractApiWrapper
{
	public function getLatestCommits( $path = '/', $max = 1000 )
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl() );
		$api_response_array		= json_decode( $api_response_as_string, true );
		
		$commit_collection		= new \CodingPride\CommitList();
		$commit_repository		= $this->_dm->getRepository( 'CodingPride\Document\Commit' );

		foreach ( $api_response_array as $commit_info )
		{
			$commit_details 		= $this->http->get( $this->getCommitDetailsUrl() . $commit_info['sha'] );
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
		return new GithubApiToCommitConverter();
	}

	protected function setUpHttp()
	{
		return true;
	}

	protected function getCommitListUrl()
	{
		return 'https://api.github.com/repos/' . $this->config['username'] . '/' . $this->config['repository'] . '/commits';
	}

	protected function getCommitDetailsUrl()
	{
		return 'https://api.github.com/repos/' . $this->config['username'] . '/' . $this->config['repository'] . '/commits/';
	}

}
