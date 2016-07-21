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
				
				$image->resizeImage(720,720,imagick::FILTER_CATROM, 0.9, true);
				
				$image->setImageFormat('jpeg');
				$image->setImageCompressionQuality(90);
				
				

				unlink($target_file);
				$target_file = 'tmp/intermediate' . $time . '.jpg';
												
				switch ($exif['Orientation'])
				{
					case 8 :
						$image->rotateImage(new \ImagickPixel(), 270);
						$rotation = 0;
						break;
						
					case 3 :
						$image->rotateImage(new \ImagickPixel(), 180);
						$rotation = 0;
						break;
					
					case 6 :
						$image->rotateImage(new \ImagickPixel(), 90);
						$rotation = 0;
						break;
						
					default: 
						$rotation = 0;
					
				}
				
				$image->writeImage('tmp/intermediate' . $time . '.jpg');
				
				
				//Generate the gif version...
			/*	
				
				$frames = array(
							$target_file,
							'files/gif-frames/frame3.jpg',
							'files/gif-frames/frame4.jpg',
							'files/gif-frames/frame5.jpg',
							'files/gif-frames/frame6.jpg',
							'files/gif-frames/frame7.jpg',
							'files/gif-frames/frame8.jpg',
							'files/gif-frames/frame9.jpg',
							'files/gif-frames/frame10.jpg',
							'files/gif-frames/frame11.jpg',
							'files/gif-frames/frame12.jpg',
							'files/gif-frames/frame13.jpg',
							'files/gif-frames/frame14.jpg',
							'files/gif-frames/frame15.jpg',
							'files/gif-frames/frame17.jpg',
							'files/gif-frames/frame19.jpg',
							'files/gif-frames/frame21.jpg',
							'files/gif-frames/frame23.jpg',
							'files/gif-frames/frame25.jpg',
							'files/gif-frames/frame27.jpg',
							'files/gif-frames/frame29.jpg',
							'files/gif-frames/frame31.jpg',
							'files/gif-frames/frame33.jpg',
							'files/gif-frames/frame35.jpg',
							'files/gif-frames/frame37.jpg',
							'files/gif-frames/frame39.jpg',
							'files/gif-frames/frame41.jpg',
							'files/gif-frames/frame43.jpg',
							'files/gif-frames/frame45.jpg',
							'files/gif-frames/frame47.jpg',
							'files/gif-frames/frame48.jpg',
							'files/gif-frames/frame49.jpg',
							'files/gif-frames/frame50.jpg',
							'files/gif-frames/frame51.jpg',
							'files/gif-frames/frame52.jpg',
							'files/gif-frames/frame53.jpg',
							'files/gif-frames/frame54.jpg',
							'files/gif-frames/frame55.jpg',
							'files/gif-frames/frame56.jpg',
							'files/gif-frames/frame57.jpg',
							'files/gif-frames/frame58.jpg',
							'files/gif-frames/frame59.jpg',
							$target_file
						);
				
				$durations = array(300,4,4,4,4,4,4,4,4,4,4,4,4,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,4,4,4,4,4,4,4,4,4,4,4,4,300);
				
				$gc = new GifCreator\GifCreator();
				$gc->create($frames, $durations, 0);
				$gifBinary = $gc->getGif();	
				
				file_put_contents('gifs/' . $time . '-cut.gif', $gifBinary);
			
				$gif = 'gifs/' . $time . '-cut.gif';
				*/
				
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
				
				
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:720,crop=out_w=in_h,crop=in_h" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
				
					break;
					
				case 'gif' :
					
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -shortest -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=1280:720" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
					
					break;
					
				default:
					
					$recode = 'ffmpeg -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=1280:720" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			}
			
			
			break;


		case ($aspectRatio < 1.8):

			
			if(!$transpose)
			{
				$padding = ((1280 - ($dimensions->getWidth() / ($dimensions->getHeight() / 720))) / 2);
			} else {
				$padding = ((1280 - ($dimensions->getHeight() / ($dimensions->getWidth() / 720))) / 2);
			}
			
			switch ($mode) {
				case 'image' :

						if ($aspectRatio < 1)
						{
							$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=720:-2,crop=out_h=in_w,crop=in_w" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
						} else {
							$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:720,crop=out_w=in_h,crop=in_h" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
						}

						break;
					
				case 'gif' :
					
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -shortest -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:720,pad=width=1280:height=720:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';

					break;
						
				default:
					
					$recode = 'ffmpeg -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:720,pad=width=1280:height=720:x=' . $padding . ':y=0:color=black" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';
			}
			
			
			break;

		case ($aspectRatio > 1.8):
		
			switch ($mode) {
				case 'image' :
				
					
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -loop 1 -i ' . $target_file .' -shortest -c:v libx264 -c:a aac -strict -2 -t 2 -r 24 -pix_fmt yuv420p -vf "' . $transpose . 'scale=-2:720,crop=out_w=in_h,crop=in_h" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';				
				
					break;
					
				case 'gif' :
				
					$recode = 'ffmpeg -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -shortest -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'crop=in_h*16/9:in_h,scale=-2:720" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';	
				
					break;
					
				default:
					
					$recode = 'ffmpeg -i ' . $target_file .'  -force_key_frames "expr:gte(t,n_forced*1)" -c:v libx264 -acodec aac -strict -2 -pix_fmt yuv420p -vf "' . $transpose . 'crop=in_h*16/9:in_h,scale=-2:720" -preset ultrafast tmp/' . $time . 'upload-x264.mp4';	
			}
			
			break;
		}
		
					

		$output=exec($recode, $out);
	
		
		$ffmpeg = FFMpeg\FFMpeg::create();
		$video = $ffmpeg->open('tmp/' . $time . 'upload-x264.mp4');
		//Extract initial frame for display
		$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save('posters/' . $time . 'cover.jpg');
	
		 
				
		$outputArray = render_standard($ffprobe, $ffmpeg, $dimensions, $time, $time_start,$mode);
		$outputArray['recode'] = $recode;
		
		if ($gif) {
			
			$outputArray['gif'] = $gif;
			
		}
		
