<?php
/**
 * User telephone number verification
 * @author Michael Peacock <mkpeacock@gmail.com>
 * @copyright Michael Peacock www.michaelpeacock.co.uk
 */
class CallHandler{
	
	/**
	 * Callback code - secret key used to verify the requests are coming from twilio
	 * @var String
	 */
	private $callbackCode = 'SECRET-YOU-DEFINE-HERE';
	
	/**
	 * Name of our site
	 */
	private $siteName = 'ACME';
	
	/**
	 * Our user adapter
	 * @var UserAdapter
	 */
	private $user;
	
	/**
	 * Twilio 
	 * @var Service_Twilio
	 */
	private $twilio;
	
	/**
	 * Caller ID
	 * @var int
	 */
	private $caller;
	
	/**
	 * Verification site URL
	 * @var String
	 */
	private $url;
	
	/**
	 * Constructor
	 * @param Services_Twilio $twilio
	 * @param UserAdapter $user
	 * @param String $code
	 * @return void
	 */
	public function __construct( Services_Twilio $twilio )
	{
		$this->twilio = $twilio;		
	}
	
	public function setUser( UserAdapter $user )
	{
		$this->user = $user;
		return $this;
	}
	
	public function setCode( $code=null )
	{
		$this->user->setVerificationCode( is_null( $code ) ? $this->generateRandomCode() : $code );
		return $this;
	}
	
	public function setCaller( $tn )
	{
		$this->caller = $tn;
		return $this;
	}
	
	public function setSiteName( $sn )
	{
		$this->siteName = $sn;
		return $this;
	}	
	
	public function setURL( $url )
	{
		$this->url = $url;
		return $this;
	}
	
	private function generateRandomCode()
	{
		return '1234';
	}

	
	public function callUser( $countryCode, $telephoneNumber )
	{
		$call = $this->twilio->account->calls->create(
			$this->caller,
		  	$countryCode . $telephoneNumber,
		  	$this->url . '?&callbackCode=' . $this->callbackCode . '&user=' . $this->user->getUserID()
		);
		return $this;
	}
	
	/**
	 * Process a call / phone verification
	 * @param String $action
	 * @return void
	 */
	public function processCall( $action='', $callbackCode='' )
	{
		$response = new Services_Twilio_Twiml();
		if( $callbackCode != $this->callbackCode )
		{
			// looks like someone is trying to trick us!
			$response->say('Sorry, we were not able to process your request');
			print $response;
		}
		else
		{
			switch( $action )
			{
				case 'repeat':
					$gather = $response->gather( array( 'numDigits' => 4, 'action' => $this->url . '?&action=verify_again&user=' . $this->user->getUserID() . '&callbackCode=' . $this->callbackCode ) );
					$gather->say('Sorry, the code you entered was incorrect, please try again.');   
					print $response;
					break;
				case 'verify':
					$code = $_REQUEST['Digits'];
					$this->user->verify( $code ) ? $this->processCall('verified', $this->callbackCode ) : $this->processCall('repeat', $this->callbackCode );
					break;
				case 'verify_again':
					$code = $_REQUEST['Digits'];
					$this->user->verify( $code ) ? $this->processCall('verified', $this->callbackCode ) : $this->processCall('sorry', $this->callbackCode );
					break;
				case 'verified':
					$response->say('Thank you, your phone number has been verified');
					print $response;
					break;
				case 'sorry':
					$response->say('Sorry, you have entered an incorrect code again, we have not been able to verify your phone number');
					print $response;
					break;
				default:
					$gather = $response->gather( array( 'numDigits' => 4, 'action' => $this->url . '?&action=verify&user=' . $this->user->getUserID() . '&callbackCode=' . $this->callbackCode ) );
					$gather->say('This is the ' . $this->siteName . ' telephone verification service.  To verify your telephone number, please enter the four digit code shown on your screen now.');   
					print $response;
					break;
			}
		}
		exit();
		
	}
	
	
	
	
	
}



?>