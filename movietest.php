<?php
	
require __DIR__ . '/vendor/autoload.php';	

$ffmpeg = FFMpeg\FFMpeg::create();

$video = $ffmpeg->open('files/Chairman.m4v');
$video
    ->filters()
    ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
    ->synchronize();
    
    
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(8))
    ->save('export/frame.jpg');
$video
    ->save(new FFMpeg\Format\Video\X264(), 'export/export-x264.mp4')
    ->save(new FFMpeg\Format\Video\WMV(), 'export/export-wmv.wmv');
    

echo "Done";    