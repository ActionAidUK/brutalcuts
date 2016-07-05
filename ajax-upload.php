<?php

$time_start = microtime(true);

header('Content-Type: application/json');
ini_set('display_errors',1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

define('APP_LOCATION', '/var/www/brutalcuts.org.uk/public_html/');
define('TARGET_DIRECTORY', "/var/www/brutalcuts.org.uk/public_html/uploads");

$_SESSION['vid'] = '';

$time = time();



if (isset($_POST["video-filename"])) {
		$fileName = basename($_POST["video-filename"]);

} else if (isset($_FILES["video-blob"]["name"])) {

		$fileName = basename($_FILES["video-blob"]["name"]);
		
} else {

	echo json_encode(array("error"=>"No valid file uploaded"));
	exit;
}


$fileName = preg_replace("/[^a-z0-9\._-]+/i", '', $fileName);


$target_file = TARGET_DIRECTORY . "/upload-" . $fileName;
$uploadOk = 1;

$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


// Check if image file is a actual image or fake image

$outputArray = array();
$pattern = '/^(video)\/(.+)|(image)\/(.+)/';
preg_match($pattern,$_FILES["video-blob"]["type"],$matches);


if ($matches)
{
	
	if ($matches[1])
	{
		$mode = 'video';
	} else {
		
		if ($matches[4] == 'gif')
		{
			$mode = 'gif';
		} else {
			$mode = 'image';
		} 
	}
	
	
	$outputArray['error'] = 0;

	if (move_uploaded_file($_FILES["video-blob"]["tmp_name"], $target_file)) {

				

		$ffprobe = FFMpeg\FFProbe::create();
		$theVideo =  $ffprobe->streams($target_file);

		$dimensions = $theVideo
		->videos()
		->first()
		->getDimensions();
		
	
		
		//Check for rotation matrix...
	   	$theTest = 'ffprobe   -show_streams ' . $target_file . '  2>/dev/null  | grep rotate';
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



		switch ( $aspectRatio ) {

		case 1.8 :
			
			
			switch ($mode) {
				case 'image' :
				
				
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2.5 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=640:360" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
				
					break;
					
				case 'gif' :
					
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -shortest -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=640:360" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
					
					break;
					
				default:
					
					$recode = 'ffmpeg -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=640:360" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			}
			
			
			break;


		case ($aspectRatio < 1.8):

			
			if(!$transpose)
			{
				$padding = ((640 - ($dimensions->getWidth() / ($dimensions->getHeight() / 360))) / 2);
			} else {
				$padding = ((640 - ($dimensions->getHeight() / ($dimensions->getWidth() / 360))) / 2);
			}
			
			switch ($mode) {
				case 'image' :


					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2.5 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:360,pad=width=640:height=360:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';

						break;
					
				case 'gif' :
					
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -shortest -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:360,pad=width=640:height=360:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';

					break;
						
				default:
					
					$recode = 'ffmpeg -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:360,pad=width=640:height=360:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			}
			
			
			break;

		case ($aspectRatio > 1.8):
		
			switch ($mode) {
				case 'image' :
				
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2.5 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:360,pad=width=640:height=360:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
				
					break;
					
				case 'gif' :
				
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -shortest -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'crop=in_h*16/9:in_h,scale=-2:360" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';	
				
					break;
					
				default:
					
					$recode = 'ffmpeg -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'crop=in_h*16/9:in_h,scale=-2:360" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';	
			}
			
			break;
		}
		
					

		$output=exec($recode, $out);
	
		
		$ffmpeg = FFMpeg\FFMpeg::create();
		$video = $ffmpeg->open('tmp/' . $time . 'upload-x264.mp4');
		//Extract initial frame for display
		$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save('export/' . $time . 'cover.jpg');
	
		 
				
		$outputArray = render_standard($ffprobe, $ffmpeg, $dimensions, $time, $time_start,$mode);
		$outputArray['recode'] = $recode;
		unlink($target_file);
		echo json_encode($outputArray);

	} else {

		$outputArray['error'] = 1;
		$outputArray['error-message'] = "Sorry, there was an error uploading your file.";

	}

} else {

	echo json_encode(array('error'=>1, 'error-message'=>"Could not upload this file"));

}



function render_standard($ffprobe,$ffmpeg,$dimensions,$time,$time_start,$mode) {
	 
	

	$insertFile = APP_LOCATION . "files/Child_Random-640x360.mp4";	   


	if (($mode == 'video') || ($mode == 'gif'))
	{

		//Open uploaded video
		$videoFormat = $ffprobe->format('tmp/' . $time . 'upload-x264.mp4') // extracts file informations
		->all();
	
		
	
		$splitTime = ($videoFormat['duration'] / 2) > 10 ? 10 : ($videoFormat['duration'] / 2);
		
		$duration2 = $videoFormat['duration'] - $splitTime;
		
		
	
		$clip1 = '/usr/bin/ffmpeg -ss 00:00:00.00 -i tmp/' . $time . 'upload-x264.mp4 -t ' . $splitTime . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part1-x264.mp4';
		$clip2 = '/usr/bin/ffmpeg -ss ' . $splitTime . ' -i tmp/' . $time . 'upload-x264.mp4 -t ' . $videoFormat['duration'] . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part2-x264.mp4';
	
	
		$output1=exec($clip1, $out1);
		$output2=exec($clip2, $out2);
		
	
		$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'part1-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'part2-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset fast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';
		
	} else {
		$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset fast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';		
	}
	

	$output=exec($concatComand, $out);

	

	unlink('tmp/' . $time . 'upload-x264.mp4');
	
	if ($mode == 'video')
	{
		unlink('tmp/' . $time . 'part1-x264.mp4');
		unlink('tmp/' . $time . 'part2-x264.mp4');

	}
	
	
	
	$time_end = microtime(true);

	$outputArray['url'] = 'export/' . $time . '-output.mp4';
	$outputArray['type'] = 'video/mp4';
	$outputArray['width'] = $dimensions->getWidth();
	$outputArray['height'] = $dimensions->getHeight();
	$outputArray['aspectratio'] = $dimensions->getRatio()->getValue();
	$outputArray['poster'] = 'export/' . $time . 'cover.jpg';
	$outputArray['command'] = $concatComand;
	$outputArray['output'] = $output;
	$outputArray['id'] = $time;
	$outputArray['time'] = $time_end - $time_start;

	$_SESSION['vid'] = $time;

	return $outputArray;

}



?>