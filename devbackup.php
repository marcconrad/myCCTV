<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Zip Contents</title>
</head>
<body>
<h1>Backup dev files</h1>

<?php
// Get real path for our folder
$myId = 22; // intval(substr($_GET['id'],0,1));
$imgfoldername = "./"; // zip recursively everything. 

$zipfoldername = "./zip/"; 
if (!file_exists($zipfoldername)) {
      mkdir($zipfoldername, 0777, true);
   }
	 
$rootPath = realpath($imgfoldername);
$prefix = "zip/bupdev";
$nowdate = gmdate("YmdHis");
$count = 20; 

$zipname= $prefix.$nowdate."ZIPB".$count."z".$myId."z.zip";

echo $zipname; 



// Initialize empty "delete list"
$filesToDelete = array();

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

// Initialize archive object




echo "Start zipping....<br>";

$zip = new ZipArchive();
$zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$added = 0; 

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
				
				

        // Add current file to archive
				if( strpos($relativePath, "zip/" ) !== false  ||
				    strpos($relativePath, "img/" ) !== false ||
						strpos($relativePath, "agifs_" ) !== false ||
						strpos($relativePath, "log/" ) !== false ||
						strpos($relativePath, "agifs/" ) !== false ||
					  strpos($relativePath, "archive/" ) !== false ||
				    strpos($relativePath, "tmp/" ) !== false ) { 
						// echo "...ignored;"; 
				} else 	if( strpos($relativePath, "zip\\" ) !== false  ||
                strpos($relativePath, "img\\" ) !== false ||
                    strpos($relativePath, "agifs_" ) !== false ||
                    strpos($relativePath, "log\\" ) !== false ||
                    strpos($relativePath, "agifs\\" ) !== false ||
                  strpos($relativePath, "archive\\" ) !== false ||
                strpos($relativePath, "tmp\\" ) !== false ) { 
                    // echo "...ignored;"; 
            } else{ 
            $zip->addFile($filePath, $relativePath);
            $added++;
						
						echo "file: ".$relativePath; 
				   //  echo "file path=".$filePath; 
						echo "...added;";
						echo "<br>"; 
						}
			
			//	echo "File added to zip"; 
        // Add current file to "delete list"
        // delete it later cause ZipArchive create archive only after calling close function and ZipArchive lock files until archive created)
       // if ($file->getFilename() != 'important.txt')
        //{
         //   $filesToDelete[] = $filePath;
        //}
    }
}

// Zip archive will be created only after closing object
echo "total files added: ".$added; 

if( $added > 0 ) {
  $zip->close();
}

// Delete all files from "delete list"
//foreach ($filesToDelete as $file)
//{
 //  DO NOT DELTE ANYTHING unlink($file); 
//}


?>
<p><a href="http://perisic.com/cam/">Home</a><p>
<a href="https://www.perisic.com/cam/zip/">Goto Zip File</a><p>
</body>
</html>
