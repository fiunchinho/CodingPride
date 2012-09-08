<?php
namespace CodingPride\Repository;

abstract class AbstractApi extends Repository
{
	protected $http;

	abstract protected function getBaseUrl();
	abstract protected function getCommitRevisionIterator( $params );
	abstract protected function getCommitDetailsApiResponse( $sha );
	abstract protected function getParamsForApiPagination( $oldest_commit );

	public function __construct( $database, $config, $http )
	{
		$this->_dm 					= $database;
		$this->config 				= $config;
		$this->http 				= $http;
		$this->http->setBaseUrl( $this->getBaseUrl() );
	}

	protected function getCommitList( $params )
	{
		$commit_collection		= new \CodingPride\CommitList();
		$commit_repository		= $this->_dm->getRepository( 'CodingPride\Document\Commit' );

		$converter = $this->getConverter();
		$revisions = $this->getCommitRevisionIterator( $params );

		foreach ( $revisions as $revision )
		{
			$api_response 	= $this->getCommitDetailsApiResponse( $converter->getRevision( $revision ) );
			$commit 		= $converter->convert( $api_response );
			$is_commit_new	= $commit_repository->create( $commit );

			if ( $is_commit_new )
			{
				$commit_collection[] = $commit;	
			}
		}

		return $commit_collection;
	}

	public function getCommits()
	{
		$params = array();
		$commits = new \CodingPride\CommitList();
		$rate_limit = static::API_RATE_LIMIT;

		do
        {
            $latest_commits     = $this->getCommitList( $params );
            $params             = $this->getParamsForApiPagination( $latest_commits );

            $number_of_commits  = count( $latest_commits );
            $rate_limit         = $rate_limit - $number_of_commits - static::NUMBER_OF_API_CALLS_NEEDED_TO_GET_COMMIT_LIST;

            $commits->combine( $latest_commits );

	        if ( $rate_limit <= 0 )
	        {
	        	throw new ApiUsageLimitReachedException( "Error: API Limit Exceeded" );
	        }
        }
        while( $number_of_commits > 0 ) ;

        return $commits;
	}
}

class ApiUsageLimitReachedException extends \Exception{}