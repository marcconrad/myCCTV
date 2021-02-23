<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>Zip Contents</title>
</head>

<body>
    <h1>ZIP Current <?php echo (isset($_GET["delete"]) ? "(and Delete)" : "") ?></h1>
    <?php

    error_reporting(-1);
    // Get real path for our folder

    $myVarfileId = intval($_POST["id"] ?? $_GET["id"] ?? 99);

    $varfile = "./vars/cam" . $myVarfileId . ".php";
    $varfile_config = "./vars/server_config" . $myVarfileId . ".php"; 
    // $varfile_global = "./vars/cam99.php";

    // @include $varfile_global;

    @include_once $varfile;
@include_once $varfile_config; 
    include_once "./util.php";


    $myId = ($_GET["id"] ?? 0);
    $h = ($lastgallery[$myId] ?? false);
    if (!$h) {
        die("no last gallery");
    }

    if (($_GET["nonce"] ?? "x") !== ($lastgallery["nonce"] ?? "yy")) {
        die("unautorized access");
    }
    ?>

    <?php
    try {
        // var_dump($h); 
        // echo '<h1>HELLO</h1>'; 
        $files = array();
        foreach ($h as $bn) {
            $x = bn2file($bn);
            if ($x !== false) {
                $files[$bn] = realpath($x);
            }
        }
        // var_dump($files);
        
        $filesToDelete = array();
        $zipfoldername = "./zip/";
        if (!file_exists($zipfoldername)) {
            mkdir($zipfoldername, 0777, true);
        }

        $prefix = "zg";
        $nowdate = gmdate("Ymd-His");
        $zipname = $prefix . $nowdate . "z" . $myId . "z.zip";
        echo '<h1><a href="zip/' . $zipname . '">Download ' . $zipname . '</a></h1>';


        $zip = new ZipArchive();
        $zip->open($zipfoldername . $zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $added = 0;


        echo "Start zipping... ";

        foreach ($files as $name => $realpath) {
            if (file_exists($realpath)) {
                $zip->addFile($realpath, $name);
                // echo "added $realpath with name $name <p>"; 
                $added++;
                $filesToDelete[] = $realpath;
            } else {
                echo "did not found: $realpath with name $name <p>";
            }
        }



        // Zip archive will be created only after closing object
        echo "Total files added: " . $added;

        if ($added > 0) {
            set_error_handler("warning_handler", E_WARNING);
            $zip->close();
            restore_error_handler();
        }

      
    } catch (Exception $e) {
        echo "<h1>Error with zipping stuff: $e </h1>";
        die("Exiting");
    }

    $k = 0;
    if (isset($_GET["delete"])) {
        foreach ($filesToDelete as $file) {
            $k++;
            echo "<br>file to delete=$file";
            // DISABLED. No deleting at the moment.  unlink($file);
            echo "DELETE HAS BEEN DISABLED IN SOURCE";
        }
        echo "<p>$myId: $k files deleted<p>";
    } else {
        echo "<p>Thank you</p>";
    }
    function warning_handler($errno, $errstr)
    {
        echo "<p>Error in closing zipfile:<p>";
        var_dump($errno);
        var_dump($errstr);
        echo "<p>";
        die("Thank you. Exiting.");
    }

    ?>
    
</body>

</html>