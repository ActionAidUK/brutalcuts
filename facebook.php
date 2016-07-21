<?php

session_start();


header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['vid']))

{
	$_SESSION['vid'] = $_GET['vid'];
}

$aspectRatio = @$_SESSION['aspectratio'];

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/settings/facebook.php';	


$time = time();


@$vid = $_GET['vid'] ? $_GET['vid'] : $_SESSION['vid'];

if ($aspectRatio == 'square')
{
	$class="embed-responsive-square";
} else {
	$class="embed-responsive-16by9";	
}


$helper = $fb->getRedirectLoginHelper();



if (isset($_SESSION['facebook_access_token']))
{
	try {
		
		$fb->get('/me',$_SESSION['facebook_access_token']);
		
	} catch( Exception $e ){
		
		session_destroy();
		session_start();

		$_SESSION['vid'] = $vid;
		$_SESSION['aspectratio'] = $aspectRatio;
		
		$permissions = ['publish_actions']; // optional
		$loginUrl = $helper->getLoginUrl('https://' . $_SERVER['HTTP_HOST'] . '/facebooklogin-callback.php', $permissions);
		header( 'Location: ' . $loginUrl );  
	
	}
} else {
	
	session_destroy();
		session_start();

		$_SESSION['vid'] = $vid;
		$_SESSION['aspectratio'] = $aspectRatio;
	$permissions = ['publish_actions']; // optional
		
	$loginUrl = $helper->getLoginUrl('https://' . $_SERVER['HTTP_HOST'] . '/facebooklogin-callback.php', $permissions);
	header( 'Location: ' . $loginUrl ); 
		
}

if (file_exists('videos/' . $vid . '-output.mp4') && file_exists('posters/' . $vid . 'cover.jpg'))
{
	$video = 'videos/' . $vid . '-output.mp4';
	$poster = 'posters/' . $vid . 'cover.jpg';
}

?><!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Share your #BrutalCut to Facebook</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="/css/styles.css">
  <link rel="stylesheet" href="/css/fancybox/jquery.fancybox.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
   
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/images/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/images/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/images/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/images/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/images/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="/images/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="/images/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="/images/mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="/images/mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="/images/mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="/images/mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="/images/mstile-310x310.png" />



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
<body class="share-window" style="background: #000">
	
<div class="share-wrap" style="background: #e9ebee">	
	
	<nav class="navbar navbar-default main-site-navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-logo" href="/" title="Home" rel="Home"><img src="images/actionaid-logo.svg" class="actionaid-logo img-responsive" alt="ActionAid"></a>
                </div>

                <div class="action-arrow"><img src="images/brandarrow.svg" alt="" height="40"></div>
            </div>
        </nav>
	
<div class="share-body">	
	
	<div class="container">
		
		<div class="row">
			
			<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
	<h1 class="popup-title">Share your video to Facebook</h1>
	
					<div class="error-box" id="noTextError"><ul><li>Please enter some text</li></ul></div>
   	
	<form id="facebooker" class="sendForm" method="post" name="facebooker">
		
		<p>
		<textarea rows="12" name="facebookText" class="shareText" data-limit="250" id="facebookText" data-type="facebook" style="width: 100%; height: 200px; border-color: #e5e6e9 #dfe0e4 #d0d1d5;" placeholder="Enter your mesage here..."></textarea>
		</p>
		
		<p class="counter"><span id="charCount">115</span> characters remaining</p>
		
		<input type="hidden" name="vid" id="vid" value="<?php echo $vid; ?>" />
		

	
		<button type="submit"  style="margin-top :20px;" class="red-box-button facebook" id="fbVideo"><span class="social-logo"></span>Share to facebook</button>
	
	
	<div align="center" class="share-video embed-responsive <?php echo $class; ?>" style="border-color: #e5e6e9 #dfe0e4 #d0d1d5;">
         <video id="brutalCut" poster="<?php echo $poster; ?>" controls class="embed-responsive-item">
	         <source src="<?php echo $video; ?>" type="video/mp4">Your browser does not support the video tag.</source>

	     </video>
    </div>
	
		
		<div class="sending" id="facebookSending" style="background-color: rgba(233,235,238,0.8)">
				<div id="sendspinner">
					<div class="spinner">
					  <div class="double-bounce1"></div>
					  <div class="double-bounce2"></div>
					</div>
				</div>
				
		</div>
		
	</form>
	
	<div id="caseForSupport" class="caseForSupport">
					<p><strong>Thank you for sharing your #BrutalCut to help raise awareness about this dangerous practice putting so many girls’ lives at risk.</strong></p>

					<p>If you’d like to <a href="https://support.actionaid.org.uk/donate?sku=496&amount=15">make a donation</a> too, however small, that would be amazing.</p>

					<p><img src="images/abigail.jpg" class="img-responsive" alt="Abigail" /></p>

					<p>14-year-old Abigail, pictured above, is from Kenya. She narrowly escaped FGM by running away and finding safety at an ActionAid-funded safe house. With your support, we can build more safe centres for girls at risk of FGM, where they can go back to school and rebuild their lives free from fear.</p>

					<p>And we won’t stop there: our centres will be community hubs, where local women’s groups can come together and continue the fight against FGM.</p>

					<p><strong>Please help Kenyan girls escape FGM, for good.</strong></p>
					
					<p><a href="https://support.actionaid.org.uk/donate?sku=496&amount=15" class="red-box-button">Make a donation now</a></p>

					
				</div>
	
	 </div>
	 </div>
	 </div>
	 </div>
</div>	

<div class="wrapper footer-dark-wrapper">
        <footer class="container">
	        
	        
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <img class="footer-logo" width="240" src="/images/actionaid-logo.svg" alt="ActionAid. Changing lives. For good.">
                </div>
                <div class="col-xs-12 col-sm-8">
                    <p class="registered-charity">© 2016. ActionAid is a charitable company limited by guarantee and registered in England and Wales (company number 01295174). Our England and Wales charity number is 274467, and our Scottish charity number is SC045476.<br>Our registered office is 33-39, Bowling Green Lane, London EC1R 0BJ.</p>                    
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-xs-12">
                    <p class="ts-and-cs"><a target="_blank" href="https://www.actionaid.org.uk/about-us/actionaid-respects-your-privacy">Privacy policy</a></p>
                </div>
            </div>
      
        </footer>

        
    </div>

	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/modernizr-custom.min.js"></script>
    
    <script src="js/js-fileupload/vendor/jquery.ui.widget.js"></script>
	<script src="js/js-fileupload/jquery.iframe-transport.js"></script>
<script src="js/js-fileupload/jquery.fileupload.js"></script>
    <script src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="js/brutalcuts-min.js" type="text/javascript"></script>
	
</body>
</html>