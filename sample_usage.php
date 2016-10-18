<?php

/**
 * IMPORTANT: require the jczip.class.php
 */
require 'jczip/jczip.class.php';

// increase script timeout value
//ini_set("max_execution_time", 0);
set_time_limit(0);
        
//instantiate the compressToZip Class
$zip = new compressToZip;

/**
 * Define our variables
 * Use $sourceToZip = '.'; // Use a dot to zip all files and folders in current directory
 * $sourceToZip can be an array of files or folder
 */

//$sourceToZip = array('app','js','skin','var/picaris');
//$destinationOfZip = 'ZIP';
//
//try
//{
//    // Process zip
//    $zip->createZip( $sourceToZip , $destinationOfZip );
//} catch (Exception $exc)
//{
//    echo $exc->getTraceAsString();
//}


$source = array('ZIP/app.zip','ZIP/js.zip','ZIP/skin.zip','ZIP/picaris.zip');
$destination = "UNZIP";

try
{
    // Extract zip
    $zip->extractZip( $source, $destination );
} catch (Exception $exc)
{
    echo $exc->getTraceAsString();
}