<?php
	
	
$time_start = microtime(true); 
$totalTime = microtime(true); 


	
header('Content-Type: application/json');	
ini_set('display_errors',1);
error_reporting(E_ALL);	
require __DIR__ . '/vendor/autoload.php';	

define('APP_LOCATION', '/var/www/brutalcuts.org.uk/public_html/');
define('TARGET_DIRECTORY', "/var/www/brutalcuts.org.uk/public_html/uploads");


$time = time();	
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/files/tampons.mp4';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/files/Tampon+vs.+Mooncup+Rap+Battle+-+mooncup.co.uk.mp4';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/files/Brutal_Cut_480x480.mp4';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/uploads/upload-video-1467133570953.webm';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/files/hugh-720.mp4';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/uploads/upload-trim.7D702F4F-6D82-47E0-8780-14AB41E626EC.MOV';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/uploads/upload-trim.68B69372-77BD-4134-B6B4-B88218B5BA5D.MOV';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/uploads/upload-trim.4BF5A3C7-0170-41E3-B8DF-9B663479BA24.MOV';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/files/IMG_6710.MOV';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/files/IMG_6731.MOV';
//$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/uploads/giphy.gif';


$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/images/IMG_6728.JPG';
$fileToProcess = '/var/www/brutalcuts.org.uk/public_html/images/IMG_6729.JPG';

	/*

if (isset($_POST["video-filename"])) {
	$fileName = basename($_POST["video-filename"]);
	
} else if (isset($_FILES["video-blob"]["name"])) {
	
	$fileName = basename($_FILES["video-blob"]["name"]);

} else {
	
	echo json_encode(array("error"=>"No valid file uploaded"));
	exit;
}
*/
//$fileName = $time . '-tampons.mp4';

//$fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName);
//$fileName = mb_ereg_replace("([\.]{2,})", '', $fileName); 

//$target_file = TARGET_DIRECTORY . "/upload-" . $fileName;
//$uploadOk = 1;

$imageFileType = pathinfo($fileToProcess,PATHINFO_EXTENSION);





	$mode = 'image';
	
	
	
	     $outputArray['error'] = 0;
//	    if (move_uploaded_file('/var/www/brutalcuts.org.uk/public_html/files/tampons.mp4', $target_file)) {
	       
	     
		       $ffprobe = FFMpeg\FFProbe::create();	       
		       $ffmpeg = FFMpeg\FFMpeg::create();
		       $video = $ffmpeg->open($fileToProcess);
		    //   $video->save(new FFMpeg\Format\Video\X264(), 'tmp/' . $time . 'upload-x264.mp4');
		       //Extract initial frame for display
		       
		      
		       
		       //unlink($target_file);
		       
		       $theVideo =  $ffprobe->streams($fileToProcess);
		       
		       $dimensions = $theVideo
			   		->videos()
			   		->first()
			   		->getDimensions();
	
	
			   	//Check for rotation matrix...
			   	$theTest = 'ffprobe   -show_streams ' . $fileToProcess . '  2>/dev/null  | grep rotate';
			   	$output=exec($theTest, $out);
	

		       
	
if (count($out) > 0) {
	
	$rPattern = '/^TAG:rotate=([0-9]{0,3})/';
	preg_match($rPattern,$out[0],$matches);
	if (isset($matches[1]))
	{
		$rotation = $matches[1];
	}
	
} else {
	$rotation  = 0;
}

if ($mode == 'image')
{
	
	
	
	
	try {
		$exif = exif_read_data($fileToProcess);
		
		
		$w = $exif['ExifImageWidth'];
		$h = $exif['ExifImageLength'];
		
		$image = new \Imagick($fileToProcess);
		
		
		if ($w > $h)
		{
			//Landscape	
			$image->cropImage($h, $h, (($w-$h)/2), 0);
			
		} else {
			//Portait
			$image->cropImage($w, $w, 0, (($h-$w)/2));
		}
		$image->scaleImage(480, 480, true);
		$image->writeImage('tmp/' . $time . '.jpg');
		

		switch ($exif['Orientation'])
		{
			case 8 :
				$rotation = 90;
				break;
				
			case 3 :
				$rotation = 180;
				break;
			
			case 6 :
				$rotation = 270;
				break;
				
			default: 
				$rotation = 0;
			
		}
		
	} catch ( Exception $e) {
		
		$rotation = 0;
	}
	
}

echo $rotation;
exit;


$aspectRatio = round($dimensions->getRatio()->getValue(),1);



switch ($rotation) {
	
	case 90 :
	
		$transpose = "transpose=1,";
		$aspectRatio = 1 / $aspectRatio;
	
		break;
		
	case 180 : 
	
		$transpose = "transpose=2,transpose=2,";
	
		break;
		
	case  270 :
	
		$transpose = "transpose=2,";
		$aspectRatio = 1 / $aspectRatio;
	
		break;
		
	default: 
	
		$transpose = "";
	
}




	//echo $transpose;
