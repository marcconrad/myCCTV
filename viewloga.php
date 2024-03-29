<!DOCTYPE html>
<html>

</html>

<head>
    <title>Log File Analysis</title>
    <style>
        body {
            background-color: rgb(252, 235, 173);

        }

        td,
        tr,
        img {
            padding: 0px;
            margin: 0px;
            border: none;
            overflow-y: visible;
        }

        table {
            border-collapse: collapse;
        }

        .outerdiv {
            height: 1px;
            overflow: visible;
        }

        .innerdiv {
            height: 150px;
            overflow: visible;
            font-size: 20px;
        }

        .theblackdot {
            filter: grayscale(1);
        }

        .thegreendot {
            filter: hue-rotate(-60deg);
        }
    </style>
</head>

<body>

    <?php

    $myId = $_GET["id"] ?? 2;
    $daysback = $_GET["daysback"] ?? 1;

    echo '<h1>Log of Cam: ' . $myId . '. Days back = ' . $daysback . '</h1>';
    echo '<a href="viewloga.php?id=' . $myId . '&daysback=1">1 day</a>; ';
    echo '<a href="viewloga.php?id=' . $myId . '&daysback=7">7 days</a>; ';
    echo '<a href="viewloga.php?id=' . $myId . '&daysback=31">31 days</a>; ';
    echo '<a href="viewloga.php?id=' . $myId . '&daysback=366">366 days</a>; ';
    echo '<a href="viewloga.php?id=' . $myId . '&daysback=4000">4000 days</a>; ';
    include_once "./util.php";
    $reddot = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAD0lEQVQI1wEEAPv/AMwAAAJoAM1NBPeuAAAAAElFTkSuQmCC";
    // $ff = @file_get_contents("red1x1.png");
    // $bb = base64_encode($ff);
    // $shareicon = '<img height="11em" src="data:image/png;base64,' . $reddot . '" alt="Share this" />';

    //   echo '<img height="1pt" width="100pt" src="' . $reddot . '" />';
    // echo "\r\n";

    function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
    function findClosest($arr, $n, $target)
    {
        // Corner cases
        if ($target <= $arr[0])
            return array(-1, 0);
        // return $arr[0];
        if ($target >= $arr[$n - 1])
            return array($n - 1, $n);
        // return $arr[$n - 1];

        // Doing binary search
        $i = 0;
        $j = $n;
        $mid = 0;
        while ($i < $j) {
            $mid = (int) (($i + $j) / 2);

            if ($arr[$mid] == $target)
                return array($mid, $mid);
            // return $arr[$mid];

            /* If target is less than array element,
            then search in left */
            if ($target < $arr[$mid]) {

                // If target is greater than previous
                // to mid, return closest of two
                if ($mid > 0 && $target > $arr[$mid - 1])
                    return array($mid - 1, $mid);
                // return getClosest($arr[$mid - 1], $arr[$mid], $target);

                /* Repeat for left half */
                $j = $mid;
            }

            // If target is greater than mid
            else {
                if (
                    $mid < $n - 1 &&
                    $target < $arr[$mid + 1]
                )
                    return array($mid, $mid + 1);
                //  return getClosest($arr[$mid], $arr[$mid + 1], $target);
                // update i
                $i = $mid + 1;
            }
        }

        // Only single element left after search
        return array($mid, $mid);
        // return $arr[$mid];
    }

    // Method to compare which one is the more close.
    // We find the closest by taking the difference
    // between the target and both values. It assumes
    // that val2 is greater than val1 and target lies
    // between these two.
    function getClosest($val1, $val2, $target)
    {
        if ($target - $val1 >= $val2 - $target)
            return $val2;
        else
            return $val1;
    }

    function getTimestampsFromFile($inFile)
    {
        if (file_exists($inFile) === false) {
            return false;
        }
        global $myId;
        global $totalbytes;
        $totalbytes += filesize(($inFile));
        $t = file($inFile);

        $previousLog = "none";
        foreach ($t as $a) {
            $parts = explode(" ", $a);
            $b = substr($a, 0, 15);
            $timezone = new DateTimeZone('UTC');
            $w = DateTime::createFromFormat("Ymd-His", $b, $timezone);

            if ($w === false) {
                // echo "<h2> false for " . $a . " </h2>";
                // retrieve previous file. 
                $aParts = explode('"', $a);
                // var_dump($aParts);
                $p = $aParts[1] ?? "does not exist";
                //  echo $p;
                $previousLog = "loga/" . $myId . "_timestamp/" . $p;
                if (file_exists($previousLog)) {
                    // echo "<br>Previous log found: " . $previousLog;
                } else {
                    echo "<h2>Invalid line found in logfile " . $inFile . ": " . $a . "</h2>";
                }
                // var_dump( $t ); 
            } else {
                $v = $w->getTimestamp();
                $timestamps2data[$v] = $parts;
                $available_timestamps[] = $v;
                
            }
        }
        return array($available_timestamps, $timestamps2data, $previousLog);
    }

    $totalbytes = 0;

    $tt = getTimestampsFromFile("loga/" . $myId . "_timestamp/__log.html");
    if ($tt === false) {
        echo "<h1>No log data available<h1></body></html>";
        die();
    }
    $available_timestamps = $tt[0];
    $timestamps2data = $tt[1];
    $previousLogFile = $tt[2];


    // var_dump($available_timestamps); 
    // var_dump($timestamps2data); 
    $nn = sizeof($available_timestamps);

    $ndays = $daysback;
    $timenow = localtimeCam($myId);
    $timeuntil = $timenow;
    $timestart = $timenow - $ndays * 60 * 60 * 24;

   // $timeuntil = $timenow - ($ndays - 1) * 60 * 60 * 24;
    // $timestart = $timenow - $ndays * 60 * 60 * 24;
    echo '<table id="maintable">';
    $previous_data_info = "not yet available";
    $previous_datapoint = 0;

    $gap = 10 * $ndays;
    $gapthreshold = 40 * $ndays;
    $zzz = 0;
   $lognow = false; // true; 

    for ($i = $timeuntil; $i > $timestart; $i = $i - $gap) {

       // echo "<h2> $i </h2>"; 
        $w = findClosest($available_timestamps, $nn, $i);
  if($lognow)  var_dump(array($w, $i, $nn)); 
        if ($w[0] == -1) {
           // $lognow = true; 
            $tt = getTimestampsFromFile($previousLogFile);
          //  var_dump($tt); 
        
            if ($tt === false) {
                echo "<tr><td> End of available data.</td></tr>";
                $i = $timestart; // to end the loop.
                break;
            } else {
                $available_timestamps = $tt[0];
                $timestamps2data = $tt[1];
                $previousLogFile = $tt[2];
                $w = findClosest($available_timestamps, $nn, $i);
               if($lognow)  var_dump(array($w, $i, $nn, $available_timestamps)); 
            }
        }
        /*
    echo "<h2>" . $i . " = " . date(DATE_RFC2822, $i) . "</h2>";
    
    var_dump($w);
    var_dump($timestamps2data[$available_timestamps[$w[0]]]);
    var_dump($timestamps2data[$available_timestamps[$w[1]]]);
    */

        $data_src_info = "ok";
        $datapoint = 0;
        $lower = $available_timestamps[$w[0]] ?? 0;
        if (abs($i - $lower) > $gapthreshold) {
          
            $lower = false;
        }
        $higher = $available_timestamps[$w[1]] ?? PHP_INT_MAX;
        if($lognow) { var_dump(array($i,$lower, $higher, $gapthreshold, $higher - $i)); }
        if (abs($higher - $i) > $gapthreshold) {
            $higher = false;
        }
       if($lognow) { var_dump(array($i,$lower, $higher, $gapthreshold)); }
        $dotclass = "d";
        if ($higher === false && $lower === false) {
            $data_src_info = "no data";
            $datapoint = 0;
        } else if ($higher === false) {
            $data_src_info = "lower only";
            $x = $timestamps2data[$lower];
            $datapoint = round($x[1]);
       
            $dotclass = "theblackdot";
        } else if ($lower === false) {
            $data_src_info = "higher only";
            $x = $timestamps2data[$higher];
            $datapoint = round($x[1]);
            $dotclass = "thegreendot";
            if( $lognow ) var_dump(array($x, $i, $datapoint, $higher));
         
        } else {
            $data_src_info = "ok";
            $q1 = $timestamps2data[$lower];
            $q2 = $timestamps2data[$higher];

            $x1 = round($q1[1]);
            $x2 = round($q2[1]);
            $datapoint = round(($x1 + $x2) / 2);
        }

        $xx = abs($datapoint - $previous_datapoint);



        //  if ($previous_data_info != $data_src_info) {
        // echo "<h5>";
        //  echo '<tr height="-100px" class="tablerow" >'; 
        echo '<tr style="height:1px !important;">';
        echo '<td class="tabled" style="font-size: 1px;" >&nbsp;';
        echo '<img class="' . $dotclass . '" height="1px" alt="hello alt" title="' . gmdate(DATE_RFC2822, $i) . '; ' . $datapoint.': '.$gapthreshold . '; ' . $data_src_info . '" width="' . (3 * $datapoint) . 'px" src="' . $reddot . '" />';
        echo '</td><td><div class="outerdiv">';
        $zzz++;
        if ($zzz % 20 == 2) {
            echo '<div class="innerdiv">' . gmdate(DATE_RFC2822, $i) . '</div>';
        }
        echo '</div>';
        echo '</td>';
        echo '</td><td><div class="outerdiv">';
        if ($zzz % 20 == 2) {
            echo '<div class="innerdiv">&nbsp; &nbsp; ' . $datapoint . '</div>';
        }
        echo '</div>';
        echo '</td>';
        /*
    echo '<td class="tabled" >';
    echo " data=" . $datapoint;
    echo '</td>'; 
    echo '<td class="tabled" >';
    echo " src=" . $data_src_info;
   //  echo "</h5>";
    echo '</td>'; 

    */
        echo '</tr>';
        // echo "\r\n"; 
        //   }

        $previous_data_info = $data_src_info;
        $previous_datapoint = $datapoint;
    }
    echo '</table>';
    echo "<h3>Total data checked = " . human_filesize($totalbytes) . ".</h3>";
    ?>
</body>

</html>