<?php

$varfile_global = "./vars/allcams.php";
if(file_exists($varfile_global) ) { 
    include_once $varfile_global;
}

function isLocalhost() { 
    return $_SERVER['SERVER_NAME'] === "localhost"; 
}
function seconds2time($s)
{
    $h = floor($s / 3600);
    $s -= $h * 3600;
    $m = floor($s / 60);
    $s -= $m * 60; 
    return $h . ':' . sprintf('%02d', $m) . ':' . sprintf('%02d', $s);
}
function myCam($target)
    {
        return intval(floor($target / 100.0));
    }

function allCams() { 
    $res = array(); 
    $dirs = glob("./img/???/"); 
   // var_dump($dirs); 
    foreach($dirs as $d) { 
        $x = explode("./img/", $d); 
        if(count($x) > 1 ) {
        $a = intval($x[1]);
        if( $a > 0 && $a < 1000 ) { 
            $b = intval(floor( $a / 100.0));
            $res[$b] = $b;  
        }
        }
    }
    return $res; 
}

function addCam() { 
    $x = allCams(); 
    for($i=1; $i < 10; $i++ ) { 
        if(!isset($x[$i])) { 
            mkdir("./img/".$i."01"); 
            return true; 
        }
    }
    return false; 
}

function id2emoji($myId)
{
    $numbers = array("0", "🏡", "🧱", "🚗", "👨", "🛏️", "🛋️", "🌳", "🐦", "💡");
    if (isset($numbers[$myId])) {
        return $numbers[$myId];
    } else {
        return "" . $myId;
    }
}
function archive_day($myId, $yyyymmdd)
{
    if (intval($yyyymmdd) < 0) {
        $x = localtimeCam($myId) + intval($yyyymmdd) * 24 * 60 * 60;
        $yyyymmdd = gmdate("Ymd", $x);
    }

    // foreach (myTargets($myId) as $tgt) {
    for ($i = 1; $i < 100; $i++) {
        $tgt = 100 * $myId + $i;
        $files = glob("./img/" . $tgt . "/aa" . $yyyymmdd . "*.jpg");
        if (count($files) > 0) {
            $outfolder = "./img/old/d" . $yyyymmdd . "/t" . $tgt . "/";
            if (!file_exists($outfolder)) {
                mkdir($outfolder, 0777, true);
            }
            foreach ($files as $file) {
                // echo "rename($file, $outfolder.basename($file ))";  
                rename($file, $outfolder . basename($file));
            }
        }
    }
}
function getLastInfo($i)
{
    $files = glob("./img/last/H" . $i . "H*.*");
    $ret = array();
    $caption =  "Camera " . id2emoji($i);
    $emoticon = "🚩"; 
    if (count($files) > 0) {
        $bb = basename($files[0]);
        $ret["echo"] = '<a class="container" href="menu.php?time=' . time() . '&id=' . $i . '&howmany=18&day=today"><img width=320 height=240 src="./img/last/' . $bb . '" alt="Cam' . $i . ' " >';
        $bnx = substr($bb, 3);
        // echo $bnx."<p>";
        $lasttimestamp = basename2timestamp($bnx);
        $tdiff =  localtimeCam($i) - $lasttimestamp;
        if ($tdiff < 181) {
            $caption .= ":  " . $tdiff . "s ago";
        } else {
            $caption .= ": " . seconds2time($tdiff) . " ago";
        }
        $ret["tdiff"] = $tdiff;
        $emoticon = "?";
        if ($tdiff < 120) {
            $emoticon = "👍";
        } else if ($tdiff < 300) { // 5 minutes
            $emoticon = "😕";
        } else if ($tdiff < 60 * 60) { // 1 hours
            $emoticon = "⚠️";
        } else if ($tdiff < 24 * 60 * 60) { // 1 day
            $emoticon = "🔥";
        } else {
            $emoticon = "💤";
        }

        $caption .= $emoticon;
        
    } else {
        $ret["echo"] = '<a class="container" href="menu.php?time=' . time() . '&id=' . $i . '&howmany=18&day=today"><img src="nopic.jpg" alt="Cam' . $i . ' ">';
    }
    
    $ret["emoji"] = $emoticon; 
    $ret["caption"] = $caption; 
    // add_caption($caption);
    // $ret["echo"] .= 'echo "</a>"';
    return $ret; 
}


