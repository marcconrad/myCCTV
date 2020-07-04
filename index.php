<?php
$start = hrtime(true);


if (!file_exists("tmp")) {
    mkdir("tmp", 0777, true);
}

if (!file_exists("log")) {
    mkdir("log", 0777, true);
}
if (!file_exists("agifs")) {
    mkdir("agifs", 0777, true);
}

if (!file_exists("vars")) {
    mkdir("vars", 0777, true);
}
/*
if (file_exists("install.php")) {
    rename("install.php", "install.NOT.txt");
}
*/

$myVarfileId = intval($_POST["id"] ?? $_GET["id"] ?? 99);
if ($myVarfileId < 0 || $myVarfileId > 20) {
    $myVarfileId = 99;
}
$varfile = "./vars/cam" . $myVarfileId . ".php";
$varfile_global = "./vars/allcams.php";


if (!file_exists($varfile)) {
    write2config();
}

if (!file_exists($varfile_global)) {
    write2config(true);
}

include_once $varfile_global;
include_once $varfile;

include_once "./util.php";

if (isset($systempassword["c"])) {
   // setcookie("sanfcctv", $systempassword["c"] ?? "a" . time() . "b", ['expires' => (time() + 8640000), 'samesite' => 'None', 'secure' => true]);
   setcookie("sanfcctv", $systempassword["c"] ?? "a" . time() . "b", ['expires' => (time() + 8640000), 'samesite' => 'lax' ]);
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite
}

$myIdx = $_GET["id"] ?? $_POST["id"] ?? 0; 
if (!isset($targeteta[$myIdx])) {
    $targeteta[$myIdx] = array((isLocalHost() ? 15000 : 100), false);
}

$countImagesSaved = 0;
if (count($_POST) > 0) {
    // error_reporting(-1);
    $myId = intval($_POST["id"] ?? -1);

    /**
     * To avoid opening too many folders as any new id generates new folders etc.
     * when a new id shows up. 
     * */
    if ($myId <= 0 || $myId > 13) {
        die('{"error"  : "id not allowed" }');
    }

    /**
     * To do: if two cameras with different uqt report to the same server then images
     * are mixed up and nothing is there to stop it. Can use $uqt for this which is a unique id
     * that a camera generates as soon as been run. However, id will be different after restart. 
     * 
     */
    $uqt = $_POST["uqt"] ?? die('{"error"  : "no uqt specified" }');
    $uqtprevious = $stats[$myId]["uqt"] ?? false;


    /**
     * Compare day when last accessed and day when now accessed. If new day (midnight) the archive all days
     * before yesterday. 
     */

    $previousaccess = $sessionpostinfo[$myId]["REQUEST_TIME"] ?? 3;


    if ($_SERVER["REQUEST_TIME"] - $previousaccess < 240 && $uqt !== $uqtprevious && $uqtprevious !== false) {
        if (!isset($performance[$myId])) {
            $performance[$myId] = array();
        }
        $pp = $performance[$myId]["uqtclash"] ?? 0;
        $performance[$myId]["uqtclash"] = 1 +  $pp;
        write2config();
        die('{"error"  : "uqt do not match. Two cams on same server?" }');
    }

    $sessionpostinfo[$myId] = $_SERVER;
    $nowaccess = $sessionpostinfo[$myId]["REQUEST_TIME"] ?? 3;

    $dprev = gmdate("Ymd", $previousaccess -  ($timezoneoffset[$myId] * 60 ?? 0));
    $dnow = gmdate("Ymd", $nowaccess - ($timezoneoffset[$myId] * 60 ?? 0));
    echo '{ "pacc"  : "' . $dprev . 'to' . $dnow . '"';
    $max = 30;
    if ($nowaccess - $previousaccess >  1209600) { // last call was more than 2 weeks ago; archive last 400 days. 
        $max = 400;
    }
    if ($nowaccess - $previousaccess >  30240000) { // last call was more than 50 weeks ago; archive last ten years. 
        $max = 3660;
    }
    if ($dnow != $dprev) {
        for ($i = 2; $i < $max; $i++) {
            archive_day($myId, 0 - $i);
        }
        echo ', "moveold"  : "yes"';
    } else {
        echo ', "moveold"  : "no"';
    }

    /**
     * If $toggleCapture[$myId] is equals 1 then the camera will switch from capturing to not capturing and vice versa. Not sure if really 
     * needed; can simply change number of images taken(?).
     */

    $togcap = ($toggleCapture[$myId] ?? 0);
    if ($togcap == 1) {
        $pauseCapture    = !$_POST["pauseCapture"];
    } else {
        $pauseCapture    = $_POST["pauseCapture"];
    }
    $toggleCapture[$myId] = ($pauseCapture ? 2 : 0);

    /**
     * Offset in minutes between the time at the camera and GMT; the time used in this application is always the 
     * time at the camera. 
     */

    if (($timezoneoffset[$_POST["id"]] ?? "y") !== ($_POST["tzo"] ?? "x")) {
        $timezoneoffset[$_POST["id"]]    = $_POST["tzo"] ?? die('{"error"  : "no timezone offset" }');
        write2config(true); // Timezoneoffset is used by homepage, therefore added to global config file. 
    }

    /**
     * More data from the camera: 
     * videoinfo: dimensions from video; 
     * batteryinfo: state of battery (only works when google chrome is used as browser)
     * avdpf: average dropped frames
     * nreq: number of requests, milliseconds between images taken, number of timeouts, number of errors; these are essentially fyi
     */
    $videoinfo[$myId] = $_POST["videoinfo"] ?? die('{"error"  : "no video info" }');
    $batteryinfo[$myId] = $_POST["bat"] ?? die('{"error"  : "no battery info" }');

    $avdpf = $_POST["avdpf"] ?? die('{"error"  : "no avdpf" }');
    $nreq = $_POST["nreq"] ?? die('{"error"  : "no nreq" }');
    $stats[$myId] = array(
        $nreq, $avdpf,
        "uqt" => $uqt,
        "updms" => ($_POST["updms"] ?? false),
        "alivesince" => localtimeCam($myId, ($_POST["alivesince"] ?? false)),
        "requests" => ($_POST["requests"] ?? false),
        "timeouts" => ($_POST["timeouts"] ?? false),
        "errors" => ($_POST["errors"] ?? false),
        "totalImgs" => ($_POST["totalImgs"] ?? false),
        "n200" => ($_POST["n200"] ?? false),
        "nNot200" => ($_POST["nNot200"] ?? false),
        "jsonerr" => ($_POST["jsonerr"] ?? false),
        "jsoninvalid" => ($_POST["jsoninvalid"] ?? false),
        "totalImgsSaved" => ($_POST["totalImgsSaved"] ?? false)
    );



    /** 
     * Tell the camera what zoom it should use next time round.
     */

    echo ', "zoom"  : ' . ($zoom[$myId] ?? 1);
    echo ', "zoomX" : ' . ($zoomX[$myId] ?? 0.5);
    echo ', "zoomY" : ' . ($zoomY[$myId] ?? 0.5);

    /**
     * If resetstats is set to true then tell camera to reset its statistics.
     */
    if ($resetstats[$myId] ?? false) {
        echo ', "resetstats" : 1';
        $resetstats[$myId] = false;
    }
    /**
     * Tell camera to pause capturing or not. 
     */
    echo ', "pauseCapture" : ' . ($pauseCapture ? 'true' : 'false');

    /** 
     * Some more info to camera fyi.
     */
    echo ', "date"  : ' . '"' . gmdate("l jS \of F Y H:i:s" . '"');
    echo ', "cole"  : ' . '"' . $_SERVER['CONTENT_LENGTH'] . '"';

    /**
     * Clarifai is a paid service so we use a counter to ensure that there are not too many requests, the counter 
     * is decreased by 1 every 720 seconds allowing on average a request every 720 seconds.
     */

    if (isset($clarifaicount) && (time() - $clarifaicount[1]) > 720) { // 1200 = every 20 minutes; every x seconds
        $clarifaicount[0] = max($clarifaicount[0] - 1, 0);
        $clarifaicount[1] = time();
    }
    /**
     * Here we check if there is a cat. Most requests will be denied because 'too recently', therefore we do 
     * not log that. 
     */
    if (isset($autocat[$myId]) && isset($autocat[$myId][1]) && $autocat[$myId][1] === TRUE) {
        $res = autocat($myId);
        echo ', "autocat" : "running"';
        if (strpos($res, "Previous request too recently") === FALSE) {
            autocat_log($myId . "; " . $res);
        }
    } else {
        echo ', "autocat" : "not enabled"';
    }
    /**
     * This is actually where the images are saved to files. 
     */
    $countImagesSaved = 0;
    $lastbgnoise = receiveImagesA($myId);
    echo ', "imsaved" : ' . $countImagesSaved;
    foreach (myTargets($myId) as $j) {
        // $eta = (hrtime(true) - $GLOBALS["start"]) / 1e+6;
        // echo ', "hrtimeB' . $myId . 'x' . $j . '" : "' . $eta . '"';
        cleanFiles($j);
        // $eta = (hrtime(true) - $GLOBALS["start"]) / 1e+6;
        // echo ', "hrtimeC' . $myId . 'x' . $j . '" : "' . $eta . '"';
    }
    /**
     * Tell the camera every how many seconds it should send something to the server (default 60, onces a minute) 
     */
    echo ', "mingap" : ' . ($mingapbeforeposts[$myId] ?? 60);

    /**
     * Calculate the time it took to process the images (eta = estimated time of arrival); If this is too high (target eta)
     * then reduce the number of images that the camera should post. This essentially keeps the load at the server sufficiently low. 
     * In auto and autoC mode the target eta is calculated based on the brightness of the picture (i.e. very low at night). 
     * The minimum number of images for each post is one. If you want even fewer images then increase mingap (see above). 
     */

    $eta = (hrtime(true) - $GLOBALS["start"]) / 1e+6;
    echo ', "hrtime" : "' . $eta . '"';

  
    if ($targeteta[$myId][1] !== false) {
        if ($targeteta[$myId][1] == "autoC") {
            $targeteta[$myId][0] = 100 + 5 * $lastbgnoise;
        } else {
            $targeteta[$myId][0] = 10 + 2 * $lastbgnoise;
        }
    }
    $tgteta = $targeteta[$myId][0] ?? 123;

    if ($eta > 2 * $tgteta || ($eta < $tgteta * 0.5 && ($fastmode[$myId] ?? 0) == 0)) {
        $imagesperpost[$myId] = ceil($imagesperpost[$myId] * $tgteta / $eta);
    }
    if ($eta >  $tgteta) {
        $imagesperpost[$myId] = max(1, ($imagesperpost[$myId] ?? 60) - 1);
    } else if ($eta <  $tgteta && ($fastmode[$myId] ?? 0) == 0) {
        $imagesperpost[$myId] = min(($maximagesperpost[$myId] ?? 120), ($imagesperpost[$myId] ?? 60));
        if ($imagesperpost[$myId] < 1) {
            $imagesperpost[$myId] = 1;
        }
    }

    echo ', "imgsperpost" : ' . ($imagesperpost[$myId] ?? 60);

    /** 
     * In fastmode the camera will post images more often; this can be useful, e.g. when setting up zoom or targets and new 
     * images are requested.
     */

    if ($fastmode[$myId] > 0) {
        $fastmode[$myId]--;
    }
    echo ', "fastmode" : ' . ($fastmode[$myId] ?? 0);

    /**
     * Sets the jpg compression used on the camera. 
     * Todo: make image size configurable; at the moment this is fixed to 640 x 480, therefore: if you need higher resolution: good luck. There is 
     * probably not too much hard coding in there. 
     * If you are ok with lower resolution then increase the compression. 
     */

    echo ', "jpgcompression" : ' . ($jpgcompression[$myId] ?? 0.7);

    echo " }";  // close JSON

    /**
     * Some cummulative performane data: in particular how much eta (see above) is used on the server on average; 
     * separatly calculated for normal and fastmode
     */
    if (!isset($performance[$myId]) || $performance[$myId][0] == -1) {
        $performance[$myId] = array(0, 0, 0, 0, 0, 0, 0);
    }
    $peId = $performance[$myId];
    $peId[0] += 1;
    $peId[1] = $eta;
    $peId[2] = ((($peId[0] - 1) *  $peId[2]) + $eta) /  $peId[0];

    if (($fastmode[$myId] ?? 0) == 0) {
        $peId[3] += 1;
        $peId[4] = $eta;
        $peId[5] = ((($peId[3] - 1) *  $peId[5]) + $eta) /  $peId[3];
    }

    $performance[$myId] = array($peId[0], $peId[1], $peId[2], $peId[3], $peId[4], $peId[5], $_POST["n"]);
    /**
     * Now write all the variables we have collected into config.php
     */
    write2config();
    die();
}

?>

<?php
/**
 * Logs any interaction with Clarifai service. 
 */

function autocat_log($log_msg)
{
    $logFile = "log/__log.html";
    $myip = getenv("REMOTE_ADDR");
    $ipNo = "<a href=\"http://ip-api.com/" . $myip . "\">" . $myip . "</a>";

    global $clarifaicount;

    $ctd = 1.0 + time() - ($clarifaicount[4] ?? 0); // add one to avoid division by zero.
    $clarifaipermonth = round(60.0 * 60 * 24 * 30 * $clarifaicount[3] / $ctd, 0);
    $clarifaiinfo = ($clarifaicount[0] ?? "x") . "; " . $clarifaipermonth;


    $logtxt = gmdate("Ymd-His", localtimeCam(1)) . ": " . $log_msg . " from " . $ipNo . " Cfai: " . $clarifaiinfo . "<br>\n";
    file_put_contents($logFile, $logtxt, FILE_APPEND);

    if (filesize($logFile) > 123456) { // bytes
        $archived = "log/__logArchived" . gmdate("YmdHis", localtimeCam(1)) . ".html";
        rename($logFile, $archived);
        $fh = fopen($logFile, 'a') or die("can't open file: " . $logFile);
        fwrite($fh, "<a href=\"$archived\">$archived</a><br>\n");
        fclose($fh);
    }
}

?>


