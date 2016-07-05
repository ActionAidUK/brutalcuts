<?php
	
ini_set('display_errors',1);
error_reporting(E_ALL);	

if(!session_id()) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';	
require __DIR__ . '/includes/facebook.php';	

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

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">
  
  <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1199685040062462',
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
<div class="container">
    <div class="row">
      <div class=" column" style="margin-top: 30px">
<img src="images/ActionAid---Brutal-Cuts.png" class="img-responsive" />




<?php
	
	
	
	
	
	
	
# login.php

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('https://actionaidhosting.org/facebookprofile-callback.php', $permissions);

echo '<p><a href="' . $loginUrl . '">Log in with Facebook!</a></p>';



	
?>
<pre>
	<?php
		
//		print_r($graphObject);
		?>
</pre>
  </div>
    </div>
  </div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/modernizr-custom.min.js"></script>
    
    <script src="js/js-fileupload/vendor/jquery.ui.widget.js"></script>
	<script src="js/js-fileupload/jquery.iframe-transport.js"></script>
<script src="js/js-fileupload/jquery.fileupload.js"></script>
    
	<script src="js/webrtc/adapter.js"></script>
  <script src="js-source/brutalcuts.js"></script>
  
</body>
</html>
