<!DOCTYPE html>
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
  <link rel="stylesheet" href="css/styles-old.css">
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
<div class="container">
    <div class="row">
      <div class=" column" style="margin-top: 30px">
<img src="images/ActionAid---Brutal-Cuts.png" class="img-responsive" />
<h1>Record</h1>
<ol>
<li>Choose a video to upload (or record one on your phone).</li>
<li>Click Submit</li> 
<li>We'll insert our clip in to your video.</li> 
</ol>

<div class="upload-area">
	<form method="post" action="process.php" id="videoUploadForm" enctype="multipart/form-data">

		<div id="simpleCapture">
		<p><input type="file" id="uploadFile" accept="capture=camcorder
			" name="video-blob"></p>

		<p id="filesize"></p>
		</div>
		
		
		
		<p id="submit-wrap-upload"><input type="submit" value="Submit" name="theSubmit" id="submitButton" /></p>
		<div id="spinner-upload">
					<div class="spinner">
					  <div class="double-bounce1"></div>
					  <div class="double-bounce2"></div>
					</div>
				</div>
		
	</form>
	
	<div id="getMediaCapture">
			
			<p id="submit-wrap"><input id="saveVideo" type="button" value="Save"/></p>
				
				<div id="spinner">
					<div class="spinner">
					  <div class="double-bounce1"></div>
					  <div class="double-bounce2"></div>
					</div>
				</div>
				
	</div>
	
	<div id="finalVideo">
		
		
	</div>
		
	
</div>

<div id="upload-response">
	
	
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
    <script src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script src="js/webrtc/adapter.js"></script>
  <script src="js-source/brutalcuts.js"></script>
  
</body>
</html>