//		unlink($target_file);
		
		
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
		
		echo json_encode($outputArray);

	} else {

		$outputArray['error'] = 1;
		$outputArray['error-message'] = "Sorry, there was an error uploading your file.";

	}

} else {

	echo json_encode(array('error'=>1, 'error-message'=>"Could not upload this file"));

}



function render_standard($ffprobe,$ffmpeg,$dimensions,$time,$time_start,$mode) {
	 
	

	$insertFile = APP_LOCATION . "files/brutal-cut-1280x720.mp4";
//	$insertFile = APP_LOCATION . "files/Brutal_Cut_ALPHA-640x360.mp4";   
	$insertFileSquare = APP_LOCATION . "files/brutal-cut-720x720.mp4";


	if (($mode == 'video') || ($mode == 'gif'))
	{

		//Open uploaded video
		$videoFormat = $ffprobe->format('tmp/' . $time . 'upload-x264.mp4') // extracts file informations
		->all();
	
		$outputArray['width'] = 1280;
		$outputArray['height'] = 720;
		$outputArray['aspectratio'] = '16-9';
		$_SESSION['aspectratio'] = '16-9';
		
	
		$splitTime = ($videoFormat['duration'] / 2) > 10 ? 10 : ($videoFormat['duration'] / 2);
		
		$duration2 = $videoFormat['duration'] - $splitTime;
		
		
	
		$clip1 = '/usr/bin/ffmpeg -ss 00:00:00.00 -i tmp/' . $time . 'upload-x264.mp4 -t ' . $splitTime . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part1-x264.mp4';
		$clip2 = '/usr/bin/ffmpeg -ss ' . $splitTime . ' -i tmp/' . $time . 'upload-x264.mp4 -t ' . $videoFormat['duration'] . ' -codec:v copy -codec:a copy -async 1 -preset ultrafast tmp/'. $time . 'part2-x264.mp4';
	
	
		$output1=exec($clip1, $out1);
		$output2=exec($clip2, $out2);
		
	
		$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'part1-x264.mp4 -i ' . $insertFile . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'part2-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset fast ' . APP_LOCATION . 'videos/' . $time . '-output.mp4 2>&1';
		
	} else {
		
		$outputArray['width'] = 720;
		$outputArray['height'] = 720;
		$outputArray['aspectratio'] = 'square';
		$_SESSION['aspectratio'] = 'square';
		
		$concatComand = '/usr/bin/ffmpeg -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -i ' . $insertFileSquare . ' -i ' . APP_LOCATION . 'tmp/' . $time . 'upload-x264.mp4 -filter_complex "[0:v] setsar=sar=1 [in1]; [1:v] setsar=sar=1 [in2]; [2:v] setsar=sar=1 [in3]; [in1][in2][in3] concat=n=3 [v];[0:a][1:a][2:a] concat=n=3:v=0:a=1 [a]" -map "[v]" -map "[a]" -preset fast ' . APP_LOCATION . 'videos/' . $time . '-output.mp4 2>&1';		
	}
	

	$output=exec($concatComand, $out);

	

	unlink('tmp/' . $time . 'upload-x264.mp4');
	
	if ($mode == 'video')
	{
		unlink('tmp/' . $time . 'part1-x264.mp4');
		unlink('tmp/' . $time . 'part2-x264.mp4');

	}
	
	
	//Check final size and duration:
	

	$videoFormaFinal = $ffprobe->format('videos/' . $time . '-output.mp4')->all();
	
	
	
	$time_end = microtime(true);

	$outputArray['url'] = 'videos/' . $time . '-output.mp4';
	$outputArray['type'] = 'video/mp4';
	$outputArray['poster'] = 'posters/' . $time . 'cover.jpg';
//	$outputArray['command'] = $concatComand;
	$outputArray['output'] = $output;
	$outputArray['id'] = $time;
	$outputArray['duration'] = @$videoFormaFinal['duration'];
	$outputArray['size'] = @$videoFormaFinal['size'];
	$outputArray['time'] = $time_end - $time_start;

	$_SESSION['vid'] = $time;

	return $outputArray;

}



?>