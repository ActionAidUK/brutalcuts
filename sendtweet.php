<?php

header('Content-Type: application/json');

	
session_start();


ini_set('display_errors',1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/settings/aa-settings.inc.php';
require __DIR__ . '/settings/dbconnection.inc.php';	


\Codebird\Codebird::setConsumerKey(TWITTERKEY, TWITTERSECRET); // static, see README


@$vid = $_POST['vid'] ? $_POST['vid'] : $_GET['vid'];
@$tweetText = $_POST['tweetText'] ? $_POST['tweetText'] : $_GET['tweetText'];



if (file_exists('export/' . $vid . '-output.mp4'))
{
	$video = 'export/' . $vid . '-output.mp4';
} else {
	
  echo json_encode(array("error"=>"No such video"));
  die();	
}


if ((!isset($_SESSION['oauth_token'])) || (!isset($_SESSION['oauth_token']))) {
 echo json_encode(array("error"=>"Not logged in to twitter"));
  die();
}
	
	
$cb = \Codebird\Codebird::getInstance();
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$size_bytes = filesize($video);



$fp = fopen($video, 'r');

// INIT the upload

$reply = $cb->media_upload([
  'command'     => 'INIT',
  'media_type'  => 'video/mp4',
  'total_bytes' => $size_bytes
]);

$media_id = $reply->media_id_string;

// APPEND data to the upload

$segment_id = 0;

while (! feof($fp)) {
  $chunk = fread($fp, 1048576); // 1MB per chunk for this sample

  $reply = $cb->media_upload([
    'command'       => 'APPEND',
    'media_id'      => $media_id,
    'segment_index' => $segment_id,
    'media'         => $chunk
  ]);

  $segment_id++;
}

fclose($fp);

// FINALIZE the upload

$reply = $cb->media_upload([
  'command'       => 'FINALIZE',
  'media_id'      => $media_id
]);


if ($reply->httpstatus < 200 || $reply->httpstatus > 299) {
	echo json_encode(array('error'=>$reply->error, 'httpstatus' => $reply->httpstatus));
	die();
}

$media_id = $reply->media_id;


// Now use the media_id in a Tweet
$sendTweet = $cb->statuses_update([
  'status'    => $tweetText,
  'media_ids' => $media_id
]);


if ($sendTweet->httpstatus < 200 || $sendTweet->httpstatus > 299) {
	echo json_encode(array('error' => @$sendTweet->error, 'httpstatus' => $sendTweet->httpstatus));
	die();
}

$twitter_screen_name = $sendTweet->user->screen_name;
$twitter_name = $sendTweet->user->name;
$tweet_id = $sendTweet->id_str;

$now = date("Y-m-d H:i:s");
$error = '';

try {

$stmt = $conn->prepare("INSERT INTO tweets (vid, twitter_sharetext, twitter_screen_name, twitter_name, tweet_id, created) VALUES (:vid, :twitter_sharetext, :twitter_screen_name, :twitter_screen_name, :tweet_id, :created)"); 
$stmt->bindParam(':vid', $vid);
$stmt->bindParam(':twitter_sharetext', $tweetText);
$stmt->bindParam(':twitter_screen_name', $twitter_screen_name);
$stmt->bindParam(':twitter_name', $twitter_name);
$stmt->bindParam(':tweet_id', $tweet_id);
$stmt->bindParam(':created', $now);
$stmt->execute();


} catch(PDOException $e)
    {
$error = $e;
    }

$conn = null;



echo json_encode(array('httpstatus' => '200','status'=>$sendTweet,'tweetid'=>$tweet_id));