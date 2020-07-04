<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Move to Archive</title>
</head>
<body>
<p>

<?php 

error_reporting(-1);

$archivefoldername = "./archive/"; 
if (!file_exists($archivefoldername)) {
      mkdir($archivefoldername, 0777, true);
   }
$nowdate = gmdate("YmdHis");

rename ("./zip/", "./archive/zip_".$nowdate."/"); 

?>	 
</p>
Thank you. <p>
<a href="./archive/">Go to Archive</a>, <a href="index.php">Home</a>
</body>
</html>
