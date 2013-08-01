<?php

/* Copyright 2013 Mohammed Nafees. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY MOHAMMED NAFEES ''AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO
 * EVENT SHALL EELI REILIN OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA,
 * OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation
 * are those of the authors and should not be interpreted as representing
 * official policies, either expressed or implied, of Mohammed Nafees.
 */

class Ideone {
	// The SOAP client
	private $client;

	/**
	 * @param String the username
	 * @param String the password
	 */
	private $username;
	private $password;

	private $link;

	function __construct( $username, $password ) {
		$this->username = $username;
		$this->password = $password;

		$this->client = new SoapClient( "http://ideone.com/api/1/service.wsdl" );
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
	public function createSubmission( $sourceCode, $language, $input = "", $run = false, $private = false ) {
		$result = $this->client->createSubmission( $this->username,
							   $this->password,
                                                           $sourceCode,
                                                           $this->languageToInt( $language ),
							   $input,
							   $run,
							   $private );

		if ( $result['error'] == 'OK' ) {
			$this->link = $array['link'];
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
	public function getSubmissionStatus( $link ) {
		$result = $this->client->getSubmissionStatus( array( $this->username,
								     $this->password,
								     $link ) );

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

	/**
	 * @return a list of supported programming languages
	 */
	public function getLanguages() {
		$result = $this->client->getLanguages( $this->username, $this->password );

		return $result;
	}

	public function languageToInt( $language ) {
		$array = $this->getLanguages();
                $values = array_values( $array['languages'] );
                $keys = array_keys( $array['languages'] );
		for ( $i = 0; $i < sizeof( $array['languages'] ); ++$i ) {
                    if( preg_match( "/\b" . $language . "\b/i", $values[$i] ) ) {
                        return $keys[ $i ];
                        exit();
                    }
                }
	}

}

// @binary :)

?>
