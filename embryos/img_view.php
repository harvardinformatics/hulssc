<?php
include('conn.php');

if (isset($_GET['id']))
{
    $id = intval($_GET['id']);
	$fldData = 'img_'.$_GET['which'];

    $result = mysql_query("SELECT $fldData FROM embryos WHERE id= $id LIMIT 1");

    if (mysql_num_rows($result) == 0)
        die('no image');

    list($data) = mysql_fetch_row($result);

    // outputing HTTP headers
    header('Content-Length: '.strlen($data));
    header('Content-type: image/jpeg');

    // outputing image
    echo $data;
    exit();
}
?>