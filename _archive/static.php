<html lang="en" class=" ">
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
    <link rel="stylesheet" href="css/normalize.css" type="text/css">
    <link rel="stylesheet" href="css/skeleton.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <link rel="stylesheet" href="css/fancybox/jquery.fancybox.css" type="text/css"><!-- Favicon
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
                <img src="images/ActionAid---Brutal-Cuts.png" class="img-responsive">

                <h1>Record</h1>

                <ol>
                    <li>Choose a video to upload (or record one on your phone).</li>

                    <li>Click Submit</li>

                    <li>We'll insert our clip in to your video.</li>
                </ol>

                <div class="upload-area">
                    <div id="finalVideo" style="display: block;">
                        <div align="center" class="embed-responsive embed-responsive-16by9">
                            <video id="brutalCut" poster="export/1467739322cover.jpg" controls class="embed-responsive-item"><source src="export/1467739322-output.mp4" type="video/mp4">Your browser does not support the video tag.</source></video><source src="export/1467739322-output.mp4" type="video/mp4"></source>
                        </div>

                        <p>
                        <br>
                        <a download="" target="_blank" class="button recording-button download-button " href="export/1467739322-output.mp4">Download</a><a target="_blank" id="shareToTwitter" class="button recording-button twitter-button shareButton" target="_blank" href="twitter.php" data-videoID="1467739322" data-sharetype="TShareOauth" data-sharevideo="export/1467739322-output.mp4">Share to twitter</a><a target="_blank" id="shareToFacebook" class="button recording-button twitter-button shareButton" href="facebook.php?vid=1467739322" data-sharetype="facebookShare" data-videoid="1467739322" data-sharevideo="export/1467739322-output.mp4">Share to Facebook</a></p>
                    </div>
                </div>

                <div id="upload-response">
                    <source src="export/1467368809-output.mp4" type="video/mp4"></source>
                </div>
            </div>
        </div>
    </div><!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/modernizr-custom.min.js"></script>
    
    <script src="js/js-fileupload/vendor/jquery.ui.widget.js"></script>
	<script src="js/js-fileupload/jquery.iframe-transport.js"></script>
<script src="js/js-fileupload/jquery.fileupload.js"></script>
    <script src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script src="js/webrtc/adapter.js"></script>
  <script src="js-source/brutalcuts.js"></script>
</script>
</body>
</html>
