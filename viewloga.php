<!DOCTYPE html>
<html></html>
<head>
    <title>Log File Analysis</title>
</head>
<body>
    <h1>This is still very Beta</h1>
<?php
// PHP program to find element closest
// to given target.

// Returns element closest to target in arr[]
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
$myId = $_GET["id"] ?? 2; 
$t = file("loga/".$myId."_timestamp/__log.html");
// var_dump( $t ); 
$available_timestamps = array();
$timestamps2data = array();
foreach ($t as $a) {
    $parts = explode(" ", $a);
    $b = substr($a, 0, 15);
    $w = DateTime::createFromFormat("Ymd-His", $b);

    if ($w === false) {
        echo "<h2> false for " . $a . " </h2>";
    } else {
        $v = $w->getTimestamp();

        $timestamps2data[$v] = $parts;
        $available_timestamps[] = $v;
    }
}

// var_dump($available_timestamps); 
// var_dump($timestamps2data); 
$nn = sizeof($available_timestamps);

$timeuntil = time();
$timestart = time() - 60 * 60 * 24;
echo '<table id="maintable">'; 
$previous_data_info = "not yet available"; 
$previous_datapoint = 0; 

$gap = 10; 
$gapthreshold = 120; 
for ($i = $timestart; $i < $timeuntil; $i = $i + $gap) {
   

    $w = findClosest($available_timestamps, $nn, $i);
    /*
    echo "<h2>" . $i . " = " . date(DATE_RFC2822, $i) . "</h2>";
    
    var_dump($w);
    var_dump($timestamps2data[$available_timestamps[$w[0]]]);
    var_dump($timestamps2data[$available_timestamps[$w[1]]]);
    */

    $data_src_info = "ok";
    $datapoint = 0;
    $lower = $available_timestamps[$w[0]] ?? 0;
    if ($i - $lower > $gapthreshold ) {
        $lower = false;
    }
    $higher = $available_timestamps[$w[1]] ?? PHP_INT_MAX;
    if ($higher - $i > $gapthreshold ) {
        $higher = false;
    }

    if ($higher === false && $lower === false) {
        $data_src_info = "no data";
        $datapoint = 0;
    } else if ($higher === false) {
        $data_src_info = "lower only";
        $x = $timestamps2data[$lower];
        $datapoint = round($x[1]);
    } else if ($lower === false) {
        $data_src_info = "higher only";
        $x = $timestamps2data[$higher];
        $datapoint = round($x[1]);
    } else {
        $data_src_info = "ok";
        $q1 = $timestamps2data[$lower];
        $q2 = $timestamps2data[$higher];

        $x1 = round($q1[1]);
        $x2 = round($q2[1]);
        $datapoint = round(($x1 + $x2) / 2);
    }

    $xx = abs($datapoint - $previous_datapoint); 
    

    if($previous_data_info != $data_src_info ) { 
   // echo "<h5>";
   echo '<tr class="tablerow" >'; 
   echo '<td class="tabled" >'; 
    echo date(DATE_RFC2822, $i);
     echo '</td>'; 
    echo '<td class="tabled" >';
    echo " data=" . $datapoint;
    echo '</td>'; 
    echo '<td class="tabled" >';
    echo " src=" . $data_src_info;
   //  echo "</h5>";
    echo '</td>'; 
    echo '</tr>';
    echo "\r\n"; 
    }

    $previous_data_info = $data_src_info; 
    $previous_datapoint = $datapoint; 
}
echo '</table>'; 
?>
</body>
</html>
