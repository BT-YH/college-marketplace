<?php

// in php variables start with dollar sign
// php comes close to Perl in syntax

// global variables
$server="cray.cs.gettysburg.edu"; 
$dbase="f23_4";
$user="tangyi02";
$passw="tangyi02";

try {
  $db=new PDO("mysql:host=$server;dbname=$dbase", $user, $passw);
  // <H1> is the largest heading in html
  // anything with angle-brackets is called a tag 
  // print "<H1>Successfully connected to database</H1>\n";
  // print ".";
}
catch (PDOException $e) { // -> similar to c++, like . in java
  die("Error connecting to database" . $e->getMessage());
  // . in php is the string concat operator
}

?>
