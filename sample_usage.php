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
 * $sourceToZip = '.'; // Use a dot to zip all files and folders in current directory
 */
//$sourceToZip = 'jc_folder';
//$destinationOfZip = 'ZIP';

// Process zip
//$zip->createZip( $sourceToZip , $destinationOfZip );

// Extract zip
//$source = "ZIP/jc_folder.zip";
//$destination = "UNZIP";
//
//$zip->extractZip( $source, $destination );