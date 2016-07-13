<html lang="en" class=" "><head>

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
	<form method="post" action="process.php" id="videoUploadForm" enctype="multipart/form-data" style="display: none;">

		<div id="simpleCapture">
		<p><input type="file" id="uploadFile" accept="video/*;capture=camcorder" name="video-blob"></p>
		<p><input type="hidden" id="test" name="test" value="For petes sake"></p>
		<p id="filesize">/usr/bin/ffmpeg -i /var/www/brutalcuts.org.uk/public_html/tmp/1467298077part1-x264.mp4 -i /var/www/brutalcuts.org.uk/public_html/files/Child_Random-854x480.mp4 -i /var/www/brutalcuts.org.uk/public_html/tmp/1467298077part2-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset ultrafast /var/www/brutalcuts.org.uk/public_html/export/1467298077-output.mp4 2&gt;&amp;1</p>
		</div>
		
		
		
		<p id="submit-wrap-upload" style="display: block;"><input type="submit" value="Submit" name="theSubmit" id="submitButton"></p>
		<div id="spinner-upload" style="display: none;">
					<div class="spinner">
					  <div class="double-bounce1"></div>
					  <div class="double-bounce2"></div>
					</div>
				</div>
		
	</form>
	
	<div id="getMediaCapture">
			
			<p id="submit-wrap"><input id="saveVideo" type="button" value="Save"></p>
				
				<div id="spinner">
					<div class="spinner">
					  <div class="double-bounce1"></div>
					  <div class="double-bounce2"></div>
					</div>
				</div>
				
	</div>
	
<div id="finalVideo" style="display: block;"><div align="center" class="embed-responsive embed-responsive-16by9"><video id="brutalCut" autoplay="" poster="export/1467368809cover.jpg" controls="" class="embed-responsive-item"><source src="export/1467368809-output.mp4" type="video/mp4">Your browser does not support the video tag.</video></div><p><br><br><a download="" target="_blank" class="button recording-button download-button" href="export/1467368809-output.mp4">Download</a><a target="_blank" id="shareToTwitter" class="button recording-button twitter-button shareButton" href="export/1467368809-output.mp4" data-sharetype="TShareOauth" data-sharevideo="export/1467368809-output.mp4">Share to twitter</a></p></div>	
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
    
	<script src="js/webrtc/adapter.js"></script>
  


</body></html>