<!DOCTYPE HTML>
<html>

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">

    <?php
    // var_dump($_SERVER); 
    if ($_SERVER['SERVER_NAME'] === "localhost") {
        echo '<base href="http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/" />'; // dirname($_SERVER['PHP_SELF']) must not be empty.

    } else {
        echo '<base href="https://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/" />'; // dirname($_SERVER['PHP_SELF']) must not be empty.
    }
    echo "\r\n";

    ?>

    <title>myCCTV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The following styles are to show dates on top of image -->
    <style>
        .closebtn {
            background-color: #123183;
            cursor: pointer;

        }

        .tableTF tr:hover {
            background-color: #ddd;
        }

        .tableTF {
            border-collapse: collapse;
        }

        .tableTF td {
            border: 1px solid #999;
            text-align: left;
            padding: 2px;
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

        .bottom-left-red {
            position: absolute;
            bottom: 9px;
            left: 17px;
            color: <?php echo ($_GET["captioncolor"] ?? "red"); ?>;
            background-color: rgba(0, 0, 0, 0.3);
        }

        .showtargets {

            opacity: 0.5;
            position: absolute;
            bottom: 3px;
            left: 0px;
        }

        .showframes {

            opacity: 0.5;
            position: absolute;
            bottom: 3px;
            left: 0px;
        }

        .container:hover .showtargets {
            opacity: 1;
        }


        <?php
        $bgcolor = id2color(($_GET["id"] ?? 13), "hexbg");
        echo "body { background-color: $bgcolor; }";
        ?>
    </style>


    <script>
        function getURL() {
            alert("The URL of this page is: " + window.location.href);
        }
    </script>
    <!-- <button type="button" onclick="moveOnTop();">Move on Top</button> -->
    <script>
        function hidebodies() {
            var divs = document.getElementsByTagName("body");
            for (var i = 0; i < divs.length; i++) {
                divs[i].style.display = 'none';
            }
        }
        /*
                if (window.parent != window) {
                    setTimeout(function() {
                        if ((x = document.getElementById("homebutton")) != null) {
                            x.style.display = "none";
                        }
                    }, 100);
                    setTimeout(function() {
                        if ((x = document.getElementById("menubutton")) != null) {
                            x.style.display = "none";
                        }
                    }, 10);
                }
          */
        // Utility to format timestamp
        function time2ymdhis(s) {
            var yyyy = s.substring(0, 4);
            var mm = s.substring(4, 6);
            var dd = s.substring(6, 8);

            var hh = s.substring(8, 10);
            var ii = s.substring(10, 12);
            var ss = s.substring(12, 14);

            var ms = s.substring(15, 18);

            return '' + yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + ii + ':' + ss + '+' + ms;
        }

        async function howmanychange(newval = null) {
            //	alert("howmanychanged: "+document.getElementById("howmany").value); 


            var array = [];
            var hm = document.getElementById("howmany");

            if (hm == null) {
                return;
            }

            newval = newval || document.getElementById("howmany").value;

            var event = new CustomEvent('updatehowmany', {
                    detail: newval
                }

            );
            window.parent.document.dispatchEvent(event);

            document.getElementById("howmany").value = newval;
            var links = document.getElementsByTagName("a");

            for (var i = 0, max = links.length; i < max; i++) {

                var str1 = links[i].href;

                if (str1 && !str1.includes("&fixedhowmany=y")) {
                    str3 = str1.replace(/howmany=\d+/g, "howmany=" + newval);
                    links[i].href = str3;
                }

                str1 = links[i].zoomhref;

                if (str1 && !str1.includes("&fixedhowmany=y")) {
                    str3 = str1.replace(/howmany=\d+/g, "howmany=" + newval);
                    links[i].href = str3;
                }

                str1 = links[i].nozoomhref;

                if (str1 && !str1.includes("&fixedhowmany=y")) {
                    str3 = str1.replace(/howmany=\d+/g, "howmany=" + newval);
                    links[i].href = str3;
                }

            }
        }

        var imgdoubleclick = false;


        async function resetdoubleclick() {
            imgdoubleclick = false;
        }

        var a0bns = "&bns=X";
        async function imgclick(newval, who = 'someone', extra = false, zoomhref = false) {
            console.log("imgclick: newval=" + newval + " who=" + who + " extra=" + extra);

            if (imgdoubleclick === who) {
                imgdoubleclick = false;
                if (window.parent == window) {
                    window.location.href = who.attributes.getNamedItem('nozoomhref').value + "&doubleclicked=1";
                } else {
                    console.log("doubleclick:" + newval);
                    var datetime = newval.substring(2, 20);

                    var event = new CustomEvent('doubleclicked', {
                        detail: datetime
                    });
                    window.parent.document.dispatchEvent(event);
                }
                return;
            }

            imgdoubleclick = who;
            setTimeout(resetdoubleclick, 500);

            //  var ids = ["fromto"];

            var remov = false;
            var a0 = a0bns; // document.getElementById(ids[0]).href;

            var datetime = newval.substring(2, 20);

            var event = new CustomEvent('imageclicked', {
                detail: {
                    dt: datetime,
                    extra: extra,
                    zoomhref: zoomhref
                }
            });
            window.parent.document.dispatchEvent(event);

            console.log("Hello (a0):" + a0);
            console.log("imgclick (a): newval=" + newval + " who=" + who + " extra=" + extra);

            if (extra === 'tgts') {
                return;
            }

            if (a0.includes(datetime)) {
                remov = true;
            }
            /* else if (a0.length > 1024) {
                           alert("Too many images selected");
                           return;
                       } */

            var bn = document.getElementById("SHOWFRAMES" + newval);

            if (remov) {
                bn.setAttribute("hidden", 1);
            } else {
                bn.removeAttribute("hidden");
            }


            var b = null;

            console.log("(x) bn=" + bn);
            /*
                        for (i = 0; i < ids.length; i++) {
                            var d = document.getElementById(ids[i]);
                            var a = d.href;
                            */
            a = a0bns;
            if (remov) {
                b = a.replace(datetime + "X", "");

            } else {
                b = a.replace("&bns=X", "&bns=X" + datetime + "X");
            }
            a0bns = b;
            /*
                d.href = b;

            }
            */
            /*
                        var allBn = b.split("X");
                        allBn.pop();
                        allBn.shift();

                        if (allBn.length > 0) {
                            allBn.sort();
                            var start = allBn.shift();
                            var end = (allBn.length > 0 ? allBn.pop() : start);
                            var dd = document.getElementById("fromto");
                            dd.innerHTML = "From " + time2ymdhis(start) + " to " + time2ymdhis(end);
                            var dd = document.getElementById("fromtoimp");
                            dd.innerHTML = "Important From " + time2ymdhis(start) + " to " + time2ymdhis(end);

                            dd = document.getElementById("fromonly");
                            dd.innerHTML = "From " + time2ymdhis(start) + " to now";
                            dd = document.getElementById("fromonlyimp");
                            dd.innerHTML = "Imp From " + time2ymdhis(start) + " to now";

                            dd = document.getElementById("toonly");
                            dd.innerHTML = "Until.. " + time2ymdhis(end) + "";
                            dd = document.getElementById("toonlyimp");
                            dd.innerHTML = "Imp Until.. " + time2ymdhis(end) + "";
                        }

                        console.log(b);
                        console.log(allBn);
            */

        }

        function showtargets(yes = false ) { 
            var overlays = document.getElementsByClassName("showtargets");
            for (var i = 0, max = overlays.length; i < max; i++) {
                if ( yes ) {
                    overlays[i].removeAttribute("hidden");
                } else {
                    overlays[i].setAttribute("hidden", 1);
                }
            }
        }

        async function imgactionchange(newval = "default") {
            console.log("imagactionchange; newval=" + newval);
            var array = [];

            if (newval == "default" && !window.location.href.includes("target")) {
                var d = document.getElementById("markimg");
                newval = "markimg";

                if (d) {
                    d.checked = 1;
                }
            }

            // console.log("here1"); 
            var overlays = document.getElementsByClassName("showtargets");
/*
            for (var i = 0, max = overlays.length; i < max; i++) {
                if (newval == "showtargets") {
                    overlays[i].removeAttribute("hidden");
                } else {
                    overlays[i].setAttribute("hidden", 1);
                }
            }
*/
            var overlays = document.getElementsByClassName("showframes");

            for (var i = 0, max = overlays.length; i < max; i++) {
                if (newval == "markimg") {
                    // overlays[i].removeAttribute("hidden");
                } else {
                    overlays[i].setAttribute("hidden", 1);
                }
            }

            if (newval == "markimg") {

                var links = document.getElementsByTagName("a");

                for (var i = 0, max = links.length; i < max; i++) {
                    if (links[i].hasAttribute('what')) {
                        links[i].removeAttribute("href");
                    }
                }

                return;

            }

            var links = document.getElementsByTagName("a");

            for (var i = 0, max = links.length; i < max; i++) {
                if (newval == "setzoom" && links[i].hasAttribute("zoomhref")) {
                    links[i].href = links[i].getAttribute("zoomhref");
                    // links[i].removeAttribute("href"); 
                } else if (links[i].hasAttribute("nozoomhref")) {
                    links[i].href = links[i].getAttribute("nozoomhref");
                    // links[i].removeAttribute("href"); 
                }

                //console.log("he"); 
                var str1 = links[i].href;

                if (!str1.includes("&fiximgaction=y")) {
                    str3 = str1.replace(/imgaction=\w+/g, "imgaction=" + newval);
                    links[i].href = str3;
                    // links[i].removeAttribute("href"); 
                }
            }

            howmanychange();

        }


        /*
                // From: https://www.simple.gy/blog/range-slider-two-handles/
                async function updateValue() {
                    var out = document.getElementById("outrange");
                    var range = document.getElementById("myrange").value;
                    var date = new Date(range * 1000);
                    out.innerHTML = date.toISOString();
                }
        */
        /*
                async function setupReload() {
                    var d = document.getElementById("reload");

                    if (d == null) {
                        return;
                    }

                    d.href = window.location.href;
                    d.innerHTML = "Reload";

                }
        */
        function hideinfo(str = null, what = 'toggle') {
            var d = document.getElementById(str);

            if (d == null) {
                return;
            }

            var n = document.getElementById("no" + str);

            if (n == null) {
                return;
            }

            if (d.style.display === "none" || what === 'on') {
                d.style.display = "inline";
                n.style.display = "none";
            } else if (d.style.display === "inline" || what === 'off') {
                d.style.display = "none";
                n.style.display = "inline";
            }


        }

        <?php

        if (($_GET["imgaction"] ?? "x") !== "showtargets") {
            echo "var myInterval =  setTimeout(imgactionchange, 1300);";
            echo "\r\n";
            echo "var myInterval =  setTimeout(imgactionchange, 30);";
            echo "\r\n";
            //  echo "var myInterval3 =  setTimeout(setupReload, 50);";
            echo "\r\n";
        }

        ?>
    </script>
</head>

<body>
    <a href="donotuse.php?bla=2&bns=X" id="fromto"></a>
    <?php
    /**
     * Set a new password if no password has been set and adds an IP if the password is correct.
     * Everything is in plain text, no encryption. 
     */

    // var_dump($_COOKIE);

    if (isset($_GET["systempassword"])) {
        if (!isset($systempassword)) {
            $systempassword = array("pw" =>  $_GET["systempassword"]);
            write2config(true);
            echo "<script>";
            echo "setTimeout(function(){ console.log('(A)'); window.location = 'index.php?cc=" . (($_GET["cc"] ?? 0) + 1) . "&t=" . time() . "&systempassword=" . $_GET["systempassword"] . "' }, 1000);";
            echo "</script>";

            echo '<div class="ack">The System Password has been set. Please wait... </div>';
            echo '</body></html>';
            sleep(2);
            die();
        } else if (
            isset($_GET["callip"]) &&
            (!isset($systempassword[$_GET["callip"]]) || !isset($systempassword["c"]))
            && $_GET["systempassword"] === $systempassword["pw"]
        ) {
            $systempassword[$_GET["callip"]] = true;
            $cookie = password_hash("a" . time() . "z" . rand() . "h", PASSWORD_DEFAULT);
            $systempassword[$cookie] = true;
            $systempassword["c"] = $cookie;
            write2config(true);
            echo " <script> ";
            echo "setTimeout(function(){ console.log('(B)'); window.location = 'index.php?cc=" . (($_GET["cc"] ?? 0) + 1) . "&callip=" . $_GET["callip"] . "&t=" . time() . "&systempassword=" . $_GET["systempassword"] . "' }, 1000);";
            echo " </script> ";
            echo '<div class="ack">The Configuration has been saved. Please wait...</div>';
            echo '</body></html>';
            sleep(2);
            die();
        } /*else if (isset($_GET["callip"]) && !isset($systempassword["c"]) && $_GET["systempassword"] === $systempassword["pw"]) {
            $systempassword[$_GET["callip"]] = true;
            $cookie = password_hash("a" . time() . "y" . rand() . "h", PASSWORD_DEFAULT);
            $systempassword[$cookie] = true;
            $systempassword["c"] = $cookie;
            // $systempassword["d"] = $cookie;
            write2config(true);
            echo " <script> ";
            echo "setTimeout(function(){ console.log('(B)'); window.location = 'index.php?cc=" . (($_GET["cc"] ?? 0) + 1) . "&callip=" . $_GET["callip"] . "&t=" . time() . "&systempassword=" . $_GET["systempassword"] . "' }, 1000);";
            echo " </script> ";
            echo '<div class="ack">The Cookie has been saved. Please wait...</div>';
            echo '</body></html>';
            sleep(2);
            die();
        } */ 
        else if ($_GET["systempassword"] !== $systempassword["pw"]) {
            echo "<p>The password that has been entered does not match the stored password. If you have forgotten the password then go to the server and";
            echo " delete the file " . $varfile_global . ".";
        } else {
            echo '<div class="ack">Please continue.</div>';
        }
        echo '<div class="ack">Thank you.</div>';
        // write2config(true);
        echo '<a href="index.php?xxxxid=1&t=' . time() . '"><h3>Continue...</h3></a>';
        echo '</body></html>';
        sleep(1);
        die();
    }
    /**
     * If no password has been set, ask to set a password. If there is a password then 
     * allow to continue only when password is entered. It stores the IP. 
     * To do: use a cookie system instead of IPs.
     */
    if (isset($systempassword["c"])) {
        unset($systempassword["c"]);

        write2config(true);
    }
    if (!isset($systempassword)) {
        echo '<h1>Please enter the System Password below, then submit</h1>';
        echo "The system password has been reset or the system has been freshly installed. ";
        echo "Please set a password.";
        echo '<form action="index.php">';
        echo '<label for="systempassword">Set new Password:</label><br>';
        echo '<input type="password" id="systempassword" name="systempassword">';
        echo '<input type="hidden" id="t" name="tt" value="' . time() . '">';
        echo '<input type="submit" value="Submit">';
        echo '</form>';
        echo '</body></html>';
        write2config(true);
        sleep(1);
        die();
    } else {
        $callingIp = $_SERVER["REMOTE_ADDR"];
        if (!isset($systempassword[$callingIp]) || !isset($systempassword[$_COOKIE["sanfcctv"] ?? "xxxx"])) {
            echo '<h1>This session is not recongnized.</h1><h2> Please enter the System Password below, then submit</h1>';
            echo '<form action="index.php">';
            echo '<label for="systempassword">Enter the Password:</label><br>';
            echo '<input type="password" id="systempassword" name="systempassword">';
            echo '<input type="hidden" id="t" name="dt" value="' . time() . '">';
            echo '<p>';
            echo '<input type="hidden" id="callip" name="callip" value="' . $callingIp . '">';
            echo '<input type="submit" value="Submit">';
            echo '</form>';
            echo '<p><a href="index.php" id="homebutton" >Home</a>';
            write2config(true);
            sleep(1);
            die();
        }
    }



    if (isset($_GET["resetsystempassword"])) {
        $systempassword = null;
        write2config(true);
        echo '<div class="ack">Thank you. The system Password has been reset.</div>';
        die();
    }

    // Export as csv starts here. 
    function getClosestKey($search, $arr)
    {
        $closest = null;
        foreach ($arr as $key => $item) {
            if ($closest === null || abs(floatval($search) - $closest) > abs(floatval($key) - floatval($search))) {
                $closest = $key;
            }
        }
        return $closest;
    }

    $tmptgt = 0;
    function isBnFromId($bn)
    {
        global $tmptgt;
        if (count(explode("z" . $tmptgt . "z", $bn)) > 1) return true;
        return false;
    }

    if (isset($_GET["csv"]) && isset($_GET["id"])) {
        global $tmptgt;
        $allImages = array();
        $minTimestamp = PHP_INT_MAX;
        $maxTimestamp = 0;
        $buckets = array();
        $bgvals = array();
        $offset = time() - 1000000;
        foreach (myTargets(intval($_GET["id"])) as $j) {
            //var_dump(myTargets(intval($_GET["id"]))); 	
            if (isset($_GET["last"])) {
                $tmptgt = $j;
                $tmp = $lastgallery["full" . $_GET["id"]];
                $allImages = array_filter($tmp, "isBnFromId");
            } else {
                $files = glob("img/" . $j . "/aa*.*");
                $allImages = array_map("basename", $files);
            }
            $ret1 = array();
            foreach ($allImages as $bn) {
                $ms = floatval(explode('i', explode('v', $bn)[1])[0]);
                //echo ", ".$ms; 
                $ddd = explode('d', $bn);
                $timestamp = floatval(basename2timestamp($bn) - $offset) + $ms / 1000.0;
                //echo "; $timestamp ;";  
                if ($timestamp < $minTimestamp) {
                    $minTimestamp = $timestamp;
                }
                if ($timestamp > $maxTimestamp) {
                    $maxTimestamp = $timestamp;
                }
                $ret1["" . $timestamp] = array($ddd[1],  $ddd[3], $bn);
                $bgvals["" . $timestamp] = array($ddd[2], $ddd[2], $bn);
            }
            $buckets[$j] =  $ret1;
        }
        $buckets["bg"] = $bgvals;


        $ret = array();

        ksort($bgvals);

        foreach ($bgvals as $i => $val) {
            $found = false;
            $vals = array("t" . gmdate("md-H:i:s", round(floor($i) + $offset, 0)));

            foreach ($buckets as $y => $x) {
                //  echo "x= "; var_dump($x); 
                if (isset($x[$i])) {
                    if ($y != "bg") {
                        $vals[] =  $x[$i][0];
                    }
                    $vals[] = $x[$i][1];
                    $found = true;
                } else {
                    $j = getClosestKey($i, $x); // extrapolate later
                    if ($y != "bg") {
                        $vals[] =   $x[$j][0];
                    }
                    $vals[] = $x[$j][1];
                }
            }
            if ($found) {

                $ret[] = implode(',', $vals);
            }
        }
        $newline = ($_GET["html"] ? "<br>" : "\n");

        $ret = implode($newline, $ret);
        $out =  "";
        foreach ($buckets as $key => $x) {
            if ($key != "bg") {
                $out .= ", $key (a), $key (b)";
            } else {
                $out .= ", $key ";
            }
        }

        $out .= $newline;
        $out .= $ret;

        if ($_GET["html"]) {
            echo $out;
        } else {
            $filename = "tmp/csv" . $_GET["id"] . "x" . gmdate("YmdHis") . ".csv";
            file_put_contents($filename, $out);
            echo '<h1><a href="' . $filename . '" >Download ' . $filename . ' </a></h1>';
        }
        die();
    }
    // Export as csv ends here. 


    if (isset($_GET["enterclarifai"])) {
        echo '<h1>Please enter your Clarifai Key below, then submit</h1>';
        echo '<a href="https://www.clarifai.com/">More information about Clarifai</a><p>';
        echo '<form action="index.php">';
        echo '<label for="clarifaikey">Enter Key:</label><br>';
        echo '<input type="text" id="clarifaikey" name="clarifaikey">';
        echo '<input type="submit" value="Submit">';
        echo '</form>';
        echo '<p><a href="index.php">Home</a>';
        die();
    }
    if (isset($_GET["clarifaikey"])) {
        // https://www.w3schools.com/js/tryit.asp?filename=tryjs_prompt
        if (!isset($clarifaicount)) {
            $clarifaicount = array("0", time(), $_GET["clarifaikey"]);
        } else {
            $clarifaicount[2] = $_GET["clarifaikey"];
        }
        write2config(true);
        echo "Thank You";
        echo '<p><a href="index.php?time=' . time() . '">Home</a><p>';
        sleep(1);
        die();
    }
    /*
    // Returns one image as a jpg
    if (isset($_GET["imgout"]) || (isset($_GET["imgaction"]) && $_GET["imgaction"] == "large")) {
        $im = false;
        $text = "...";
        if (isset($_GET["b"])) {
            $fullpath = bn2file($_GET["b"]);
            if ($fullpath !== FALSE) {
                $im = @imagecreatefromjpeg($fullpath);
                $text = basename2time($_GET["b"]);
            }
        } else {
            $tgt = $_GET["target"] ?? 101;
            $imp = findImages($tgt);
            if (count($imp) > 0) {
                $im = @imagecreatefromjpeg("img/" . $tgt . "/" . $imp[0]);
                $text = basename2time($imp[0]);
            }
        }
        if (!$im) {
            $im = imagecreatefromjpeg("nopic.jpg");
        }

        $textcolour = imagecolorallocate($im, 204, 204, 0);
        $textshadow = imagecolorallocate($im, 0, 0, 0);

        $font = 'arial.ttf';
        @imagettftext($im, 24, 0, 10, 446, $textshadow, $font, $text);
        @imagettftext($im, 24, 0, 9, 445, $textcolour, $font, $text);

        header('Content-Type: image/jpeg');
        imagejpeg($im, NULL, 100);
        die();
    }
    */

    ?>

    <?php
    $oldestbn = null;
    $newestbn = null;
    if (isset($_GET["imgaction"]) && $_GET["imgaction"] == "showtargets") {
        $_GET["settargetnow"] = 4;
    }
    if (isset($_GET["id"]) && !isset($_GET["home"])) {
        $myId = $_GET["id"];
        error_reporting(-1);
        $sessiongetinfo[$myId] = $_SERVER;
        if (isset($_GET["startcam"])) {
            echo ' <h1><a target="_blank" href="cam.php?id=' . $myId . '">Start Cam ' . $myId . '</a></h1>';
            echo '(will open in a new window)';
            if (isset($_GET["resetuqt"])) {
                $stats[$myId]["uqt"] = NULL;
                write2config();
                echo '<p>uqt has been reset<p>';
            }
            echo "</body></html>";
            sleep(1);
            die();
        }
        if (isset($_GET["testbestimage"])) {

            $bn = findBestImageA($myId, 60, 30);
            echo "(A)";
            var_dump($bn);
            if ($bn !== false) {
                displayImages(array($bn));
            } else {
                echo "no image found.";
            }

            $bn = findBestImageB($myId, 60, 30);
            echo "(B)";
            var_dump($bn);
            if ($bn !== false) {
                displayImages(array($bn));
            } else {
                echo "no image found.";
            }

            $data["minimumage"] = 1800;
            $data["agelimit"] = 5400;
            // $data["donotsort"] = 1;

            $tgts = myTargets($myId);
            $data["howmany"] = count($tgts);
            $bna = findImagesByDate($myId, $data);

            displayImages($bna);
            die();
        }
        if (isset($_GET["testautocat"])) {
            var_dump($_GET);
            echo "<p>Auto Cat<p>";
            $result = autocat($myId);
            echo "<p>";
            var_dump($result);
            echo '<p><a id="homebutton" href="index.php">Home</a>';
            echoTimeUsed();
            die("Thank you");
        }
        if (isset($_GET["settargeteta"])) {
            echo '<h1>Please enter the target ETA</h1>';
            echo 'ETA = Estimated Time of Arrival: maximum number of milliseconds to be used by the server for each request.<br>';
            echo '<form action="index.php">';
            echo '<label for="enter">Enter ETA in Milliseconds:</label><br>';
            echo '<input type="text" id="thetargeteta" name="thetargeteta" value=150>';
            echo '<input  hidden type="text" id="id" name="id" value=' . $myId. ' >';
            echo '<input  class="button submitbtn" type="submit" value="✔️">';
            echo '<span class="button closebtn" onclick="hidebodies()" >⛔</span>';
            echo '</form>';
            echo '<p>';

            die();
        }
        if (isset($_GET["thetargeteta"])) {
            if (strpos($_GET["thetargeteta"], "auto") !== false) {
                $targeteta[$myId] = array(99, $_GET["thetargeteta"]);
            } else {
                $targeteta[$myId] = array(intval($_GET["thetargeteta"]), false);
            }
            write2config();
            echo "Thank You";
            echo "<p>";
            echo '<button class="button closebtn" onclick="hidebodies()" >⛔</button>';
            sleep(1);
            die();
        }
        if (isset($_GET["setjpgcompression"])) {
            echo '<h1>Please enter the JPG compression in percent (0-100)</h1>';
            echo '<form action="index.php">';
            echo '<label for="enter">Enter  JPG value in percent:</label><br>';
            echo '<input type="text" id="thejpgcompression" name="thejpgcompression" value=70>%';
            echo '<input  hidden type="text" id="id" name="id" value=' . $_GET["id"] . ' >';
            echo '<p>';
            echo '<input  type="submit" value="Submit">';
            echo '</form>';
            echo '<p><a href="index.php">Home</a>';
            die();
        }
        if (isset($_GET["thejpgcompression"])) {
            $x = floatval($_GET["thejpgcompression"]);
            if ($x < 0) {
                $x = 0;
            }
            if ($x > 100) {
                $x = 100;
            }
            $jpgcompression[$_GET["id"]] = $x / 100;
            write2config();
            echo "Thank You";
            echo '<p><a href="index.php?time=' . time() . '">Home</a><p>';
            echo '<p><a href="index.php?id=' . $_GET["id"] . '&time=' . time() . '">Back</a><p>';
            sleep(1);
            die();
        }
        if (isset($_GET["unsetimgsizeinfo"])) {
            $imgsizeinfo = null;
            $imgsizeinfo[$_GET["id"]] = array(localtimeCam($_GET["id"]), 0, 0);
            write2config();
            echo '<div class="ack">Thank You. The image size calculation has been reset.</div>';
            sleep(1);
            die();
        }
        if (isset($_GET["savecurrentasgifs"])) {
            echo '<p><b><a href="index.php?time=' . time() . '">Home</a></b><p>';
            $t = $lastgallery[$myId];
            sort($t);
            saveasgifs($t, 20, isset($_GET["showdate"]));
            sleep(1);
            die("<p>Thank you</p>");
        }
        if (isset($_GET["unsetperformance"])) {
            $performance[$myId] = array(0, 0, 0, 0, 0, 0, 0, 0);
            write2config();
            echo '<p><b><a href="index.php?time=' . time() . '">Home</a></b><p>';
            sleep(1);
            die("Thank you");
        }

        if (isset($_GET["clarifaithis"])) {
            echo '<p><a href="index.php?time=' . time() . '">Home</a><p>';
            $clarifaiinfo = clarifaiImage($_GET["clarifaithis"]);
            if (is_array($clarifaiinfo)) {
                $conceptlist = implode(", ", $clarifaiinfo);
                echo "<h2>This image contains: $conceptlist</h2>";
            } else {
                echo "<h2>Clarifai not possible because of $clarifaiinfo</h2>";
                echo "<p>Last request: " . gmdate(DATE_RFC822, $clarifaicount[1]) . "<p>";
            }
            echo '<p><b><a href="index.php?time=' . time() . '">Home</a></b>';
            echo "  clarifaicount=$clarifaicount[0]  <p>";
            sleep(1);
            die();
        }

        if (isset($_GET["showstats"])) {
            echo "\r\n";
            if (isset($timezoneoffset[$myId])) {
                echo 'Time at Camera<b> ' . $myId . '</b>: ' . gmdate("M j H:i", localtimeCam($myId));
            } else {
                echo 'Local time at Camera ' . $myId . ' unknown; assume ' . gmdate("M j H:i", localtimeCam($myId)) . " for now";
            }


            if (isset($sessionpostinfo[$myId])) {
                $lasttimestamp =  $sessionpostinfo[$myId]['REQUEST_TIME'] ?? 0;
                $tdiff =  time() - $lasttimestamp;
                echo ". Last call: <b " . ($tdiff > 120 ? "style=\"color:red\"" : "") . " >" . seconds2time($tdiff) . "</b> hms ago: ";
            } else {
                echo ". No call recorded";
            }
            $togp = ($toggleCapture[$myId] ?? 0);
            echo '<b>' . ($togp == 0 ? " 🎥" : ($togp == 1 ? " request toggle" : "paused ")) . '</b>. ';

            $ntgt = count(myTargets($myId));
            echo '<br>Using ' . $ntgt . ' target' . ($ntgt === 1 ? '' : 's') . '; ';
            if ($togp != 2) { // otherwise paused
                echo "<b>" . ($imagesperpost[$myId] ?? 60) . "</b> imgs every " . ($mingapbeforeposts[$myId] ?? 60) . "s; max " . ($maximagesperpost[$myId] ?? 120) . " imgs.";
                echo ' <a href="index.php?t='.time().'&id='.$myId.'&setupcontrolA=2&nomenu=1">Change</a>'; 
            } else {
                echo "The Camera will call every " . ($mingapbeforeposts[$myId] ?? 60) . "s (recording is paused; no images are made).";
            }

            $batdetails = explode(',', ($batteryinfo[$myId] ?? "a,a,a,a,0"));
            $charging = ($batdetails[1] === "true" ? true : false);
            echo "<br>Battery level: " . (is_numeric($batdetails[0]) ? "<b>" . round(100 * $batdetails[0], 0) . '%</b>, ' . ($charging ? 'charging' : '<b>not charging</b>') : 'n/a');
            // var_dump($batdetails); 


            echo '<br>Target ETA = ';  // var_dump($targeteta); 
            if (isset($targeteta[$myId])) {
                echo round($targeteta[$myId][0], 2) . ($targeteta[$myId][1] !== false ? "ms (" . $targeteta[$myId][1] . ") " : "ms ");
            } else {
                echo "not set (100)";
            }
            echo '; <a href="index.php?time=' . time() . '&settargeteta=1&id=' . $myId . '" >Set Target ETA</a>';

            if (isset($clarifaicount[2])) {
                echo '<br>';


                $c3 = $clarifaicount[3] ?? 0;
                $c4 =  $clarifaicount[4] ?? localtimeCam($myId);
                echo "$c3  Clarifai since " . gmdate("M j H:i", $c4) . " Current: <b>" . $clarifaicount[0] . "</b>";
                $ctd = 1.0 + time() - ($clarifaicount[4] ?? 0); // add one to avoid division by zero.
                $clarifaipermonth = round(60.0 * 60 * 24 * 30 * $c3 / $ctd, 0);
                echo " means <b> $clarifaipermonth </b> Clarifai per 30 days. ";
                
                if (isset($autocat[$myId]) && isset($autocat[$myId][1]) && $autocat[$myId][1] === TRUE) {
                    echo "Autocat is <b>on</b>. ";

                  //  echo '<a id="autocatdisable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&setautocat=disable">Disable</a>';
                } else {
                    echo "Autocat is <b>off</b>. ";
                //    echo '<a id="autocatenable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&setautocat=cat">Enable</a>  &nbsp;';
                }
                
                echo '<a id="autocatenable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&showclarifai=1">Manage</a>  &nbsp;';

                echo "\r\n";
            } else {
                echo '<br>No Clairfai key has been set. <a href="index.php?enterclarifai=1&time=' . time() . '... ">Enter Clarifai Key</a>';
            }


            $imgsreceivedpersecond = 0;
            if (isset($imgsizeinfo[$myId])) {
                echo '<br>Received ' . ($imgsizeinfo[$myId][2] ?? 0) . ' imgs since ' . gmdate("M j, H:i:s", ($imgsizeinfo[$myId][0]) ?? 999999) . ' = ';

                $tdiff = max(1, (localTimeCam($myId) - ($imgsizeinfo[$myId][0] ?? 0))); // in seconds.
                $imgsreceivedpersecond = ($imgsizeinfo[$myId][2] ?? 0) / $tdiff;
                $bytespersecond  = ($imgsizeinfo[$myId][1] ?? 0) / $tdiff;
                $GBperDay = 60 * 60 * 24 * $bytespersecond * 1e-9;

                echo '<b>' . ($GBperDay < 0.1 ? round((1e3 * $GBperDay), 3) . "</b> MB" : round($GBperDay, 2) . "</b> GB") . '/day';
                echo '; <a href="index.php?time=' . time() . '&unsetimgsizeinfo=' . time() . '&id=' . $myId . '">Reset</a>';
                echo '<br> Average image size: ' . round(1e-3 * ($imgsizeinfo[$myId][1] ?? 0) / max(1, ($imgsizeinfo[$myId][2] ?? 0)), 1) . ' kB;';

                echo ' Compression: ' . round(($jpgcompression[$myId] ?? 0.7) * 100, 1) . '% <a href="index.php?time=' . time() . '&setjpgcompression=' . time() . '&id=' . $myId . '">Adjust</a>';
            } else {
                $imgsizeinfo[$myId][0] = localtimeCam($myId);
            }
            // echo 	'</div>'; 		
            echo "\r\n";


            echo '<div>';
            $rowT = "<tr><td>Target:</td>";
            $rowF = "<tr><td>Files:</td>";
            $sumF = 0;
            foreach (myTargets($myId) as $tgt) {
                $fi = count(glob("img/" . $tgt . "/aa*.jpg"));
                $rowT .= "<td>" . $tgt . "</td>";
                $rowF .= "<td>" . $fi . "</td>";
                $sumF += $fi;
            }
            $rowT .= "<td>Total</td></tr>";
            $rowF .= "<td><b>" . $sumF . "</b></td></tr>";
            echo '<table summary="Files currently in each Target" class="tableTF" >' . $rowT . $rowF . "</table>";

            echo ' Keep at least <b>' . ($keephowmany[$myId] ?? 500) . '</b> files on Server for each target.';
            echo ' <a href="index.php?t='.time().'&id='.$myId.'&setupcontrolA=2&nomenu=1">Change</a>'; 
            echo '</div>';
            echo '<div>';
            echo "Fastmode is <b>" . (($fastmode[$myId] ?? -1) > 0 ? "ON (" . $fastmode[$myId] . ")" : "off") . "</b>";
            echo '</div>';

            $k = $stats[$myId] ?? array();
            echo "The Camera is live since: " . (isset($k["alivesince"]) ?  gmdate("M j, H:i:s",  $k["alivesince"]) : "(not recorded)") . ". ";
            echo "Since then " . ($k["requests"] ?? "?") . " requests were made of which";
            echo " " . ($k["n200"] ?? "?") . " where successful (status 200).";
            echo " There were " . ($k["errors"] ?? "?") . " errors and " . ($k["timeouts"] ?? "?") . " timeouts ";
            echo " and " . ($k["nNot200"] ?? "?");
            echo " with other status codes. " . ($k["jsonerr"] ?? "?") . " requests were processed by the server; but controlledly rejected with error. ";
            echo " " . ($k["jsoninvalid"] ?? "?") . " requests returned invalid json. ";

            echo ($k["totalImgs"] ?? "?") . " images were sent in total of which " . ($k["totalImgsSaved"] ?? "?") . " have been acknowledged by the Server";

            $k1 = ($k["totalImgs"] ?? 1);
            if ($k1 != 0) {
                $p2 = round(100 * ($k["totalImgsSaved"] ?? 0) / $k1,  1);
                echo " (" . $p2 . "%).";
            }
            $secsincelive = localtimeCam($myId) -  ($k["alivesince"] ?? 0);
            echo "<br> \r\n";
            $imgssentpersecond =  ($k["totalImgs"] ?? 0) /  $secsincelive;
            $imgsackpersecond =  ($k["totalImgsSaved"] ?? 0) /  $secsincelive;

            echo "" . round(60 * $imgsackpersecond, 2) . " imgs / minute since cam was started.";
            echo "<br> \r\n";
            echo "" . round(60 * $imgsreceivedpersecond, 2) . " imgs / minute since stats reset on server.";

            echo "<br> \r\n";
            $togp = ($toggleCapture[$myId] ?? 0); 
            echo 'Status: <b>' . ($togp == 0 ? "capturing" : ($togp == 1 ? "request toggle" : "paused")) . '</b>. ';
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&toggleCapture=1">Request Toggle</a></li>';
            
            echo "<br> \r\n";
            echo 'Zoom =  ' . ($zoom[$myId] ?? 1).'x; ';
            echo ' Zoom Center at (x,y) = (' . round(100 * ($zoomX[$myId] ?? 0.5), 1);
            echo '%,' . round(100 * ($zoomY[$myId] ?? 0.5), 1).'%).';
            echo ' <a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&setzoom=1">Change</a>.';
            


           
            echo "<br>   \r\n";
       
            global $performance;
           
            $x = $performance[$myId] ?? array(0, 0, 0, 0, 0, 0, 0, 0, 0);
            $avg = round($x[2], 2);
            $last = round($x[1], 2);
            $avg60 = round($x[5], 2);
            $last60 = round($x[4], 2);

            echo 'Average time used on server for each request: '; 
            echo $avg."ms measured over ".$x[0]." requests.";         
     echo '; <a href="index.php?time=' . time() . '&unsetperformance=' . time() . '&id=' . $myId . '">Reset</a>';
/*
            echo "avg=$avg, last=$last ($x[0]); ";
            echo "a60=$avg60, la60=$last60 ($x[3]); ";
    
            echo "lastN=$x[6]";
           
            echo "\r\n";
            echo "</li>   \r\n";
            echo '</ol><p>';
  */
            echo "\r\n";
            echo '</body></html>';
            die();
        }
        write2config();
if(isset($_GET["showclarifai"])) { 
    if (isset($clarifaicount[2])) {
        echo '<br>';


        $c3 = $clarifaicount[3] ?? 0;
        $c4 =  $clarifaicount[4] ?? localtimeCam($myId);
        echo "$c3  Clarifai since " . gmdate("M j H:i", $c4) . " Current: <b>" . $clarifaicount[0] . "</b>";
        $ctd = 1.0 + time() - ($clarifaicount[4] ?? 0); // add one to avoid division by zero.
        $clarifaipermonth = round(60.0 * 60 * 24 * 30 * $c3 / $ctd, 0);
        echo " means <b> $clarifaipermonth </b> Clarifai per 30 days. ";
        if (isset($autocat[$myId]) && isset($autocat[$myId][1]) && $autocat[$myId][1] === TRUE) {
            echo "Autocat is <b>on</b>. ";

            echo '<a id="autocatdisable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&setautocat=disable">Disable</a>';
        } else {
            echo "Autocat is <b>off</b>. ";
            echo '<a id="autocatenable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&setautocat=cat">Enable</a>  &nbsp;';
        }

        echo "\r\n";
    } else {
        echo '<br>No Clairfai key has been set. <a href="index.php?enterclarifai=1&time=' . time() . '... ">Enter Clarifai Key</a>';
    }
    echo '<p><a href="index.php?time=' . time() . '&enterclarifai=1">Enter a Clarifai key</a>';
    echo "<h2>🚧To do: set Concepts; link to concepts🚧</h2>";
    var_dump($clarifaicount); 
    echo "<p>"; 
    var_dump($autocat); 
    echo "\r\n";
    echo '</body></html>';
    die();

}
        if (isset($_GET["setupcontrol"])) {
            echo "\r\n";
            echoSetupMenu($myId);
            $fastmode[$myId] = 8;
            echo "\r\n";
            echo '</body></html>';
            die();
        }
        if (isset($_GET["setupcontrolA"])) {
            echo "\r\n";
            echoSetupMenuA($myId);
            $fastmode[$myId] = 8;
            echo "\r\n";
            echo '</body></html>';
            die();
        }


        if (isset($_GET["history"])) {
            $what = $_GET["history"];
            if ($what == "add") {
                $h = $history[$myId] ?? array();
                $lg = $lastgallery[$myId] ?? array();
                $history[$myId] = array_merge($h, $lg);
                write2config();
                var_dump($history[$myId]);
            } else if ($what == "replace") {
                $lg = $lastgallery[$myId] ?? array();
                $history[$myId] = $lg;
                write2config();
            } else if ($what == "view") {
                $notfound = 0;
                $displaythis = array();
                // var_dump($history[$myId] ); 
                foreach ($history[$myId] ?? array() as $bn) {
                    $f = glob("img/*/" . substr($bn, 0, 25) . "*.jpg");
                    // var_dump($f); 
                    if (isset($f[0])) {
                        $fbn = basename($f[0]);
                        $displaythis[$fbn] = $fbn;
                    } else {
                        $notfound++;
                    }
                    // var_dump($displaythis);  
                }
                ksort($displaythis);
                displayImages($displaythis);
                echo "<p>$notfound images no longer exist.";
            }

            echo '<p><b><a href="index.php?time=' . time() . '">Home</a></b><p>';
            die();
        }
        if (isset($_GET["setgap"])) {
            $mingapbeforeposts[$myId] = intval($_GET["setgap"]);
            echo "<h2>The Camera will send something to you every " . $mingapbeforeposts[$myId] . " seconds</h2>";
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            write2config();
            sleep(1);
        }

        if (isset($_GET["setmaximages"])) {
            $maximagesperpost[$myId] = intval($_GET["setmaximages"]);
            echo "<h2>The Camera will send a maximum of " . $maximagesperpost[$myId] . " images with each post.</h2>";
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            write2config();
            sleep(1);
        }
        if (isset($_GET["keephowmany"])) {
            $keephowmany[$myId] = intval($_GET["keephowmany"]);
            echo "<h2>The System will keep at least " . $keephowmany[$myId] . " images on the server for each bucket.</h2>";
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            write2config();
            sleep(1);
        }
        if (isset($_GET["setautocat"])) {


            $x = $autocat[$myId] ?? array();
            if ($_GET["setautocat"] == "disable") {
                $x[1] = false;
                $x[2] = '[disabled]';
            } else {

                $x[1] = true;
                $x[2] = $_GET["setautocat"];
            }
            echo "<h2>The System will now save gifs that contain " . $x[2] . "</h2>";
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            $autocat[$myId] = $x;
            write2config();
            sleep(1);
        }
        if (isset($_GET["resetstats"])) {
            $resetstats[$myId] = true;
            $videoinfo = NULL;
            $timezoneoffset = NULL;
            $history = NULL;
            $lastgallery = NULL;
            $batteryinfo = NULL;
            $fastmode = NULL;
            $stats[$myId] = NULL;

            echo "<h2>The Camera will reset the statistics. Thank you. </h2>";
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            write2config();
            sleep(1);
        }
        if (isset($_GET["addtarget"])) {
            $i = 1 + 100 * $myId;
            $a = $targets[$myId] ?? array($i => $i);

            $z = 90;
            while (isset($a[$i]) && $a[$i] != NULL && $z-- > 0) {
                $i++;
            }
            $a[$i] = $i;
            $targets[$myId] = $a;
            // var_dump($targets);
            echo "<p>Target $i added to Camera $myId. <p>";
            write2config();
            sleep(1);
        }
        if (isset($_GET["removetarget"])) {
            $a = $targets[$myId] ?? array();

            var_dump($a);
            if (($key = array_search(intval($_GET["removetarget"]), $a)) !== false) {
                echo "<p>Target " . $_GET["removetarget"] . " removed from Camera $myId. <p>";
                unset($a[$key]);
                $targets[$myId] = $a;
                write2config();
                sleep(1);
            } else if ($_GET["removetarget"] == "all") {
                $tgt = 1 + 100 * $myId;
                $targets[$myId] = array($tgt => $tgt);
                write2config();
                sleep(1);
            } else {
                echo "<p>Target " . $_GET["removetarget"] . " not found by Camera $myId. <p>";
            }
        }
        if (isset($_GET["resetzoom"])) {
            $zoom[$myId] = 1;
            $zoomX[$myId] = 0.5;
            $zoomY[$myId] = 0.5;

            echo "<p>Zoom Center is now at x =" . round(100 * $zoomX[$myId], 2) . "%, y=" . round(100 * $zoomY[$myId], 2) . "%.<p>";
            echo '<a href="index.php?day=today&time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            write2config();
            sleep(1);
            die();
        } else if (isset($_GET["zoom"]) && isset($_GET["xC"]) && isset($_GET["yC"])) {
            $zoomX[$myId] = abs($_GET["xC"]);
            $zoomY[$myId] = abs($_GET["yC"]);
            $zoom[$myId] =  $_GET["zoom"];

            echo "<p>Zoom Center is now at x =" . round(100 * $zoomX[$myId], 2) . "%, y=" . round(100 * $zoomY[$myId], 2) . "%.    ";
            echo "Zoom = " . $zoom[$myId] . ".<p>";
            echo '<a href="index.php?day=today&time=' . time() . '&id=' . $myId . '" >Back</a><p>';
            write2config();
            sleep(1);
            die();
        }/* else if (isset($_GET["settargetdisplay"])) {
            echo "<h2>Click on any of the images below to adjust the targets using this image</h2>";
            echo "(if no image is shown, wait)<p>";
            // echo "myId = $myId"; 
            $allImages = array();
            foreach (myTargets($myId) as $j) {
                $allImages = array_merge($allImages, findImages($j));
            }
            sort($allImages);
            $a = array_slice($allImages, -12);
            displayImages($a);
            echo '<p><a href="index.php?howmany=' . $_GET["howmany"] . '&time=' . time() . '&addtarget=1&id=' . $myId . '">Add additional target</a><p>';
        } */ else if (isset($_GET["settargetid"]) || isset($_GET["settargetnow"])) {
            echo "<h1>Change Targets here</h1>";

            if (($bucket = intval($_GET["settargetid"] ?? 0)) > 0 && $_GET["xy"]) {
                $xy = explode(",", substr($_GET["xy"], 1 + strpos($_GET["xy"], "?")));
                $focusX[$bucket] = intval($xy[0]) / 320.0;
                $focusY[$bucket] = intval($xy[1]) / 240.0;
                write2config();
                sleep(1);
                echo "<p>New target has been set for target $bucket </p>";
            }
            echo '<p> Click here when done: <button class="button closebtn" onclick="hidebodies()" >⛔</button></p>';
            $res = addTargets($myId, $_GET["b"] ?? "nonezxz");
            displayImages($res);
            echo '<p><a href="index.php?howmany=' . $_GET["howmany"] . '&time=' . time() . '&settargetnow=1&addtarget=1&id=' . $myId . '">Add additional target</a><p>';
            foreach (myTargets($myId) as $t) {
                echo '<p><a href="index.php?howmany=' . $_GET["howmany"] . '&time=' . time() . '&settargetnow=1&removetarget=' . $t . '&id=' . $myId . '">Remove Target ' . $t . '</a><p>';
            }
            echo '<p><a href="index.php?howmany=' . $_GET["howmany"] . '&time=' . time() . '&settargetnow=1&removetarget=all&id=' . $myId . '">Remove all targets.</a><p>';
            die("<p>Thank you! 🙂</p>");
        } else if (isset($_GET["day"]) || isset($_GET["bntd"])) {
            $allImagesA = findImagesByDate($myId);

            displayImages($allImagesA);
        } else if (isset($_GET['cleanup'])) {
            echo '<h1>Cleaning up Files</h1>';
            foreach (myTargets($myId) as $target) {
                cleanFiles($target);
            }
        } else if (($_GET["toggleCapture"] ?? 0) == 1) {
            $toggleCapture[$myId] = 1;
            echo '<h1>Camera Start / Pause requested</h1><p><a href="index.php?time=' . time() . '&id=' . $myId . '">Back</a></h1>';
            write2config();
            sleep(1);
        } /*else if (isset($_GET["bestimage30"])) {
            $bestImage = findBestImage($myId, 30); // last 30 minutes
            if ($bestImage !== FALSE) {
                displayImages(array($bestImage));
            } else {
                displayImages(array());
            }
        } else if (isset($_GET["fromto"])) {
            $buckets = myTargets($myId);
            $h = ($history[$myId] ?? false);

            $allImages = array();
            $bnfrom = false;
            $bnto = false;
            if ($h !== false && count($h > 2)) {
                $bnfrom = $h[count($h) - 2];
                $bnto  = $h[count($h) - 1];
                if (strcmp($bnfrom, $bnto) > 0) {
                    $temp = $bnfrom;
                    $bnfrom = $bnto;
                    $bnto = $temp;
                }
                foreach ($buckets as $j) {
                    $allImages = array_merge($allImages, findImages($j, ($_GET["howmany"] ?? 6), false, false, false, $bnfrom, $bnto));
                }
                sort($allImages);
                displayImages($allImages);
            } else {
                echo "<h1>Sorry, no history saved</h1>";
                die("Thank you");
            }
        } else if (isset($_GET["fromtoclick"])  || isset($_GET["fromonlyclick"]) || isset($_GET["toonlyclick"])) {
            $bns = explode("X", ($_GET["bns"] ?? "a"));
            if (count($bns) < 3) {
                echo "<h1>Nothing to show here</h1>";
                echo "<a href=\"index.php\">Home</a>";
                die("Thank you.");
            }
            //var_dump($bns); 
            array_shift($bns);
            array_pop($bns);
            sort($bns);
            // var_dump($bns); 
            //echo '<p>'; 
            $first = $bns[0];
            $last = $bns[count($bns) - 1];

            $buckets = myTargets($myId);
            $allImages = array();
            foreach ($buckets as $j) {
                if (isset($_GET["fromonlyclick"])) {

                    $allImages = array_merge($allImages, findImages($j, ($_GET["howmany"] ?? 6), isset($_GET["important"]), false, false, "aa" . $first, PHP_INT_MAX));
                } else if (isset($_GET["toonlyclick"])) {

                    $allImages = array_merge($allImages, findImages($j, ($_GET["howmany"] ?? 6), isset($_GET["important"]), false, false, 0, "aa" . $last));
                } else {

                    $allImages = array_merge($allImages, findImages($j, ($_GET["howmany"] ?? 6), isset($_GET["important"]), false, false, "aa" . $first, "aa" . $last));
                }
            }
            sort($allImages);
            $nn = count($allImages);



            if ($nn < 1) {
                echo "<h1>Nothing to show here</h1>";
                echo "<a href=\"index.php\">Home</a>";
                die("Thank you.");
            }

            $disp = array();
            $howmany = ($_GET["howmany"] ?? 6) - 1;
            if ($nn > 1 && $howmany > 0) {
                $modulus = 1.0 * ($nn - 1) / $howmany;
                $f = 1000000.0;
                for ($i = 0; $i < $nn - 1; $i++) {
                    if ((intval($i * $f) % intval($modulus * $f)) / $f < 1) {
                        $disp[] = $allImages[$i];
                    }
                }
            }
            $disp[] = $allImages[$nn - 1];
            echo "Showing " . count($disp) . " of " . $nn . " images.<p>";
            displayImages($disp);
        } else if (isset($_GET["showmarked"])) {
            $bns = explode("X", ($_GET["bns"] ?? "b"));
            if (count($bns) < 3) {
                echo "<h1>Nothing to show here</h1>";
                echo "<a href=\"index.php\">Home</a>";
                die("Thank you.");
            }
            // var_dump($bns); 
            array_shift($bns);
            array_pop($bns);
            sort($bns);
            // var_dump($bns); 
            echo '<p>';

            $allImages = array();
            foreach ($bns as $bn) {

                $srcfiles = glob("img/" . $myId . "??/aa" . $bn . "*.jpg");
                $basenames = array_map("basename", $srcfiles);
                $allImages = array_merge($allImages, $basenames);
            }
            sort($allImages);
            displayImages($allImages);
        } else if (isset($_GET["b"])) {
            $buckets = myTargets($myId);
            $allImages = array();
            foreach ($buckets as $j) {
                $allImages = array_merge($allImages, findImages($j, ($_GET["howmany"] ?? 6), false, $_GET["b"], false, 0, PHP_INT_MAX, true ));
            }
            if (isset($_GET["doubleclicked"])) {
                // $allImages = array_merge($allImages, $lastgallery[$myId]);
            }
            rsort($allImages);
            displayImages($allImages);
        } else if (!isset($_GET["nogallery"])) {
            $buckets = myTargets($myId, true);
            $allImages = array();
            foreach ($buckets as $j) {
                $allImages = array_merge($allImages, findImages($j));
            }
            sort($allImages);
            displayImages($allImages);
        } */ else {
            echo '<p>Thank you. <a href="index.php?time=' . time() . '&a=1">Home</a></p>';
        }
        echo '</body>   </html>';
    } else {
        error_reporting(-1);
        echo "<h1>Welcome to myCCTV!</h1><em>Choose a camera below</em><p>";
        echo "<script>";

        echo "if (window.parent != window) { window.parent.location = window.location; }";

        echo "</script>";

        for ($i = 1; $i < 10; $i++) {


            $info = getLastInfo($i);
            echo $info["echo"];
            add_caption($info["caption"]);
            echo "</a>";
            echo "\r\n";
        }
        echo '<p>';
        echo '<a href="zipdelete.php?homepage=yes&delete=1">Zip and Delete Preview</a>, ';
        try {
            $fi = new FilesystemIterator(__DIR__ . '/tmp', FilesystemIterator::SKIP_DOTS);
            echo '<a href="zipdelete.php?tmp=yes&delete=1 ">Zip and Delete tmp (' . iterator_count($fi) . ' files)</a>, ';
        } catch (exception $ex) {
        }
        echo '<a href="setupinstall.php">Generate install.php.txt file</a>';
        echo ' Goto: <a href="zip/">(zip)</a>,<a href="img/">(img)</a>, <a href="log/">(log)</a>, <a href="tmp/">(tmp)</a>';
        echo ' <a href="devbackup.php">dev backup</a>';
        echo ' <a href="viewgifs.php">view gifs</a>';
        echo "\r\n";
        echo '</body></html>';
        die();
    }

    function autocat($myId, $test = FALSE)
    {
        global $autocat;

        $outputfolder = "agifs/";

        $defaultsearchterms = array("cat", "bird",  "squirrel", "rodent", "mammal", "animal", "dog", "wildlife", "lifestyle");

        if (!isset($autocat)) {
            $autocat = array();
        }
        if (!isset($autocat[$myId])) {
            $autocat[$myId] = array();
        }

        if (!isset($autocat[$myId][5])) {
            $autocat[$myId][5] = array_combine($defaultsearchterms, $defaultsearchterms);
            write2config();
        }
        $searchterms = $autocat[$myId][5];
        // $x = ($autocat[$myId] ?? array("", FALSE, "cat", 0, $searchterms));
        // $x[5] = $searchterms; // overwrite; not yet configurable online.


        // 	$mainconcept = $x[2] ?? "cat"; 
        // $searchterms = $x[5] ?? array("cat");
        // $autocat[$myId][5] = $searchterms; 

        $tdiff = localtimeCam($myId) - ($autocat[$myId][4] ?? 0);
        if (($autocat[$myId][1] ?? false) !== false && $tdiff < 782 && !$test) {
            return "Previous request too recently: $tdiff seconds ago";
        } else {
            $autocat[$myId][4] = localtimeCam($myId);
            write2config();
        }



        $bn = findBestImageA($myId, 60, 30); // From 30+60 minutes ago to 30 minutes ago

        if ($bn === FALSE) {
            return "No best images was found.";
        }



        // $s = substr($bn, 0, 20); 
        $t = bn2bntd($bn);
        if ($test) {
            $t = $_GET["bntd"];
            $bn = bntd2bn($myId, $t);
        }
        $processedhistory = $autocat[$myId][6] ?? array();
        $bnlink = '<a href="index.php?id=' . $myId . '&bntd=' . $t . '">aa' . $t . '*' . $myId . '*.jpg</a>';
        if ($t == ($autocat[$myId][0] ?? "nothing")) {
            return "The file $bnlink has already been processed";
        }
        if (isset($processedhistory[$t])) {
            return "The file $bnlink has been processed earlier";
        }

        $ddd = explode("d", $bn);
        if (count($ddd) > 3) {
            if (floatval($ddd[2] < 15) && floatval($ddd[3] < 15)) {
                $autocat[$myId][0] = $t;

                if (count($processedhistory) > 5) {
                    array_shift($processedhistory);
                }
                $processedhistory[$t] = $t;
                $autocat[$myId][6] = $processedhistory;
                write2config();
                return "The file $bnlink is too dark and will not be processed.";
            }
        }



        $concepts = clarifaiImage($bn, true);

        if (!is_array($concepts)) {
            return "Clasrifai problem with $bnlink: " . $concepts;
        }
        $autocat[$myId][0] = $t;

        if (count($processedhistory) > 5) {
            array_shift($processedhistory);
        }
        $processedhistory[$t] = $t;
        $autocat[$myId][6] = $processedhistory;
        write2config();

        $theconcept = FALSE;
        foreach ($searchterms as $c) {
            if (array_search($c, $concepts) !== FALSE) {
                $theconcept = $c;
                break;
            }
        }

        if ($theconcept === FALSE) {
            return "The  image $bnlink did not contain one of the searchterms " . implode(",",  $autocat[$myId]["searchterms"]) . " but only: " . implode(", ", $concepts);
        }




        $outputfolder = "agifs/" . $theconcept . "/";
        if (!file_exists($outputfolder)) {
            mkdir($outputfolder, 0777, true);
        }

        $files = glob("img/*/aa" . $t . "*.jpg");
        if (!isset($files[0])) {
            return "File " . $bnlink . " was not found.";
        }
        copy($files[0], $outputfolder . $bn);
        $dd = gmdate("Ymd-His", localTimeCam($myId));
        file_put_contents($outputfolder . "aa" . $t . "z" . $myId . "x" . $dd . ".txt", implode(", ", $concepts));

        $myNeigtbours = array();
        $bnt = basename2timestamp($bn);
        $fromTime = $bnt - 300; // 2 minutes
        $toTime = $bnt + 300;
        foreach (myTargets($myId) as $tgt) {
            $a = findImages($tgt, 12, true, $bn, false, $fromTime, $toTime);
            foreach ($a as $bna) {
                $sa = substr($bna, 0, 20);
                $myNeighbours[$sa] = $bna;
            }
        }
        // var_dump($myNeighbours); 
        ksort($myNeighbours);
        $nframes = count($myNeighbours);
        $filename = $outputfolder . "aa" . $t . "z" . $myId . "y" . $dd . "x" . $nframes . "w.gif";
        saveasgifs($myNeighbours, 20, false, $filename, true);

        return "<b>Animated GIF</b> about $theconcept been made for " . $bnlink . "; " . $filename . " - " . implode(", ", $concepts);
    }

    function findBestImageB($myId, $minutes = 60, $minimumage = 30)
    {
        $data["minimumage"] = 60 * $minimumage; // 1800;
        $data["agelimit"] = 60 * ($minimumage + $minutes); // 5400;
        $data["donotsort"] = 1;
        $data["howmany"] = 1;
        $bna = findImagesByDate($myId, $data);
        foreach ($bna as $x) {
            return $x; // return first element if there is one. 
        }
        return false;
    }

    function orderByClosestToAverage($bns)
    {
        // var_dump($bns); 
        $avgtime = 0;
        $m = count($bns);
        foreach ($bns as $x) {
            $avgtime += basename2timestamp($x) / $m;
        }
        $ret = array();
        $c = 1;
        foreach ($bns as $x) {
            $dist = abs(basename2timestamp($x) - $avgtime);
            $dms = $dist * 100 + $c++;
            $ret[$dms] = $x;
        }
        ksort($ret);

        // var_dump($ret); 
        return $ret;
    }

    function findBestImageA($myId, $minutes = 30, $minimumage = 0, $bucket = FALSE)
    {

        $buckets = myTargets($myId);
        if ($bucket !== FALSE) {
            $buckets = array($bucket); // search only in bucket
        }
        $allImages = array();
        $lastseconds = intval($minutes * 60);
        foreach ($buckets as $j) {
            $imgend = localtimeCam(myCam($j)) - intval($minimumage * 60);
            $imgstart = $imgend - $lastseconds;
            $allImages = array_merge($allImages, findImages($j, 1, true, false, false, $imgstart, $imgend));
        }/*
        echo "<p>(BA)"; 
        var_dump($allImages); 
        echo "(BZ)<\p>"; 
        */
        if (($m = count($allImages)) > 0) {
            sort($allImages);
            $avgtime = 0;
            foreach ($allImages as $x) {
                $avgtime += basename2timestamp($x) / $m;
            }
            $dist = PHP_INT_MAX;
            $bnClosestToAvg = NULL;
            foreach ($allImages as $x) {
                if (($d = abs(basename2timestamp($x) - $avgtime)) < $dist) {
                    $bnClosestToAvg = $x;
                    $dist = $d;
                }
            }
            return $bnClosestToAvg;
        } else {
            return false;
        }
    }

    function clarifaiImage($bn, $silent = FALSE)
    {
        global $clarifaicount;
        if (!isset($clarifaicount)) {
            $clarifaicount = array("0", time(), false);
        }

        $files = glob("img/*/" . substr($bn, 0, 24) . "*.jpg");
        if (count($files) < 1) {
            // echo "<h2>Sorry, the file ".$bn." can not be found. </h2>"; 
            // echo '<p><b><a href="index.php?time='.time().'">Home</a></b><p>'; 
            return "file cannot be found";
        }


        if (isset($clarifaicount[0]) && $clarifaicount[0] > 50) {
            return "request over quota";
        } // Request over quota 

        $clarifaikey = ($clarifaicount[2] ?? false);
        if ($clarifaikey === false) {
            return "key not valid";
        } // no valid key

        $x = "tmp/x" . time() . ".jpg";
        copy($files[0], $x);
        $imgurl = 'https://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/' . $x;

        if ($silent === FALSE) {
            echo "<h2> imgurl=$imgurl </h2>";
            echo "<p><img src=\"$imgurl\" >";
        }

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_VERBOSE, '1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, "https://api.clarifai.com/v2/models/aaa03c23b3724a16a56b629203edc62c/outputs");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // curl_exec returns the value

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


        $headers = array(
            'Content-Type: application/json',
            "Authorization: Key " . $clarifaikey
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $fields = '{"inputs":[{"data":{"image":{"url":"' . $imgurl . '"}}}]}'; // input required by Clarifai
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        // grab URL and pass it to the browser 
        $result = curl_exec($ch);

        if ($silent === FALSE) {
            echo '<h2>Result=' . $result . '</h2>';
        }

        $mydata = json_decode($result, true);

        if ($silent === FALSE) {
            echo "<p> mydata=";
            var_dump($mydata);
        }


        $max = 0;

        if (isset($mydata["outputs"][0])) {
            $concepts = $mydata["outputs"][0]["data"]["concepts"];
            $max = sizeof($concepts);
        }
        $ret = array();
        for ($i = 0; $i < $max; $i++) {
            $value = $concepts[$i];
            $ret[] = $value["name"];
        }
        $clarifaicount[0]  = $clarifaicount[0]  + 1;
        $clarifaicount[1] = time();
        $clarifaicount[2] = $clarifaikey;
        $clarifaicount[3] = ($clarifaicount[3] ?? 0) + 1;
        $clarifaicount[4] = ($clarifaicount[4] ?? time());

        write2config(true);

        return $ret;
        // var_dump($conceptlist); 

    }

    function myTargets($myId, $includedefunct = false)
    {
        global $targets;
        if ($includedefunct) {
            $myIds = array();
            for ($i = 1; $i < 99; $i++) { // explode("b",$_GET["buckets"]); 
                if (file_exists("img/" . (100 * $myId + $i) . '/')) {
                    $myIds[] = 100 * $myId + $i;
                }
            }
            return $myIds;
        }
        $a = $targets[$myId] ?? array(100 * $myId + 1 => 100 * $myId + 1);
        return $a;
    }



    function add_caption($str)
    {
        echo '<em class="bottom-left">' . $str . '</em>';
        if (($_GET["id"] ?? "") === "9999") { // maybe used in the future?
            echo '<em class="bottom-left-red">' . $str . '</em>';
        } else {
            echo '<em class="bottom-left-yellow">' . $str . '</em>';
        }
    }


    function write2config($cam_agnostic = false)
    {
        global $varfile, $varfile_global;
        global $focusX, $focusY, $zoom, $zoomX, $slaves, $batteryinfo;
        global $zoomY, $timezoneoffset, $toggleCapture, $mingapbeforeposts, $update;
        global $fastmode, $maximagesperpost, $imagesperpost, $keephowmany, $stats, $resetstats, $history, $lastgallery;
        global $videoinfo, $targets, $clarifaicount, $performance, $sessiongetinfo, $sessionpostinfo, $targeteta, $imgsizeinfo, $jpgcompression;
        global $systempassword, $autocat;

        $savefile = null;


        $content = "<?php ";

        if ($cam_agnostic === true) {
            $content .= PHP_EOL . " \$systempassword = " . var_export($systempassword, true) . "; ";
            $content .= PHP_EOL . " \$clarifaicount = " . var_export($clarifaicount, true) . "; ";
            $content .= PHP_EOL . " \$timezoneoffset = " . var_export($timezoneoffset, true) . "; ";
            $savefile = $varfile_global;
        } else {
            $content .= PHP_EOL . " \$focusX = " . var_export($focusX, true) . "; ";
            $content .= PHP_EOL . " \$focusY = " . var_export($focusY, true) . "; ";
            $content .= PHP_EOL . " \$targets = " . var_export($targets, true) . "; ";

            $content .= PHP_EOL . " \$autocat = " . var_export($autocat, true) . "; ";


            $content .= PHP_EOL . " \$performance = " . var_export($performance, true) . "; ";
            $content .= PHP_EOL . " \$sessionpostinfo = " . var_export($sessionpostinfo, true) . "; ";
            $content .= PHP_EOL . " \$sessiongetinfo = " . var_export($sessiongetinfo, true) . "; ";
            $content .= PHP_EOL . " \$targeteta = " . var_export($targeteta, true) . "; ";
            $content .= PHP_EOL . " \$imgsizeinfo = " . var_export($imgsizeinfo, true) . "; ";
            $content .= PHP_EOL . " \$jpgcompression = " . var_export($jpgcompression, true) . "; ";





            $content .= PHP_EOL . " \$mingapbeforeposts = " . var_export($mingapbeforeposts, true) . "; ";
            $content .= PHP_EOL . " \$imagesperpost = " . var_export($imagesperpost, true) . "; ";
            $content .= PHP_EOL . " \$maximagesperpost = " . var_export($maximagesperpost, true) . "; ";
            $content .= PHP_EOL . " \$keephowmany = " . var_export($keephowmany, true) . "; ";

            $content .= PHP_EOL . " \$zoom = " . var_export($zoom, true) . "; ";
            $content .= PHP_EOL . " \$zoomX = " . var_export($zoomX, true) . "; ";
            $content .= PHP_EOL . " \$zoomY = " . var_export($zoomY, true) . "; ";

            $content .= PHP_EOL . " \$stats = " . var_export($stats, true) . "; ";
            $content .= PHP_EOL . " \$resetstats = " . var_export($resetstats, true) . "; ";
            $content .= PHP_EOL . " \$videoinfo = " . var_export($videoinfo, true) . "; ";


            $content .= PHP_EOL . " \$toggleCapture = " . var_export($toggleCapture, true) . "; ";
            $content .= PHP_EOL . " \$history = " . var_export($history, true) . "; ";
            $content .= PHP_EOL . " \$lastgallery = " . var_export($lastgallery, true) . "; ";
            $content .= PHP_EOL . " \$fastmode = " . var_export($fastmode, true) . "; ";
            $content .= PHP_EOL . " \$batteryinfo = " . var_export($batteryinfo, true) . "; ";
            $savefile = $varfile;
        }

        $content .= PHP_EOL . " ?>";


        // $datei = fopen("config.php", "w");

        file_put_contents($savefile, $content);
    }
    function echoSetupMenuA($myId)
    {
        global $imagesperpost, $targeteta;
        global $toggleCapture, $zoom, $zoomX, $zoomY, $mingapbeforeposts, $maximagesperpost;
        echo "<h3>Control Cam $myId</h3>";
        $ntgt = count(myTargets($myId)); 
        echo '<br>Using ' . $ntgt . ' target' . ($ntgt === 1 ? '' : 's') . '; ';
        echo "<b>" . ($imagesperpost[$myId] ?? 60) . "</b> imgs every " . ($mingapbeforeposts[$myId] ?? 60) . "s; max " . ($maximagesperpost[$myId] ?? 120) . " imgs.";
              
        echo '<p><ol>';



        echo '<li><a href="index.php?time=' . time() . '&id=' . $myId . '&settgts=1&day=today">Set targets</a></li>';

        echo '<li>Gap Between Posts: ';
        for ($j = 10; $j < 510; $j += 20) {
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&setgap=' . $j . '">' . $j . ' </a>;';
        }
        echo ' Seconds</li>';

        echo '<li>Maximum number of images per post: ';
        for ($j = 20; $j < 240; $j += 20) {
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&setmaximages=' . $j . '">#' . $j . ' </a>;';
        }
        echo '<br>';
        echo  'Note that the actual number of images that are transfered may change depending on the value of <a href= index.php?time=' . time() . '&id=' . $myId . '&settargeteta=1&">target ETA</a>'; 
        echo "</li>   \r\n";

        echo '<li>Number of images to keep on server for each target: ';
        for ($j = 50; $j < 1000; $j += 50) {
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&keephowmany=' . $j . '">#' . $j . ' </a>;';
        }
        echo "</li>   \r\n";
        echo '<li>';
        echo '<a href="index.php?time=' . time() . '&enterclarifai=1">Enter a Clarifai key</a>';

        echo "</li>   \r\n";
       
        
    }

    function echoSetupMenu($myId)
    {
        global $imagesperpost, $keephowmany;
        global $toggleCapture, $zoom, $zoomX, $zoomY, $mingapbeforeposts;
        echo "<h3>Control Cam $myId</h3>";
        echo '<p><ol>';


        echo '<li>';
        // echo '<a href="zipdelete.php?merge=1&delete=y&buckets=yes&id='.$myId.'">merge, zip and delete</a>; ';
        echo '<a href="zipdelete.php?merge=1&buckets=yes&id=' . $myId . '">merge, zip (no delete)</a>; ';
        echo '</li>';

        echo '<li><a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&toggleCapture=1">Toggle Capture</a></li>';
        echo '<li>';

        echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&howmany=9&resetsystempassword=1">Reset System Password</a> &nbsp; ';
        echo '</li>';

        echo '<li>Gap Between Posts: ';
        for ($j = 20; $j < 350; $j += 20) {
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&setgap=' . $j . '">' . $j . ' </a>;';
        }
        echo ' Seconds</li>';

        echo '<li>#images / post: ';
        for ($j = 20; $j < 240; $j += 20) {
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&setmaximages=' . $j . '">#' . $j . ' </a>;';
        }
        echo "</li>   \r\n";

        echo '<li>keep on server: ';
        for ($j = 50; $j < 1000; $j += 50) {
            echo '<a href="index.php?time=' . time() . '&id=' . $myId . '&nogallery=1&nomenu=1&keephowmany=' . $j . '">#' . $j . ' </a>;';
        }
        echo "</li>   \r\n";
        echo '<li>';
        echo '<a href="index.php?time=' . time() . '&enterclarifai=1">Enter a Clarifai key</a>';

        echo "</li>   \r\n";
        echo "<li>   \r\n";
        // echo '<a id="autocattest" href="index.php?showmarked=1&time='.time().'&id='.$myId.'&autocat=1">Check best image last hour; if cat then make gif</a> &nbsp;';
        echo '<a id="autocatenable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&setautocat=cat">Enable Autocat</a>  &nbsp;';
        echo '<a id="autocatdisable" href="index.php?showmarked=1&time=' . time() . '&id=' . $myId . '&setautocat=disable">Disable Autocat</a>';
        echo "</li>   \r\n";
        echo '<li>';
        $togp = ($toggleCapture[$myId] ?? 0);
        echo 'Status: <b>' . ($togp == 0 ? "capturing" : ($togp == 1 ? "request toggle" : "paused")) . '</b>. ';
        echo 'Zoom=' . ($zoom[$myId] ?? 1);
        echo ' Zoom Center X=' . ($zoomX[$myId] ?? 0.5);
        echo ', Y=' . ($zoomY[$myId] ?? 0.5);
        echo ' ,' . ($imagesperpost[$myId] ?? 60) . ' imgs every ' . ($mingapbeforeposts[$myId] ?? 60) . ' seconds.';
        // echo ' <a href="cam.php?id=' . $myId . '">Start Cam ' . $myId . '</a>';
        echo "</li>   \r\n";


        global $stats;

        echo '<li>Stats on Camera=' . ($stats[$myId] ? implode('; ', $stats[$myId]) : "no stats");
        echo ' <a href="index.php?time=' . time() . '&id=' . $myId . '&resetstats=yes&nomenu=1&nogallery=1&howmany=1">Reset Stats</a> &nbsp;';

        echo "</li>   \r\n";

        global $performance;
        echo '<li>Performance: '; // var_dump($performance[$myId]); 
        $x = $performance[$myId] ?? array(0, 0, 0, 0, 0, 0, 0, 0, 0);
        $avg = round($x[2], 2);
        $last = round($x[1], 2);
        $avg60 = round($x[5], 2);
        $last60 = round($x[4], 2);

        echo "avg=$avg, last=$last ($x[0]); ";
        echo "a60=$avg60, la60=$last60 ($x[3]); ";

        echo "lastN=$x[6]";
        echo '; <a href="index.php?time=' . time() . '&unsetperformance=' . time() . '&id=' . $myId . '">Reset</a>';
        echo "\r\n";
        echo "</li>   \r\n";
        echo '</ol><p>';
        echo "\r\n";
        echo "\r\n";
    }
    function findImagesByDate($myId, $dd = array())
    {
        $thedayfrom = $_GET["day"] ?? "notset";
        $thedayto = ($_GET["dayto"] ?? $thedayfrom);
        $includeold = FALSE;

        $bnfrom = FALSE;
        $bnto = FALSE;

        if ($thedayfrom == "fromto") {
            if (($_GET["fromdate"] ?? "") !== "") {
                $thedayfrom = implode("", explode("-", $_GET["fromdate"]));
            } else {
                $thedayfrom = "today";
            }
            if (($_GET["todate"] ?? "") !== "") {
                $thedayto = implode("", explode("-", $_GET["todate"]));
            } else {
                $thedayto = gmdate("Ymd", localtimeCam($myId));
            }
            if ($thedayfrom != $thedayto) {
                $includeold = "*";
            }
        }
        if (isset($_GET["bntd"])) {
            $bntd =  $_GET["bntd"];
            $bnfrom = "aa" . $bntd . "h";
            $bnto = "aa" . $bntd . "j";
        } else if (isset($_GET["bns"])) {
            $bns = explode("X", ($_GET["bns"] ?? "a"));
            if (count($bns) < 2) {
                echo "<h1>Nothing to show here</h1>";
                echo "<a href=\"index.php\">Home</a>";
                die("Thank you.");
            }
            //var_dump($bns); 
            array_shift($bns);
            array_pop($bns);
            sort($bns);

            $first = $bns[0];
            $last = ($bns[count($bns) - 1] ?? "null");
            if ($last === "null") {
                $last = gmdate("Ymd", localtimeCam($myId)) . "235959v999";
            }

            $bnfrom = "aa" . $first . "h";
            $bnto = "aa" . $last . "j";
            if ($first == $last) {
                $includeold = substr($first, 0, 8);
            } else {
                $includeold = "*";
            }
        } else if ($thedayfrom === "today") {
            $x = localtimeCam($myId);
            $thedayfrom = $thedayto = gmdate("Ymd", $x);
        } else if ($thedayfrom === "yesterday") {
            $x = localtimeCam($myId) - 24 * 60 * 60;
            $thedayfrom = $thedayto = gmdate("Ymd", $x);
        } else if ($thedayfrom === "todayyesterday") {
            $x = localtimeCam($myId);
            $thedayto = gmdate("Ymd", $x);
            $x = localtimeCam($myId) - 24 * 60 * 60;
            $thedayfrom = gmdate("Ymd", $x);
        } else if (intval($thedayfrom) < 0) {
            $x = localtimeCam($myId) + intval($thedayfrom) * 24 * 60 * 60;
            $thedayfrom = $thedayto = gmdate("Ymd", $x);
            $includeold = $thedayfrom;
        } else if ($includeold === FALSE) {
            $includeold = $thedayfrom;
        }
        // echo "day = $theday <p>"; 

        $fromtime = "000000v000h";
        $totime = "235959v999j";

        if (($_GET["fromtime"] ?? "") !== "") {
            $fromtime = implode("", explode(":", $_GET["fromtime"]));
            $fromtime = str_pad($fromtime, 6, "0");
            $fromtime .= "v000h";
        }
        if (($_GET["totime"] ?? "") !== "") {
            $totime = implode("", explode(":", $_GET["totime"]));
            $totime = str_pad($totime, 6, "59");
            $totime .= "v999j";
        }

        if (isset($_GET["minimumage"]) || isset($dd["minimumage"])) {
            $timeto = localtimeCam($myId) - ($_GET["minimumage"] ?? $dd["minimumage"]);
            $bnto = "aa" . gmdate("YmdHis", $timeto) . "v999j";
        }
        if (isset($_GET["agelimit"]) || isset($dd["agelimit"])) {
            $timefrom = localtimeCam($myId) - ($_GET["agelimit"] ?? $dd["agelimit"]);
            $bnfrom = "aa" . gmdate("YmdHis", $timefrom) . "v000h";
        }

        if ($bnfrom === FALSE) {
            $bnfrom = "aa" . $thedayfrom . $fromtime;
        }
        if ($bnto === FALSE) {
            $bnto = "aa" . $thedayto . $totime;
        }

        //	echo "<h1> $bnfrom - $bnto </h1>"; 
        if (!isset($dd["nooutput"])) {

            echo "<script>";
            echo "var event = new CustomEvent('updatedate', { detail: { from: '" . $bnfrom . "', to: '" . $bnto . "' } });";
            echo "window.parent.document.dispatchEvent(event);";
            echo "</script>";
        }

        if (isset($_GET["setzoom"])) {
            $fastmode[$myId] = 20;
            echo "<p><b>Click on an image below to set zoom. <a href=\"index.php?time=" . time() . "&id=5&nogallery=1&nomenu=1&howmany=9&resetzoom=1\">Reset Zoom</a></p>";
        } else if (isset($_GET["settgts"])) {
            $fastmode[$myId] = 20;
            echo "<p><b>Click on an image below to set targets. </b></p>";
        }

        global $findImagesStats;

        $findImagesStats = array();
        $allImages = array();
        $allImagesByTarget = array();
        $maxidx = 0;
        $mytgts = myTargets($myId);

        $howmany = ($dd["howmany"] ?? $_GET["howmany"] ?? 6);
        foreach ($mytgts as $j) {
            $tgtImages = findImages($j, ($howmany ?? 6), true, false, false, $bnfrom, $bnto, $includeold);
            krsort($tgtImages);
            if (($tmp = count($tgtImages)) > $maxidx) {
                $maxidx = $tmp;
            }
            $allImagesByTarget[$j] = $tgtImages;
            // echo "<p>j= $j "; var_dump($tgtImages); 
            // $allImages = array_merge($allImages, $tgtImages);
        }
        //  var_dump(array("old" => $oldestbn, "new" => $newestbn));

        $allImagesA = array();
        /*
        foreach($mytgts as $j ) {
        echo "<p>(AA) $j "; 
        var_dump($allImagesByTarget[$j]); 
        echo "(AZ) $j <\p>"; 
        }
        */


        $donotsort = (isset($_GET["donotsort"]) || isset($dd["donotsort"]));
        $c = 0;
        $jj = 100000;
        for ($k = 0; $c < $howmany && $k < $maxidx; $k++) {
            $xat = array();
            foreach ($mytgts as $j) {
                // krsort($allImagesByTarget[$j]); 
                $bn = array_shift($allImagesByTarget[$j]);
                $xat[] = $bn;
            }
            $xats = orderByClosestToAverage($xat);
            // sort xat in order of closest distance to middle point. 

            foreach ($xats as $a) {
                // $a = array_shift($allImagesByTarget[$j]);
                if ($a != NULL && $c < $howmany) {

                    if (array_key_exists(bn2bntd($a),  $allImagesA)) {
                        $allImagesA[bn2bntd($a) . $jj++] = $a;
                    } else {

                        $allImagesA[bn2bntd($a)] = $a;
                        $c++;
                    }
                }
            }
        }
        if (isset($_GET["donotsort"]) || isset($dd["donotsort"])) {
            // var_dump($allImagesA); 
            return $allImagesA;
        }
        global $oldestbn, $newestbn;
        // var_dump(	$findImagesStats ); 
        if (isset($_GET["includefirstlast"])) {
            if (isset($oldestbn[0])) {
                $allImagesA[bn2bntd($oldestbn[0])] = $oldestbn[0];
            }
            if (isset($newestbn[0])) {
                $allImagesA[bn2bntd($newestbn[0])] = $newestbn[0];
            }
        }
        if (isset($_GET["oldestfirst"])) {
            sort($allImagesA);
        } else {
            rsort($allImagesA);
        }
        return ($allImagesA);
    }



    function saveasgifs($myBn, $delay = 20, $showdate = true, $outpath = FALSE, $silent = FALSE)
    {
        include 'GIFEncoder.class.php';


        $imgGifFolder = "./img/agif/"; // where animated gifs are stored.

        if (!file_exists($imgGifFolder)) {
            mkdir($imgGifFolder, 0777, true);
        }

        $n = 0;
        $frames = array();
        $delays = array();
        foreach ($myBn as $bn) {
            $filename = bn2file($bn);
            if ($filename !== FALSE) {
                $im = imagecreatefromjpeg($filename);

                if ($showdate) {
                    $ts = basename2timestamp($bn);
                    $text = gmdate("D, d M 'y H:i:s", $ts);
                    $textcolour = imagecolorallocate($im, 204, 204, 0);
                    $textshadow = imagecolorallocate($im, 0, 0, 0);

                    // $font = 'arial.ttf'; does not work on windows.
                    $font = dirname(__FILE__) . '/arial.ttf';
                    @imagettftext($im, 24, 0, 10, 446, $textshadow, $font, $text);
                    @imagettftext($im, 24, 0, 9, 445, $textcolour, $font, $text);
                }
                ob_start();
                imagegif($im);
                $frames[] = ob_get_contents();
                $delays[] = $delay;

                ob_end_clean();


                $n++;
            }
        }
        if (count($frames)  > 0) {
            // var_dump($frames);
            if ($silent === FALSE) {
                echo "<p>Processed $n frames<p>";
            }
            $loops = 0;
            $gif = new AnimatedGif($frames, $delays, $loops);
            $outimg = $gif->getAnimation();
            $nf = count($frames);
            if ($outpath === FALSE) {
                $outpath = $imgGifFolder . "g" . time() . "z" . $nf . "f.gif";
            }
            file_put_contents($outpath, $outimg);

            if ($silent === FALSE) {
                echo '<h2><a href="' . $outpath . '">' . $outpath . '</a></h2>';
            }
        } else {
            if ($silent === FALSE) {
                echo "<p>No frames found; no animated gif made</p>";
            }
        }
    }

    function averageBackgroundNoise($bn)
    {

        $numberOfChecks = 25;

        $img = $bn;
        if (is_string($bn)) {
            $file = glob("img/?*?/" . $bn)[0];
            $img = @imagecreatefromjpeg($file);
        }
        if ($img) {

            $w = imagesx($img);
            $h = imagesy($img);
            $r = $g = $b = 0;
            $inc = 0.02;
            srand(1881);
            for ($img && $i = 0; $i < $numberOfChecks; $i++) {
                $x = rand(0, $w - 1);
                $y = rand(0, $h - 1);
                $rgb = @imagecolorat($img, $x, $y);
                if ($rgb !== FALSE) {
                    $r += $rgb >> 16 & 255;
                    $g += $rgb >> 8 & 255; //<<
                    $b += $rgb & 255;
                    if (isset($_GET["showbackground"])) {
                        $colorA = imagecolorallocate($img, 85, 85, 85);
                        // @imagefilledellipse($img, intval(floor($x)), intval(floor($y)), ceil($w * $inc / 2), ceil($h * $inc / 2), $colorA);
                        @imagefilledellipse($img, intval(floor($x)), intval(floor($y)), 10, 10, $colorA);
                    }
                }
            }

            return ($r + $g + $b) / $numberOfChecks;
        } else {
            return -1;
        }
    }
    function average($tgt, $img, $addTarget = false, $highlight = false)
    {
        global $focusX, $focusY;

        $w = imagesx($img);
        $h = imagesy($img);
        $r = $g = $b = 0;
        $fX = ($focusX[$tgt] ?? 0.5);
        $fY = ($focusY[$tgt] ?? 0.5);

        $colorA = null;
        $colorB = null;

        if ($highlight) {
            $colorA = id2color($tgt, $img);
            $colorB = id2color($tgt + 17, $img);
        } else { // a shade of grey.
            $colorA = imagecolorallocate($img, 85, 85, 85);
            $colorB = imagecolorallocate($img, 190, 190, 190);
        }

        $count = 0;
        $inc = 0.02;


        if ($addTarget) {
            $inc = 0.005;
        }
        for ($y = $h * ($fY - 0.06); $y < $h * ($fY + 0.06); $y = $y + ($h * $inc)) {
            for ($x = $w * ($fX - 0.05); $x < $w * ($fX + 0.05); $x = $x + ($w * $inc)) {
                $rgb = @imagecolorat($img, intval(floor($x)), intval(floor($y)));
                if ($rgb !== FALSE) {
                    $r += $rgb >> 16 & 255;
                    $g += $rgb >> 8 & 255; //<<
                    $b += $rgb & 255;
                    $count++;
                }
                if ($addTarget) {
                    @imagesetpixel($img, intval(floor($x)), intval(floor($y)), $colorA);
                    @imagesetpixel($img, intval(floor($x)), intval(floor($y - 1)), $colorA);
                    @imagesetpixel($img, intval(floor($x)), intval(floor($y + 1)), $colorA);
                    @imagesetpixel($img, intval(floor($x + 1)), intval(floor($y)), $colorA);
                    @imagesetpixel($img, intval(floor($x + 1)), intval(floor($y)), $colorA);

                    @imagesetpixel($img, intval(floor($x - 1)), intval(floor($y - 1)), $colorB);
                    @imagesetpixel($img, intval(floor($x - 1)), intval(floor($y + 1)), $colorB);
                    @imagesetpixel($img, intval(floor($x + 1)), intval(floor($y + 1)), $colorB);
                    @imagesetpixel($img, intval(floor($x + 1)), intval(floor($y - 1)), $colorB);
                }
            }
        }

        $targetavg =  ($count == 0 ? 0 : ($r + $g + $b) / $count);
        return $targetavg;
    }

    function aaaa($bn0)
    {
        return ($bn0 == "nopic.jpg" ? 'na' : floatval(explode('d', $bn0)[1]));
    }

    function distance($bn0, $bn1)
    {
        $a0 = explode('d', $bn0);
        $a1 = explode('d', $bn1);

        if (!is_numeric($a0[1]) && !is_numeric($a1[1])) {
            return 0;
        } else if (!is_numeric($a0[1])) {
            return $a1[1];
        } else if (!is_numeric($a1[1])) {
            return $a0[1];
        }

        return abs($a0[1] - $a1[1]);
    }




    function cleanFiles($tgt, $yyyymmdd = FALSE)
    {
        global $keephowmany;

        $i = 0;
        $basenames = array();
        $imgfoldername = "img/" . $tgt . "/";
        $cleanUpConst = ($keephowmany[myCam($tgt)] ?? 500);

        $srcfiles =  glob($imgfoldername . "aa*z.???");
        $n = count($srcfiles);
        echo ',"clean' . $tgt . '" : "';
        echo "Enter Clean: n=$n cleanUpConst=" . $cleanUpConst . "; ";

        if ($n < $cleanUpConst * 1.3) {
            echo " nothing to clean up now.\"";
            return;
        }
        if ($n > 2 * $cleanUpConst) {
            echo " deepclean ";
        }

        $basenames = array_map("basename", $srcfiles);

        sort($basenames);

        $distances = array();
        $distances2sort = array();

        $localMax = array();
        $localMin = array();

        for ($i = 0; $i < $n; $i++) {
            $d = 0;
            if ($i == 0) {
                $d = 2 * distance($basenames[$i], $basenames[$i + 1]);
            } else if ($i == $n - 1) {
                $d = 2 * distance($basenames[$i], $basenames[$i - 1]);
            } else {
                $d = distance($basenames[$i], $basenames[$i + 1]) + distance($basenames[$i], $basenames[$i - 1]);
                if (aaaa($basenames[$i]) > aaaa($basenames[$i - 1]) && aaaa($basenames[$i]) > aaaa($basenames[$i + 1])) {
                    $localMax[$basenames[$i]] = true;
                }
                if (aaaa($basenames[$i]) < aaaa($basenames[$i - 1]) && aaaa($basenames[$i]) < aaaa($basenames[$i + 1])) {
                    $localMin[$basenames[$i]] = true;
                }
            }
            $distances[$i] =  $distances2sort[$i] = $d;
        }
        sort($distances2sort);

        $thresholdIndex = intval($n - $cleanUpConst);
        $threshold = -1;

        if ($thresholdIndex <= 0) {
            $threshold = 0;
        } else {
            $threshold = $distances2sort[$thresholdIndex];
        }

        echo "threshold=" . $threshold . "; ";
        echo "n=" . $n . "; ";

        $r = 0;
        $bestbn = NULL;
        $maxdistance = 0;
        for ($i = 0; $i < $n - 10; $i = $i + 3) {
            if ($distances[$i] <= $threshold) {
                $bn = $basenames[$i];
                if (((array_key_exists($bn, $localMax) == FALSE && array_key_exists($bn, $localMin) == FALSE)) ||
                    $n > 2 * $cleanUpConst
                ) {
                    @unlink($imgfoldername . $bn);
                    $r++;
                }
            }
        }
        echo '..' . $r . ' files removed."';
    }
    // END cleanFiles



    /**
     * tgt: target
     * howmany: number of images output
     * important: use algorithm to find most significant images
     * beforeandafter: choose from howmany /2 images before and howmany/2 images after beforeandafterbn
     * addlast: add the most recent image
     * bnfrom: choose images since bnfrom (including bnfrom). Can be timetstamp or basnemane (then basename2timetstamp)
     * bnto: choose images until bnfrom (including bnto). Can be timetstamp or basnemane (then basename2timetstamp) 
     */


    $findImagesStats = array();
    function findImages($tgt, $howmany = 6, $important = true, $bnmiddle = false, $addlast = false, $bnfrom = 0, $bnto = PHP_INT_MAX, $includeold = false)
    {

        // echo "includeold = $includeold <p>"; 
        global $oldestbn, $newestbn;
        global $findImagesStats;
        $fulltotal = 0;
        /*
        if (isset($_GET["minimumage"])) {
            $bnto = localtimeCam(myCam($tgt)) - $_GET["minimumage"];
        }
        if (isset($_GET["agelimit"])) {
            $bnfrom = localtimeCam(myCam($tgt)) - $_GET["agelimit"];
        }
        */
        if (is_int($bnfrom)) {
            $bnfrom = "aa" . gmdate("YmdHis", $bnfrom) . "v000h";
        }
        if (is_int($bnto)) {
            $bnto = "aa" . gmdate("YmdHis", $bnto) . "v999j";
        }

        if ($howmany === false) {
            $howmany = intval(($_GET["howmany"] ?? 8));
        }
        // echo "<p> bnfrom=$bnfrom bnto=$bnto <p>"; 
        $basenames = array();
        $imgfoldername = "img/" . $tgt . "/";

        $found = false;
        $files = glob($imgfoldername . "aa*z.???", GLOB_NOSORT);
        if ($includeold !== false) {
            if (is_string($includeold)) {
                $filesold = glob("img/old/d" . $includeold . "/t" . $tgt . "/aa*z.jpg", GLOB_NOSORT);
                $files = array_merge($files, $filesold);
            } else {
                $filesold = glob("img/old/d*/t" . $tgt . "/aa*z.jpg", GLOB_NOSORT);
                $files = array_merge($files, $filesold);
            }
        }
        //var_dump($files); 
        $j = 10000;
        $files2 = array();
        foreach ($files as $filename) {
            $bnf = basename($filename);

            $key = bn2bntd($bnf) . "T" . $tgt . "C" . $j++;
            $files2[$key] = $filename;
        }
        //var_dump($files2); 
        ksort($files2);

        $files3 = array_values($files2);
        $i = 0;
        foreach ($files3 as $filename) {
            $fulltotal++;
            $bbn = basename($filename);



            if (strncmp($bbn, $bnfrom, 20) >= 0  && strncmp($bbn, $bnto, 20) <= 0) {
                if ($oldestbn === null || strncmp($bbn, $oldestbn[0], 20) < 0) {
                    $oldestbn = array($bbn, $filename);
                }
                if ($newestbn === null || strncmp($bbn, $newestbn[0], 20) > 0) {
                    $newestbn = array($bbn, $filename);
                }



                $basenames[$i] = $bbn;
                if ($bnmiddle !== false && $found === false) {
                    if (strncmp($bbn, $bnmiddle, 20) >= 0) {
                        $found = $i;
                    }
                }
                $i++;
            }
        }
        $findImagesStats["fulltotal"] = ($findImagesStats["fulltotal"] ?? 0) + $fulltotal;
        $findImagesStats["age"] = ($findImagesStats["age"] ?? 0) + $i;

        // echo "<p>found=$found i=$i<p>"; 
        $res = array();
        if ($bnmiddle !== false) {
            $lowerI = intval(round($found - $howmany / 2, 0));
            $upperI = intval(round($found + $howmany / 2, 0));

            for ($i = $lowerI; $i <= $upperI; $i++) {
                if (array_key_exists($i, $basenames)) {
                    $res[] = $basenames[$i];
                }
            }
            return $res;
        } else if ($important === false) {
            return $basenames;
        }
        // var_dump($basenames); 
        $n = count($basenames);
        //  echo "(a) Selected $n images of total of $fulltotal based on age; then choose $howmany images from these.<br>"; 	

        if ($n == 0) {
            return array();
        }

        // echo "; imgfoldername=". $imgfoldername; 	
        sort($basenames);

        $res = array();
        $distances = array();
        $distances2sort = array();

        if ($n == 1) {
            return array($basenames[0]);
        }
        for ($i = 0; $i < $n; $i++) {
            $d = 0;
            if ($i == 0) {
                $d = 2 * distance($basenames[$i], $basenames[$i + 1]);
            } else if ($i == $n - 1) {
                $d = 2 * distance($basenames[$i], $basenames[$i - 1]);
            } else {
                $d = distance($basenames[$i], $basenames[$i + 1]) + distance($basenames[$i], $basenames[$i - 1]);
            }
            $distances[$i] =  $distances2sort[$i] = $d;
        }
        sort($distances2sort);
        $threshold = $distances2sort[intval(max(($n - 1) - $howmany, 0))];



        // echo "threshold=".$threshold.";";
        // echo " <p>";
        $countOut = 0;
        for ($i = 0; $i < $n; $i++) {
            $bn = $basenames[$i];
            if ($distances[$i] > $threshold || ($addlast && $i == $n - 1)) {
                $key = (intval(10000 * $distances[$i]) * 2 * $n + $countOut) * 100 + ($tgt % 100);
                $res[$key] = $bn;
                $countOut++;
            }
        }
        for ($i = 0; $i < ($addlast ? $n - 1 : $n) && $countOut < $howmany; $i++) {  // $i == $n-1 has already been added, no matter what.
            $bn = $basenames[$i];
            if ($distances[$i] == $threshold) {
                $key = (intval(10000 * $distances[$i]) * 2 * $n + $countOut) * 100 + ($tgt % 100);
                //  $key = ($distances[$i] * 2 * $n + $countOut) * 20 + $tgt;
                $res[$key] = $bn;
                $countOut++;
            }
        }
        // var_dump($res); 
        return $res;
    }

    function getTransparentImage($w = 320, $h = 240)
    {
        $filename = "tmp/transparent" . $w . "x" . $h . "T.png";
        if (!file_exists($filename)) {
            $img = imagecreatetruecolor($w, $h);
            imagesavealpha($img, true);
            $color = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $color);
            imagepng($img, $filename);
        }
        $img = imagecreatefrompng($filename);
        imagesavealpha($img, true);
        return $img;
    }

    function getFrameFile($theId = NULL, $w = 320, $h = 240)
    {
        $myId = $theId ?? $_GET["id"] ?? 10;
        $filename = "tmp/rectangle" . $w . "x" . $h . "a" . $myId . "a.png";
        if (!file_exists($filename)) {
            $im = getTransparentImage($w, $h);
            $colour = id2color($myId ?? 10, $im, 1);

            for ($i = 0; $i < ($w / 10); $i++) {

                @imagerectangle($im, $i, $i, $w - $i, $h - $i, $colour);
            }
            imagepng($im, $filename);
        }
        return $filename;
    }

    function addTargets($myId, $bn = false)
    {
        global $focusX, $focusY;
        $res = array();
        if ($bn) $zz = explode("z", $bn);

        $sourcebn = "nopic.jpg";
        if ($bn && file_exists("img/" . ($zz[1] ?? 0) . "/" . $bn)) {
            $sourcebn = "img/" . ($zz[1] ?? 0) . "/" . $bn;
            copy($sourcebn, "tmp/" . "tgtsrc" . $myId . ".jpg");
        } else if ($bn && file_exists("tmp/tgtsrc" . $myId . ".jpg")) {
            $sourcebn = "tmp/tgtsrc" . $myId . ".jpg";
        } else if (!file_exists("tmp/transparent320x240A.png")) {
            $img = imagecreatetruecolor(320, 240);
            imagesavealpha($img, true);
            $color = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $color);
            imagepng($img, 'tmp/transparent320x240A.png');
        }

        $imgoutfoldername = "tmp/";

        foreach (myTargets($myId) as $bucket) {
            $im = false;
            if ($bn) {
                $im = @imagecreatefromjpeg($sourcebn); // does not work for png
            } else {
                $im = getTransparentImage(); // imagecreatefrompng("tmp/transparent320x240A.png"); 
                imagesavealpha($im, true);
            }
            foreach (myTargets($myId) as $x) {
                if ($x == $bucket) {
                    average($x, $im, true, true); // adds target to $im
                } else {
                    average($x, $im, true, false);
                }
            }
            if (!file_exists($imgoutfoldername)) {
                mkdir($imgoutfoldername, 0777, true);
            }
            $outfilename = "tgt" . $bucket . "tgt_overlay" . ($focusX[$bucket] ?? "X") . "x" . ($focusY[$bucket] ?? "Y") . ".png";
            if ($bn) {
                $outfilename = "tgt" . $bucket . "tgt" . time() . 'z' . $bucket . 'z.jpg';
                imagejpeg($im, $imgoutfoldername . $outfilename);
            } else {
                imagepng($im, $imgoutfoldername . $outfilename);
            }
            $res[$bucket] = $outfilename;
        }

        return $res;
    }



    function displayImages($basenames,  $width = 320, $height = 240)
    {
        global $lastgallery, $videoinfo, $zoom, $zoomX, $zoomY;
        //  echo '<h1>New Gallery starts here</h1>'; 		
        $notexist = 0;
        if (count($basenames) == 0) {
            echo '<h2>Nothing to display here</h2>';
            return;
        }

        $countImgsOut = 0;

        $previousBasenameNoId = "none";
        $theIdString = "";

        $basenamesNoDuplicates = array();
        $idStrings = array();

        $i = 0;
        foreach ($basenames as $bn) {
            $a = explode("z", $bn);
            $cc = explode("c", $a[0])[0];

            $myTargetId = $a[1] ?? 'x';
            $imgfoldername = "img/" . $myTargetId . "/";
            $imgoutfoldername = (isset($_GET["settargetxy"]) || isset($_GET["showtargets"])) ? "tmp/" : $imgfoldername;



            if ($previousBasenameNoId == $cc && !isset($_GET["settargetnow"])) {
                $idStrings[$basenamesNoDuplicates[$i]] .= ", " . ($a[1] ?? 'x');
            } else {
                $i++;
                $basenamesNoDuplicates[$i] = $bn;
                $idStrings[$bn] = ($a[1] ?? 'x');
                $previousBasenameNoId = $cc;
            }
        }
        $i = 0;

        $myId = intval($_GET["id"]);
        $lastgallery[$myId] = $basenamesNoDuplicates;
        // $lastgallery["full" . $myId] = $basenames;
        $lastgallery["nonce"] = "AADKJADSK" . time() . rand(28, 999999);
        echo "\r\n<script> \r\n";
        echo "function getNonce() { return '" . $lastgallery["nonce"] . "'; } \r\n";
        echo "</script> \r\n";

        echo '<div id="images">';
        $tgtoverlay = addTargets($myId);
        // var_dump($tgtoverlay); 

        write2config();
        // var_dump($basenamesNoDuplicates);
        $lastBn = null;
        foreach ($basenamesNoDuplicates as $bn) {
            $a = explode("z", $bn);

            $myTargetId = intval($a[1]);
            if (!isset($tgtoverlay[$myTargetId])) {
                $tgtoverlay[$myTargetId] = current($tgtoverlay); // Just choose any. 
            }
            $imgfoldername = "img/" . $myTargetId . "/";
            if (!file_exists($imgfoldername . $bn)) {
                $files = glob("img/old/d*/t" . $myTargetId . "/" . $bn);
                if (count($files) > 0) {
                    $imgfoldername = dirname($files[0]) . "/";
                }
            }
            $imgoutfoldername = (isset($_GET["settargetxy"]) || isset($_GET["showtargets"])) ? "tmp/" : $imgfoldername;

            $targetid = -1;
            if (isset($_GET["settargetnow"])) {
                $imgoutfoldername = "tmp/";
                $targetid = explode("tgt", $bn)[1];
                // var_dump(explode("tgt", $bn)); 
            }

            $setXY =  (isset($_GET["settargetxy"]) ? "&mode=settargetXY&settargetxy=1&bucket=" . $_GET["id"] : "");
            $setXY .=  (isset($_GET["setzoomxy"]) ? "&mode=setzoomXY" : "");
            $setXY .=  (isset($_GET["settargetdisplay"]) ? "&settargetnow=1" : "");
            $setXY .=  (isset($_GET["settargetnow"]) ? "&settargetnow=10&settargetid=" . $targetid : "");
            //  if( $_GET["settargetxy"] ?? false) { addTarget($myId, $bn, $imgfoldername, $imgoutfoldername ); } 

            $targetfile = 'index.php?time=' . time() . '&';
            // var_dump($videoinfo);
            // var_dump($lastgallery);
            $targetfileA = 'setzoom.php?time=' . time() . '&videoinfo=' . ($videoinfo[$myId] ?? "-1,-1,-1,-1") . "&zoomx=" . ($zoomX[$myId] ?? 0.5) . "&zoomy=" . ($zoomY[$myId] ?? 0.5) . "&zoom=" . ($zoom[$myId] ?? 1) . "&";

            if (isset($_GET["setzoomdisplay"])) {
                $targetfile = $targetfileA;

                //"setzoom.php?videoinfo=".($videoinfo[$myId] ?? "-1,-1,-1,-1")."&zoomx=".($zoomX[$myId] ?? 0.5)."&zoomy=".($zoomY[$myId] ?? 0.5)."&zoom=".($zoom[$myId] ?? 1)."&"; 
            }





            $framefile = getFrameFile();
            $nozoomhref = $targetfile . "id=" . $myId . $setXY . "&imgaction=default&howmany=" . intval($_GET["howmany"] ?? 6) . "&b=" . $bn . "&xy=xy";
            $zoomhref = $targetfileA . "id=" . $myId . $setXY . "&imgaction=default&howmany=" . intval($_GET["howmany"] ?? 6) . "&b=" . $bn . "&xy=xy";
            // echo "<p> zoomhref= $zoomhref <p>"; 
            $extra = "'none'";
            $extrahref = "";
            if (isset($_GET["setzoom"])) {
                $extra = "'zoom'";
                $extrahref = $zoomhref;
            } else if (isset($_GET["settgts"])) {
                $extra = "'tgts'";
                $extrahref = 'index.php?time=' . time() . '&settgts=1&id=' . $myId . $setXY . "&settargetnow=1&imgaction=default&howmany=" . intval($_GET["howmany"] ?? 6) . "&b=" . $bn . "&xy=xy";
            }

            $hidetgts = "hidden=1"; 
          //  $hidetgts = ""; 
            if (file_exists($imgoutfoldername . $bn)) {
                $lastBn = $bn;
                $countImgsOut++;
                echo "<a class=\"container\" what=1 onClick=\"imgclick('" . $bn . "', this, " . $extra . ", '" . $extrahref . "')\"  zoomhref=\"$zoomhref\" nozoomhref=\"$nozoomhref\"><img ismap width=$width height=$height src=\"" . $imgoutfoldername . $bn . "\" alt=\"" . $bn . "\" title=\"" . substr($bn, 2, 8) . "-" . substr($bn, 10, 6) . "\">";
                echo '<img  '.$hidetgts.' width=' . $width . ' height=' . $height . ' class="showtargets" src="tmp/' . $tgtoverlay[$myTargetId] . '" alt="overlay">';
                echo '<img  hidden=1 id=SHOWFRAMES' . $bn . ' width=' . $width . ' height=' . $height, ' class="showframes" src="' . $framefile . '" alt="overlay">';
                if (isset($_GET["settargetnow"])) {
                    add_caption("Target $targetid ");
                } else {
                    add_caption(basename2time($bn) . " {" . $idStrings[$bn] . "}");
                }
                echo "</a>";
            } else {
                $notexist++;
            }


            echo "\r\n";
        }
        echo '</div>';
        echo "<p>not exist: " . $notexist;
        if ($countImgsOut == 1) {
            echo '  <a href="index.php?id=' . $myId . '&clarifaithis=' . $lastBn . '">Clarifai this image</a>;   ';
        }
        echoTimeUsed();
    }

    function echoTimeUsed()
    {

        $end = hrtime(true);
        $eta = $end - $GLOBALS["start"];
        echo $eta / 1e+6;
        echo '<button type="button" onclick="getURL();">Show Page URL</button>';
        echo '<p><hr>';
    }



    function receiveImagesA($myId)
    {
        global $keephowmany, $imgsizeinfo;
        $itotalThisUpload = 0;
        $icountThisUpload  = 0;

        if (!isset($imgsizeinfo[$myId])) {
            $imgsizeinfo[$myId] = array(localtimeCam($myId), 0, 0);
        }

        /* To provide a unique number ($count) to be used in the filename. 
		    As we also save the index $i below, one $count for each batch is sufficient
		 */
        $countfile = "countlog4.txt";
        $datei = @fopen($countfile, "r");
        $count = @fgets($datei, 1000);
        if ($count === FALSE) {
            $count = 37;
        }
        @fclose($datei);
        $count = $count + 1;
        $datei = fopen($countfile, "w");
        fwrite($datei, $count);
        fclose($datei);
        $ret = 0;

        $imgMax = $_POST['n'] ?? 0; // $imgMax == 0: return gallery.

        $tgts = myTargets($myId);

        for ($i = 0; $i < $imgMax; $i++)  if (isset($_POST['imgData' . $i])) { // should always be true		     
            $img = $_POST['imgData' . $i];
            $img = str_replace('data:image/jpeg;base64,', '', $img);

            $img = str_replace(' ', '+', $img); // dont ask me why, something to do with base64 decoding and html spaces.
            $fileData = base64_decode($img);

            $prefix = "aa"; // needs to be two letters, otherwise dates don't work.

            $nowdate = $_POST['imgTime' . $i] ?? 0;
            $nowMillies = ($_POST['imgMs' . $i] ?? 0);
            $zoominfo = $_POST['imgZoom' . $i] ?? "0000000";
            $jpginfo = $_POST['imgJpgc' . $i] ?? "xx";

            $imgout = imagecreatefromstring($fileData);
            if (!$imgout) {
                $imgout = imagecreatefromjpeg("nopic.jpg");
            }
            $imgfoldername = false;

            $bgavg = averageBackgroundNoise($imgout);
            $ret = $bgavg;

            $GLOBALS["countImagesSaved"]++;
            foreach ($tgts as $tgt) {
                $imgfoldername = "img/" . $tgt . "/";
                if (!file_exists($imgfoldername)) {
                    mkdir($imgfoldername, 0777, true);
                }
                $targetavg = average($tgt, $imgout);



                $formuladiff = $targetavg - $bgavg;
                $imgValues =  array($formuladiff, $bgavg, $targetavg);

                $newfilename = $prefix . $nowdate . "v" . sprintf('%03d', $nowMillies) . "i" . sprintf('%04d', $i) . "c" . $count . "d" . round($imgValues[0], 3) . "d" . round($imgValues[1], 3) . "d" . round($imgValues[2], 3) . "d" . $jpginfo . "y" . $zoominfo . "yz" . $tgt . "z.jpg";
                file_put_contents($imgfoldername . $newfilename, $fileData);
            }


            $imgsizeinfo[$myId][1] += strlen($fileData); //equals filesize( $imgfoldername.$newfilename )
            $imgsizeinfo[$myId][2]++;



            if ($i == $imgMax - 1 && $imgfoldername) { // last one. 
                $imgLastFolder = "./img/last/"; // where the last image is stored.
                if (!file_exists($imgLastFolder)) {
                    mkdir($imgLastFolder, 0777, true);
                }
                // Destroy the previous one.
                foreach (glob($imgLastFolder . "H" . $myId . "H*z*z.???") as $filename) {
                    @unlink($filename);
                }
                copy($imgfoldername . $newfilename, $imgLastFolder . 'H' . $myId . 'H' . $newfilename);
            }  // end of last one

            imagedestroy($imgout);
        }


        return $ret;
    }

    ?>