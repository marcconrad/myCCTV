<?php
echo "<!DOCTYPE HTML> <html> <head>";
echo "\r\n";
echo '<meta content="text/html;charset=utf-8" http-equiv="Content-Type">';
echo "\r\n";
echo '<base href="https://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/" />'; // dirname($_SERVER['PHP_SELF']) must not be empty.
echo "\r\n";
?>
<script>
    
    if( window.parent != window) { 
      // setTimeout(function(){document.getElementById("homebutton").style.display = "none";}, 100);
    }

</script>
<style>
    body {
        background-color: rgb(222, 185, 63);
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
        if($_SERVER['SERVER_NAME'] === "localhost") { 
            echo "var httpx = 'http';";
        } else { 
            echo "var httpx = 'https';";
        }
        ?>

    function copy2clipboard(str) {
        var copyText = document.getElementById(str);
        if(copyText.value.startsWith("/cam/")) { copyText.value = httpx+'://' + window.location.host +'' + copyText.value; }
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

    if (isset($_GET["listconcepts"])) {
        $dirs = glob("agifs/*/", GLOB_ONLYDIR);
        // var_dump($dirs); 

        foreach ($dirs as $dir) {
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

        echo "<h1>" . ucfirst($concept) . "s</h1>";

        if ($edit) {
            echo '<a href="index.php">Home</a> ';
            if (isset($_GET["outtakes"])) {
                echo '<a href="viewgifs.php?' . ($edit ? 'edit=1&' : '') . 'concept=' . $concept . '">Intakes</a> ';
            } else {
                echo '<a href="viewgifs.php?' . ($edit ? 'edit=1&' : '') . 'outtakes=1&concept=' . $concept . '">Outtakes</a> ';
            }
        }
        echo '<a href="viewgifs.php?silent=1&concept=' . $concept . '">Just ' . ucfirst($concept) . 's</a> ';
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
        echo '<a href="viewgifs.php?silent=1">Just Cats</a> ';
    } else {
        // echo '<a href="viewgifs.php?showdate=1">Cats and Dates</a> ';
        echo '<a href="viewgifs.php?showdate=1&concept=' . $concept . '">' . ucfirst($concept) . 's and Dates (back)</a> ';
    }


    ?>

</body>

</html>