//	exit;
//	$transpose = "";
			   		
			 	 
	switch ( $aspectRatio ) {

		case 1.8 :

			$recode = 'ffmpeg -i ' . $fileToProcess .' -c:v libx264 -acodec aac -strict -2 -vf "' . $transpose . 'scale=854:480"  -force_key_frames "expr:gte(t,n_forced*1)" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			
			
			$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $fileToProcess .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2.5 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=854:480" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			
						
			
			break;


		case ($aspectRatio < 1.8):

			
			if(!$transpose)
			{
				$padding = ((854 - ($dimensions->getWidth() / ($dimensions->getHeight() / 480))) / 2);
			} else {
				$padding = ((854 - ($dimensions->getHeight() / ($dimensions->getWidth() / 480))) / 2);
			}
			
			
			$recode = 'ffmpeg -i ' . $fileToProcess .' -c:v libx264 -acodec aac -strict -2 -vf "' . $transpose . 'scale=-2:480,pad=width=854:height=480:x=' . $padding . ':y=0:color=black"  -force_key_frames "expr:gte(t,n_forced*1)"  -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			
			$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $fileToProcess .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2.5 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:360,pad=width=640:height=360:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';

			
			break;

		case ($aspectRatio > 1.8):

			$recode = 'ffmpeg -i ' . $fileToProcess .' -c:v libx264 -acodec aac -strict -2 -vf "' . $transpose . 'crop=in_h*16/9:in_h,scale=-2:480" -force_key_frames "expr:gte(t,n_forced*1)" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			break;
		}
			

		//unlink($target_file);

		$output=exec($recode, $out);
		
		echo $recode;
		exit;
		
		$ffmpeg = FFMpeg\FFMpeg::create();
		$video = $ffmpeg->open('tmp/' . $time . 'upload-x264.mp4');
		//Extract initial frame for display
		
		
		$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save('export/' . $time . 'cover.jpg');		       
		       

		       $output=exec($recode, $out);
	 
			   
	
			   $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save('export/' . $time . 'cover.jpg');
		       
		       
		       
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

//execution time of the script
echo '<b>Total Execution Time to end of copy:</b> '.$execution_time.' Secs';
		    
		
	
				$outputArray = render_standard($ffprobe,$ffmpeg,$theVideo,$dimensions,$time,$aspectRatio,$totalTime,'image');
				   
						   
			   echo json_encode($outputArray);
			   
//		} else {
	    
//		    $outputArray['error'] = 1;
//		    $outputArray['error-message'] = "Sorry, there was an error uploading your file.";
//		    
		//}
	    
//    } else {
	    
//	    echo json_encode(array('error'=>1, 'error-message'=>"Could not upload this file"));
	    
//    }
 
 
 
 function render_standard($ffprobe,$ffmpeg,$theVideo,$dimensions,$time,$aspectRatio,$totalTime,$mode = 'video') {
	 
	 	      
	$time_start = microtime(true);

	$outputDimensions = new FFMpeg\Coordinate\Dimension(854,480);
	$insertFile = APP_LOCATION . "files/Brutal_Cut_854x480.mp4";
			   		
	
	if ($mode == 'video')
	{
	 	//Open uploaded video
	   $videoFormat = $ffprobe->format('tmp/' . $time . 'upload-x264.mp4') // extracts file informations
	   		->all();
	   	  

	   		$splitTime = ($videoFormat['duration'] / 2) > 10 ? 10 : ($videoFormat['duration'] / 2);
	
	   		$duration2 = gmdate("H:i:s", ($videoFormat['duration'] - $splitTime));
	   		$splitTime = gmdate("H:i:s", $splitTime);

			 
		
	
	
	   
//	   $recodedVideo->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds(($splitTime)))->resize($outputDimensions);
//	   $recodedVideo->save(new FFMpeg\Format\Video\X264(), 'tmp/' . $time . 'part1-x264.mp4');

//		$clip1 = '/usr/bin/ffmpeg -ss ' . $splitTime . ' -i tmp/' . $time . 'upload-x264.mp4  -ss ' . $splitTime . ' -t ' . $splitTime . ' -c copy tmp/' . $time . 'part2-x264.mp4 -ss 0 -t ' . $splitTime . ' -codec copy tmp/' . $time . 'part1-x264.mp4';
		
		
			$clip1 = '/usr/bin/ffmpeg -ss 00:00:00.00 -i tmp/' . $time . 'upload-x264.mp4 -t ' . $splitTime . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part1-x264.mp4';
			$clip2 = '/usr/bin/ffmpeg -ss ' . $splitTime . ' -i tmp/' . $time . 'upload-x264.mp4 -t ' . $videoFormat['duration'] . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part2-x264.mp4';


			$output1=exec($clip1, $out1);
			$output2=exec($clip2, $out2);
	   
		   
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
 $time_start = microtime(true);

//execution time of the script
echo '<b>Total Execution Time to end of split:</b> '.$execution_time.' Secs';


$concatList = "file '" . APP_LOCATION . "tmp/" . $time . "part1-x264.mp4'\rfile '" . $insertFile . "'\rfile '" . APP_LOCATION . "tmp/" . $time . "part2-x264.mp4'\r";
$concatFile = fopen('tmp/' . $time . "-fileList.txt", "w") or die("Unable to open file!");
fwrite($concatFile, $concatList);
fclose($concatFile);
	   		     
//	   $concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'part1-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'part2-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset ultrafast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';

//$concatComand = '/usr/bin/ffmpeg  -f concat -i tmp/' . $time . '-fileList.txt -vcodec copy -acodec copy ' . APP_LOCATION . 'export/' . $time . '-output.mp4';

$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'part1-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'part2-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset ultrafast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';

} else {
	$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset ultrafast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';

}
	

$output=exec($concatComand, $out);
//$output = '';	
	
		  $time_end = microtime(true);

//execution time of the script
//echo '<b>Total Execution Time to end of concat:</b> '.$execution_time.' Secs';		   
	   
	   
	   $outputArray['url'] = 'export/' . $time . '-output.mp4';
	   $outputArray['type'] = 'video/mp4';
	   $outputArray['width'] = $dimensions->getWidth();
	   $outputArray['height'] = $dimensions->getHeight();
	   $outputArray['aspectratio'] = $dimensions->getRatio()->getValue();
	   $outputArray['poster'] = 'export/' . $time . 'cover.jpg';
	   $outputArray['command'] = $concatComand;
	   $outputArray['output'] = $output;
	   $outputArray['time'] = $time_end - $totalTime;
	  	       
	
	
	return $outputArray;
 }
 

 
 
