<?php

session_start();

if (isset($_GET['vid']))

{
	$_SESSION['vid'] = $_GET['vid'];
}

ini_set('display_errors',1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/settings/aa-settings.inc.php';


$time = time();

\Codebird\Codebird::setConsumerKey(TWITTERKEY, TWITTERSECRET); // static, see README

$cb = \Codebird\Codebird::getInstance();

if (! isset($_SESSION['oauth_token'])) {
  // get the request token
  $reply = $cb->oauth_requestToken([
    'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
  ]);

  // store the token
  $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
  $_SESSION['oauth_token'] = $reply->oauth_token;
  $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
  $_SESSION['oauth_verify'] = true;

  // redirect to auth website
  $auth_url = $cb->oauth_authorize();
  header('Location: ' . $auth_url);

  die();

} elseif (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
  // verify the token
  $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
  unset($_SESSION['oauth_verify']);

  // get the access token
  $reply = $cb->oauth_accessToken([
    'oauth_verifier' => $_GET['oauth_verifier']
  ]);

  // store the token (which is different from the request token!)
  $_SESSION['oauth_token'] = $reply->oauth_token;
  $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

  // send to same URL, without oauth GET parameters
  header('Location: ' . basename(__FILE__));

  die();
} 

// assign access token on each page load
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);


@$vid = $_GET['vid'] ? $_GET['vid'] : $_SESSION['vid'];

$video = 'export/1467292065-output.mp4';
$poster = 'export/1467292065cover.jpg';

if (file_exists('export/' . $vid . '-output.mp4') && file_exists('export/' . $vid . 'cover.jpg'))
{
	$video = 'export/' . $vid . '-output.mp4';
	$poster = 'export/' . $vid . 'cover.jpg';
}

?><!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Brutal Cuts cat inserter</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/fancybox/jquery.fancybox.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

</head>
<body>
	
	<div style="width: 640px;">
	
	<div align="center" class="embed-responsive embed-responsive-16by9">
         <video id="brutalCut" poster="<?php echo $poster; ?>" controls class="embed-responsive-item">
	         <source src="<?php echo $video; ?>" type="video/mp4">Your browser does not support the video tag.</source>

	     </video>
    </div>
    
    </div>
		
	
	<form id="tweeter" class="sendForm" method="post" name="tweeterform">
		
		<p>
		<textarea rows="12" name="tweetText" id="tweetText" style="width: 100%; height: 200px;">I'm sharing this video</textarea>
		</p>
		
		<input type="hidden" name="vid" id="vid" value="<?php echo $vid; ?>" />
		
		<p>
		<input type="button" class="recording-button twitter-button" id="tweetVideo" value="Tweet this"/>
		</p>
		
		<div class="sending" id="tweetSending">
				<div id="sendspinner">
					<div class="spinner">
					  <div class="double-bounce1"></div>
					  <div class="double-bounce2"></div>
					</div>
				</div>
				
		</div>
		
	</form>
	
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/modernizr-custom.min.js"></script>
    
    <script src="js/js-fileupload/vendor/jquery.ui.widget.js"></script>
	<script src="js/js-fileupload/jquery.iframe-transport.js"></script>
<script src="js/js-fileupload/jquery.fileupload.js"></script>
    <script src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script src="js/webrtc/adapter.js"></script>
  <script src="js-source/brutalcuts.js"></script
	
</body>
</html>