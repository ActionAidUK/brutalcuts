<!DOCTYPE html>

<html lang="en">
<head>
    <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">

    <title>ActionAid #Brutal Cut generator</title>
    <meta name="description" content="">
    <meta name="author" content=""><!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1"><!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css"><!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/styles.css" type="text/css"><!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="images/favicon.png">
    
    
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="images/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="images/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="images/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="images/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="images/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="images/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="images/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="images/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="images/mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="images/mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="images/mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="images/mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="images/mstile-310x310.png" />

    
    <script type="text/javascript">
  
     var FB;
     
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
        <div class="row" id="uploadRow">
	        <div class="col-xs-12 col-sm-6 bc-instructions">
		       <h2>How to create your own #BrutalCut</h2>
<ol>
<li>Just click on the #BrutalCut tool <span class="mobile-message">below</span><span class="desktop-message">to the right</span></li>
<li>Choose a photo or short video</li>
<li>Our tool will add a #BrutalCut to your picture or video for you to share on Facebook or Twitter</li>
</ol>
	        </div>
            <div class="col-xs-12 col-sm-6">
                <div class="upload-area">
                    <form method="post" action="ajax-upload.php" id="videoUploadForm" class="" enctype="multipart/form-data">
                        <div id="simpleCapture">
                            <div class="dropzoneWrapper">
	                            <div id="dropzone" class="dropzone-border">
		                            <div id="dropzoneTrigger"></div>

	                                <div class="dropzone-inner">
	                                	<div class="dropzone-content">
											
											<div class="dropzone-text" id="dropzone-text">
											<p>
					                             <img src="images/image.svg" alt="Image" width="90" height="90" />
					                             <img src="images/video.svg" alt="Video" width="90" height="90" />   
		                             		</p>
									 		
			                                <p id="bcInstructions">To get started, click here, or drag and drop an image or video here.</p>
			                                <p id="filesize">&nbsp;</p>
											<p><input type="file" id="uploadFile" accept="capture=camcorder" name="video-blob"></p>
											</div>
											
											<div class="dropzone-results" id="dropzone-results">
												
												
											</div>
											
											<p id="submit-wrap-upload"><input type="submit" value="Next" class="btn btn-primary outline-box-button" name="theSubmit" id="submitButton"></p>
	                                	
	                                	</div>
	                                
	                                </div>
	                               
                                </div>
                            </div>

                                

                                <div id="spinner-upload">
                                    <div class="spinner">
                                        <div class="double-bounce1"></div>

                                        <div class="double-bounce2"></div>
                                    </div>
                            </div>
                        </div>

                       
                    </form>
                   
                </div>

                <div id="getMediaCapture">
                    <p id="submit-wrap"><input id="saveVideo" type="button" value="Save"></p>

                    <div id="spinner">
                        <div class="spinner">
                            <div class="double-bounce1"></div>

                            <div class="double-bounce2"></div>
                        </div>
                    </div>
                </div> <!-- End getMediaCapture -->
            
            </div><!-- End Col 1 -->

           
            <!--div class="col-xs-12 col-sm-6">
	           <img src="images/glitch.gif" class="img-responsive" alt="Brutal cuts" />
                <div id="finalVideo"></div>
           
            </div--><!-- End Col 2 -->
        </div><!-- End Row -->
        
        <div class="row" id="displayRow">
            <div class="col-xs-12 col-md-offset-2 col-md-8">
				 <div id="finalVideo"></div>
            </div>
        </div>
        
         <div class="row" id="shareRow">
            <div class="col-xs-12">
				 <div class="shareWrapper"><div id="shareContainer"></div></div>
            </div>
        </div>
        
        
        
    </div><!-- End Container -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript">
</script><script src="js/modernizr-custom.min.js" type="text/javascript">
</script><script src="js/js-fileupload/vendor/jquery.ui.widget.js" type="text/javascript">
</script><script src="js/js-fileupload/jquery.iframe-transport.js" type="text/javascript">
</script><script src="js/js-fileupload/jquery.fileupload.js" type="text/javascript">
</script><script src="js/iframeResizer.contentWindow.min.js" type="text/javascript">
</script><script src="js/dropzone.js" type="text/javascript">
</script><script src="js-source/brutalcuts.js" type="text/javascript">
</script>
</body>
</html>
