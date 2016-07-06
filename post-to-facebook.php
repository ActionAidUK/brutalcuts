<?php
	
ini_set('display_errors',1);
error_reporting(E_ALL);	

header('Content-Type: application/json');



if(!session_id()) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';	
require __DIR__ . '/settings/facebook.php';	




$time = time();

@$vid = $_POST['vid'] ? $_POST['vid'] : $_GET['vid'];
@$message = $_POST['facebookText'] ? $_POST['facebookText'] : $_GET['facebookText'];


if (file_exists('export/' . $vid . '-output.mp4'))
{
	$video = 'export/' . $vid . '-output.mp4';
} else {
	 echo json_encode(array('error' => 'No video chosen','type'=>'vidError'));
	 exit;
}


if (isset($_SESSION['facebook_access_token']))
{
	try {
		
		$fb->get('/me',$_SESSION['facebook_access_token']);
		
		$accessToken = $_SESSION['facebook_access_token'];
		
	} catch( Exception $e ){
		
		 echo json_encode(array('error' => 'Facebook access token not valid','type'=>'tokenError'));
		 exit;
	
	}
	
} else {
	
	 echo json_encode(array('error' => 'No Facebook access token','type'=>'tokenNotPresentError'));
	
}




$data = [
  'title' => 'ActionAid Brutal Cut',
  'description' => $message,
];

$response = '';

try {
  $response = $fb->uploadVideo('me', $video, $data, $_SESSION['facebook_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  
  echo json_encode(array('error' => $e->getMessage(),'type'=>'graphError'));
  
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
    echo json_encode(array('error' => $e->getMessage(),'type'=>'sdkError'));

  exit;
}


echo json_encode(array('success' => @$response['success'], 'video_id' => @$response['video_id']));




