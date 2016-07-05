<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Your video</title>
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
      <div class="column" style="margin-top: 25%">


<?php
	
ini_set('display_errors',1);
error_reporting(E_ALL);	
require __DIR__ . '/vendor/autoload.php';	

$appLocation = "/var/www/brutalcuts.org.uk/public_html/";
$insertFile = $appLocation . "files/chairmaninsert.mov";

$time = time();	
	
$target_dir = "/var/www/brutalcuts.org.uk/public_html/uploads";
$target_file = $target_dir . "/" . $time . "-" . basename($_FILES["theVideo"]["name"]);
$uploadOk = 1;

$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image


	$pattern = '/^video.+/';
    preg_match($pattern,$_FILES["theVideo"]["type"],$matches);
    
    
    if ($matches)
    {
	    
	    if (move_uploaded_file($_FILES["theVideo"]["tmp_name"], $target_file)) {
	       
	       $ffprobe = FFMpeg\FFProbe::create();
	       $theVideo =  $ffprobe->streams($target_file);
	       
	       $ffmpeg = FFMpeg\FFMpeg::create();
	       
	       $video = $ffmpeg->open($target_file);
		   
		   //Open uploaded video
		   $videoFormat = $ffprobe->format($target_file) // extracts file informations
		   		->all();
		   
		   $dimensions = $theVideo
		   		->videos()
		   		->first()
		   		->getDimensions();
		   
		   
		   $video->save(new FFMpeg\Format\Video\X264(), 'tmp/' . $time . 'upload-x264.mp4');
		   
		   $video->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($videoFormat['start_time']), FFMpeg\Coordinate\TimeCode::fromSeconds(($videoFormat['duration'] / 2)))->resize(new FFMpeg\Coordinate\Dimension(640,360))->synchronize();
		   $video->save(new FFMpeg\Format\Video\X264(), 'tmp/' . $time . 'part1-x264.mp4');
		   
		   $video->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(($videoFormat['duration'] / 2)), FFMpeg\Coordinate\TimeCode::fromSeconds($videoFormat['duration']))->resize(new FFMpeg\Coordinate\Dimension(640,360))->synchronize();
		   $video->save(new FFMpeg\Format\Video\X264(), 'tmp/' . $time . 'part2-x264.mp4');
		   
		   
		   $concatComand = '/usr/bin/ffmpeg -i ' . $appLocation . 'tmp/' . $time . 'part1-x264.mp4 -i ' . $insertFile . ' -i ' . $appLocation . 'tmp/' . $time . 'part2-x264.mp4 -filter_complex "[0:v:0] [0:a:0] [1:v:0] [1:a:0] [2:v:0] [2:a:0] concat=n=3:v=1:a=1 [v] [a]" -map "[v]" -map "[a]" ' . $appLocation . 'export/' . $time . '-output.mp4';
		   $output=exec($concatComand, $out);


		   		   
		   

		   //Extract initial frame for display
		   $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save('export/' . $time . 'cover.jpg');
		   
		   
		   echo '<div align="center" class="embed-responsive embed-responsive-16by9">';
		   			
		   echo '<video  autoplay poster="export/' . $time . 'cover.jpg"  class="embed-responsive-item">
		   		<source src="export/' . $time . '-output.mp4" type="video/mp4">
		   			Your browser does not support the video tag.
		   		</video>';
		   
		    echo "</div>";
	       
	       echo "<p>W: " . $dimensions->getWidth() . ", H: " . $dimensions->getHeight() . ", AR: " . $dimensions->getRatio()->getValue()  . ", Duration: " . $videoFormat['duration'] . "</p>";
		   
		  echo "<pre>";
		  
		  print_r($output);
		  
		  echo "</pre>";
	       
	       		   echo "<p>" .$concatComand ."</p>";

	       
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	    
	    
    }
    
    
 ?>
 
 
 
 </div>
    </div>
  </div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  
    
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/modernizr-custom.min.js"></script>
    <script src="js/webrtc/adapter.js"></script>
  <script src="js-source/brutalcuts.js"></script>
</body>
</html>
