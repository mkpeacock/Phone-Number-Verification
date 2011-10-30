<?php
/**
 * UserAdapter Class
 * @author Michael Peacock <mkpeacock@gmail.com>
 */
class UserAdapter{
	
	/**
	 * User object as it relates to your framework/CMS/use case
	 * @var Object
	 */
	private $user;
	
	/**
	 * Constructor
	 * @param Object $user
	 * @return void
	 */
	public function __construct( $user=null )
	{
		$this->user = $user;
	}
	
	/**
	 * Get the user ID from the user object
	 * @return int
	 */
	public function getUserID()
	{
		return $this->user->getUserID();
	}
	
	/**
	 * Get a users verification code
	 * @return String
	 */
	public function getVerificationCode()
	{
		return $this->user->getVerificationCode();
	}
	
	/**
	 * Set a users verification code
	 * @param String $code
	 * @return void
	 */
	public function setVerificationCode( $code )
	{
		$this->user->setVerificationCode( $code );
		$this->user->save();
	}	
	
	/**
	 * Verify the user based on the code entered
	 * @param String $code
	 * @return bool
	 */
	public function verify( $code )
	{
		if( $this->getVerificationCode() == $code )
		{
			$this->user->setVerified( true );
			$this->user->save();
			return true;
		}
		else
		{
			return false;
		}
	}
	
}


?>