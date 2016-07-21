<?php
	
ini_set('display_errors',1);
error_reporting(E_ALL);	

header('Content-Type: application/json');



if(!session_id()) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';	
require __DIR__ . '/settings/facebook.php';	
require __DIR__ . '/settings/dbconnection.inc.php';	




$time = time();

@$vid = $_POST['vid'] ? $_POST['vid'] : $_GET['vid'];
@$message = $_POST['facebookText'] ? $_POST['facebookText'] : $_GET['facebookText'];


if (file_exists('videos/' . $vid . '-output.mp4'))
{
	$video = 'videos/' . $vid . '-output.mp4';
} else {
	 echo json_encode(array('error' => 'No video chosen','type'=>'vidError'));
	 exit;
}


if (isset($_SESSION['facebook_access_token']))
{
	try {
		
		$me = $fb->get('/me?fields=id,name',$_SESSION['facebook_access_token']);
		
		$accessToken = $_SESSION['facebook_access_token'];
		
	} catch( Exception $e ){
		
		 echo json_encode(array('error' => 'Facebook access token not valid','type'=>'tokenError'));
		 exit;
	
	}
	
} else {
	
	 echo json_encode(array('error' => 'No Facebook access token','type'=>'tokenNotPresentError'));
	
}




$data = [
  'title' => 'ActionAid #BrutalCut',
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

$user = $me->getGraphUser();

$name = $user['name'];
$user_id = $user['id'];
$fbVidId = $response['video_id'];
$now = date("Y-m-d H:i:s");
$error = '';

try {

$stmt = $conn->prepare("INSERT INTO facebook (vid, facebook_sharetext, facebook_userid, facebook_name, created) VALUES (:vid, :facebook_sharetext, :facebook_userid, :facebook_name, :created)"); 
$stmt->bindParam(':vid', $vid);
$stmt->bindParam(':facebook_sharetext', $message);
$stmt->bindParam(':facebook_userid', $user_id);
$stmt->bindParam(':facebook_name', $name);
$stmt->bindParam(':created', $now);
$stmt->execute();


} catch(PDOException $e)
    {
$error = $e;
    }

$conn = null;


echo json_encode(array('success' => @$response['success'], 'video_id' => @$response['video_id']));




