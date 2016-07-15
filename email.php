<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require __DIR__ . '/vendor/autoload.php';
require_once 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';


// Create the Transport
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'ssl')
  ->setUsername('actionaidbrutalcut@gmail.com')
  ->setPassword('0ttersAndBadgers!')
  ;



$mailer = Swift_Mailer::newInstance($transport);

// Create the message
$message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject('Your #BrutalCut')

  // Set the From address with an associative array
  ->setFrom(array('actionaidbrutalcut@gmail.com' => 'BrutalCut'))

  // Set the To addresses with an associative array
  ->setTo(array('stuart.wilkes@actionaid.org' => 'Stuart Wilkes'))

  // Give it a body
  ->setBody('Here is the message itself')

  // And optionally an alternative body
  ->addPart('<q>Here is the message itself</q>', 'text/html')

  // Optionally add any attachments
  ->attach(Swift_Attachment::fromPath('videos/1468514499-output.mp4'))
  ;


// Send the message
$result = $mailer->send($message);


echo "<pre>";

print_r($result);

echo "</pre>";