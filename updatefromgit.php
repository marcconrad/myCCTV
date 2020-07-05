<!DOCTYPE html>
<html>

<head>
    <title>Download Master from git and install</title>
</head>

<body>
    <h1>Update or Install...</h1>
    <?php
    $tmp = file_get_contents("https://github.com/marcconrad/myCCTV/archive/master.zip");
    echo "Received from github: " . strlen($tmp) . " bytes.<p>";
    $zipfilename = "tmp/inst" . time() . "B.zip";
    if (!file_exists("tmp")) {
        mkdir("tmp");
    }
    /*
$files2update =  = array("setupinstall.php", "viewlog.php", "menu.php",
 "info.php", "util.php", "cam.php", "index.php", "nopic.jpg", "devbackup.php", 
 "choosedate.php", "zipdelete.php", "setzoom.php", "archive.php", "zipcurrent.php", "arial.ttf", 
 "GIFEncoder.class.php", "viewgifs.php", "updatefromgit.php", "LICENCSE"); 
*/
    $files2update =  array("setupinstall.php", "viewlog.php", "menu.php");


    if (false === file_put_contents($zipfilename, $tmp)) {
        die("Cannot save zip file. Exiting.</body></html>");
    };
    echo 'Zip file saved as <a href="' . $zipfilename . '">' . $zipfilename . '</a><p>';
    echo "Version control goes here (to do)<p>";
    $zip = new ZipArchive;
    $folderprevious = "./tmp/previous_" . date("Ymd_His") . "/";
    if (!file_exists($folderprevious)) {
        mkdir($folderprevious);
    }

    if ($zip->open($zipfilename) === true) {
        $zip->extractTo("tmp/");
        foreach ($files2update as $fn) {

            $newfn = "./tmp/myCCTV-master/" . $fn;
            $oldfn = $folderprevious . $fn;
            $currfn = './' . $fn;
            if (!file_exists($newfn)) {
                echo "The file $newfn does not exist on github. No change.<br>;";
            } else {
                if (!file_exists($currfn)) {
                    echo "The file $currfn cound not be found.<br>;";
                } else {
                    rename($currfn, $oldfn);
                    echo "Old version in " . $oldfn . ". ";
                    echo "Size = " . filesize($oldfn) . " bytes<br>";
                }
                rename($newfn, $currfn);
                echo "Replaced " . $currfn . ". ";
                echo "Size = " . filesize($currfn) . " bytes<br>";
            }
        }
        $zip->close();
    } else {
        echo "Unable to open $zipfilename. No update took place.<p>";
    }

    ?>
    <p>
        <h2><a href="index.php">Home</a></h2>
        <hr>Thank you. Good bye!<p>
</body>

</html>