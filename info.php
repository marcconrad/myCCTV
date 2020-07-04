    <?php
    include "util.php";
    if (isset($_GET["statusemoji"]) && isset($_GET["id"])) {
        $x = getLastInfo($_GET["id"]);
        echo '{ "emoji"  : "' . $x["emoji"] .  '" }';

        die();
    }

    ?>

    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

    <html>

    <head>
        <title>info.php - BLA</title>
    </head>

    <body>
        <?php

        $f = './img/old/d* ./zip';
        $f = './img/*';
        $handle = popen('/usr/bin/du -hs ' . $f, 'r');
        while (($buffer = fgets($handle, 4096)) !== false) {
            echo $buffer;
            echo "<br>";
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }

        $size = substr($size, 0, strpos($size, "\t"));
        pclose($handle);
        echo 'Directory: ' . $f . ' => Size: ' . $size;

        ?>
        /*
        echo "<h1>Zip Test...</h1>";
        var_dump($_GET);

        if(isset($_GET["ziptest"])) {
        echo "YES";
        $out = zipDateId("20200616", "4");
        echo "<p>out z=";
            var_dump($out);
            }

            if(isset($_GET["unziptest"])) {
            $out = unzipDateId("20200616", "4");
            echo "<p>out=";
                var_dump($out);
                }
                ?>
                <p>Thank you</p>
            </p>
            <p>
                <a href="info.php?ziptest=1">Zip folder</a>
            </p>
            <p>
                <a href="info.php?unziptest=1">Unzip folder</a>
            </p>
            <p>

                <a href="https://perisic.com/cam/img/old/d20200616/t401/">Go to Folder</a>
                <a href="https://perisic.com/cam/img/old/">Go img old to Folder</a>
            </p>
            <p>
                */
    </body>

    </html>