<?php

session_start();

$time_start = microtime(true);

ini_set('display_errors',1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/settings/aa-settings.inc.php';


$time = time();

\Codebird\Codebird::setConsumerKey(TWITTERKEY, TWITTERSECRET); // static, see README

$cb = \Codebird\Codebird::getInstance();

if (! isset($_SESSION['oauth_token'])) {
  // get the request token
  $reply = $cb->oauth_requestToken([
    'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
  ]);

  // store the token
  $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
  $_SESSION['oauth_token'] = $reply->oauth_token;
  $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
  $_SESSION['oauth_verify'] = true;

  // redirect to auth website
  $auth_url = $cb->oauth_authorize();
  header('Location: ' . $auth_url);
  die();

} elseif (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
  // verify the token
  $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
  unset($_SESSION['oauth_verify']);

  // get the access token
  $reply = $cb->oauth_accessToken([
    'oauth_verifier' => $_GET['oauth_verifier']
  ]);

  // store the token (which is different from the request token!)
  $_SESSION['oauth_token'] = $reply->oauth_token;
  $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

  // send to same URL, without oauth GET parameters
  header('Location: ' . basename(__FILE__));
  die();
}


// assign access token on each page load
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);


$video       = 'export/1467298351-output.mp4';

//$video       = 'export/1467368617-output.mp4';
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

//var_dump($reply);

$signature = $cb->signExternal('POST','https://upload.twitter.com/1.1/media/upload.json',array());


$curl_header = array("Authorization: " . $signature[0], 'Expect:');



$curl_request = curl_init();


$curl_request = curl_init();
curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
curl_setopt($curl_request, CURLOPT_POST, 1);
curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($curl_request, CURLOPT_HEADER, 0);
curl_setopt($curl_request, CURLOPT_USERPWD, TWITTERKEY . ':' . TWITTERSECRET);
curl_setopt($curl_request, CURLOPT_POSTFIELDS, array('command'=>'FINALIZE','media_id'=>$media_id));
curl_setopt($curl_request, CURLOPT_URL, 'https://upload.twitter.com/1.1/media/upload.json');
curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl_request, CURLOPT_VERBOSE, 1);

curl_setopt($curl_request, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl_request, CURLOPT_CAINFO, __DIR__ . '/vendor/jublonet/codebird-php/src/cacert.pem');
curl_setopt($curl_request, CURLOPT_USERAGENT, 'codebird-php/3.0.0 +https://github.com/jublonet/codebird-php');


$json = curl_exec($curl_request);


echo "<pre>";
print_r( $json );
echo "</pre>";

echo "<pre>";
$curlHeaders = curl_getinfo($curl_request);


curl_close($curl_request);



//STATUS

$signature = $cb->signExternal('GET','https://upload.twitter.com/1.1/media/upload.json?command=STATUS&media_id=' . $media_id,array());


$curl_header = array("Authorization: " . $signature[0], 'Expect:');



$curl_request = curl_init();


$curl_request = curl_init();
curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($curl_request, CURLOPT_HEADER, 0);
curl_setopt($curl_request, CURLOPT_USERPWD, TWITTERKEY . ':' . TWITTERSECRET);
curl_setopt($curl_request, CURLOPT_URL, 'https://upload.twitter.com/1.1/media/upload.json?command=STATUS&media_id=' . $media_id);
curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl_request, CURLOPT_VERBOSE, 1);

curl_setopt($curl_request, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl_request, CURLOPT_CAINFO, __DIR__ . '/vendor/jublonet/codebird-php/src/cacert.pem');
curl_setopt($curl_request, CURLOPT_USERAGENT, 'codebird-php/3.0.0 +https://github.com/jublonet/codebird-php');


$json = curl_exec($curl_request);


echo "<pre>";
print_r( $json );
echo "</pre>";

exit;


// FINALIZE the upload

/*	$reply = $cb->media_upload([
	  'command'       => 'FINALIZE',
	  'media_id'      => $media_id
	]);
	
	var_dump($reply);
*/	

echo "<p>We’re done.</p>";

exit;

	


echo "<p>Finished</p>";



if ($reply->httpstatus < 200 || $reply->httpstatus > 299) {
  die();
}

/*

// Now use the media_id in a Tweet
$sendTweet = $cb->statuses_update([
  'status'    => 'Test video',
  'media_ids' => $reply->media_id
]);

echo "<pre>";

print_r($sendTweet);

echo "</pre>";