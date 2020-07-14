<!DOCTYPE html>
<html>

<head>
    <title>Download Master from git and install</title>
    <style>


		body {
			background-color: lightseagreen;
		}

		.button {

			background-color: limegreen;
			/* Green */
			border: none;
			color: black;
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;

		}
	</style>
</head>

<body>
    <h1>Update or Install...</h1>
    <?php
    /**
     * From: https://stackoverflow.com/questions/3060125/can-i-use-file-get-contents-to-compare-two-files
     */
    function files_are_equal($a, $b)
    {
        // Check if filesize is different
        if (@filesize($a) !== @filesize($b))
            return false;

        // Check if content is different
        $ah = fopen($a, 'rb');
        $bh = fopen($b, 'rb');

        $result = true;
        while (!feof($ah)) {
            if (fread($ah, 8192) != fread($bh, 8192)) {
                $result = false;
                break;
            }
        }

        fclose($ah);
        fclose($bh);

        return $result;
    }

    $doitnow = isset($_GET["domove"]);
    $token = $_GET["token"] ?? date("Ymd_His");
    $zipfilename = "tmp/inst" . $token . "B.zip";

    if (!file_exists("tmp")) {
        mkdir("tmp");
    }
    $folderprevious = "./tmp/previous_" . $token . "/";
    if (!file_exists($folderprevious)) {
        mkdir($folderprevious);
    }

    
$files2update =  array("setupinstall.php", "viewlog.php", "menu.php",
 "info.php", "util.php", "cam.php", "index.php", "nopic.jpg", "devbackup.php", 
 "choosedate.php", "zipdelete.php", "setzoom.php", "archive.php", "zipcurrent.php", "arial.ttf", "viewgiphy.php",
 "GIFEncoder.class.php", "viewgifs.php", "updatefromgit.php", "LICENSE", "bg1.jpg", "README.md"); 

 //   $files2update =  array("setupinstall.php", "viewlog.php", "menu.php");

    if ($doitnow === FALSE) {
        $tmp = file_get_contents("https://github.com/marcconrad/myCCTV/archive/master.zip?time=" . time() . "");
        echo "Received from github: " . strlen($tmp) . " bytes.<p>";
        if (false === file_put_contents($zipfilename, $tmp)) {
            die("Cannot save zip file. Exiting.</body></html>");
        }
        echo 'Zip file saved as <a href="' . $zipfilename . '">' . $zipfilename . '</a><p>';

        $zip = new ZipArchive;


        if ($zip->open($zipfilename) === true) {
            $zip->extractTo("tmp/");
            $zip->close();
        } else {
            echo "Unable to open $zipfilename. No update took place.<p>";
        }


        echo "<p><em>Updates will change as follow. Please review and prese Update when ok.</em></p>";
    }
    foreach ($files2update as $fn) {

        $newfn = "./tmp/myCCTV-master/" . $fn;
        $oldfn = $folderprevious . $fn;
        $currfn = './' . $fn;

        echo "<br><b>File = " . $fn . ":</b> ";
        if (!file_exists($newfn)) {
            echo "File not found on Git or deleted from tmp; no change.";
        } else if (files_are_equal($newfn, $currfn)) {
            echo "New and old file are the same. No change.";
        } else {
            if (!file_exists($currfn)) {
                echo "<br>Warning: the file $currfn cound not be found.<br>";
            } else {

                //  echo "Old version move to " . $oldfn . ". ";
                echo "<br>Old size = " . filesize($currfn) . " bytes.";
                if ($doitnow) {
                    rename($currfn, $oldfn);
                }
            }

            //  echo "Replace from " . $newfn . ". ";
            echo "<br>New size = " . filesize($newfn) . " bytes.";
            if ($doitnow) {
                rename($newfn, $currfn);
            }
        }
    }
    if ($doitnow === false) {
        echo '<p><a href="updatefromgit.php?domove=1&token=' . $token . '"><button class="button" >Update for Real</button></a>';
    }
    ?>
    <p>


        <h2><a href="index.php">Home</a></h2>
        <hr>Thank you. Good bye!<p>
</body>

</html>