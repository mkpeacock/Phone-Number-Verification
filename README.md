# Telephone Number Verification Tool using PHP 5 and Twilio

This tool allows you to verify a users telephone number by presenting them with a 4 digit number on the screen, and then phoning them on the number they provided, asking them to confirm the digits on the screen.  The tool uses Twilio to make the call and process the users interaction.

## Usage

Download and extract the twilio PHP library into the libraries/twilio folder. https://github.com/twilio/twilio-php.

### Making the call

	require_once( 'PhoneVerification.class.php' );
	require_once( 'UserAdapter.class.php' );
	
	// You should update the adapter code to work with your framework/application
	$user = new UserAdapter( $myApplicationsUserObject );
	
	// where the URL is the URL to the script which processes the call
	$phoneVerification = new PhoneVerification( 'http://yourdomain/usage_processcall.php', $user, 'TwilioSID', 'TwilioToken' );
	
	// pass 4 digit code, Caller ID (twilio app phone number), users country code, users phone number
	$phoneVerification->callUser( '1234', '44111111111', '44', '12345678' );
	
### Handling the call

	require_once( 'PhoneVerification.class.php' );
	require_once( 'UserAdapter.class.php' );
	
	$user = new UserAdapter( $myApplicationsUserObject );
	$phoneVerification = new PhoneVerification( 'http://yourdomain/usage_processcall.php', $user, 'TwilioSID', 'TwilioToken' );
	$phoneVerification->handleCall();
	exit();
	
### Informing the user

Depending on your framework/application, the recommended approach would be:
	
1. Generate a 4 digit code, and call the user
2. Display a message on screen informing the user that they are being called
3. Use a JavaScript timer to check every 5 seconds to see if the users status has been updated to verified
3.1. If the users status has been updated, redirect the user to a page confirming this
3.2  If more than 70 seconds has passed, redirect the user to a page indicating the verification failed, with the option to try again