<?php
$file_name = 'log.txt';


$file_contents =  file_get_contents( $file_name );

echo "<pre style='word-wrap: break-word; white-space: pre-wrap;'>". $file_contents . "</pre>";