/*
function yyyymmdd2localtimeCam($date, $myId) { 
    global $timezoneoffset;
    $t = DateTime::createFromFormat("Ymd", $date); 
    return $t -  ($timezoneoffset[$myId] ?? 0) * 60;
} 
*/
function localtimeCam($myId, $t = false)
{ // $myId can be target or cam. 
    global $timezoneoffset;
    if ($t === false) {
        $t = time();
    }

    // The function now also works for tgts. 
    $myCamId = ($myId > 99 ? myCam($myId) : $myId);

    // If we dont' have the offset yet, we just use UTC. 

    return $t -  ($timezoneoffset[$myCamId] ?? 0) * 60;
}


function id2color($myId, $im = null, $s = 3.0)
{
    if(is_int($myId)=== false ) { $myId = 17; }
    $myId = $myId * 37 % 64; // 64 different colours, mix then a little bit up

    $r = $myId % 4;
    $g = ($myId / 4) % 4;
    $b = ($myId / 16) % 4;

    $rr = floor($r * 255 / $s);
    $gg = floor($g * 255 / $s);
    $bb = floor($b * 255 / $s);

    $res =  array($rr, $gg, $bb);
    if ($im === "hexbg") {
        $res =  sprintf("#%02x%02x%02x", floor(170 + $res[0] / 3), floor(170 + $res[1] / 3), floor(170 + $res[2] / 3));
    } else if ($im === "hex") {
        $res =  sprintf("#%02x%02x%02x", $res[0], $res[1], $res[2]);
    } else if ($im) {
        $res =  imagecolorallocate($im, $res[0], $res[1], $res[2]);
    }
    return $res;
}

function zipDateId($date, $id)
{
    $x = glob("./img/old/d" . $date . "/t" . $id . "??/", GLOB_ONLYDIR);
    // var_dump($x); 
    $ret = "zz";
    foreach ($x as $d) {
        $tgt =  substr(explode("./img/old/d" . $date . "/t", $d)[1], 0, 3);
        $ret .= zipDateTgt($date, $tgt);
    }
    return $ret;
}
function zipDateTgt($date, $tgt)
{
    $foldername = "./img/old/d" . $date . "/t" . $tgt . "/";
    // echo "Start zipping $foldername=".$foldername; 
    // Enter the name of directory 
    $pathdir = $foldername;

    $files2delete = array();

    if (!file_exists($foldername)) {
        return "$foldername does not exist (3)";
    }
    if (strpos($foldername, './img/old/') !== 0) {
        return "$foldername Not allowed (1)";
    }

    $x = explode("./img/old/", $foldername);
    if (count($x) != 2) {
        return "$foldername Not allowed (2)";
    }

    $nameofzip =  str_replace("/", "x", $x[1]);

    // Enter the name to creating zipped directory 
    $zipcreated = "./img/old/" . $nameofzip . ".zip";

    // echo "<p>Zipname = ".$zipcreated; 


    // Create new zip class 
    $n1 = 0;
    $n2 = false;

    $zip = new ZipArchive;

    if ($zip->open($zipcreated, ZipArchive::CREATE) === TRUE) {

        // Store the path into the variable 
        $dir = opendir($pathdir);

        while ($file = readdir($dir)) {
            if (is_file($pathdir . $file)) {
                $zip->addFile($pathdir . $file, $file);
                $files2delete[] = $pathdir . $file;
                $n1++;
            }
        }
        $n2 = $zip->numFiles;
        $zip->close();
    }

    if ($n1 !== $n2) {
        return "Something went wrong with zip $foldername; n1=$n1 but n2=$n2 no delete (4)";
    }
    if (count($files2delete) !== $n2) {
        var_dump($files2delete);
        return "Something went wrong with zip $foldername; n1=$n1 but n2=$n2 no delete (5)";
    }
    if (count($files2delete) !== $n1) {
        var_dump($files2delete);
        return "Something went wrong with zip $foldername; n1=$n1 but n2=$n2 no delete (6)";
    }
    foreach ($files2delete as $f) {
        unlink($f);
    }
    rmdir($pathdir);
    return "OK";
}

