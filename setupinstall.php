<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Install</title>
</head>
<body>
<h1>Hello</h1>
<?php
$myfiles = array("setupinstall.php", "clock.php", "updatefromgit.php","viewlog.php", "menu.php", "info.php", "util.php", "cam.php", "index.php", "nopic.jpg", "devbackup.php", "choosedate.php", "zipdelete.php", "setzoom.php", "archive.php", "zipcurrent.php", "arial.ttf", "GIFEncoder.class.php", "viewgifs.php"); 
$outputfile = "install.php"; 

// echo basename(__FILE__);
$delimiter = "zzzzabc"."zzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz";

if( basename(__FILE__) == "setupinstall.php" ) {  
echo '<h1>Generate install.txt</h1>'; 
$out = "";  
foreach($myfiles as $f ) { 
   $contents = file_get_contents($f);
	 if(pathinfo($f, PATHINFO_EXTENSION) != 'php' ) { $contents = base64_encode($contents); } 
	 $out .= $contents; 
	 $out .= $delimiter; 
	 }
echo '<p>'; 
// echo $out; 
$zip = new ZipArchive();
$filename = "./mycctv.zip";

if(file_exists($filename) ) { 
    @unlink($filename); 
}

if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
}

$zip->addFromString("install.php" , $out );
echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";
$zip->close();
echo '<a href="'.$filename.'">Download Zip file</a>'; 
echo '<p>'; 


echo '<a href="install.txt">Download install.txt</a><p>'; 
file_put_contents("install.txt", $out); 
echo "After downloading, rename install.txt to install.php, then upload to your server and run. Thank you."; 
echo "</body></html>";
die(); 	 
	 
} else if( basename(__FILE__) == "install.php" ) { 
  $in = file_get_contents("install.php");
	$parts = explode($delimiter, $in); 
	// var_dump($parts); 
	$i=0; 
	foreach($myfiles as $f ) { 
   $contents = $parts[$i++]; 
	 if(pathinfo($f, PATHINFO_EXTENSION) != 'php' ) { $contents = base64_decode($contents); } 
	 $filename = $f;
	 if( file_exists($filename) && !isset($_GET["overwriteall"])) { 
		echo("The file ".$filename." is already installed.<p>"); 
	   }
	 else { 
	  file_put_contents($filename, $contents);
		echo("The file ".$filename." <b>has</b> been installed.<p>"); 
		}
	 }
	echo '<p><a href="index.php">START HERE</a></p>'; 
	die(); 
} 

?>


