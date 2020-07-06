<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>Zip Contents</title>
</head>

<body>
    <h1>ZIP and Delete</h1>
    <a href="index.php">Home</a>
    <p>
        <p>
            <a href="zip/">Goto Zip Folder</a>
            <p>
                <?php

                error_reporting(-1);
                // Get real path for our folder
                include "./vars/allcams.php";
                $myIds = array();
                $merge = isset($_GET["merge"]);
                $zip = NULL;
                $zipname = NULL;
            


                if (isset($_GET["buckets"])) {
                    $myIds = array();
                    for ($i = 0; $i < 99; $i++) { // explode("b",$_GET["buckets"]); 
                        if (file_exists("img/" . (100 * $_GET['id'] + $i) . '/')) {
                            $myIds[] = 100 * $_GET['id'] + $i;
                        }
                    }
                } else if (isset($_GET["id"])) {
                    $myIds = array(intval(substr($_GET['id'], 0, 1)));
                } else if (isset($_GET['homepage'])) {
                    $myIds = array("H");
                } else if (isset($_GET['tmp'])) {
                    $myIds = array("T");;
                } else {
                    $myIds = array("Q");;
                }

                var_dump($myIds);

                try {

                    $zipfoldername = "./zip/";
                    if (!file_exists($zipfoldername)) {
                        mkdir($zipfoldername, 0777, true);
                    }


                    if ($merge) {
                        // $rootPath = realpath($imgfoldername);
                        $prefix = "am";
                        $nowdate = gmdate("YmdHis");
                        $count = 17;
                        $zipname = $prefix . $nowdate . "ZIPM" . $count . "z" . ($_GET["id"] ?? "X") . "z.zip";
                        echo '<h1><a href="zip/' . $zipname . '">Download ' . $zipname . '</a></h1>';

                        $zip = new ZipArchive();
                        $zip->open($zipfoldername . $zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                        $added = 0;
                    }
                    $filesToDelete = array();

                    foreach ($myIds as $myId) {
                        $imgfoldername = "./img/" . $myId . "/";
                        if (isset($_GET['homepage'])) {
                            $imgfoldername = "./img/last/";
                            $myId = 'H';
                        }
                        if (isset($_GET['tmp'])) {
                            $imgfoldername = "./tmp/";
                            $myId = 'T';
                        }

                        $rootPath = realpath($imgfoldername);
                        $prefix = "aa";
                        $nowdate = gmdate("YmdHis");
                        $count = 81;

                        echo "<p>rootPath=" . $rootPath . "<p>";

                        if ($rootPath) {
                            // Initialize empty "delete list"


                            // Create recursive directory iterator
                            /** @var SplFileInfo[] $files */
                            $files = new RecursiveIteratorIterator(
                                new RecursiveDirectoryIterator($rootPath),
                                RecursiveIteratorIterator::LEAVES_ONLY
                            );

                            // Initialize archive object




                            echo "$myId - Start zipping....";

                            if (!$merge) {
                                $zipname = $prefix . $nowdate . "ZIPA" . $count . "z" . $myId . "z.zip";
                                echo '<h1><a href="zip/' . $zipname . '">Download ' . $zipname . '</a></h1>';

                                $zip = new ZipArchive();
                                $zip->open($zipfoldername . $zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                                $added = 0;
                            }
                            foreach ($files as $name => $file) {
                                // Skip directories (they would be added automatically)
                                if (!$file->isDir()) {
                                    // Get real and relative path for current file
                                    $filePath = $file->getRealPath();
                                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                                    // Add current file to archive
                                    $zip->addFile($filePath, $relativePath);
                                    $added++;
                                    //	echo "File added to zip"; 
                                    // Add current file to "delete list"
                                    // delete it later cause ZipArchive create archive only after calling close function and ZipArchive lock files until archive created)
                                    if ($file->getFilename() != 'important.txt') {
                                        $filesToDelete[] = $filePath;
                                    }
                                }
                            }

                            // Zip archive will be created only after closing object
                            echo "total files added to delete: " . $added;

                            if ($added > 0 && !$merge) {
                                set_error_handler("warning_handlerA", E_WARNING);
                                $zip->close();
                                restore_error_handler();
                            }

                            // Delete all files from "delete list"



                        } else {
                            echo "Something happened; nothing done.";
                            var_dump($_GET);
                            die();
                        }
                    }

                    if ($added == 0) {
                        echo "<p>" . implode(',', $myIds) . ": - no images to zip; nothing done.";
                        error_reporting(0);
                        die();
                    }



                    if ($added > 0 && $merge) {
                        set_error_handler("warning_handlerA", E_WARNING);
                        $zip->close();
                        restore_error_handler();
                    }
                } catch (Exception $e) {
                    echo "<h1>Error with zipping stuff: $e </h1>";
                    die("Exiting");
                }

                if (isset($_GET["delete"])) {
                    foreach ($filesToDelete as $file) {
                        unlink($file);
                    }
                } else {
                    echo "<p>$myId - zip only, no delete<p>";
                }



                if (isset($_GET["delete"])) {
                    foreach ($myIds as $x) {
                        @rmdir("img/" . $x . "/");
                    }
                }
                if (isset($_GET['tmp'])) { 
                    echo "<p>Deleting empty folders...";
                    $dirs = glob("./tmp/*"); 
                    // var_dump($dirs); 
                    foreach($dirs as $d ) { 
                        if(is_dir($d)) { 
                            @rmdir($d); 
                        }
                    }
                }
                echo '<h1><a href="zip/' . $zipname . '">Download ' . $zipname . '</a></h1>';


                function warning_handlerA($errno, $errstr)
                {
                    echo "<p>Error in closing zipfile:<p>";
                    var_dump($errno);
                    var_dump($errstr);
                    echo "<p>";
                    die("Thank you. Exiting.");
                }
                ?>
                <hr>
                <a href="index.php">Home</a></h1>
</body>

</html>