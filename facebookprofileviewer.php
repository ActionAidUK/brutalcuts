<?php
	
ini_set('display_errors',1);
error_reporting(E_ALL);	

if(!session_id()) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';	
require __DIR__ . '/settings/facebook.php';	

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


<div class="clear-both">


<?php


try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,picture.width(300)', $_SESSION['facebook_access_token']);
  
   $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();
	
?>
<div class="row">
      <div class="one-half column" style="margin-top: 30px">
	      
	      <img src="<?php echo $userNode->getPicture()->getURL(); ?>" />
	      
	      
      </div>
      
            <div class="one-half column" style="margin-top: 30px">
			
			
			<?php
				
			$time = time();
				
			// Create an array containing file paths, resource var (initialized with imagecreatefromXXX), 
			// image URLs or even binary code from image files.
			// All sorted in order to appear.
			$frames = array(
				$userNode->getPicture()->getURL(),
				'images/nyan-1.png',
				'images/nyan-2.png',
				'images/nyan-3.png',
				'images/nyan-4.png',
				'images/nyan-5.png',
				'images/nyan-6.png',
				'images/nyan-7.png',
				'images/nyan-8.png',
				'images/nyan-9.png',
				'images/nyan-10.png',
				'images/nyan-11.png',
				'images/nyan-12.png',
				'images/nyan-1.png',
				'images/nyan-2.png',
				'images/nyan-3.png',
				'images/nyan-4.png',
				'images/nyan-5.png',
				'images/nyan-6.png',
				'images/nyan-7.png',
				'images/nyan-8.png',
				'images/nyan-9.png',
				'images/nyan-10.png',
				'images/nyan-11.png',
				'images/nyan-12.png',
				'images/nyan-1.png',
				'images/nyan-2.png',
				'images/nyan-3.png',
				'images/nyan-4.png',
				'images/nyan-5.png',
				'images/nyan-6.png',
				'images/nyan-7.png',
				'images/nyan-8.png',
				'images/nyan-9.png',
				'images/nyan-10.png',
				'images/nyan-11.png',
				'images/nyan-12.png',
				'images/nyan-1.png',
				'images/nyan-2.png',
				'images/nyan-3.png',
				'images/nyan-4.png',
				'images/nyan-5.png',
				'images/nyan-6.png',
				'images/nyan-7.png',
				'images/nyan-8.png',
				'images/nyan-9.png',
				'images/nyan-10.png',
				'images/nyan-11.png',
				'images/nyan-12.png',
				$userNode->getPicture()->getURL()
			);
			$durations = array(300,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,300);
 
			// Initialize and create the GIF !
			$gc = new GifCreator\GifCreator();
			$gc->create($frames, $durations, 0);
			$gifBinary = $gc->getGif();	
				
			file_put_contents('export/' . $time . '-profile.gif', $gifBinary);
			
			?>
			
			<img src="<?php echo 'export/' . $time . '-profile.gif'; ?>" />
			
			
			</div>
</div>


</div>
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
