<!DOCTYPE HTML>

<html>

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Choose Date</title>


    <?php


    include "./util.php";
    $bgcolor = id2color(($_GET["id"] ?? 17), "hexbg");
    echo "<style> body { background-color: $bgcolor; } </style>";

    ?>
    <style>
        /**
https://leaverou.github.io/css3patterns/
 */


        .button:hover {
            background-color: yellow;
            color: #020305;
        }

        .button {
            border-radius: 12px;
            background-color: #00ddf9;
            color: #020305;
            min-width: 120px;
            padding: 0px 10px;
            z-index: 1;
        }
        .datebutton { 
            width:150px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
        }
    </style>
    <script>
    if (window.parent != window) {
            setTimeout(function() {
                if ((x = document.getElementById("homebutton")) != null) {
                    x.style.display = "none";
                }
            }, 100);
    }
    </script>
</head>

<body>
    <?php

    error_reporting(-1);

  


    $myId = ($_GET["id"] ?? 0);

    $zipthisday = $_GET["zipthisday"] ?? false;
    if ($zipthisday !== false) {
        $ret = zipDateId($zipthisday, $myId);
        echo "<p>$zipthisday has been archived for Camera $myId with result: $ret </p>";
    }

    $unzipthisday = $_GET["unzipthisday"] ?? false;
    if ($unzipthisday !== false) {
        $ret = unzipDateId($unzipthisday, $myId);
        echo "<p>$unzipthisday has been un-archived for Camera $myId with result: $ret </p>";
    }

    $bunzip = $_GET["bulkunzip"] ?? false; 
    if( $bunzip ) { 
        $d = explode("X", $bunzip); 
        foreach($d as $a) { 
            $ret = unzipDateId($a, $myId);
            echo "<p>$a has been un-archived for Camera $myId with result: $ret </p>";
        }
    }
    echo "Working on it...\n"; 
   
    flush();
    

    $x = glob("img/old/d*/t" . $myId . "??");

    // var_dump($x);

    if( sizeof($x) == 0 ) { 
        echo "<p>Nothing found for id=$myId. </p> "; 
    }

    $available_dates = array();
    foreach ($x as $ad) {
        $d = explode("img/old/", $ad)[1];
        $yyyy = substr($d, 1, 4);
        $mm = substr($d, 5, 2);
        $dd = substr($d, 7, 2);

        // echo "d=$d <br> $yyyy $mm $dd <br>"; 
        $t = strtotime($yyyy . "-" . $mm . "-" . $dd);
        $dt = date("d M Y", $t);
        $idx = $yyyy . $mm . $dd;
        //  $bns = $available_dates[$idx][2] ?? array();
        // $bns[] = $d;
        //	echo "added $d to $idx <br>"; 
    
      //  echo "Aidx=$idx t= $t<br>";
        $available_dates[$idx] = array(date("D, d M Y", $t), date("N", $t), $t);
    }
    // var_dump($available_dates); 
    if (isset($_GET["archive7"])) {
        $max = ($_GET["howmanydaysback"] ?? 30 ); // one month back 
        for ($i = 2; $i < $max; $i++) {
            archive_day($myId, 0 - $i);
        }

        // $archiveThese = array();
        $tNow = localtimeCam($myId);
        foreach ($available_dates as $key => $value) {
            $t = localtimeCam($myId, $value[2]);
            if ($tNow - $t > 8 * 24 * 60 * 60) { // 8 days = last 7 days 
                // $archiveThese[] = $key; 
                $ret = zipDateId($key, $myId);
                echo "<p>$key has been archived for Camera $myId with result: $ret </p>";
              
                flush();
            } else { 
                echo "<p>$key has not been archived for Camera $myId (too recent)</p>";
               
                flush();
            }
        }
        // var_dump($archiveThese);
        if(isset($_GET["autoarchive"]) && $myId < 13 ) { 
            echo "<p> Please wait...      (" . ($_GET["cc"] ?? 0) . ")</p>";
            echo " <script> ";
            echo "setTimeout(function(){ window.location = 'choosedate.php?cc=" . 
                (($_GET["cc"] ?? 0) + 1) . "&autoarchive=" . $_GET["autoarchive"] . 
                "&archive7=" . $_GET["archive7"] .
                "&howmanydaysback=" . $max .
                "&t=" . time() . "&id=" . ($myId + 1). "' }, 5000);";
            echo " </script> </body></html>";
            die(); 
        }
        echo '<p><a href=choosedate.php?t=' . time() . '&id=' . $myId . '&howmany=1 ><button class="button">Back</button></a></p>';
        echo '</body></html>';
        die();
    }

    $x = glob("img/" . $myId . "??/aa*.jpg");
    // echo "<p>There are " . count($x) . " images available as of today and yesterday. </p>";
    // var_dump($x); 
    foreach ($x as $ad) {
        $d = basename($ad);
        $yyyy = substr($d, 2, 4);
        $mm = substr($d, 6, 2);
        $dd = substr($d, 8, 2);

        $t = strtotime($yyyy . "-" . $mm . "-" . $dd);
        $dt = date("d M Y", $t);

        $idx = $yyyy . $mm . $dd;
        // $bns = $available_dates[$idx][2] ?? array();
        // $bns[] = $d;
        //	echo "added $d to $idx <br>"; 
       // echo "Bidx=$idx t= $t<br>";
        $available_dates[$idx] = array(date("D, d M Y", $t), date("N", $t), false);
    }

    krsort($available_dates);

    // var_dump($available_dates); 
    echo "<h1>Camera " . $myId . "</h1>";
    echo "<h2>Click on any of the dates below to show them.</h2>";
    $lastN = 0;
    foreach ($available_dates as $key => $value) {
        // echo $key."-".$value."<br>";		
       
        /*
        $i = $value[1] +1 % 7; 
        while ($i > $lastN) { 
            echo '<button class="datebutton" style=background-color: black >' . $i . '</button>';
            $i--; 
        }
        */
        $color = id2color($value[1], "hex");
        // echo $color; 
        if ($value[1] > $lastN) {
            echo '<br>';
        } 
        /*
        if($lastN == 0 ) { $lastN = 7; }
        while ($lastN > $value[1] + 1) { 
            $lastN--; 
            echo '<button class="datebutton" style=background-color: black >' . $lastN . '</button>';
        }
        echo $value[1]; echo " lastN=$lastN"; 	
        
        */
        $lastN = $value[1];
        echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&day=' . $key . '&howmany=' . ($_GET["howmany"] ?? 18) . '"><button class="datebutton" style=background-color:' . $color . ' >' . $value[0] . '</button></a> ';
    }
    echo "<h2>Click on any of the dates below to archive them</h2>";
    foreach ($available_dates as $key => $value) {
        if ($value[2] !== false) {
            // echo $key."-".$value."<br>";			
            $color = id2color($value[1], "hex");
            // echo $color; 
            if ($value[1] > $lastN) {
                echo '<br>';
            }
            $lastN = $value[1];
            echo '<a href="choosedate.php?time=' . time() . '&id=' . $myId . '&zipthisday=' . $key . '&howmany=2"><button class="datebutton" style=background-color:' . $color . ' >' . $value[0] . '</button></a> ';
        }
    }
    echo "<h2>The following dates are available; but archived. Click to un-archive them.</h2>";
    $x = glob("img/old/d*xt" . $myId . "??x.zip");

    // var_dump($x); 

    $available_dates_zipped = array();
    foreach ($x as $ad) {
        $d = explode("img/old/", $ad)[1];
        $yyyy = substr($d, 1, 4);
        $mm = substr($d, 5, 2);
        $dd = substr($d, 7, 2);

        // echo "d=$d <br> $yyyy $mm $dd <br>"; 
        $t = strtotime($yyyy . "-" . $mm . "-" . $dd);
        $dt = date("d M Y", $t);
        $idx = $yyyy . $mm . $dd;
        // $bns = $available_dates_zipped[$idx][2] ?? array();
        // $bns[] = $d;
        //	echo "added $d to $idx <br>"; 
        $available_dates_zipped[$yyyy . $mm . $dd] = array(date("D, d M Y", $t), intval(date("N", $t)));
    }
    // var_dump($available_dates_zipped);
    krsort($available_dates_zipped);
    $out = ""; 
    $line = ""; 
    $weeks = array(); 
    $w = array(); 
    foreach ($available_dates_zipped as $key => $value) {
        // echo $key."-".$value."<br>";			
        $color = id2color($value[1], "hex");
        // echo $color; 
        
        if ($value[1] > $lastN) {
            $weeks[] = $w; 
            $w = array(); 
            $line .= '<br>';
        }
        $w[$key] = $value; 
        $lastN = $value[1];
        $line .= '<a href="choosedate.php?time=' . time() . '&id=' . $myId . '&unzipthisday=' . $key . '&howmany=2"><button class="datebutton" style=background-color:' . $color . ' >' . $value[0] . '</button></a> ';
    }
    $weeks[] = $w; 
    $out = $out.$line; 
    echo $out; 
   echo '</p><p>'; 
    // var_dump($weeks); 

    foreach($weeks as $w) { 
        if(count($w) > 0 ) { 
        ksort($w); 
        $ws = implode("X", array_keys($w));
        $txt = "From ".reset($w)[0]." to ". end($w)[0]; 

        echo '<a href="choosedate.php?time=' . time() . '&howmany=1&id=' . $myId . '&bulkunzip='.$ws.'" > <button class="button">'.$txt.'</button></a></p>';
        } 
    }
    echo "<h2>Other Actions</h2>";
    echo '<p><a href="choosedate.php?time=' . time() . '&howmany=1&id=' . $myId . '&archive7=1"> <button class="button">Archive recent dates older than 7 days.</button></a></p>';
    echo '<p><a href="choosedate.php?time=' . time() . '&howmany=1&id=' . $myId . '&archive7=1&howmanydaysback=3660"> <button class="button">Archive everything (10yrs back) older than 7 days.</button></a></p>';
   
   ?>
    <p><a href="index.php"><button class="button" id="homebutton">Home</button></a></p>

</body>

</html>