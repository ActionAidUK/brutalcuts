<?php
	
ini_set('display_errors',1);
error_reporting(E_ALL);	

if(!session_id()) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';	
require __DIR__ . '/settings/facebook.php';	


	
	
$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  
  header( 'Location: https://www.actionaid.org.uk/get-involved/fgm-brutal-cut'); 
  exit;
  
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  header( 'Location: https://www.actionaid.org.uk/get-involved/fgm-brutal-cut'); 
  exit;
}

if (isset($accessToken)) {
  // Logged in!
  $_SESSION['facebook_access_token'] = (string) $accessToken;

  // Now you can redirect to another page and use the
  // access token from $_SESSION['facebook_access_token']
  
  header( 'Location: /facebook.php' );
  
} else {
	
	header( 'Location: /facebook.php' );
	
}
	
	
	
