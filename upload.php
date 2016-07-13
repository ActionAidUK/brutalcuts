<?php

$time_start = microtime(true);

session_start();

header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/settings/dbconnection.inc.php';	

define('APP_LOCATION', '/var/www/brutalcuts.org.uk/public_html/');
define('TARGET_DIRECTORY', "/var/www/brutalcuts.org.uk/public_html/uploads");

$_SESSION['vid'] = '';

$time = time();



if (isset($_POST["video-filename"])) {
		$fileName = basename($_POST["video-filename"]);

} else if (isset($_FILES["video-blob"]["name"])) {

		$fileName = basename($_FILES["video-blob"]["name"]);
		
} else {
	
	$_SESSION['error'] = 'No valid file uploaded';
	header('Location: index.php');

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
		


	
		
		
		//Rotates for images
		if ($mode == 'image')
		{
			
			try {
				$exif = exif_read_data($target_file);
				
				
				
				$image = new \Imagick($target_file);
				
				
				$imageprops = $image->getImageGeometry();
							
				if ($imageprops['width'] > $imageprops['height'])
				{
					//Landscape	
					$image->cropImage($imageprops['height'], $imageprops['height'], (($imageprops['width']-$imageprops['height'])/2), 0);
					
					
					
				} else {
					//Portait
					$image->cropImage($imageprops['width'], $imageprops['width'], 0, (($imageprops['height']-$imageprops['width'])/2));
				}
				
				$image->resizeImage(480,480,imagick::FILTER_CATROM, 0.9, true);
				$image->writeImage($target_file);

												
				switch ($exif['Orientation'])
				{
					case 8 :
						$rotation = 270;
						break;
						
					case 3 :
						$rotation = 180;
						break;
					
					case 6 :
						$rotation = 90;
						break;
						
					default: 
						$rotation = 0;
					
				}
				
			} catch ( Exception $e) {
				
				$rotation = 0;
			}
			
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
				
				
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:480,crop=out_w=in_h,crop=in_h" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
				
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

						if ($aspectRatio < 1)
						{
							$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=480:-2,crop=out_h=in_w,crop=in_w" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
						} else {
							$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:480,crop=out_w=in_h,crop=in_h" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
						}

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
				
					
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:480,crop=out_w=in_h,crop=in_h" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
				
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
		
		//Save to database
		$now = date("Y-m-d H:i:s");
		try {

			$stmt = $conn->prepare("INSERT INTO videos (vid, type, duration, created) VALUES (:vid, :type, :duration, :created)"); 
			$stmt->bindParam(':vid', $time);
			$stmt->bindParam(':type', $mode);
			$stmt->bindParam(':duration', $outputArray['duration']);
			$stmt->bindParam(':created', $now);
			$stmt->execute();
			
			
			} catch(PDOException $e)
			    {
			$error = $e;
			    }
		
			
		$conn = null;
		
		
		
		header('Location: index.php');
		
		

	} else {


		
		$_SESSION['error'] = 'Sorry, there was an error uploading your file.';
		header('Location: index.php');

	}

} else {
	$_SESSION['error'] = 'Could not upload this file';
	header('Location: index.php');

}



function render_standard($ffprobe,$ffmpeg,$dimensions,$time,$time_start,$mode) {
	 
	

	$insertFile = APP_LOCATION . "files/brutal-cut-640x360.mp4";
//	$insertFile = APP_LOCATION . "files/Brutal_Cut_ALPHA-640x360.mp4";   
	$insertFileSquare = APP_LOCATION . "files/brutal-cut-480x480.mp4";


	if (($mode == 'video') || ($mode == 'gif'))
	{

		//Open uploaded video
		$videoFormat = $ffprobe->format('tmp/' . $time . 'upload-x264.mp4') // extracts file informations
		->all();
	
		$outputArray['width'] = 640;
		$outputArray['height'] = 360;
		$outputArray['aspectratio'] = '16-9';
		$_SESSION['aspectratio'] = '16-9';
		
	
		$splitTime = ($videoFormat['duration'] / 2) > 10 ? 10 : ($videoFormat['duration'] / 2);
		
		$duration2 = $videoFormat['duration'] - $splitTime;
		
		
	
		$clip1 = '/usr/bin/ffmpeg -ss 00:00:00.00 -i tmp/' . $time . 'upload-x264.mp4 -t ' . $splitTime . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part1-x264.mp4';
		$clip2 = '/usr/bin/ffmpeg -ss ' . $splitTime . ' -i tmp/' . $time . 'upload-x264.mp4 -t ' . $videoFormat['duration'] . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part2-x264.mp4';
	
	
		$output1=exec($clip1, $out1);
		$output2=exec($clip2, $out2);
		
	
		$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'part1-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'part2-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset fast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';
		
	} else {
		
		$outputArray['width'] = 480;
		$outputArray['height'] = 480;
		$outputArray['aspectratio'] = 'square';
		$_SESSION['aspectratio'] = 'square';
		
		$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -i ' . $insertFileSquare . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset fast ' . APP_LOCATION . 'export/' . $time . '-output.mp4 2>&1';		
	}
	

	$output=exec($concatComand, $out);

	

	unlink('tmp/' . $time . 'upload-x264.mp4');
	
	if ($mode == 'video')
	{
		unlink('tmp/' . $time . 'part1-x264.mp4');
		unlink('tmp/' . $time . 'part2-x264.mp4');

	}
	
	
	//Check final size and duration:
	

	$videoFormaFinal = $ffprobe->format('export/' . $time . '-output.mp4')->all();
	
	
	
	$time_end = microtime(true);

	$_SESSION['no-ajax'] = 1;
	$_SESSION['url'] = 'export/' . $time . '-output.mp4';
	$_SESSION['type'] = 'video/mp4';
	$_SESSION['poster'] = 'export/' . $time . 'cover.jpg';
//	$outputArray['command'] = $concatComand;
	$_SESSION['output'] = $output;
	$_SESSION['id'] = $time;
	$_SESSION['duration'] = @$videoFormaFinal['duration'];
	$_SESSION['size'] = @$videoFormaFinal['size'];
	$_SESSION['time'] = $time_end - $time_start;

	$_SESSION['vid'] = $time;

	return $outputArray;

}



?>