function unzipDateId($date, $id)
{
    $x = glob("./img/old/d" . $date . "xt" . $id . "??x.zip");
    // var_dump($x); 

    $ret = "uz";
    foreach ($x as $a) {
        $tgt = substr(explode("./img/old/d" . $date . "xt", $a)[1], 0, 3);
        $ret .= unzipDateTgt($date, $tgt);
    }
    return $ret;
}

function unzipDateTgt($date, $tgt)
{
    $foldername = "./img/old/d" . $date . "/t" . $tgt . "/";
    if (file_exists($foldername)) {
        return "folder already exists; no unzipping.";
    }
    $zipname = "./img/old/d" . $date . "xt" . $tgt . "x.zip";
    if (!file_exists($zipname)) {
        return "Zip file $zipname does not exist.";
    }

    $n2 = false;
    // Create new zip class 
    $zip = new ZipArchive;

    // Add zip filename which need 
    // to unzip 
    $zip->open($zipname);
    $n2 = $zip->numFiles;
    // Extracts to current directory 
    $zip->extractTo($foldername);

    $zip->close();

    $check = glob($foldername . "aa*.jpg");
    if (count($check) !== $n2) {
        var_dump($check);
        return "Number of files do not much: n2 = $n2";
    }
    unlink($zipname);
    return "ok";
}


function bn2bntd($bn)
{
    return substr($bn, 2, 18);
}
/*
function bntd2fileX($myId, $str ) { 
		$files = glob("img/".$myId."??/aa".$str."*z".$myId."*z.jpg"); 
		if(count($files) < 1 ) { return FALSE; } 
		return $files[0]; 		 
} 
*/
function bntd2file($myId, $str, $returnallfiles = false)
{
    $files1 = glob("img/" . $myId . "??/aa" . $str . "*z" . $myId . "*z.jpg");
    if (count($files1) > 0) {
        return ($returnallfiles ? $files1 : $files1[0]);
    }
    $dateofbn = substr($str, 0, 8);

    $files2 = glob("img/old/d" . $dateofbn . "/t" . $myId . "??/aa" . $str . "*z" . $myId . "*z.jpg");
    // $files2 = glob("img/old/d".$dateofbn."/t".$tgt."/".$bn); 
    if (count($files2) > 0) {
        return ($returnallfiles ? $files2 : $files2[0]);
    }
    return FALSE;
}
//echo $tgt; 
function bn2file($bn, $returnallfiles = false)
{
    $files1 = glob("img/*/" . $bn);
    if (count($files1) > 0) {
        return ($returnallfiles ? $files1 : $files1[0]);
    }
    $dateofbn = substr($bn, 2, 8);
    $tgt = intval(explode("z", $bn)[1]);

    $files2 = glob("img/old/d" . $dateofbn . "/t" . $tgt . "/" . $bn);
    if (count($files2) > 0) {
        return ($returnallfiles ? $files2 : $files2[0]);
    }
    return FALSE;
}
function bntd2bn($myId, $str)
{
    $ret = bntd2file($myId, $str);
    return ($ret ? basename($ret) : FALSE);
}

function basename2time($bn)
{
    if ($bn == "nopic.jpg") {
        return "";
    }
    // return substr($bn,10,2).":". substr($bn,12,2).":".substr($bn,14,2)." (".substr($bn,8,2).")"; 
    $t = basename2timestamp($bn);
    return gmdate("d M 'y; H:i:s ", $t);
}
// END basename2time

function basename2timestamp($bn)
{
    return @gmmktime(intval(substr($bn, 10, 2)), intval(substr($bn, 12, 2)), intval(substr($bn, 14, 2)),  intval(substr($bn, 6, 2)), intval(substr($bn, 8, 2)), intval(substr($bn, 2, 4)));
}
?>