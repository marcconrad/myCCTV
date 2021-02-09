<?php
echo "<!DOCTYPE HTML> <html> <head>";
echo "\r\n";
echo '<meta content="text/html;charset=utf-8" http-equiv="Content-Type">';
echo "\r\n";
// echo '<base href="https://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/" />'; // dirname($_SERVER['PHP_SELF']) must not be empty.

// var_dump($_SERVER); 
if ($_SERVER['SERVER_NAME'] === "localhost") {
    echo '<base href="http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/" />'; // dirname($_SERVER['PHP_SELF']) must not be empty.

} else {
    echo '<base href="https://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/" />'; // dirname($_SERVER['PHP_SELF']) must not be empty.
}
echo "\r\n";


echo "\r\n";
?>
<script>
    if (window.parent != window) {
        // setTimeout(function(){document.getElementById("homebutton").style.display = "none";}, 100);
    }
</script>
<style>
    body {
        background-color: rgb(222, 185, 63);
    }

    .container {
        position: relative;
        color: magenta;
    }

    .bottom-left {
        position: absolute;
        bottom: 8px;
        left: 16px;
        color: black;
    }

    .bottom-left-yellow {
        position: absolute;
        bottom: 9px;
        left: 17px;
        /* color: #FFD700; */
        color: <?php echo ($_GET["captioncolor"] ?? "#FFD700"); ?>;
        background-color: rgba(0, 0, 0, 0.3);
    }

    .button {
        background-color: red;
        /* Red */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .button2 {
        background-color: blue;
    }

    /* Blue */
    .button3 {
        background-color: magenta;
    }

    /* Blue */
    .button4 {
        background-color: #ba3913;
    }

    /* Blue */
    .button5 {
        background-color: #ac8348;
    }

    /* Blue */
</style>

<title>View Gifs</title>
<script>
    <?php
    if ($_SERVER['SERVER_NAME'] === "localhost") {
        echo "var httpx = 'http';";
    } else {
        echo "var httpx = 'https';";
    }
    ?>

    function copy2clipboard(str) {
        var copyText = document.getElementById(str);
        if (copyText.value.startsWith("/cam/")) {
            copyText.value = httpx + '://' + window.location.host + '' + copyText.value;
        }
        copyText.type = 'text';
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        copyText.type = 'hidden';
        // alert("Copied the text: " + copyText.value);

    }
</script>

</head>

<body>

    <?php

    include "./util.php";

    if (isset($_GET["listbydate"])) {
        $allgifs = glob("agifs/*/aa*.gif");
        // var_dump($allgifs); 

        $allbydate = array();
        foreach ($allgifs as $fn) {
            $parts = explode("/", $fn);
            $key = $parts[2] . "_" . $parts[1];
            $parts["fn"] = $fn;
            $allbydate[$key] = $parts;
        }
        // var_dump($allbydate); 
        if (isset($_GET["oldesfirst"])) {
            ksort($allbydate);
        } else {
            krsort($allbydate);
        }
        // var_dump($allbydate); 
        $from = $_GET["from"] ?? 1;
        $dx = ($_GET["dx"] ?? 12); 
        $textonly = $_GET["textonly"] ?? "no"; 
        $to = $from + $dx;
        $z = 1;

        foreach ($allbydate as $parts) {
            if ($z >= $from && $z < $to) {
                $concept = $parts[1];
                $bn = $parts[2];
                $ts = basename2timestamp($bn);
                $date = gmdate("d M 'y; H:i:s", $ts);
               
                if ($textonly == "yes") {
                    

                   //  echo "<b> $date </b> ";
                    echo '<a href="viewgifs.php?edit=1&concept=' . $concept . '" >' . $date. ' - '. ucfirst($concept)  . '</a>; ';
                    /*
                    echo '<a href="viewgifs.php?edit=1&concept=' . $concept . '" >' . ucfirst($concept) . ' (Edit)</a>; ';
                    echo '<a href="viewgifs.php?silent=1&concept=' . $concept . '" >' . ucfirst($concept) . ' (no Dates)</a>; ';
                    echo "\r\n <br>\r\n";
                    echo '<img width="320" height="240" src="' . $parts["fn"] . '" alt="' . $concept . '">';
                    */
                    echo "\r\n";
                    echo '<p>';
                } else {
                    echo '<a class="container" href="viewgifs.php?edit=1&concept=' . $concept . '"><img width=320 height=240  src="' . $parts["fn"] . '" alt="' . $concept . '">';
                    $str = $date . " - " . ucfirst($concept);
                    echo '<em class="bottom-left">' . $str . '</em>';
                    echo '<em class="bottom-left-yellow">' . $str . '</em>';
                    echo "</a>  \r\n";
                }
            }
            $z++;
        }
        echo "<hr>";
        echo '<a href="viewgifs.php?t=' . time() . '&listbydate=2&textonly='.$textonly.'&from=' . $to . '&dx=' . $dx . '"><button class="button4" id="morebutton">More</button></a> ';
        echo '<a href="viewgifs.php?t=' . time() . '&listbydate=2&textonly='.$textonly.'&from=' . ($from - $dx) . '&dx=' . $dx . '"><button class="button4" id="prevbutton">Previous</button></a> ';
        echo '<a href="viewgifs.php?t=' . time() . '&listbydate=2&textonly='.$textonly.'&from=1&dx=' . $dx . '"><button class="button4" id="prevbutton">First</button></a> ';
        echo '<a href="viewgifs.php?t=' . time() . '&listconcepts=1&id=4&a=1&oldestfirst=1&sortbyage=1' . $dx . '"><button class="button4" id="prevbutton">Concepts</button></a> ';
        echo '<a href="menu.php"><button class="button3" id="homebutton">Home</button></a><p>';
        die("Thank you very much!<p></body></html>");
    }
    if (isset($_GET["listconcepts"])) {
        $allgifs = glob("agifs/*/", GLOB_ONLYDIR);
        // var_dump($allgifs); 

        foreach ($allgifs as $dir) {
            $concept = explode("/", $dir)[1];
            echo '<a href="viewgifs.php?concept=' . $concept . '" >' . ucfirst($concept) . '</a>; ';
            echo '<a href="viewgifs.php?edit=1&concept=' . $concept . '" >' . ucfirst($concept) . ' (Edit)</a>; ';
            echo '<a href="viewgifs.php?silent=1&concept=' . $concept . '" >' . ucfirst($concept) . ' (no Dates)</a>; ';
            echo "\r\n";
            echo '<p>';
        }
        echo "<hr>";

        echo '<a href="menu.php"><button class="button9" id="homebutton">Home</button></a><p>';
        die("Thank you<p></body></html>");
    }

    if (isset($_GET["takeout"])) {
        $src = $_GET["takeout"];
        $new = str_replace("/aa", "/ax", $src);
        rename($src, $new);
        echo "Thank you.<p>";
        echo '<a href="viewgifs.php">Back</a>';
        die();
    }

    if (isset($_GET["takein"])) {
        $src = $_GET["takein"];
        $new = str_replace("/ax", "/aa", $src);
        rename($src, $new);
        echo "Thank you.<p>";
        echo '<a href="viewgifs.php">Back</a>';
        die();
    }

    $concept = $_GET["concept"] ?? "cat";
    $files = NULL;
    if ($concept == "cat") {
        if (isset($_GET["outtakes"])) {
            $files = array_merge(glob("agifs/ax*y*.gif"), glob("agifs/cat/ax*y*.gif"));
        } else {
            $files = array_merge(glob("agifs/aa*y*.gif"), glob("agifs/cat/aa*y*.gif"));
        }
    } else {
        if (isset($_GET["outtakes"])) {
            $files = glob("agifs/" . $concept . "/ax*y*.gif");
        } else {
            $files = glob("agifs/" . $concept . "/aa*y*.gif");
        }
    }

    $out = !isset($_GET["silent"]);
    $edit = isset($_GET["edit"]) || isset($_GET["outtakes"]);

    if ($out) {

        echo "<h1>Concept: " . ucfirst($concept) . "</h1>";

        if ($edit) {
            echo '<a href="index.php">Home</a> ';
            if (isset($_GET["outtakes"])) {
                echo '<a href="viewgifs.php?' . ($edit ? 'edit=1&' : '') . 'concept=' . $concept . '">Intakes</a> ';
            } else {
                echo '<a href="viewgifs.php?' . ($edit ? 'edit=1&' : '') . 'outtakes=1&concept=' . $concept . '">Outtakes</a> ';
            }
        }
        echo '<a href="viewgifs.php?silent=1&concept=' . $concept . '">Just ' . ucfirst($concept) . '</a> ';
    }
    // var_dump($files); 
    $txt = array();
    $orig = array();
    foreach ($files as $ag) {

        $a = basename($ag);
        $b = bn2bntd($a);


        $q = array_merge(glob("agifs/*" . $b . "*.txt"), glob("agifs/*/*" . $b . "*.txt"));


        $concepts = file_get_contents($q[0]);
        $txt[$ag] = $concepts;

        $r = array_merge(glob("agifs/*" . $b . "*.jpg"), glob("agifs/*/*" . $b . "*.jpg"));

        // $r = glob("agifs/*".$b."*.jpg"); 
        $orig[$ag] = $r[0];
    }




    $txt = array_reverse($txt, TRUE);

    $from = intval(($_GET["from"] ?? 0));
    $to = intval(($_GET["to"] ?? 16));
    $count = 0;
    if ($out) {
        if ($from > 0) {
            echo '<a href="viewgifs?' . (isset($_GET["outtakes"]) ? 'outtakes=1&' : '') . ($edit ? 'edit=1&' : '') . 'time=' . time() . '&concept=' . $concept . '&from=' . ($from - 16) . '&to=' . $from . '">More recent</a> ';
        }
        if ($to < count($txt)) {
            echo '<a href="viewgifs?' . (isset($_GET["outtakes"]) ? 'outtakes=1&' : '') . 'time=' . time() . '&concept=' . $concept . '&from=' . $to . '&to=' . ($to + 16) . '"> Older</a> ';
        }
    }
    echo "<p>";
    foreach ($txt as $key => $value) {

        if ($count >= $from && $count < $to) {
            if ($out) {
                echo "\r\n";
                $bn = basename($key);
                $d = basename2timestamp($bn);
                $u = preg_split("/[zxyw]/", $bn);
                $nframes = $u[3] ? intval($u[3]) : 0;
                echo "<p><hr><p>";
                echo "<b>" . gmdate("d M 'y; H:i:s", $d) . "</b>";
                if ($nframes > 0 && $out) {
                    echo " - <em>" . $nframes . " frames</em><p>";
                }
                echo "<table><tr>";
                echo "<td>";
            }
            echo '<img width="320" height="240" src="' . $key . '" alt="' . $value . '">';
            if ($out) {
                echo "\r\n";
                echo "</td>";
                echo "<td>";
                echo "&nbsp;";
                echo '<img width="320" height="240" src="' . $orig[$key] . '" alt="Original Source Picture">';
                echo "</td>";
                if ($edit) {
                    echo "<td>";
                    echo "&nbsp;";
                    echo "<table>";
                    echo "<tr><td>";
                    echo '<button class="button button4" onclick="copy2clipboard(\'loc' . $count . '\' )">Copy Image Location</button>';
                    echo "</td><tr><td>";
                    echo '<button class="button button2" onclick="copy2clipboard(\'tag' . $count . '\')">Copy tags</button>';
                    echo "</td><tr><td>";
                    echo "\r\n";
                    if (isset($_GET["outtakes"])) {
                        echo '<button class="button button3" onclick="document.location = \'viewgifs.php?takein=' . $key . '\'">Take In</button>';
                    } else {
                        echo '<button class="button button1" onclick="document.location = \'viewgifs.php?takeout=' . $key . '\'">Take Out</button>';
                    }
                    echo "\r\n";
                    echo "<input  type=\"hidden\" value=\"" . $value . "\" id=\"tag" . $count . "\">";
                    echo "<input  type=\"hidden\" value=\"/cam/" . $key . "\" id=\"loc" . $count . "\">";
                    echo '</td></td>';
                    echo "</table>";
                    echo "</td>";
                }

                echo "</tr></table>";
                if ($edit) {
                    echo "<br><h2> $value </h2>";
                }
            }
        }
        $count++;
    }


    echo "<p><hr><p>";

    if ($edit) {
        echo '<a href="index.php">Home</a> ';
        echo '<a href="viewgifs.php?outtakes=1">Outtakes</a> ';
    }
    if ($out) {
        echo '<a href="viewgifs.php?silent=1&concept=' . $concept . '">Just ' . ucfirst($concept) . '</a> ';
    } else {
        // echo '<a href="viewgifs.php?showdate=1">Cats and Dates</a> ';
        echo '<a href="viewgifs.php?showdate=1&concept=' . $concept . '">' . ucfirst($concept) . ' and Dates (back)</a> ';
    }


    ?>
    <p>
        <a href="index.php">Home</a>
    </p>
</body>

</html>