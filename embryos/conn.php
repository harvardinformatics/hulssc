<?php
// set database server access variables:
$host = getenv('EMBRYO_HOSTNAME');
$user = getenv('EMBRYO_USERNAME');
$pass = getenv('EMBRYO_PASSWORD');
$db = getenv('EMBRYO_DATABASE');

// open connection
$connection = mysql_connect($host, $user, $pass) or die ("Unable to connect!");

// If all is well so far we select the database to work with
$my_select_db = mysql_select_db($db, $connection);

// If we can't select the database we want, we need to know
if (!$my_select_db) {
   echo 'Could not select database';
   exit;
}
?>