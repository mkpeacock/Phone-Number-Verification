<?php

require_once( 'libraries/twilio/Twilio.php');
require_once( 'CallHandler.class.php');
require_once( 'UserAdapter.class.php');

/**
 * Telephone number verification system using Twilio
 * @author Michael Peacock <mkpeacock@gmail.com>
 * @copyright Michael Peacock www.michaelpeacock.co.uk
 * @version 0.9
 */
class PhoneVerification {
	
	/**
	 * Twilio Client object
	 * @var Services_Twilio
	 */
	private $twilioClient;
	
	/**
	 * the URL which calls $phoneVerification->makeCall();
	 * @var String
	 */
	private $verificationURL;
	
	/**
	 * Phone verification
	 * @param String $verificationURL the URL which calls $phoneVerification->makeCall();
	 * @param UserAdapter $user 
	 * @param String $sid
	 * @param String $token
	 */
	public function __construct( $verificationURL, $user, $sid, $token, $verifySSL=true )
	{
		$this->verificationURL = $verificationURL;
		if( ! $verifySSL )
		{
			$http = new Services_Twilio_TinyHttp('https://api.twilio.com', array('curlopts' => array(
		   		CURLOPT_SSL_VERIFYPEER => false
			)));
			$this->twilioClient = new Services_Twilio( $sid, $token, '2010-04-01', $http );
		}
		else
		{
			$this->twilioClient = new Services_Twilio( $sid, $token );
		}
		
	}
	
	/**
	 * Handle the outgoing verification call, and any inputs the user enters
	 * @return void
	 */
	public function handleCall()
	{
		$verification->setUser( $this->user )->setURL( $this->verificationURL )->processCall( ( isset( $_GET['action'] ) && $_GET['action'] != '' ) ? $_GET['action'] : '', ( isset( $_GET['callbackCode'] ) &&  $_GET['callbackCode'] != '' ) ?  $_GET['callbackCode'] : '' );
	}
	
	/**
	 * Make the outgoing call to verify the phone number
	 * @param String $code 4 digit code user enters to verify their number (string, because can start with 0)
	 * @param int $callerID
	 * @param int $countryCode country code of the number to dial
	 * @param int $dial the telephone number to dial
	 * @return void
	 */
	public function makeCall( $code, $callerID, $countryCode, $dial )
	{
		$handler = new CallHandler( $this->twilioClient );
		$handler->setUser( $this->user )->setCode( $code )->setURL( $this->verificationURL )->setCaller( $callerID )->callUser( $countryCode, $dial );	
	}
	
	
	
}



?>