<?php
##################################################
# Created by Abdul Ibrahim
# Jul 25, 2015 9:15:44 PM
# website http://www.abdulibrahim.com/
##################################################
include 'parse.class.php';
include 'curl.class.php';
$parse = new Parse() ;
$curl = new Curl() ;

$url = 'http://abdulibrahim.com';
$curl->getFile($url);
$file = $curl->file;

 



