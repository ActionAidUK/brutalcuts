<?php

session_start();

if (isset($_GET['vid']))

{
	$_SESSION['vid'] = $_GET['vid'];
}

ini_set('display_errors',1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/settings/facebook.php';	


$time = time();


@$vid = $_GET['vid'] ? $_GET['vid'] : $_SESSION['vid'];



$helper = $fb->getRedirectLoginHelper();



if (isset($_SESSION['facebook_access_token']))
{
	try {
		
		$fb->get('/me',$_SESSION['facebook_access_token']);
		
	} catch( Exception $e ){
		
		session_destroy();
		session_start();

		$_SESSION['vid'] = $vid;
		
		$permissions = ['publish_actions']; // optional
		$loginUrl = $helper->getLoginUrl('https://' . $_SERVER['HTTP_HOST'] . '/facebooklogin-callback.php', $permissions);
		header( 'Location: ' . $loginUrl );  
	
	}
} else {
	
	session_destroy();
		session_start();

		$_SESSION['vid'] = $vid;
	$permissions = ['publish_actions']; // optional
		
	$loginUrl = $helper->getLoginUrl('https://' . $_SERVER['HTTP_HOST'] . '/facebooklogin-callback.php', $permissions);
	header( 'Location: ' . $loginUrl ); 
		
}

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

   <script>
	  
	 
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1199685040062462',
      cookie: true, // This is important, it's not enabled by default      
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>



</head>
<body>
	
	<div style="width: 640px;">
	
	<div align="center" class="embed-responsive embed-responsive-16by9">
         <video id="brutalCut" poster="<?php echo $poster; ?>" controls class="embed-responsive-item">
	         <source src="<?php echo $video; ?>" type="video/mp4">Your browser does not support the video tag.</source>

	     </video>
    </div>
    
    </div>
		
	
	<form id="facebooker" class="sendForm" method="post" name="facebooker">
		
		<p>
		<textarea rows="12" name="facebookText" id="facebookText" style="width: 100%; height: 200px;">My message to facebook</textarea>
		</p>
		
		<input type="hidden" name="vid" id="vid" value="<?php echo $vid; ?>" />
		
		<p>
		<input type="button" class="recording-button twitter-button" id="fbVideo" value="Share to facebook"/>
		</p>
		
		<div class="sending" id="facebookSending">
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