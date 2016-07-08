<!DOCTYPE html>

<html lang="en">
<head>
    <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">

    <title>Brutal Cuts cat inserter</title>
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
            <div class="col-xs-12 col-sm-offset-2 col-sm-9 col-md-offset-3 col-md-6">
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
									 		<h2>Create your #BrutalCut</h2>
			                                <p id="bcInstructions">To get started, drag and drop any image or video here, or click to upload.</p>
											<p><input type="file" id="uploadFile" accept="capture=camcorder" name="video-blob"></p>
											</div>
											
											<div class="dropzone-results" id="dropzone-results">
												
												
											</div>
											
											<p id="submit-wrap-upload"><input type="submit" value="Next" class="btn btn-primary outline-box-button" name="theSubmit" id="submitButton"></p>
	                                	
	                                	</div>
	                                
	                                </div>
	                               
                                </div>
                            </div>

                                <p id="filesize"></p>

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
            <div class="col-xs-12 col-md-offset-2 col-md-9">
				 <div id="finalVideo"></div>
            </div>
        </div>
        
        
        
    </div><!-- End Container -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript">
</script><script src="js/modernizr-custom.min.js" type="text/javascript">
</script><script src="js/js-fileupload/vendor/jquery.ui.widget.js" type="text/javascript">
</script><script src="js/js-fileupload/jquery.iframe-transport.js" type="text/javascript">
</script><script src="js/js-fileupload/jquery.fileupload.js" type="text/javascript">
</script><script src="js/iframeResizer.contentWindow.min.js" type="text/javascript">
</script><script src="js/webrtc/adapter.js" type="text/javascript">
</script><script src="js/dropzone.js" type="text/javascript">
</script><script src="js-source/brutalcuts.js" type="text/javascript">
</script>
</body>
</html>
