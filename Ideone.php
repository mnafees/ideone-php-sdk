<?php

require_once 'Config.php';

class Ideone 
{

	// The SOAP client
	private $client;

	/**
	 * @param String the username
	 * @param String the password
	 */
	private $username;
	private $password;

	private $link;

	function __construct() 
	{
		$this->username = Config::IDEONE_USER;
		$this->password = Config::IDEONE_PASS;

		$this->client = new SoapClient( Config::IDEONE_SOAP_URL );
	}



	/**
	 * creates a new paste
	 * @param $sourceCode(String) - the source code of the program
	 * @param $language(String) - name of the programming language
	 * @param $input(String) - the desired input
	 * @param $run(bool) - to the program or not
	 * @param $private(bool) - is a private paste or not
	 * @return an array of error and paste identifier
	 */
	public function createSubmission( $sourceCode, $language, $run = false, $input = "", $private = false ) 
	{
		$result = $this->client->createSubmission( 
			$this->username, 
			$this->password,
            $sourceCode,
            $this->languageToInt( $language ),
			$input,
			$run,
			$private 
		);
		
		if( $result['error'] == 'OK' )
		{
			$link = $result['link'];
		}

		return $result;
	}

	/**
	 * returns the link identifier of the current paste
	 */
	public function getCurrentLink() {
		return $link;
	}

	/**
	 * @param $link(String) - the link identifier
	 * @return an array of error, status and result
	 */
	public function getSubmissionStatus() {
		$result = $this->client->getSubmissionStatus( 
			array( 
				$this->username,
				$this->password,
				$this->link 
			) 
		);

        return $result;
	}

	/**
	 * @param $link(String) - the link identifier
	 * @param $withSource(bool) - determines whether to return the source code
	 * @param $withInput(bool) - determines whether to take input
	 * @param $withOutput(bool) - determines whether to show output
	 * @param $withStderr(bool) - determines whethere to return stderr
	 * @param $withCmpinfo(bool) - determines ¬† whether ¬† compilation information¬†should¬†be¬†returned 
	 * @return an array of many details
	 */
	public function getSubmissionDetails( $link, $withSource = false, $withInput = false, $withOutput = false,
	                                      $withStderr = false, $withCmpinfo = false ) {
		$result = $this->client->getSubmissionDetails( array( $this->username,
								      $this->password,
								      $link,
								      $withSource,
								      $withInput,
								      $withStderr,
								      $withCmpinfo ) );

		return $result;
	}


	private function getLanguages() 
	{
		$result = $this->client->getLanguages( $this->username, $this->password );
		
		return $result;
	}

	private function languageToInt( $language ) 
	{
		$languages = $this->getLanguages();

		return preg_grep( $language , $languages );
	}

}
