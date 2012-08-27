<?php
namespace CodingPride\Source;

class FisheyeApiWrapper extends AbstractApiWrapper
{
	/*
	public function __construct( $url )
	{
		$this->url = $url;
		$this->setContext();
	}
	*/

	/*
	public function setContext()
	{
		$options = array(
		  'http'=>array(
		    'method' => 'GET',
		    'header' => 'Accept: application/json, Accept-Charset: UTF-8, Authorization: Basic am9zZW1hbnVlbC5hcm1lc3RvOldDM200bjRnM3Ih'
		  )
		);

		$this->context = stream_context_create( $options );
	}
	*/

	/**
	 * @returns string The API response for a commit.
	 */
	/*
	protected function getCommitDetailsFromApi( $revision )
	{
		return file_get_contents( $this->url . '/rest-service-fe/revisionData-v1/changeset/Core3/' . $revision, false, $this->context );
	}
	*/
	/**
	 * @returns string The API response for the list of commits.
	 */
	/*
	protected function getCommitListFromApi( \Datetime $end, \Datetime $start = null, $max = 10, $path = '/' )
	{
		if ( empty( $start ) )
		{
			$start = new \DateTime( '2007-03-15');
		}

		return file_get_contents( $this->url . '/rest-service-fe/revisionData-v1/changesetList/Core3?path=' . $path . '&start=' . $start->format( 'Y-m-d' ) . '&end=' . $end->format( 'Y-m-d' ) . '&maxReturn=' . $max, false, $this->context );
	}
	*/

	/**
	 * Transform the API response with the commits ( a stringify XML ), into a CommitList value object.
	 *
	 * @param string $data The API response with the list of commits.
	 * @return \CodingPride\CommitList The list of commits as a value object.
	 *
	 */
	/*
	protected function transformToCollection( $data )
	{
		$xml 			= new \SimpleXMLElement( $data );
		$commit_list 	= new CommitList();

		foreach( $xml as $revision )
		{
			$commit_details_as_string = $this->getCommitListFromApi( (string) $revision );
			$commit_list->addCommit( $commit_details_as_string );
			//$commit_list[] = $this->getCommitDetails( (string) $xml_commit );
		}
		
		return $commit_list;
	}
	*/
	/*
	public function getLatestCommits( $path = '/', $max = 1000 )
	{
		$api_response_as_string = file_get_contents( $this->url . '/rest-service-fe/revisionData-v1/changesetList/Core3?path=' . $path . '&maxReturn=' . $max, false, $this->context );
		$api_response_json		= json_decode( $api_response_as_string, true );
		$comment_collection		= new CommitList();

		foreach ( $api_response_json as $commit_info )
		{
			$comment_collection[] = $this->_dm->getRepository( '\CodingPride\Document\CommitRepository' )->create( $commit_info );
		}
		
		return $comment_collection;
	}
	*/

	public function getLatestCommits( $path = '/', $max = 1000 )
	{
		$api_response_as_string = $this->http->get( $this->getCommitListUrl() );
		$api_response_array		= json_decode( $api_response_as_string, true );
		$commit_collection		= new \CodingPride\CommitList();

		foreach ( $api_response_array['csid'] as $key => $revision )
		{
			$commit_details 		= $this->http->get( $this->getCommitDetailsUrl() . $revision . '.json'  );
			$commit_collection[] 	= $this->_dm->getRepository( 'CodingPride\Document\Commit' )->create( json_decode( $commit_details, true ), $this->getConverter() );
		}

		$commit_collection->reverseSort();

		return $commit_collection;
	}

	protected function getConverter()
	{
		return new FisheyeApiToCommitConverter();
	}

	protected function setUpHttp()
	{
		$this->http->setContext(
			array(
				//'Accept: application/json',
				'Authorization: Basic ' . base64_encode( $this->config['login'] . ':' . $this->config['password'] )
			)
		);
		return $this->http;
	}

	protected function getCommitListUrl()
	{
		return $this->config['host'] . '/rest-service-fe/revisionData-v1/changesetList/' . $this->config['repository'] . '.json' ;
	}

	protected function getCommitDetailsUrl()
	{
		return $this->config['host'] . '/rest-service-fe/revisionData-v1/changeset/' . $this->config['repository'] . '/' ;
	}
}