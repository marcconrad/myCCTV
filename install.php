<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Install</title>
</head>
<body>
<h1>Hello</h1>
<?php
$myfiles = array("setupinstall.php", "viewlog.php", "menu.php", "info.php", "util.php", "cam.php", "index.php", "nopic.jpg", "devbackup.php", "choosedate.php", "zipdelete.php", "setzoom.php", "archive.php", "zipcurrent.php", "arial.ttf", "GIFEncoder.class.php", "viewgifs.php"); 
$outputfile = "install.php"; 

// echo basename(__FILE__);
$delimiter = "zzzzabc"."zzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz";

if( basename(__FILE__) == "setupinstall.php" ) {  
echo '<h1>Generate install.txt</h1>'; 
$out = "";  
foreach($myfiles as $f ) { 
   $contents = file_get_contents($f);
	 if(pathinfo($f, PATHINFO_EXTENSION) != 'php' ) { $contents = base64_encode($contents); } 
	 $out .= $contents; 
	 $out .= $delimiter; 
	 }
echo '<p>'; 
// echo $out; 
$zip = new ZipArchive();
$filename = "./mycctv.zip";

if(file_exists($filename) ) { 
    @unlink($filename); 
}

if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
}

$zip->addFromString("install.php" , $out );
echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";
$zip->close();
echo '<a href="'.$filename.'">Download Zip file</a>'; 
echo '<p>'; 


echo '<a href="install.txt">Download install.txt</a><p>'; 
file_put_contents("install.txt", $out); 
echo "After downloading, rename install.txt to install.php, then upload to your server and run. Thank you."; 
echo "</body></html>";
die(); 	 
	 
} else if( basename(__FILE__) == "install.php" ) { 
  $in = file_get_contents("install.php");
	$parts = explode($delimiter, $in); 
	// var_dump($parts); 
	$i=0; 
	foreach($myfiles as $f ) { 
   $contents = $parts[$i++]; 
	 if(pathinfo($f, PATHINFO_EXTENSION) != 'php' ) { $contents = base64_decode($contents); } 
	 $filename = $f;
	 if( file_exists($filename) ) { 
	 		 echo("The file ".$filename." is already installed.<p>"); 
			 }
	 else { 
	  file_put_contents($filename, $contents);
		echo("The file ".$filename." <b>has</b> been installed.<p>"); 
		}
	 }
	echo '<p><a href="index.php">START HERE</a></p>'; 
	die(); 
} 

?>


zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>Clarifai Log</title>
    <style>
        body {

            background-color: #fff;
            background-image:
                linear-gradient(90deg, transparent 79px, #abced4 79px, #abced4 81px, transparent 81px),
                linear-gradient(#eee .1em, transparent .1em);
            background-size: 100% 1.2em;
        }
    </style>
</head>

<body>
    <h2>Clarifai Log File</h2>
    <em>Times are in UTC.</em>
    <p>
        <?php
        $logfile = $_GET["logfile"] ?? "log/__log.html";
        if(file_exists($logfile)) { 
        $txt = file_get_contents($logfile);
        // echo $txt; 


        $tt = array_reverse(explode("<br>", $txt));
        array_shift($tt);

        echo "<ol>";
        foreach ($tt as $t) {
            echo "<li>";
            echo $t;
            echo "</li>";
        }
        echo "</ol>";
        echo "<p>Thank you";
    } else { 
        echo "<p>The file $logfile does not exist."; 
        echo '<p> <a href="index.php?t='.time().'&src=fromlog" >Home </a></p>';
    }

        ?>
    </p>
</body>

</html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE html>
<html>
<?php
include_once "./util.php";
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <!-- https://leaverou.github.io/css3patterns/ -->
    <style>
        body {
            /* background-color: rgb(202, 185, 63); */
            background:
                radial-gradient(circle farthest-side at 0% 50%, #fb1 23.5%, rgba(240, 166, 17, 0) 0)21px 30px,
                radial-gradient(circle farthest-side at 0% 50%, #B71 24%, rgba(240, 166, 17, 0) 0)19px 30px,
                linear-gradient(#fb1 14%, rgba(240, 166, 17, 0) 0, rgba(240, 166, 17, 0) 85%, #fb1 0)0 0,
                linear-gradient(150deg, #fb1 24%, #B71 0, #B71 26%, rgba(240, 166, 17, 0) 0, rgba(240, 166, 17, 0) 74%, #B71 0, #B71 76%, #fb1 0)0 0,
                linear-gradient(30deg, #fb1 24%, #B71 0, #B71 26%, rgba(240, 166, 17, 0) 0, rgba(240, 166, 17, 0) 74%, #B71 0, #B71 76%, #fb1 0)0 0,
                linear-gradient(90deg, #B71 2%, #fb1 0, #fb1 98%, #B71 0%)0 0 #fb1;
            background-size: 40px 60px;
        }
 

        .mainframe {
            overflow: hidden;
            width: 99%;
            height: 90%;
            position: absolute;
            animation: out 2s;
            animation-fill-mode: forwards;
            animation: in 2s;
        }

        .statsframe {
            width: 50vw;
            position: absolute;
            min-height: 380px;
            overflow: hidden;
            z-index: 5;
        }

        .dropdownsub {
            position: relative;
            display: inline-block;

        }

        .dropdownsub-content {
            border-radius: 5px;
            display: none;
            position: absolute;
            background-color: rgba(50, 50, 0, 0.8);
            min-width: 120px;
            box-shadow: 20px 20px 10px rgba(50, 50, 0, 0.7);
            padding: 0px 0px;
            left: 33%;
        }

        .dropdown {

            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            border-radius: 5px;
            display: none;
            position: absolute;
            background-color: #f9aaf9;
            min-width: 80px;
            box-shadow: 20px 20px 10px rgba(50, 50, 0, 0.7);
            padding: 0px 10px;
            z-index: 2;
            left: 10%;
        }

        .button:hover {
            background-color: yellow;
            color: #020305;
        }

        .button {
            border-radius: 5px;
            background-color: #f9ddf9;
            color: #020305;
            min-width: 120px;
            padding: 0px 0px;
            z-index: 1;
            min-height: 30px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16pt;
            vertical-align: middle;
        }

        .buttonhowmanyop {
            min-width: 1px;
            background-color: #ada77c;
            border-radius: 3px;
            font-family: Impact, Charcoal, sans-serif;

        }


        .buttontop {
            min-width: 1px;
        }

        .datetime {
            background-color: #ad007c;
            /*
            border-radius: 1px;
            border-style: none;
            border-width: 5px;
            */
        }

        .select {
            min-width: 20px;
            padding: 0px 0px;
            text-align: center;
            vertical-align: middle;
        }

        .howmany {
            min-width: 2em;
            text-align: center;
            width: 3em;
            font-family: Impact, Charcoal, sans-serif;

        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdownsub:hover .dropdownsub-content {
            display: block;
            z-index: 1;
        }

        .time {

            padding: 0px 0px;
            /* box-shadow: none; */
        }
    </style>
</head>

<body>
    <div class="dropdown">
        <button class="buttontop button" id="cats" name="cats">&#x1F408;</button>
        <div id="myDropdown0" class="dropdown-content">
            <button class="button" onClick="goTo('viewgifs.php?t='+Date.now()+'')">Cats</button>
            <br>
            <button class="button" onClick="goTo('viewgifs.php?t='+Date.now()+'&edit=1')">Cats (Edit)</button>
            <br>
            <button class="button" onClick="goTo('viewgifs.php?t='+Date.now()+'&silent=1')">Only Cats</button>
            <br>
            <button class="button" onClick="goTo('viewlog.php?t='+Date.now()+'')">Cfai Log</button>
            <br>
            <button class="button" onClick="goTo('viewgifs.php?t='+Date.now()+'&listconcepts=1')">Concepts</button>
            <br>
            <button class="button" onClick="goTo('img/agif/?t='+Date.now()+'')">Stored Gifs</button> <br>

            <br>
        </div>
    </div>
    <div class="dropdown">
        <button class="buttontop button" id="statusemoji" onClick="goTo('index.php?t='+Date.now()+'&showstats=1')" onmouseover="resetstatsframe()">🎪</button>
        <div id="myDropdown" class="dropdown-content">

            <iframe id="statsframe" class="statsframe" src="index.php?time=1590796951&showstats&id=<?php echo $_GET["id"] ?>"></iframe>
        </div>

    </div>
    <div class="dropdown">
        <button class="buttontop button" id="manage" name="manage">🔨</button>
        <div id="myDropdown" class="dropdown-content">
            <a href="index.php"><button class="button">Home</button></a>
            <br>
            <!--
            <div class="dropdownsub">
                <button class="button" onClick="goTo('index.php?t='+Date.now()+'&showstats=1')" onmouseover="resetstatsframe()">Stats</button>
                <div class="dropdownsub-content">
                    <iframe id="statsframe" class="notthestatsframe" src="index.php?time=1590796951&showstats&id=<?php echo $_GET["id"] ?>"></iframe>
                </div>
            </div>
    -->
            <!-- <button class="button" onClick="removeFrame()">Remove Frame</button> -->
            <br>
            <div class="dropdownsub">
                <button class="button">Setup &raquo;</button>
                <div class="dropdownsub-content">
                    <button class="button" onClick="goFromTo('setzoom')">Set Zoom</button>
                    <button class="button" onClick="goFromTo('settgts')">Set Targets</button>
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+'&startcam=1')">Start Cam</button>
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+'&startcam=1&resetuqt=1')">Start Cam!</button>

                </div>
            </div>
            <br>
            <button class="button" onClick="goTo('index.php?t='+Date.now()+'&nomenu=1&setupcontrol=1')">Control</button>
            <div class="dropdownsub">
                <button class="button">Animate &raquo;</button>
                <div class="dropdownsub-content">
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+'&savecurrentasgifs=1')">No Date</button>
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+'&showdate=1&savecurrentasgifs=1')">With Date</button>
                </div>
            </div>
            <button class="button" onClick="zipcurrent()">Zip Current</button>
        </div>
    </div>


    <select class="button select" id="topselect" onChange="selectCam()" onClick="selectCam()">
        <?php
       // $numbers = array("0", "🏡", "🧱", "🚗", "👨", "🛏️", "🛋️", "🌳");
        $tgts = glob("img/*0*/", GLOB_ONLYDIR);
        $cams = array();
        foreach ($tgts as $tgtdir) {
            $k = intval(substr($tgtdir, 4, 1));
            $cams[$k] = $k;
        }

        $cams[intval($_GET["id"] ?? 1 ) ] = intval($_GET["id"] ?? 1 ) ; 
      


        foreach ($cams as $j) {
            echo "<option name=$j value=$j ";
            if (intval($_GET["id"] ?? 1 ) == $j) {
                echo " selected ";
            }
            // $jj = $numbers[$j] ?? $j;
            $jj = id2emoji($j);
            echo ">" . $jj . "</option>";
        }
        ?>
    </select>


    <div class="dropdown">

        <?php
        echo '<input class="howmany button" id="howmany" type="number" value="' . ($_GET["howmany"] ?? 12) . '" name="n" min="0" max="9999" onchange="howmanychange()" blastyle="width:3em">';
        ?>
        <div id="myDropdown4" class="dropdown-content">
            <?php
            $jjj = array(1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610, 987, 1597);
            // $jjj = array(2, 5, 9, 19, 50, 100, 999); 
            foreach ($jjj as $j) {
                echo '<button class="button buttonhowmanyop" onclick="howmanychange(' . $j . ')" >' . $j . '</button> ';
                // echo '<br>';
            }
            ?>
        </div>
    </div>

    <button class="buttontop button" id="includefirstlast" onClick="toggleIncludefirstlast()">⭕</button>
    <button class="buttontop button" id="direction" onClick="toggleDirection()">↪️</button>
    <button class="buttontop button" id="target" onClick="toggleTarget()">🎯</button>
    <button class="buttontop button" id="justGO" onClick="goFromTo()">🔄</button>
    <button class="buttontop button tooltip" id="sortbtn" title="Order chronologically or by importance." onClick="toggleSort()">🕰️</button>

    
    <button hidden=1 class="buttontop button" id="testbestimage" onClick="goTo('index.php?t='+Date.now()+'&howmany=7&testbestimage=1')">🔮</button>

    &NonBreakingSpace; &NonBreakingSpace; 

    <span class="button datetime" onmouseover="comeDateGlue()" onmouseout="leaveDateGlue()">
        <div class="dropdown">
            <button class="buttontop button" id="byday" name="day">📆</button>
            <div id="myDropdown2" class="dropdown-content">
                <div class="dropdownsub">
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&howmany=12')">Today</button>
                    <div class="dropdownsub-content">
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=300&addlast=1&howmany=12')">Last 5 min</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=1800&addlast=1&howmany=12')">&frac12; hr ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=3600&addlast=1&howmany=12')">1 hr ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=7200&howmany=12')">2 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=21600&howmany=12')">6 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=43200&howmany=12')">12 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=7200&minimumage=1800&howmany=12')">&frac12;-2 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=today&agelimit=5400&minimumage=1800&howmany=12')">&frac12;-1&frac12; hrs ago</button>
                   
                    </div>
                </div>
                <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=yesterday&howmany=12')">Yesterday</button>
                <button class="button" onClick="goTo('index.php?t='+Date.now()+' &day=todayyesterday&howmany=12')">Today & Yesterday</button>

                <?php
                for ($i = 2; $i < 8; $i++) {
                    echo " <button class=\"button\" onClick=\"goTo('index.php?t='+Date.now()+'&day=" . (0 - $i) . "&howmany=12')\" >" . $i . " days ago</button>  ";
                }
                ?>
                <button class="button" onClick="goTo('choosedate.php?t='+Date.now()+' &howmany=12')">Choose Day</button>
            </div>
        </div>

        <input class="button buttontop date" onchange="dateOrTimeChanged()" type="date" id="fromdate" name="fromdate">
        <input class="button buttontop time" onchange="dateOrTimeChanged()" step=1 type="time" id="fromtime" name="fromtime">

        <button class="buttontop button" id="dateglue" onClick="goFromTo()">😴</button>

        <input class="button buttontop date" onchange="dateOrTimeChanged()" type="date" id="todate" name="todate">
        <input class="button buttontop time" onchange="dateOrTimeChanged()" step=1 type="time" id="totime" name="totime">
    </span>


    <script>
        var id = <?php echo intval($_GET["id"] ?? 1); ?>;
        <?php
        if($_SERVER['SERVER_NAME'] === "localhost") { 
            echo "var httpx = 'http';";
        } else { 
            echo "var httpx = 'https';";
        }
        ?>
        var to1 = setTimeout("selectCam()", 1);
        // var id = 0; 
        function zipcurrent() {
            var tn = document.getElementById('mainframe').contentWindow.getNonce;
            // var nonce = document.getElementById('mainframe').contentWindow.getNonce();
            if (typeof(tn) === "function") {
                console.log("nonce=" + tn());
                goTo('zipcurrent.php?nonce=' + tn() + '&t=' + Date.now() + '');
            } else {
                // goTo('zipcurrent.php?nonce=cannotzipthat&t='+Date.now()+''); 
                alert("✋This cannot be zipped. 😕")
            }
        }

        function selectCam() {
            var x = document.getElementById("topselect").selectedIndex;
            var y = document.getElementById("topselect").options;
            // document.getElementById("infoT").innerHTML = Date.now();
            console.log(y[x]);

            var t = y[x].getAttribute("name");

            console.log(t);
            if (id === t) {
                return;
            }
            id = t;
            clearTimeout(gotofalse);
            gotofalse = setTimeout('goFromTo()', 202);
        }

        window.document.addEventListener('updatehowmany', handleHowmanyUpdate, false)

        function handleHowmanyUpdate(e) {
            console.log("handleHowmmanyUpdate" + e.detail) // outputs: {foo: 'bar'}
            // document.getElementById("infoT").innerHTML = e.detail;
            howmanychange(e.detail);
        }


        function goFromTo(mode = false) {

            var at = document.getElementById("fromtime").value;
            var ad = document.getElementById("fromdate").value;

            var bt = document.getElementById("totime").value;
            var bd = document.getElementById("todate").value;

            var extra = "";
            if (mode === 'setzoom') {
                extra += "&setzoom=1";
            }
            if (mode === 'settgts') {
                extra += "&settgts=1";
            }
            goTo('index.php?t=' + Date.now() + '&day=fromto&fromdate=' + ad + '&todate=' + bd + '&fromtime=' + at + '&totime=' + bt + '&howmany=12' + extra);

        }

        function toggleIncludefirstlast() {
            if (document.getElementById("includefirstlast").innerHTML == "⭕") {
                document.getElementById("includefirstlast").innerHTML = "🔛";
            } else {
                document.getElementById("includefirstlast").innerHTML = "⭕";
            }
            goFromTo();
        }

        function toggleTarget() {
            if (document.getElementById("target").innerHTML == "🎯") {
                document.getElementById("target").innerHTML = "✨";
                f = document.getElementById("mainframe"); 
                f.contentWindow.showtargets(true); 
            } else {
                document.getElementById("target").innerHTML = "🎯";
                f = document.getElementById("mainframe"); 
                f.contentWindow.showtargets(false); 
            }
           // goFromTo();
        }

        function toggleDirection() {
            if (document.getElementById("direction").innerHTML == "↪️") {
                document.getElementById("direction").innerHTML = "↩️";
            } else {
                document.getElementById("direction").innerHTML = "↪️";
            }
            goFromTo();
        }

        function getDirection() {
            if (document.getElementById("direction").innerHTML == "↪️") {
                return "oldestfirst";
            } else {
                return "newestfirst";
            }

        }

        function toggleSort() {
            if (document.getElementById("sortbtn").innerHTML == "🕰️") {
                document.getElementById("sortbtn").innerHTML = "📉";
            } else {
                document.getElementById("sortbtn").innerHTML = "🕰️";
            }
            goFromTo();
        }

        function getSort() {
            if (document.getElementById("sortbtn").innerHTML != "🕰️") {
                return "donotsort";
            } else {
                return "sortbyage";
            }

        }

        function comeDateGlue() {
            document.getElementById("dateglue").innerHTML = "❤️";
            document.getElementById("dateglue").style.backgroundColor = "red";
        }

        function leaveDateGlue() {
            document.getElementById("dateglue").innerHTML = "⏰";
            document.getElementById("dateglue").style.backgroundColor = "green";
        }
        var gotofalse;

        function hideOrNotToDate() {

            if (document.getElementById("todate").value != document.getElementById("fromdate").value) {
                document.getElementById("todate").style.display = "inline-block";
            } else {
                document.getElementById("todate").style.display = "none";
            }

            // goFromTo(false);
        }

        function dateOrTimeChanged() {
            hideOrNotToDate()
            clearTimeout(gotofalse);
            gotofalse = setTimeout('goFromTo()', 204);
        }
        window.document.addEventListener('updatedate', handleUpdateDate, false);

        function handleUpdateDate(e) {
            console.log("from>>>>>" + e.detail.from);
            console.log("toto>>>>>" + e.detail.to);

            var from = e.detail.from;
            document.getElementById("fromdate").value = from.substring(2, 6) + "-" + from.substring(6, 8) + "-" + from.substring(8, 10);
            document.getElementById("fromtime").value = from.substring(10, 12) + ":" + from.substring(12, 14) + ":" + from.substring(14, 16);

            var to = e.detail.to;
            document.getElementById("todate").value = to.substring(2, 6) + "-" + to.substring(6, 8) + "-" + to.substring(8, 10);
            document.getElementById("totime").value = to.substring(10, 12) + ":" + to.substring(12, 14) + ":" + to.substring(14, 16);

            hideOrNotToDate();


        }

        window.document.addEventListener('imageclicked', handleImageClicked, false);

        var date1 = null;
        var date2 = null;

        function handleImageClicked(e) {
            date2 = date1;
            date1 = e.detail.dt;
            console.log("Extra1: " + e.detail.extra + "dt: " + e.detail.dt + " zhref=" + e.detail.zoomhref);

            if (e.detail.extra === "zoom" || e.detail.extra === "tgts") {
                date1 = null;
                date2 = null;
                goTo(e.detail.zoomhref);
                return;
            }

            if (date1 === date2) {
                date1 = null;
                date2 = null;
                return;
            }
            if (date2 === null) {
                return;
            }

            var gohere = 'index.php?t=' + Date.now() + '&day=bns&bns=X' + date1 + 'X' + date2 + 'X&howmany=12';
            date1 = null;
            date2 = null;
            goTo(gohere);

        }

        window.document.addEventListener('doubleclicked', handleImageDoubleClicked, false);

        function handleImageDoubleClicked(e) {
            console.log("doubleclicked(parent)" + e.detail);
            var a = e.detail;
            var gohere = 'index.php?t=' + Date.now() + '&day=bns&bns=X' + a + 'X' + a + 'X&howmany=12';
            date1 = null;
            date2 = null;
            goTo(gohere);

        }

        var pW = setTimeout('goTo("index.php?t0="+Date.now()+"&day=today&howmany=12")', 2);


        var newsrc = null;

        function goTo(str) {
            if (str === false) {
                str = newsrc;
            }
            if (str === "reload") {
                str = document.getElementById("mainframe").src;
            }
            console.log("(a)" + str);
            str = str.replace(/howmany=\d+/g, "howmany=" + document.getElementById("howmany").value);
            str = str.replace(/id=\d+/g, "id=" + id);

            setTimeout(updateStatusEmoji, 10); 
            document.getElementById("target").innerHTML = "🎯";

            if (str.startsWith("http")) {
                document.getElementById("mainframe").src = str
            } else {
                var pn = window.location.pathname;
                var dir = pn.substring(0, pn.lastIndexOf('/'));
                var includeFL = "";
                if (document.getElementById("includefirstlast").innerHTML == "🔛") {
                    includeFL = "&includefirstlast=1";
                }

                newsrc = httpx+"://" + window.location.host + "" + dir + "/" + str + '&id=' + id + '&a=1' + includeFL + "&" + getDirection() + "=1&" + getSort() + "=1";
                console.log("(b)" + newsrc);
                document.getElementById("mainframe").src = newsrc;
            }
            console.log("fr=" + document.getElementById("mainframe").src);
        }

        function resetstatsframe() {
            var pn = window.location.pathname;
            var dir = pn.substring(0, pn.lastIndexOf('/'));
            console.log(dir);
            document.getElementById("statsframe").src = httpx+"://" + window.location.host + "" + dir + "/index.php?time=" + Date.now() + "&showstats&id=" + id;
            setTimeout(updateStatusEmoji, 10); 

        }
        // var to = setInterval(updateStatusEmoji, 60000); 


        function updateStatusEmoji() {
            console.log("update Status Emoji called"); 
            var pn = window.location.pathname;
            var dir = pn.substring(0, pn.lastIndexOf('/'));
            var xmlhttp = new XMLHttpRequest();
            var url =  httpx+"://" + window.location.host + "" + dir + "/info.php?t=s"+Date.now()+"&id=" + id + "&statusemoji=1";

            xmlhttp.onreadystatechange = function() {
                console.log("url = "+url); 
                console.log("Hello from bla: "+this.readyState );
                if (this.readyState == 4 && this.status == 200) { 
                    console.log("Hello from bla: "+this.responseText );
                    console.log("url = "+url); 
                    var x = document.getElementById("statusemoji");
                    var t = JSON.parse(this.responseText);
                      x.innerHTML = t.emoji;
                }
            };
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
           
        }

        function removeFrame() {
            // fr = document.getElementById("mainframe").src; 
            newURL = document.getElementById("mainframe").contentWindow.location.href;
            // var newURL = window.location.protocol + "//" + window.location.host + "/" + fr.location.pathname + fr.location.search
            console.log("newURL=" + newURL);
            window.location.href = newURL;
        }

        async function howmanychange(newval = null) {
            var array = [];
            var hm = document.getElementById("howmany");
            if (hm == null) {
                return;
            }
            if (newval == document.getElementById("howmany").value) {
                return;
            }
            newval = newval || document.getElementById("howmany").value;
            document.getElementById("howmany").value = newval;
            if (newsrc != null) {
                clearTimeout(gotofalse);
                gotofalse = setTimeout('goFromTo()', 2);
            }
        }
    </script>
    <p>


        <iframe src="nopic.jpg" class="mainframe" id="mainframe" title="Pictures are usually shown here" width="100%" scrolling="yes" style="border:none;"></iframe>

</body>

</html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz    <?php
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

    </html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<?php

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

function id2emoji($myId)
{
    $numbers = array("0", "🏡", "🧱", "🚗", "👨", "🛏️", "🛋️", "🌳");
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
    $myId = ($myId ?? 21) * 37 % 64; // 64 different colours, mix then a little bit up

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
?>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML>

<html>

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <style>
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

    <title>Cam A</title>
</head>

<body>

    <a target="_blank" href="index.php?mode=home">Home</a>,

    <?php
    echo "<h1>Camera " . intval($_GET["id"] ?? 0) . "</h1>";

    ?>
    <!--
  From: https://davidwalsh.name/browser-camera
  	Ideally these elements aren't created until it's confirmed that the 
  	client supports video/camera, but for the sake of illustrating the 
  	elements involved, they are created with markup (not JavaScript)
		
		Replace by: https://simpl.info/getusermedia/sources/ (?)
		<button id="snap">Snap Photo</button>
	<p>
  -->

    <p>
        <video id="video" width="100" height="100" autoplay></video>
    </p>
    <p>



        <canvas id="canvas" width="50" height="50"></canvas>
    </p>
    <p>
        <button class="button" onclick="hideSomething('canvas')">Show/Hide Canvas</button>
        <button class="button button2" onclick="hideSomething('video')">Show/Hide Video</button>
        <button class="button button3" onclick="togglepause()">Start/Pause</button>
    </p>
    <p>
        <button class="button button4" onclick="hideSomething('ulinfo')">Show/Hide Info</button>
        <button class="button button5" onclick="hideSomething('olinfo')">Show/Hide Debug Info</button>
        <script>
            <?php
           // $updateInMilliseconds = (intval($_GET["update"] ?? 0 ) > 0 ? intval($_GET["update"] ?? 0) : 1000);
           $updateInMilliseconds = intval($_GET["update"] ?? 1000 ) ;
            echo "var updateInMilliseconds = " . $updateInMilliseconds . ";";

            $uniquetoken = "U" . base_convert("b" . rand(11, 99) . (time() % rand(99999, 999999)), rand(12, 24), 33);
            echo "\n var uniquetoken = '" . $uniquetoken . "';";

            ?>

            var newUpdateInMilliseconds = updateInMilliseconds;
            var imgMaxHardLimit = 240;
            var gapBetweenPostsHardLowerLimit = 10000; // Millieconds
            var gapBetweenPostsHardUpperLimit = 3600000; // Millieconds



            var battery;
            var batteryinfo = "w,w,w,w,0";

            async function startReadBattery() {
                if (navigator.battery) {

                    readBattery(navigator.battery);

                } else if (navigator.getBattery) {
                    navigator.getBattery().then(readBattery);

                } else {
                    addLI("battery", "Battery not supported," + getTimeNow(), 1);
                    batteryinfo = "x,x,x,x," + getTimeNow();
                }

            }

            function readBattery(b) {
                battery = b || battery;

                var percentage = parseFloat((battery.level * 100).toFixed(2)) + '%',
                    fully,
                    remaining;

                if (battery.charging && battery.chargingTime === Infinity) {
                    fully = 'Calculating...';
                } else if (battery.chargingTime !== Infinity) {
                    fully = battery.chargingTime;
                } else {
                    fully = '---';
                }

                if (!battery.charging && battery.dischargingTime === Infinity) {
                    remaining = 'Calculating...';
                } else if (battery.dischargingTime !== Infinity) {
                    remaining = battery.dischargingTime;
                } else {
                    remaining = '---';
                }
                batteryinfo = battery.level + "," + battery.charging + "," + battery.chargingTime + "," + battery.dischargingTime + "," + getTimeNow();
                addLI("battery", getTimeNow() + ": Battery=" + batteryinfo, 2);
                // document.getElementById('batteryinfo').innerHTML=res; 

            }

            function hideSomething(what) {
                var x = document.getElementById(what);
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }

            function togglepause() {
                if (donotsend) {
                    addLI("cycle", getTimeNow(true) + "REQUEST IN PROGRESS; PLEASE TRY AGAIN LATER", 3);
                } else {
                    pauseCapture = !pauseCapture;
                }
            }

            var myInterval = setInterval(updateClock, updateInMilliseconds);

            var pauseCapture = false;
            var tempCount = 2;
            var countDownBeforeStart = 2;
            var thePostData = "";
            var countImages = 0;
            var imagesAdded = 0;
            var zoom = 1;
            var zoomX = 0.5;
            var zoomY = 0.5;
            var oneOffPost = true;
            var twidth = 640; // target width
            var theight = 480; // target width
            var jpgcompression = 0.7;

            var droppedFrames = 0;
            var averageDroppedFrames = 0;
            var numberOfRequests = 0;
            var numberOfTimeouts = 0;
            var numberOfConnectErrors = 0;
            var numberOfSuccesses200 = 0; 
            var numberOfSuccessesNot200 = 0; 
            var totalImagesSent = 0; 
            var totalImagesSavedByServer = 0; 

            var totaljsonreturnerror = 0; 
            var totalinvalidjson = 0; 

            var donotsend = false;

            var lastConnect = Date.now();
            var minWaitBetweenConnections = 30000; // in milliseconds

           

            document.getElementsByTagName("canvas")[0].setAttribute("width", twidth);
            document.getElementsByTagName("canvas")[0].setAttribute("height", theight);
            <?php
            // echo 'var maxImagesPost = ' . (intval($_GET["imagesperpost"]) ? intval($_GET["imagesperpost"]) : 60) . ';';
            echo 'var maxImagesPost = ' . intval($_GET["imagesperpost"] ?? 60); 
            ?>

            var timeTaken = "notworking";
            var timeMillies = 0;

            Date.prototype.yyyymmdd = function(separator = '') {
                var mm = this.getMonth() + 1; // getMonth() is zero-based
                var dd = this.getDate();

                return [this.getFullYear(),
                    (mm > 9 ? '' : '0') + mm,
                    (dd > 9 ? '' : '0') + dd
                ].join(separator);
            };

            Date.prototype.hhmmss = function(separator = '') {
                var hh = this.getHours();
                var mm = this.getMinutes();
                var ss = this.getSeconds();

                return [(hh > 9 ? '' : '0') + hh,
                    (mm > 9 ? '' : '0') + mm,
                    (ss > 9 ? '' : '0') + ss
                ].join(separator);
            };

             var dt = new Date(); 
             var  alivesince = Math.round(dt.getTime() / 1000);
            
            function addLI(listId, what, maxNumber = 5) {

                var newItem = document.createElement("LI");
                var txt = document.createTextNode(what);
                newItem.appendChild(txt);

                var listOfItems = document.getElementById(listId);
                if (listOfItems == null) {
                    document.getElementById("info6").innerHTML = "listId=" + listId + " for:" + what;

                } else {
                    listOfItems.insertBefore(newItem, listOfItems.childNodes[0]);

                    // remove: 
                    var theItems = document.getElementById(listId);
                    var numberofchildren = theItems.children.length;
                    for (i = maxNumber; i < numberofchildren; i++) {
                        try {
                            theItems.removeChild(theItems.children[i]);
                        } catch (e) {
                            document.getElementById('info8').innerHTML = "addLI error. e=" + e + ' at ' + getTimeNow();
                        }
                    }
                }
            }

            function getTimeNow(includemillies = false) {
                var now = new Date();
                var tt = now.yyyymmdd('-') + ' ' + now.hhmmss(':');
                if (includemillies) return tt + '+' + now.getMilliseconds();
                return tt;
            }

            function updateClock() {

                addLI("cycle", getTimeNow(true) + " (" + updateInMilliseconds + ") " + (pauseCapture ? "pause" : "rec"), 4);
                var now = new Date();

                var elem = document.getElementById('clock_time');
                timeTaken = now.yyyymmdd('') + '' + now.hhmmss('');
                timeMillies = now.getMilliseconds();

                elem.innerHTML = timeTaken + "+" + timeMillies + " " + (pauseCapture ? "paused" : "recording") + (donotsend ? " request in progress" : " ready");
                saveImage();
                if (newUpdateInMilliseconds != updateInMilliseconds) {
                    updateInMilliseconds = newUpdateInMilliseconds;
                    addLI("cycle", "CHANGE: " + getTimeNow(true) + " (" + updateInMilliseconds + ") " + (pauseCapture ? "pau" : "rec"), 4);
                    clearInterval(myInterval);
                    myInterval = setInterval(updateClock, updateInMilliseconds);
                }
            }

            function saveImage() {

                // addLI("cycle", getTimeNow(true)+": "+minWaitBetweenConnections, 4); 
                if (maxImagesPost > imgMaxHardLimit) {
                    maxImagesPost = imgMaxHardLimit;
                }
                if (minWaitBetweenConnections < gapBetweenPostsHardLowerLimit) {
                    minWaitBetweenConnections = gapBetweenPostsHardLowerLimit;
                }
                if (minWaitBetweenConnections > gapBetweenPostsHardUpperLimit) {
                    minWaitBetweenConnections = gapBetweenPostsHardUpperLimit
                }

                //	 addLI("cycle", getTimeNow(true)+": "+minWaitBetweenConnections, 4); 
                var video = document.getElementById('video');
                var skiplevel = -1;
                if (countDownBeforeStart > 0) {
                    countDownBeforeStart--;
                    addLI("cycle", getTimeNow(true) + " (" + updateInMilliseconds + ") Start in: " + countDownBeforeStart, 4);
                    // countDownBeforeStartgive the video some time to initialise etc
                } else if (pauseCapture) {
                    addLI("cycle", getTimeNow(true) + " (" + updateInMilliseconds + ") no save", 4);
                } else if (video.videoWidth) {
                    var canvas = document.getElementById('canvas');
                    var context = canvas.getContext('2d');

                    maxImagesPost = ((maxImagesPost && maxImagesPost > 0) ? maxImagesPost : 2);
                    var imagesProducedProSend = minWaitBetweenConnections / updateInMilliseconds;
                    skiplevel = imagesProducedProSend / maxImagesPost;
                    if (skiplevel < 1) {
                        skiplevel = 1;
                    }

                    addLI("skipped", getTimeNow() + ": skiplevel=" + skiplevel + " countImages=" + countImages, 3);
                    var cc = 10000.0;
                    var d = 0.132;
                    try {
                        var x = cc * countImages;
                        var y = Math.round(cc * skiplevel);
                        var z = x % y;
                        d = z / cc;
                        // d = ((cc * countImages) % (Math.round(cc * skiplevel)) ) / cc;
                        addLI("misc", getTimeNow() + ": expA d=" + d + " x=" + x + " y=" + y + " z=" + z + " cc=" + cc, 2);
                    } catch (err) {
                        addLI("err", "ERROR: " + err.message, 4);
                    }

                    if (d < 1) {
                        addLI("misc", getTimeNow() + ": expB=" + d, 2);
                        croppedVideoHeight = video.videoWidth * theight / twidth;
                        if (croppedVideoHeight > video.videoHeight) {
                            croppedVideoHeight = video.videoHeight;
                        } // adjustment if video is too wide 
                        croppedVideoWidth = croppedVideoHeight * twidth / theight;

                        croppedVideoHeight = Math.ceil(croppedVideoHeight / zoom);
                        croppedVideoWidth = Math.ceil(croppedVideoWidth / zoom);

                        document.getElementById('info2').innerHTML = 'croppedVideoWidth=' + croppedVideoWidth + '; croppedVideoHeight=' + croppedVideoHeight + "; zoom=" + zoom;

                        var offsetX = Math.round((zoomX - 0.5) * video.videoWidth);
                        var offsetY = Math.round((zoomY - 0.5) * video.videoHeight);



                        var startX = offsetX + video.videoWidth / 2.0 - croppedVideoWidth / 2.0;
                        if (startX < 0) {
                            startX = 0;
                        }
                        if (startX > video.videoWidth - croppedVideoWidth) {
                            startX = video.videoWidth - croppedVideoWidth;
                        }

                        var startY = offsetY + video.videoHeight / 2.0 - croppedVideoHeight / 2.0;
                        if (startY < 0) {
                            startY = 0;
                        }
                        if (startY >= video.videoHeight - croppedVideoHeight) {
                            startY = video.videoHeight - croppedVideoHeight;
                        }

                        document.getElementById('info9').innerHTML = 'offsetX=' + offsetX + ' offsetY=' + offsetY;

                        context.drawImage(document.getElementById('video'), // in case video has updated
                            Math.round(startX),
                            Math.round(startY),
                            croppedVideoWidth,
                            croppedVideoHeight,
                            0, 0, twidth, theight);


                        var canvasData = canvas.toDataURL("image/jpeg", jpgcompression);
                        if (imagesAdded < maxImagesPost) {
                            zoominfo = "" + (((Math.round(100 * zoom) * 100 + Math.round(100 * zoomX)) * 100) + Math.round(100 * zoomY));

                            thePostData = thePostData + "imgJpgc" + countImages + "=" + Math.round(100 * jpgcompression) + "&";
                            thePostData = thePostData + "imgData" + countImages + "=" + canvasData + "&";
                            thePostData = thePostData + "imgTime" + countImages + "=" + timeTaken + "&";
                            thePostData = thePostData + "imgMs" + countImages + "=" + timeMillies + "&";
                            thePostData = thePostData + "imgZoom" + countImages + "=" + zoominfo + "&";
                            imagesAdded++;
                        } else {
                            droppedFrames++;
                        }

                    } else { // if(d < 1)
                        // newUpdateInMilliseconds = 2 *  updateInMilliseconds;
                        if (maxImagesPost == 0) {
                           //  newUpdateInMilliseconds = minWaitBetweenConnections;
                        } else {
                            newUpdateInMilliseconds = Math.round(minWaitBetweenConnections / maxImagesPost);
                        }

                        addLI("videoerror", getTimeNow() + " video.videoWidth=" + video.videoWidth + " update=" + newUpdateInMilliseconds + "ms from" + updateInMilliseconds + "ms");

                    }
                    countImages++;
                } else { // if( video.videoWidth)
                    newUpdateInMilliseconds = 10 + updateInMilliseconds;
                    addLI("videoerror", getTimeNow() + " video.videoWidth=X update=" + newUpdateInMilliseconds + "ms from" + updateInMilliseconds + "ms");
                }

                // addLI("ajaxsuccess", getTimeNow()+": donotsend (1) "+donotsend, 8);


                if (oneOffPost || Date.now() - lastConnect > minWaitBetweenConnections) {

                    if (donotsend) {
                        addLI("skipped", getTimeNow() + ": Data not sent (donotsend==true): " + thePostData.length, 8);
                        thePostData = "";
                        countImages = 0;
                        imagesAdded = 0;

                    } else {

                        startReadBattery()
                        if (window.XMLHttpRequest) {
                            ajax = new XMLHttpRequest();
                        } else if (window.ActiveXObject) {
                            ajax = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        var tzo = (new Date()).getTimezoneOffset(); // sending this allows the server to know what timezone the client is. 
                        <?php echo "var myId=" . intval($_GET["id"] ?? 0); ?>;

                        // var bat = document.getElementById('batteryinfo').innerHTML; 
                        var info = "re" + numberOfRequests + ";up" + updateInMilliseconds + ";to" + numberOfTimeouts + ";er" + numberOfConnectErrors + ";";
                        info += "vw" + video.videoWidth + "vh" + video.videoHeight;
                        totalImagesSent += imagesAdded; 
                        var statusInfo = "n=" + imagesAdded + "&tzo=" + tzo + "&bat=" + batteryinfo +
                            "&alivesince=" + alivesince +
                            "&jsonerr=" +  totaljsonreturnerror +
                            "&jsoninvalid=" + totalinvalidjson +
                            "&requests=" + numberOfRequests +
                            "&timeouts=" + numberOfTimeouts +
                            "&errors=" + numberOfConnectErrors +
                            "&updms=" + updateInMilliseconds +
                            "&totalImgs=" + totalImagesSent +
                            "&totalImgsSaved=" + totalImagesSavedByServer + 
                            "&n200=" +  numberOfSuccesses200 + 
                            "&nNot200=" +  numberOfSuccessesNot200 +   
                            "&uqt=" + uniquetoken +
                            "&dpf=" + droppedFrames +
                            "&avdpf=" + averageDroppedFrames +
                            "&videoinfo=" + video.videoWidth + "," + video.videoHeight + "," + twidth + "," + theight +
                            "&nreq=" + info +
                            "&id=" + myId + "&pauseCapture=" + (pauseCapture ? 1 : 0);
                        var dataToPost = thePostData + statusInfo;
                        thePostData = "";

                        addLI("statusinfo", getTimeNow() + ": " + statusInfo + " length=" + dataToPost.length, 5);

                        ajax.open("POST", "index.php", true); // true == asynchronous request.
                        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                        ajax.timeout = 120000;
                        ajax.ontimeout = function(e) {
                            document.getElementById('info6').innerHTML = 'timeout: ' + e + ' at ' + getTimeNow();
                            addLI("timeout", getTimeNow() + ": " + e, 10);
                            donotsend = false;
                            numberOfTimeouts++;
                            oneOffPost = true; // give it a chance to retrieve different settings.

                        };

                        ajax.onerror = function(e) {
                            document.getElementById('info7').innerHTML = 'Error: ' + e + ' at ' + getTimeNow();
                            addLI("ajaxerror", getTimeNow() + ": Loaded=" + e.loaded + " State=" + ajax.readyState, 10);
                            donotsend = false;
                            numberOfConnectErrors++;
                        };
                        // ajax.onreadystatechange = function() { // see https://teamtreehouse.com/community/xhronreadystatechange-vs-xhronload
                        ajax.onload = function() {
                            document.getElementById('ajaxresponse').innerHTML = ajax.responseText;
                          
                            var resp = ajax.responseText;

                            try {
                                var parsed = JSON.parse(resp);
                                buckets = (parsed.buckets ? parsed.buckets : false);
                                pauseCapture = (parsed.pauseCapture ? parsed.pauseCapture : false);

                                zoom = (parsed.zoom ? parsed.zoom : 1.0);
                                zoomX = (parsed.zoomX ? parsed.zoomX : 0.5);
                                zoomY = (parsed.zoomY ? parsed.zoomY : 0.5);
                                jpgcompression = (jpgcompression ? parsed.jpgcompression : 0.7);

                                totaljsonreturnerror += (parsed.error ?  1 : 0 );
                                totalImagesSavedByServer += (parsed.imsaved ? parsed.imsaved : 0); 

                                //  newUpdateInMilliseconds = ( parsed.update ? parsed.update : updateInMilliseconds);
                                speedupfactor = (parsed.speedupfactor ? parsed.speedupfactor : 1);
                                if (parsed.fastmode && parsed.fastmode > 0) {
                                    speedupfactor = 10;
                                }

                                if (parsed.resetstats) {
                                    // not implemented.
                                    // averageDroppedFrames = 0;
                                    // numberOfRequests = 0;
                                }



                                maxImagesPost = Math.max(1, Math.round((parsed.imgsperpost ? parsed.imgsperpost : 37) / speedupfactor));
                                minWaitBetweenConnections = Math.max(1, Math.round((parsed.mingap ? parsed.mingap * 1000 : 20000) / speedupfactor)); // data in seconds. 
                                newUpdateInMilliseconds = Math.round(minWaitBetweenConnections / maxImagesPost);



                                // if( newzoom != zoom ) { zoom = newzoom; }  
                            } catch (e) {
                                document.getElementById('info8').innerHTML = "JSON parse error. e=" + e + " resp=" + resp + ' at ' + getTimeNow();
                                totalinvalidjson++; 
                            }

                            document.getElementById('info4').innerHTML = JSON.stringify(parsed);
                            addLI("ajaxreceived", getTimeNow() + ": " + JSON.stringify(parsed), 5)
                            addLI("ajaxsuccess", getTimeNow() + ": Received " + ajax.status, 4);
                            if( ajax.status == 200 ) { 
                                numberOfSuccesses200++;
                            } else { 
                                numberOfSuccessesNot200++; 
                            } 
                            donotsend = false;


                        }
                        document.getElementById('info3').innerHTML = 'Length=' + dataToPost.length + ' at ' + getTimeNow();

                        try {
                            ajax.send(dataToPost);
                            var timeGap = 999;
                            var timeGap = Date.now() - lastConnect;
                            addLI("ajaxsuccess", getTimeNow() + ": " +
                                (dataToPost.length / 1000000).toFixed(1) + 'M ' + (timeGap / 1000).toFixed(1) + 's skip:' + skiplevel.toFixed(3) + " #imgs=" + imagesAdded + " of " + countImages + " cycles", 4);
                        } catch (err) {
                            addLI("err", "ERROR2: " + err.message, 4);
                        }

                        numberOfRequests++;
                        averageDroppedFrames = ((numberOfRequests - 1) * averageDroppedFrames + droppedFrames) / numberOfRequests;
                        countImages = 0;
                        imagesAdded = 0;
                        donotsend = true;
                        lastConnect = Date.now();
                        droppedFrames = 0;

                        oneOffPost = false;


                    }
                }
            }
            // Put event listeners into place
            window.addEventListener("DOMContentLoaded", function() {
                // Grab elements, create settings, etc.
                var video = document.getElementById('video');

                var mediaConfig = {
                    video: {
                        width: {
                            ideal: 4096
                        }, // use maxmimal available resolution
                        height: {
                            ideal: 2160
                        }, // use maxmimal available resolution
                        facingMode: "environment"
                    }
                }

                var errBack = function(e) {
                    console.log('An error has occurred!', e)
                    document.getElementById('videoerror').innerHTML = 'An error has occurred: ' + e;
                };

                // Put video listeners into place
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
                        video.srcObject = stream;
                        video.play();
                    }).catch(errBack);
                }


                // Trigger photo take
                //	document.getElementById('snap').addEventListener('click', function() {
                //		saveImage();
                //	});
            }, false);
        </script>

        <p>
            <b id="clock_time">@@:!!:##</b>
            <p>

                <ul id="ulinfo">
                    <li>[cycle started]<ol id="cycle"> </ol>
                    </li>
                    <li>[video error]<ol id="videoerror"></ol>
                    </li>
                    <li>[timeout]<ol id="timeout"></ol>
                    </li>
                    <li>[connect issue]<ol id="ajaxerror"></ol>
                    </li>
                    <li>[connect?]<ol id="ajaxsuccess"></ol>
                    </li>
                    <li>[data received]<ol id="ajaxreceived"></ol>
                    </li>
                    <li>[battery]<ol id="battery"></ol>
                    </li>
                    <li>[skip]<ol id="skipped"></ol>
                    </li>
                    <li>[misc]<ol id="misc"></ol>
                    </li>
                    <li>[statusinfo]<ol id="statusinfo"></ol>
                    </li>
                    <li>[err]<ol id="err"></ol>
                    </li>
                </ul>
                <hr>
                <ol id="olinfo">
                    <li id="batteryinfo">z,z,z,z,z</li>
                    <li id="info1">info1</li>
                    <li id="info2">info2</li>
                    <li id="info3">info3</li>
                    <li id="info4">info4</li>
                    <li id="info5">info5</li>
                    <li id="info6">info6</li>
                    <li id="info7">info7</li>
                    <li id="info8">info8</li>
                    <li id="info9">info9</li>

                    <li id="ajaxresponse">abcdefg</li>
                </ol>
</body>

</html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<?php
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

    ?>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<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Zip Contents</title>
</head>
<body>
<h1>Backup dev files</h1>

<?php
// Get real path for our folder
$myId = 22; // intval(substr($_GET['id'],0,1));
$imgfoldername = "./"; // zip recursively everything. 

$zipfoldername = "./zip/"; 
if (!file_exists($zipfoldername)) {
      mkdir($zipfoldername, 0777, true);
   }
	 
$rootPath = realpath($imgfoldername);
$prefix = "zip/bupdev";
$nowdate = gmdate("YmdHis");
$count = 20; 

$zipname= $prefix.$nowdate."ZIPB".$count."z".$myId."z.zip";

echo $zipname; 



// Initialize empty "delete list"
$filesToDelete = array();

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

// Initialize archive object




echo "Start zipping....<br>";

$zip = new ZipArchive();
$zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$added = 0; 

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
				
				

        // Add current file to archive
				if( strpos($relativePath, "zip/" ) !== false  ||
				    strpos($relativePath, "img/" ) !== false ||
						strpos($relativePath, "agifs_" ) !== false ||
						strpos($relativePath, "log/" ) !== false ||
						strpos($relativePath, "agifs/" ) !== false ||
					  strpos($relativePath, "archive/" ) !== false ||
				    strpos($relativePath, "tmp/" ) !== false ) { 
						// echo "...ignored;"; 
				} else 	if( strpos($relativePath, "zip\\" ) !== false  ||
                strpos($relativePath, "img\\" ) !== false ||
                    strpos($relativePath, "agifs_" ) !== false ||
                    strpos($relativePath, "log\\" ) !== false ||
                    strpos($relativePath, "agifs\\" ) !== false ||
                  strpos($relativePath, "archive\\" ) !== false ||
                strpos($relativePath, "tmp\\" ) !== false ) { 
                    // echo "...ignored;"; 
            } else{ 
            $zip->addFile($filePath, $relativePath);
            $added++;
						
						echo "file: ".$relativePath; 
				   //  echo "file path=".$filePath; 
						echo "...added;";
						echo "<br>"; 
						}
			
			//	echo "File added to zip"; 
        // Add current file to "delete list"
        // delete it later cause ZipArchive create archive only after calling close function and ZipArchive lock files until archive created)
       // if ($file->getFilename() != 'important.txt')
        //{
         //   $filesToDelete[] = $filePath;
        //}
    }
}

// Zip archive will be created only after closing object
echo "total files added: ".$added; 

if( $added > 0 ) {
  $zip->close();
}

// Delete all files from "delete list"
//foreach ($filesToDelete as $file)
//{
 //  DO NOT DELTE ANYTHING unlink($file); 
//}


?>
<p><a href="http://perisic.com/cam/">Home</a><p>
<a href="https://www.perisic.com/cam/zip/">Goto Zip File</a><p>
</body>
</html>
zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML>

<html>

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Choose Date</title>


    <?php


    include "./util.php";
    $bgcolor = id2color($_GET["id"], "hexbg");
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


    $x = glob("img/old/d*/t" . $myId . "??");

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
        $max = ($_GET["howmanydaysback"] ?? 30 ); // one years back 
        for ($i = 2; $i < $max; $i++) {
            archive_day($myId, 0 - $i);
        }

        $archiveThese = array();
        $tNow = localtimeCam($myId);
        foreach ($available_dates as $key => $value) {
            $t = localtimeCam($myId, $value[2]);
            if ($tNow - $t > 8 * 24 * 60 * 60) { // 8 days = last 7 days 
                // $archiveThese[] = $key; 
                $ret = zipDateId($key, $myId);
                echo "<p>$key has been archived for Camera $myId with result: $ret </p>";
            }
        }
        // var_dump($archiveThese);

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

</html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Zip Contents</title>
</head>
<body>
<h1>ZIP and Delete</h1>
<a href="index.php">Home</a><p>
<p>
<a href="zip/">Goto Zip Folder</a>
<p>
<?php

error_reporting(-1);
// Get real path for our folder
include "./vars/allcams.php"; 	
$myIds = array(); 
$merge = isset($_GET["merge"]);  
$zip = NULL; 
$zipname = NULL; 


if(isset($_GET["buckets"])) { 
		$myIds = array(); 
		for($i = 0; $i < 99; $i++ ) { // explode("b",$_GET["buckets"]); 
		if( file_exists("img/".(100 * $_GET['id'] + $i).'/' ) ){ $myIds[] = 100 * $_GET['id'] + $i; }
		}
} else if(isset($_GET["id"])) { 
    $myIds = array (intval(substr($_GET['id'],0,1)));
} else if(isset($_GET['homepage'] )) {  
    $myIds = array("H"); 
} else if(isset($_GET['tmp'])) { 
		$myIds = array("T"); ;
} else { 
    $myIds = array("Q"); ;
}  

var_dump($myIds);  

try { 

$zipfoldername = "./zip/"; 
if (!file_exists($zipfoldername)) {
      mkdir($zipfoldername, 0777, true);
   }

	 
if( $merge ) { 
// $rootPath = realpath($imgfoldername);
$prefix = "am";
$nowdate = gmdate("YmdHis");
$count = 17; 
$zipname= $prefix.$nowdate."ZIPM".$count."z".($_GET["id"] ?? "X")."z.zip";
echo '<h1><a href="zip/'.$zipname.'">Download '.$zipname.'</a></h1>';

$zip = new ZipArchive();
$zip->open($zipfoldername.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$added = 0; 

} 	
$filesToDelete = array();

foreach($myIds as $myId ) { 	 
    $imgfoldername = "./img/".$myId."/"; 
    if(isset($_GET['homepage'] )) { 
		 $imgfoldername = "./img/last/";
		 $myId = 'H'; 
    } if(isset($_GET['tmp'])) { 
     $imgfoldername = "./tmp/";
		 $myId = 'T'; 
     }

$rootPath = realpath($imgfoldername);
$prefix = "aa";
$nowdate = gmdate("YmdHis");
$count = 81; 

echo "<p>rootPath=".$rootPath."<p>"; 

if($rootPath) { 
// Initialize empty "delete list"


// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

// Initialize archive object




echo "$myId - Start zipping....";

if( !$merge ) { 
$zipname= $prefix.$nowdate."ZIPA".$count."z".$myId."z.zip";
echo '<h1><a href="zip/'.$zipname.'">Download '.$zipname.'</a></h1>';

$zip = new ZipArchive();
$zip->open($zipfoldername.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$added = 0; 
} 
foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
        $added++;
			//	echo "File added to zip"; 
        // Add current file to "delete list"
        // delete it later cause ZipArchive create archive only after calling close function and ZipArchive lock files until archive created)
        if ($file->getFilename() != 'important.txt')
        {
            $filesToDelete[] = $filePath;
        }
    }
}

// Zip archive will be created only after closing object
echo "total files added to delete: ".$added; 

if( $added > 0 && !$merge ) {
    set_error_handler("warning_handlerA", E_WARNING);
  $zip->close();
	restore_error_handler();
}

// Delete all files from "delete list"



} else { 
	echo "Something happened; nothing done.";
	var_dump($_GET);  
	die(); 
}
}

if($added == 0 ) { 
	echo "<p>".implode(',', $myIds).": - no images to zip; nothing done."; 
	  error_reporting(0);
	  die();
    } 
  


if( $added > 0 && $merge ) {
  set_error_handler("warning_handlerA", E_WARNING);
  $zip->close();
	restore_error_handler();
}
} catch(Exception $e ) { 
	echo "<h1>Error with zipping stuff: $e </h1>"; 
	die("Exiting"); 
	}
	
if(isset($_GET["delete"]) ) { 
	  foreach ($filesToDelete as $file)
    {
       unlink($file); 
    }
	
} else { 
	echo "<p>$myId - zip only, no delete<p>"; 
}


 
if(isset($_GET["delete"])  ) { 
  foreach($myIds as $x ) { 
		@rmdir("img/".$x."/"); 
		} 	
	} 
	
echo '<h1><a href="zip/'.$zipname.'">Download '.$zipname.'</a></h1>';


function warning_handlerA($errno, $errstr) { 
 echo "<p>Error in closing zipfile:<p>"; var_dump($errno); var_dump($errstr); 
 echo "<p>"; 
 die("Thank you. Exiting."); 
}
?>
<hr>
<a href="index.php">Home</a></h1>
</body>
</html>
zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML> <html> 
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<title>Set Zoom Center</title>
<?php
 error_reporting(-1);
 
$zoomsourcefile =  "tmp/zoomsource".$_GET["b"]."A.jpg"; 
if( !isset( $_GET["preview"] ) ) {  
    $imgs = glob("./img/*/".$_GET["b"]);
		if( count($imgs) > 0 ) { copy($imgs[0], $zoomsourcefile); } 
		else { copy("nopic.jpg", $zoomsourcefile ); }
		}

if( !isset($_GET["videoinfo"] ) ) { 
		echo "<p>No video information available from Camera; assume 640 x 480. <p>"; 
		}
// var_dump($_GET); 

$dimensions = explode(',', $_GET["videoinfo"] ?? "640,480"); 
// var_dump($dimensions);

$w = $owidth = $dimensions[0]; 
$h = $oheight = $dimensions[1];  

$imfull = null; 
$zoomX = abs($_GET["zoomx"]); 
$zoomY = abs($_GET["zoomy"]); 
			
// $aX = abs( ($_GET["x2"] ?? 0.1) - ($_GET["x1"] ?? 0) );  
// $aY = abs( ($_GET["y2"] ?? 0.1) - ($_GET["y1"] ?? 0) ); 

/*
$maxZoomX = $owidth / 640; 
$maxZoomY = $oheight / 480; 

echo "<p>Max Zoom X = $maxZoomX; Max Zoom Y = $maxZoomY;<p>"; 
*/

			$croppedVideoHeight =  $owidth * 480 / 640.0; 
			if( $croppedVideoHeight > $oheight ) { $croppedVideoHeight = $oheight; } // adjustment if video is too wide 
		  $croppedVideoWidth = $croppedVideoHeight * 640.0 / 480.0;

$maxZoomXC = $croppedVideoWidth / 640; 
$maxZoomYC = $croppedVideoHeight / 480; 

if( round($maxZoomXC - $maxZoomYC, 2) > 0  ) { 
		echo "Error maxZoom: $maxZoomXC != $mazZoomYC <p>"; 
		die();  
} 
/*
echo "<p>Max Zoom XC = $maxZoomXC; Max Zoom YC = $maxZoomYC;<p>"; 
*/	
	
			$zoom = $_GET["zoom"]; 
		//  echo "<br>(9) zoom=$zoom "; 
			 					
			// echo "<br>(1) croppedVideoHeight=$croppedVideoHeight "; 
		  $croppedVideoHeight = ceil($croppedVideoHeight / $zoom); 
		//	echo "<br>(2) croppedVideoHeight=$croppedVideoHeight ";
			
		//	echo "<br>(1) croppedVideoWidth=$croppedVideoWidth "; 
			$croppedVideoWidth =  ceil($croppedVideoWidth / $zoom); 
			// echo "<br>(1) croppedVideoWidth=$croppedVideoWidth "; 
				
			// echo "<p>croppedVideoHeight=$croppedVideoHeight croppedVideoWidth=  $croppedVideoWidth<p>"; 				 
				 		
		  $offsetX =  round(($zoomX - 0.5) * $w); 
			$offsetY =  round(($zoomY - 0.5) * $h);  
			
			// echo "<p>offsetX=$offsetX offsetY=$offsetY <p>";
			
			$startX = $offsetX + $w / 2.0 - $croppedVideoWidth / 2.0;
		  if($startX < 0 ) { $startX = 0; } 
		  if($startX >  $w - $croppedVideoWidth ) { $startX = $w - $croppedVideoWidth; }
			$startX = round($startX); 
				
			$startY = $offsetY + $h / 2.0 - $croppedVideoHeight / 2.0;
		  if($startY < 0 ) { $startY = 0; } 
			if($startY >=  $h - $croppedVideoHeight) { $startY =  $h - $croppedVideoHeight ;  }
		  $startY = round($startY);
			 
		  // echo "<p>startxX=$startX startY=$startY <p>";
if( isset($_GET["b"] ) ){ 
		$imfull = imagecreatetruecolor($owidth, $oheight); 	
		$mauve  = imagecolorallocate($imfull,177,156,217);
		imagefilledrectangle($imfull,0,0,$owidth-1,$oheight-1,$mauve);
	
$src = $zoomsourcefile;  
$im = @imagecreatefromjpeg($src); 



if( $imfull && $im) { 
      $w = imagesx($imfull);
      $h = imagesy($imfull);
			
			// echo "<p>w=$w h=$h <p>";
			$yy = explode("y", $src); 
			if( count($yy) != 3 ) { 
					echo "An error occured src=$src <p>."; 
					var_dump($_GET); 
					die("<p>Thank you<p>"); 
			} 
		$zd = $yy[1];
		$mzoomY = ($zd % 100 ) / 100.0; $zd -= $mzoomY;  $zd /= 100.0;
		$mzoomX = ($zd % 100 ) / 100.0; $zd -= $mzoomX;  $zd /= 100.0;
		$mzoom = $zd / 100; 
		
			$mcroppedVideoHeight =  $owidth * 480 / 640.0; 
			if( $mcroppedVideoHeight > $oheight ) { $mcroppedVideoHeight = $oheight; } // adjustment if video is too wide 
		  $mcroppedVideoWidth = $mcroppedVideoHeight * 640.0 / 480.0;

		// echo "<p>mzoomX=$mzoomX mzoomY=$mzoomY mzoom=$mzoom <p>";  
		 $mcroppedVideoHeight = ceil($mcroppedVideoHeight / $mzoom); 
			// echo "<br>(2) mcroppedVideoHeight=$mcroppedVideoHeight ";
			
			// echo "<br>(1) mcroppedVideoWidth=$mcroppedVideoWidth "; 
			$mcroppedVideoWidth =  ceil($mcroppedVideoWidth / $mzoom); 
			// echo "<br>(1) mcroppedVideoWidth=$mcroppedVideoWidth "; 
				
			// echo "<p>mcroppedVideoHeight=$mcroppedVideoHeight mcroppedVideoWidth=  $mcroppedVideoWidth<p>"; 				 
				 		
		  $moffsetX =  round(($mzoomX - 0.5) * $w); 
			$moffsetY =  round(($mzoomY - 0.5) * $h);  
			
			// echo "<p>moffsetX=$moffsetX moffsetY=$moffsetY <p>";
			
			$mstartX = $moffsetX + $w / 2.0 - $mcroppedVideoWidth / 2.0;
		  if($mstartX < 0 ) { $mstartX = 0; } 
		  if($mstartX >  $w - $mcroppedVideoWidth ) { $mstartX = $w - $mcroppedVideoWidth; }
			$mstartX = round($mstartX); 
				
			$mstartY = $moffsetY + $h / 2.0 - $mcroppedVideoHeight / 2.0;
		  if($mstartY < 0 ) { $mstartY = 0; } 
			if($mstartY >=  $h - $mcroppedVideoHeight) { $mstartY =  $h - $mcroppedVideoHeight ;  }
		  $mstartY = round($mstartY);
			 
		  // echo "<p>mstartxX=$mstartX mstartY=$mstartY <p>";
		
		
		
			// imagecopyresized($imfull, $im, $startX, $startY, 0, 0, $croppedVideoWidth, $croppedVideoHeight, imagesx($im), imagesy($im)); 
		imagecopyresized($imfull, $im, $mstartX, $mstartY, 0, 0, $mcroppedVideoWidth, $mcroppedVideoHeight, imagesx($im), imagesy($im)); 
		
		$col_ellipse = imagecolorallocate($imfull, 255, 0, 255);
    imagefilledellipse($imfull, $startX, $startY, 5, 5, $col_ellipse);
			
			
			
		
    // imagejpeg($imfull, "tmp/zoomnozoom.jpg");
		if( !isset($_GET["preview"] ) ){ 
		    imagejpeg($imfull, "tmp/zoomnozoomsource.jpg");
		} 
} else { 
die("Something went wrong! (AAA)"); 
}			
}

$src = "tmp/zoomnozoomsource.jpg"; 
$im = @imagecreatefromjpeg($src); 



if( $im ) { 
      $w = imagesx($im);
      $h = imagesy($im);
			// echo "<p>(a) w=$w h=$h <p>"; 
			
			$inc = 0.0146;  
		 
			$lbx = min( $_GET["x1"] ?? 0, $_GET["x2"] ?? 1); 
			$ubx = max( $_GET["x1"] ?? 0, $_GET["x2"] ?? 1); 
			$lby = min( $_GET["y1"] ?? 0, $_GET["y2"] ?? 1); 
			$uby = max( $_GET["y1"] ?? 0, $_GET["y2"] ?? 1); 
			
			for($y = 0 ; $y < $h ; $y = $y + ($h * $inc) ) {
          for($x = 0; $x < $w ; $x = $x + ($w * $inc)) {
							if( $lbx <= $x / $w && $x / $w < $ubx && $lby <= $y / $h  && $y / $h < $uby )  { 
							// do nothing
							} else { 
							   $colorB = imagecolorallocate($im, 255, 255,0 ); 
								
              //  @imagefilledellipse($im, intval(floor($x)), intval(floor($y)), ceil($w * $inc / 2), ceil($h * $inc / 2), $colorB);
								
								}
          }
      }
			
				$inc = 0.003;
			
			$xC = ($ubx + $lbx) / 2;
			$yC = ($uby + $lby) / 2; 	
			  	  
			/*	
			$dX = ($ubx - $lbx) / 2; 
			$dY = ($uby - $lby) / 2; 
			*/
			
			// echo "dx = $dX dy = $dY <p>"; 
			// echo "(a) dx/dy = ".$dX / $dY." dy/dx = ".$dY / $dX."<p>"; 
		
			
			// echo "(b) dx/dy = ".$dX / $dY." dy/dx = ".$dY / $dX."<p>"; 
		
			
			$lbxA = $startX / $owidth; 
			$ubxA = ($startX + $croppedVideoWidth) / $owidth;  
			$lbyA = $startY / $oheight; 
			$ubyA = ($startY + $croppedVideoHeight) / $oheight; 
			
			$colorF = imagecolorallocate($im, 255, 0,255 ); 
			@imagefilledellipse($im, $startX, $startY, 15, 15, $colorF);
			
			$y0 = max(0,$lbyA - 0.02); 
			$y1 = min($h, $ubyA +0.02); 
			
			$x0 = max(0,$lbxA - 0.02); 
			$x1 = min($w, $ubxA +0.02); 
			
			for($y = $y0 * $h ; $y < $y1 * $h ; $y = $y + ($h * $inc) ) {
          for($x = $x0 * $w; $x < $x1 * $w ; $x = $x + ($w * $inc)) {
							if( $lbxA <= $x / $w && $x / $w < $ubxA && $lbyA <= $y / $h  && $y / $h < $ubyA )  { 
							// do nothing
							} else { 
								$colorA = imagecolorallocate($im, 255, 0,0 ); 
								 @imagefilledellipse($im, intval(floor($x)), intval(floor($y)), ceil($w * $inc / 2), ceil($h * $inc / 2), $colorA);
								
								
								}
          }
      }
			$col_ellipse = imagecolorallocate($im, 255, 200, 255);
      imagefilledellipse($im, $w * $xC, $h* $yC, 15, 15, $col_ellipse);
			imagejpeg($im, "tmp/zoomnozoom.jpg");  		
			 
  	}
		



?>
<script type="text/javascript">
<!--
// Source: https://www.chestysoft.com/imagefile/javascript/get-rectangle.asp
var Point = 1;
var X1, Y1, X2, Y2;

var maxZoom = <?php echo $maxZoomXC ?>; 

 
		

function FindPosition(oElement)
{
  if( typeof( oElement.offsetParent ) != "undefined" )
  {
    for( var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent )
    {
      posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }
    return [ posX, posY ];
  }
  else
  {
    return [ oElement.x, oElement.y ];
  }
}

function setXCYC() { 

			var oheight= <?php echo $oheight; ?>; 
			var owidth= <?php echo $owidth; ?>; 
			
			var x1 = document.getElementById("x1").value;
			var x2 = document.getElementById("x2").value;
			var y1 = document.getElementById("y1").value;
			var y2 = document.getElementById("y2").value;
			
			if( y1 < 0 || x1 < 0 ) { 
			    document.getElementById("zoom").value = <?php echo $_GET["zoom"] ?> ;
          document.getElementById("xC").value = <?php echo $_GET["zoomx"] ?> ;
          document.getElementById("yC").value = <?php echo $_GET["zoomy"] ?> ;
 			} else { 
			
			
			// if( Math.abs(y2- y1) < 0.01 ) { y2 += 0.01; } 
			// if( x2 == x1 ) { x2 += 0.01; } 
			var sollheight = Math.max(1, Math.abs(y2-y1) * oheight ); 
			var sollwidth = Math.max(1, Math.abs(x2-x1) * owidth );
			 
			var croppedVideoHeight =  owidth * 480 / 640.0; 
			if( croppedVideoHeight > oheight ) { croppedVideoHeight = oheight; } // adjustment if video is too wide 
		  croppedVideoWidth = croppedVideoHeight * 640.0 / 480.0;
			
			var z1 = croppedVideoHeight / sollheight; 
			var z2 = croppedVideoWidth / sollwidth; 
			var z3 = croppedVideoWidth / owidth; 
			var z4 = croppedVideoHeight / oheight; 
			
			zoom2 = Math.max(z3, z4, Math.min(z1,z2)); 
			 if( document.getElementById("keepMaxZoom").checked && zoom2 > maxZoom ) { zoom2 = maxZoom; } 
      document.getElementById("zoom").value = zoom2.toFixed(2);
			
			
 			
			var xC = findCenter(x1 , x2 ) ; 
			xC = (xC > 0.99 ? 0.99 : xC);
			 
      document.getElementById("xC").value = xC.toFixed(2); 
			
			var yC = findCenter( y1, y2 ) ; 
			yC = (yC > 0.99 ? 0.99 : yC);
			document.getElementById("yC").value = yC.toFixed(2); 
			} 
} 
function findCenter(a1, a2) { 
				 // return a1 + (a2 - a1) / 2;
				 return (a1 * 1.0 + a2 * 1.0) / 2;  
}
 
function GetCoordinates(e)
{
 var PosX = 0;
 var PosY = 0;
 var ImgPos;
 ImgPos = FindPosition(myImg);
 if (!e) var e = window.event;
 if (e.pageX || e.pageY)
 {
  PosX = e.pageX;
  PosY = e.pageY;
 }
 else if (e.clientX || e.clientY)
   {
    PosX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
    PosY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
 }
 PosX = PosX - ImgPos[0];
 PosY = PosY - ImgPos[1];
  <?php
 $ha =  640 *  $oheight / $owidth;
 echo "ha = $ha ;"; 
 ?>
 
 if (Point == 1)
 {
   X1 = PosX;
   Y1 = PosY;
   Point = 2;
   document.getElementById("x1").value = (PosX / 640).toFixed(2);
   document.getElementById("y1").value = (PosY / ha).toFixed(2); 
	 setXCYC()
 }
 else
 { 
   
   X2 = PosX;
   Y2 = PosY;
   // Point = 3;
   document.getElementById("x2").value = (PosX / 640).toFixed(2);
   document.getElementById("y2").value = (PosY / ha).toFixed(2);
	 setXCYC(); 
	 Point = 1; 
	 Preview(); 
  //  document.form1.drawbutton.disabled = false;
 }
}

function Clear()
{
  Point = 1;
  document.getElementById("x1").value = '';
	document.getElementById("y1").value = '';
	document.getElementById("x2").value = '';
  document.getElementById("y2").value = '';
  document.form1.drawbutton.disabled = true;
  myImg.src = "nopic.png";
}
function Preview() { 
  setXCYC(); 
	xC = document.getElementById("xC").value; 
	yC = document.getElementById("yC").value;
	
  zz = "<?php 
	// $cid = $_GET["id"]; 
	echo $_GET["videoinfo"] ?>" ;
    document.location.href ="setzoom.php?preview=1&videoinfo="+ zz +  "&zoomx="+xC+"&zoomy="+yC+"&x1=" + document.getElementById("x1").value + "&y1=" +  document.getElementById("y1").value

 //  document.location.href ="https://www.perisic.com/cam/setzoom.php?preview=1&videoinfo="+ zz +  "&zoomx="+xC+"&zoomy="+yC+"&x1=" + document.getElementById("x1").value + "&y1=" +  document.getElementById("y1").value
	 + "&zoom=" + document.getElementById("zoom").value 
	 + "&x2=" + document.getElementById("x2").value + "&y2=" +document.getElementById("y2").value+"&id=<?php echo $_GET["id"] ?>&b=<?php echo $_GET["b"] ?>";

} 


function Initialisation()
{ 
 // document.form1.drawbutton.disabled = true
	setTimeout(setXCYC,1000); 
}

//-->
</script>

</head>
<body onload="Initialisation();">


<form action="index.php">
<h2>Click on the image to set the coordinates.</h2>
<p>
<!--  Do not zoom more than it makes sense: --> <input hidden type="checkbox" checked="true" id="keepMaxZoom">

<p>
<?php 
$ha =  640 *  $oheight / $owidth;
echo '<img src="tmp/zoomnozoom.jpg?t='.time().'" width="640" height="'.intval($ha).'" alt="" id="myImgId" />';
?>
<!--
<input type="button" name="submitbutton" value="Preview" onclick="Preview();" /> 
&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  
-->
<p>
<input type="submit" name="Submitcenter" value="Submit for Real"  />
<p>




<input type="hidden" name="x1" id="x1" value="<?php echo ($_GET['x1'] ?? -1); ?>"></input>
<input type="hidden" name="y1" id="y1" value="<?php echo ($_GET['y1'] ?? -1); ?>"></input>

<input type="hidden" name="x2" id="x2" value="<?php echo ($_GET['x2'] ?? -1); ?>"></input>
<input type="hidden" name="y2" id="y2" value="<?php echo ($_GET['y2'] ?? -1); ?>"></input>



<p>XC: <input readonly type="text" name="xC" id="xC" value="wait"></input>
; YC <input readonly type="text" name="yC" id="yC" value="wait"></input>
; Zoom <input readonly type="text" name="zoom" id="zoom" value="<?php echo $zoom; ?>"></input>
; Max Zoom <input readonly type="text" name="maxzoomxc" id="maxzoomxc" value="<?php echo $maxZoomXC; ?>"></input>

<p><input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"></input>


</form>

<a href="index.php?day=19670526&id=<?php echo $_GET['id']; ?>" >Back (no change)</a>
<script type="text/javascript">

<!--
var myImg = document.getElementById("myImgId");
myImg.onmousedown = GetCoordinates; 
//-->
</script>

</body>

</html>
zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Move to Archive</title>
</head>
<body>
<p>

<?php 

error_reporting(-1);

$archivefoldername = "./archive/"; 
if (!file_exists($archivefoldername)) {
      mkdir($archivefoldername, 0777, true);
   }
$nowdate = gmdate("YmdHis");

rename ("./zip/", "./archive/zip_".$nowdate."/"); 

?>	 
</p>
Thank you. <p>
<a href="./archive/">Go to Archive</a>, <a href="index.php">Home</a>
</body>
</html>
zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>Zip Contents</title>
</head>

<body>
    <h1>ZIP Current <?php echo ($_GET["delete"] ? "(and Delete)" : "") ?></h1>
    <?php

    error_reporting(-1);
    // Get real path for our folder

    $myVarfileId = intval($_POST["id"] ?? $_GET["id"] ?? 99);

    $varfile = "./vars/cam" . $myVarfileId . ".php";
    // $varfile_global = "./vars/cam99.php";

    // @include $varfile_global;

    @include $varfile;
    include "util.php";


    $myId = ($_GET["id"] ?? 0);
    $h = ($lastgallery[$myId] ?? false);
    if (!$h) {
        die("no last gallery");
    }

    if (($_GET["nonce"] ?? "x") !== ($lastgallery["nonce"] ?? "yy")) {
        die("unautorized access");
    }
    ?>

    <?php
    try {
        // var_dump($h); 
        // echo '<h1>HELLO</h1>'; 
        $files = array();
        foreach ($h as $bn) {
            $x = bn2file($bn);
            if ($x !== false) {
                $files[$bn] = realpath($x);
            }
        }
        // var_dump($files);
        
        $filesToDelete = array();
        $zipfoldername = "./zip/";
        if (!file_exists($zipfoldername)) {
            mkdir($zipfoldername, 0777, true);
        }

        $prefix = "zg";
        $nowdate = gmdate("Ymd-His");
        $zipname = $prefix . $nowdate . "z" . $myId . "z.zip";
        echo '<h1><a href="zip/' . $zipname . '">Download ' . $zipname . '</a></h1>';


        $zip = new ZipArchive();
        $zip->open($zipfoldername . $zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $added = 0;


        echo "Start zipping... ";

        foreach ($files as $name => $realpath) {
            if (file_exists($realpath)) {
                $zip->addFile($realpath, $name);
                // echo "added $realpath with name $name <p>"; 
                $added++;
                $filesToDelete[] = $realpath;
            } else {
                echo "did not found: $realpath with name $name <p>";
            }
        }



        // Zip archive will be created only after closing object
        echo "Total files added: " . $added;

        if ($added > 0) {
            set_error_handler("warning_handler", E_WARNING);
            $zip->close();
            restore_error_handler();
        }

      
    } catch (Exception $e) {
        echo "<h1>Error with zipping stuff: $e </h1>";
        die("Exiting");
    }

    $k = 0;
    if (isset($_GET["delete"])) {
        foreach ($filesToDelete as $file) {
            $k++;
            echo "<br>file to delete=$file";
            // DISABLED. No deleting at the moment.  unlink($file);
            echo "DELETE HAS BEEN DISABLED IN SOURCE";
        }
        echo "<p>$myId: $k files deleted<p>";
    } else {
        echo "<p>Thank you</p>";
    }
    function warning_handler($errno, $errstr)
    {
        echo "<p>Error in closing zipfile:<p>";
        var_dump($errno);
        var_dump($errstr);
        echo "<p>";
        die("Thank you. Exiting.");
    }

    ?>
    
</body>

</html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzzAAEAAAAXAQAABABwRFNJRyQ9+ecABX+MAAAafEdERUZeI11yAAV1GAAAAKZHU1VC1fDdzAAFdcAAAAmqSlNURm0qaQYABX9sAAAAHkxUU0iAZfo8AAAceAAABo5PUy8yDN8yawAAAfgAAABWUENMVP17PkMABXTgAAAANlZETVhQkmr1AAAjCAAAEZRjbWFw50BqOgAA0cQAABdqY3Z0IJYq0nYAAPqgAAAGMGZwZ23MeVmaAADpMAAABm5nYXNwABgACQAFdNAAAAAQZ2x5Zg73j+wAARr8AAPnYmhkbXi+u8OXAAA0nAAAnShoZWFkzpgmkgAAAXwAAAA2aGhlYRIzEv8AAAG0AAAAJGhtdHgONFhAAAACUAAAGihrZXJuN2E5NgAFAmAAABVgbG9jYQ5haTIAAQDQAAAaLG1heHALRwyoAAAB2AAAACBuYW1lwPJlOwAFF8AAABsNcG9zdI/p134ABTLQAABB/3ByZXBS/sTpAADvoAAACv8AAQAAAAMAAObouupfDzz1CBsIAAAAAACi4ycqAAAAALnVtPb6r/1nEAAIDAAAAAkAAQABAAAAAAABAAAHPv5OAEMQAPqv/iYQAAABAAAAAAAAAAAAAAAAAAAGigABAAAGigEAAD8AdgAHAAIAEAAvAFYAAAQNCv8AAwACAAEDiAGQAAUAAAWaBTMAAAEbBZoFMwAAA9EAZgISCAUCCwYEAgICAgIEAAB6h4AAAAAAAAAIAAAAAE1vbm8AQAAg//wF0/5RATMHPgGyQAAB////AAAAAAYAAQAAAAAAAjkAAAI5AAACOQCwAtcAXgRzABUEcwBJBx0AdwVWAFgBhwBaAqoAfAKqAHwDHQBABKwAcgI5AKoCqgBBAjkAugI5AAAEcwBVBHMA3wRzADwEcwBWBHMAGgRzAFUEcwBNBHMAYQRzAFMEcwBVAjkAuQI5AKoErABwBKwAcgSsAHAEcwBaCB8AbwVW//0FVgCWBccAZgXHAJ4FVgCiBOMAqAY5AG0FxwCkAjkAvwQAADcFVgCWBHMAlgaqAJgFxwCcBjkAYwVWAJ4GOQBYBccAoQVWAFwE4wAwBccAoQVWAAkHjQAZBVYACQVWAAYE4wApAjkAiwI5AAACOQAnA8EANgRz/+ECqgBZBHMASgRzAIYEAABQBHMARgRzAEsCOQATBHMAQgRzAIcBxwCIAcf/ogQAAIgBxwCDBqoAhwRzAIcEcwBEBHMAhwRzAEgCqgCFBAAAPwI5ACQEcwCDBAAAGgXHAAYEAAAPBAAAIQQAACgCrAA5AhQAvAKsAC8ErABXBVb//QVW//0FxwBoBVYAogXHAJwGOQBjBccAoQRzAEoEcwBKBHMASgRzAEoEcwBKBHMASgQAAFAEcwBLBHMASwRzAEsEcwBLAjkAvQI5ACMCOf/lAjkACQRzAIcEcwBEBHMARARzAEQEcwBEBHMARARzAIMEcwCDBHMAgwRzAIMEcwBJAzMAgARzAGsEcwAbBHMAUQLNAG0ETAABBOMAmQXlAAMF5QADCAAA4QKqAN4CqgA9BGQATggAAAEGOQBTBbQAmgRkAE4EZABNBGQATQRz//0EnACgA/QAOAW0AHoGlgChBGQAAAIxAAAC9gAvAuwALQYlAH8HHQBEBOMAgQTjAJ4CqgDoBKwAcgRkAFQEcwAuBGQAMwTlABoEcwCGBHMAjAgAAO8FVv/9BVb//QY5AGMIAACBB40AUgRz//wIAAAAAqoAUwKqAEcBxwCAAccAbARkAE4D9AAvBAAAIQVWAAYBVv45BHP/5AKqAFwCqgBcBAAAFwQAABcEcwBJAjkAuQHHAGwCqgBHCAAAJQVW//0FVgCiBVb//QVWAKIFVgCiAjkAjQI5/+ACOQAEAjkAFQY5AGMGOQBjBjkAYwXHAKEFxwChBccAoQI5AMYCqgAZAqoABgKqAB0CqgAuAqoA5QKqAKICqgBrAqoAOgKqALcCqgAoBHMAAAHHAAMFVgBcBAAAPwTjACkEAAAoAhQAvAXH//0EcwBJBVYABgQAACEFVgCeBHMAhwSsAHIErAChAqoAawKqABkCqgAhBqwAawasAGsGrAAhBHMAAAY5AG0EcwBCAjkAsQVWAFwEAAA/BccAZgQAAFAFxwBmBAAAUARzAEYEa//hAqoB8QVW//0EcwBKBVb//QRzAEoFxwCeBOsARwXH//0FVgCiBHMASwVWAKIEcwBLBHMAlgHHAEIEcwCWAlUAiARzAJoCrACDBccAnARzAIcFxwCcBHMAhwY5AGMEcwBEBccAoQKqAIUFxwChAqoAPAVWAFwEAAA/BOMAMAI5ACQE4wAwAwAAIwXHAKEEcwCDBccAoQRzAIME4wApBAAAKATjACkEAAAoBGgApAY5AGAGYgBVBKAASAR0AEgDkQBiBPAARAMpAC4FMABIBGv/4QQAALAC6wBSCMAAMwgAAE8EAACZCAAATwQAAJkIAABPBAAAmAQAAJgH1QFqBcAAngSrAHIE1QCdBKwAcQTVAiIE1QEFBav/6QUAAckFqwJ+Bav/6QWrAn4Fq//pBasCfgWr/+kFq//pBav/6QWr/+kFq//pBasBwAWrAn4FqwHABasBwAWr/+kFq//pBav/6QWrAn4FqwHABasBwAWr/+kFq//pBav/6QWrAn4FqwHABasBwAWr/+kFq//pBav/6QWr/+kFq//pBav/6QWr/+kFq//pBav/6QWr/+kFq//pBav/6QWr/+kFq//pBav/6QWr/+kFqwLWBasAZgWr/+oF1f//BNUAkggAAAAH6wEwB+sBIAfrATAH6wEgBNUAsgTVAIAE1QAqCCsBmAhrAbgHVQAQBgAA9AYAAG8EQAA6BUAANwTAAD8EFQBABAAAJQYAAFUF4QC/A40AiQTV/9kBgACAAtUAhgcVAGEClgAPBNUAkgLWAIMC1gCDBNUAsgLWAHAFVv/9BHMASgXHAGYEAABQBccAZgQAAFAFVgCiBHMASwVWAKIEcwBLBVYAogRzAEsGOQBtBHMAQgY5AG0EcwBCBjkAbQRzAEIFxwCkBHMAhwXHAB8EcwAGAjn/zgI5/84COf/kAjn/5AI5//YCOf/1AjkAowHHAGYEAAA3Acf/ogVWAJYEAACIBAAAhgRzAJYBx//6BccAnARzAIcFyQClBHMAiwY5AGMEcwBEBjkAYwRzAEQFxwChAqoAawVWAFwEAAA/BOMAMAI5AAwFxwChBHMAgwXHAKEEcwCDBccAoQRzAIMFxwChBHMAgweNABkFxwAGBVYABgQAACEBxwCJBVb//QRzAEoIAAABBx0ARAY5AFME4wCBAjkAuQeNABkFxwAGB40AGQXHAAYHjQAZBccABgVWAAYEAAAhAccAigKq/+EEcwAbBM0AWgasAGsGrAAiBqwAIgasAEoCqgDiAqoAawKqAN4Cqv/qBVf//wZG/6cGtP+oAxL/qAYy/6cG2P+nBgX/pwHH/3gFVv/9BVYAlgVY//4FVgCiBOMAKQXHAKQCOQC/BVYAlgVYAAsGqgCYBccAnAUzAG0GOQBjBccApAVWAJ4E8gCUBOMAMAVWAAYFVgAJBq8AfwX7AGECOQAEBVYABgSgAEgDkQBiBHMAiwHHAGsEYACIBJoAjAQAABkDhwBIBHMAiwRzAFwBxwCJBAAAhgQAABgEnACgBAAAGgOVAFwEcwBEBI0AgwPbAFYEYACIBDMAEQW0AHoGPwBXAcf/yQRgAIgEcwBIBGAAiAY/AFcFVwCiBusAMgRVAKEFwABkBVYAXAI5AL8COQAEBAAANwh1AA0IFQCkBtUAMQSpAKEFFQAKBcAAoAVW//0FQACnBVYAlgRVAKEFawAABVYAogdjAAcE1QBOBcAAoQXAAKEEqQChBUAAEgaqAJgFxwCkBjkAYwXAAKAFVgCeBccAZgTjADAFFQAKBhUAUgVWAAkF6wCfBVUAVwdVAKEHgAChBlUAAAcVAKgFQAClBcAAVQgVAKQFxwAaBHMASgSVAFsEQACIAusAiASrAAAEcwBLBVr/+wOrADIEeACHBHgAhwOAAIYEqwAYBYAAjARrAIgEcwBEBFUAiARzAIcEAABQA6oAJgQAACEGlQBLBAAADwSVAIoEKwBFBmsAjQaVAI0FAAAoBcAAiwQrAIQEFQAwBgAAiQRVAB8EcwBLBHMAAALrAIkEFQBLBAAAPwHHAIgCOQAJAcf/ogdAABMGgACDBHMAAAOAAIYEAAAhBGsAiAPpAKEDSgCICAAAQQiVAKAFhQAtAqoBAQKqAB4CqgAxAqoAMQKqAQECqgB+AqoAfgKqAIwCqgCMAqoBAQKqABACqgEBAqoBIQMQAH0CqgCMAjMA0gKqAwsCqv8EAjkAuQSBAGkEVgAyAzEAGQQRAC0E0QCWAfkAmwMPAF8EygCbBLgAjAH5AJsEEwAoA7AAUAO0ADwEygCbBM8AUAH5AJsC0gA8BJgAWgQ8ABkEiABuBF8AcwOxABkD1AAKBGYAlgQTACgFjgBkBSQAKAPyAJsD8gCbA/IAmwHjAFoDVgBaBoYAmwH5/6wEEwAoBBMAKAO0/1cDtP9XBEgALQWOAGQFjgBkBY4AZAWOAGQEgQBpBIEAaQSBAGkEVgAyAzEAGQQRAC0E0QCWAksAAANKAAAEuACMAksAAAQTACgDsABQA7QAPATPAFAC0gA8BJgAWgSIAG4EXwBzA9QACgRmAJYEEwAoBY4AZAUkACgB+QCbBFYAMgOwAFAEXwBzBJsAPAAA/9wAAP8lAAD/3AAA/lECjQCrAo0AoALaAEMDTQB5Aaj/ugGcAEYB5QBGAZwARgGcAEYBrQBIAZwARgGxAEYBUQBGBDUBfAQ1AS4ENQC3BDUAgQQ1ASwENQC+BDUArwQ1AIEENQCaBDUA2wQ1AIUCjQDBBDUAswYAAQAGAAEAAkIANgYAAQAENQCeBDUAmAQ1AMsGAAEABgABAAYAAQAGAAEABgABAAGxAEYGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAUb/7oGAAEABgABAAYAAQAFtQA6BbUAOgH0/7oB9P+6BgABAAYAAQAGAAEABgABAASBADYENQA2BD3/ugQ9/7oD6QBKA+kASgZ/ABQHdgAUAyf/ugQe/7oGfwAUB3YAFAMn/7oEHv+6BRsAMgS1ACQGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEAAc8AMAGxAEYBsQBGAbEAQAGxAEYGAAEABgABAAAA/9wAAP5RAAD/FgAA/xYAAP8WAAD/FgAA/xYAAP8WAAD/FgAA/xYAAP8WAAD/3AAA/xYAAP/cAAD/IAAA/9wEcwBKCAAAAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQACjQB/Ao0AXQYAAQAE7gAVA00AeQGoAA4B1v/cAagAVgHWABADdQAyA3UAMgGoAC0B1gATBRsAMgS1ACQB9P+6AfT/ugGoAJMB1gATBbUAOgW1ADoB9P+6AfT/ugJCAAADAP/3BbUAOgW1ADoB9P+6AfT/ugW1ADoFtQA6AfT/ugH0/7oEgQA2BDUANgQ9/7oEPf+6BIEANgQ1ADYEPf+6BD3/ugSBADYENQA2BD3/ugQ9/7oCswBfArMAXwKzAF8CswBfA+kASgPpAEoD6QBKA+kASgaSAD4GkgA+BD//ugQ//7oGkgA+BpIAPgQ//7oEP/+6CMkAPgjJAD4Gxf+6BsX/ugjJAD4IyQA+BsX/ugbF/7oEp/+6BKf/ugSn/7oEp/+6BKf/ugSn/7oEp/+6BKf/ugRaACoDmgA2BDX/ugMn/7oEWgAqA5oANgQ1/7oDJ/+6Bk8AJwZPACcCJP+6Ahr/ugSnAEYEpwBGAiT/ugIa/7oEzwAtBM8ALQMn/7oDJ/+6BA0ARwQNAEcBqP+6Aaj/ugK0ACMCtAAjAyf/ugMn/7oENQBFBDUARQH0/7oB9P+6AkIANgMA//cDmv+6Ayf/ugN1ADIDdQAyBRsAMgS1ACQFGwAyBLUAJAH0/7oB9P+6BFoAQATOAEkEWgAmBM4AOQRaAFMEzgBKBFoAUwTOAEoGAAEABgABAAGcAEYBnABGBgABAAYAAQAGAAEAAVEARgGxAEYGAAEABgABAAGtAEgB5QBGBgABAAYAAQAGAAEAAbEARgGxAEYBsQBGAbEARgGxAEABzwAwBgABAAGcAEYBnABGBgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEAAo0AygKNAMcCjQDGBgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEABgABAAYAAQAGAAEAAQD/uggA/7oQAP+6BtwAYwU/AEQG1QChBVsAgwAA/dwAAPwvAAD8pgAA/lQAAPzXAAD9cwAA/ikAAP4NAAD9EQAA/GcAAP2dAAD79QAA/HIAAP7VAAD+1QAA/wIEGwCgBqwAawasABkAAP62AAD9cwAA/ggAAPymAAD+UwAA/REAAPvIAAD69AAA+q8AAPxyAAD7qgAA+2oAAPzxAAD8fQAA+90AAPzBAAD7mAAA/eoAAP6EAAD9wgAA/PEAAP1fAAD+dgAA/rwAAPzrAAD9bAAA/VgAAPyQAAD9FQAA/CwAAPwTAAD8EgAA+5YAAPuWAccAiAVW//0EcwBKBVb//QRzAEoFVv/9BHMASgVW//0EcwBKBVb//QRzAEoFVv/9BHMASgVW//0EcwBKBVb//QRzAEoFVv/9BHMASgVW//0EcwBKBVb//QRzAEoFVv/9BHMASgVWAKIEcwBLBVYAogRzAEsFVgCiBHMASwVWAKIEcwBLBVYAogRzAEsFVgCiBHMASwVWAKIEcwBLBVYAogRzAEsCOQBjAccAHwI5ALoBxwB8BjkAYwRzAEQGOQBjBHMARAY5AGMEcwBEBjkAYwRzAEQGOQBjBHMARAY5AGMEcwBEBjkAYwRzAEQG3ABjBT8ARAbcAGMFPwBEBtwAYwU/AEQG3ABjBT8ARAbcAGMFPwBEBccAoQRzAIMFxwChBHMAgwbVAKEFWwCDBtUAoQVbAIMG1QChBVsAgwbVAKEFWwCDBtUAoQVbAIMFVgAGBAAAIQVWAAYEAAAhBVYABgQAACEFVv/9BHMASgI5/+IBx/+wBjkAYwRzAEQFxwChBHMAgwXHAKEEcwCDBccAoQRzAIMFxwChBHMAgwXHAKEEcwCDAAD+/gAA/v4AAP7+AAD+/gRV//0C6wAMB2MABwVa//sEqQChA4AAhgSpAKEDgACGBccApARrAIgEc//9BAAAFARz//0EAAAUBVYACQQAAA8FVQBXBCsARQVVAKEEcwCHBgUAYwRzAFUGOQBgBHMARAW1ADoB9P+6AiT/ugIa/7oEpwBGAfQAngH0ABAB9AAbAfQAEAH0AGsB9P/5Aif/zgGoAA8BqP/1AqoApAKqAKQBqAAOAagAVgGoAFYAAP/PAagADwHW/78BqP/1Adb/zQGoAB0B1v/1AagAkwHWABMDdQAyA3UAMgN1ADIDdQAyBRsAMgS1ACQFtQA6BbUAOgH0/7oB9P+6BbUAOgW1ADoB9P+6AfT/ugW1ADoFtQA6AfT/ugH0/7oFtQA6BbUAOgH0/7oB9P+6BbUAOgW1ADoB9P+6AfT/ugW1ADoFtQA6AfT/ugH0/7oFtQA6BbUAOgH0/7oB9P+6BIEANgQ1ADYEPf+6BD3/ugSBADYENQA2BD3/ugQ9/7oEgQA2BDUANgQ9/7oEPf+6BIEANgQ1ADYEPf+6BD3/ugSBADYENQA2BD3/ugQ9/7oEgQA2BDUANgQ9/7oEPf+6ArMAMgKzADICswBfArMAXwKzAF8CswBfArMAMgKzADICswBfArMAXwKzAF8CswBfArMAXwKzAF8CswA4ArMAOAKzAEkCswBJA+kASgPpAEoD6QBKA+kASgPpAEoD6QBKA+kASgPpAEoD6QBKA+kASgPpAEoD6QBKA+kASgPpAEoD6QBKA+kASgaSAD4GkgA+BD//ugQ//7oGkgA+BpIAPgQ//7oEP/+6BpIAPgaSAD4EP/+6BD//ugjJAD4IyQA+BsX/ugbF/7oIyQA+CMkAPgbF/7oGxf+6BKf/ugSn/7oEWgAqA5oANgQ1/7oDJ/+6Bk8AJwZPACcGTwAnAiT/ugIa/7oGTwAnBk8AJwIk/7oCGv+6Bk8AJwZPACcCJP+6Ahr/ugZPACcGTwAnAiT/ugIa/7oGTwAnBk8AJwIk/7oCGv+6BKcARgSnAEYEpwBGBKcARgZ/ABQHdgAUAyf/ugQe/7oGfwAUB3YAFAMn/7oEHv+6BM8ALQTPAC0DJ/+6Ayf/ugTPAC0EzwAtAyf/ugMn/7oEzwAtBM8ALQMn/7oDJ/+6Bn8AFAd2ABQDJ/+6BB7/ugZ/ABQHdgAUAyf/ugQe/7oGfwAUB3YAFAMn/7oEHv+6Bn8AFAd2ABQDJ/+6BB7/ugZ/ABQHdgAUAyf/ugQe/7oEDQBHBA0ARwGo/7oBqP+6BA0ARwQNAEcBqP+6Aaj/ugQNAEcEDQBHAaj/ugGo/7oEDQBHBA0ARwGo/7oBqP+6BDUARQQ1AEUB9P+6AfT/ugQ1AEUENQBFBDUARQQ1AEUENQBFBDUARQH0/7oB9P+6BDUARQQ1AEUEgQA2BDUANgQ9/7oEPf+6AkIANgMA//cDGgAaAxoAGgMaABoDdQAyA3UAMgN1ADIDdQAyA3UAMgN1ADIDdQAyA3UAMgN1ADIDdQAyA3UAMgN1ADIDdQAyA3UAMgN1ADIDdQAyBRv/ugS1/7oFGwAyBLUAJAH0/7oB9P+6A3UAMgN1ADIFGwAyBLUAJAH0/7oB9P+6BRsAMgS1ACQGfwBFBn8ARQZ/AEUGfwBFAagAKAAA/ikAAP6iAAD/MAAA/x0AAP8SAAD/kgAA/n4I/AAyCK0AMgAA/7UAAP+2AAD+7QAA/2QAAP5+AAD/nwGNAAAC9v/9AAD+ggAA/xAEzQAyAAD/WAAA/1gAAP9kBpIAPgaSAD4EP/+6BD//ugjJAD4IyQA+BsX/ugbF/7oEWgAqA5oANgQ1/7oDJ/+6A00AeQK0ACMCQgA2AfT/ugKQ/7oB9AAvAfQAOwH0ABIB9ACxAfQAbQZ/ABQHdgAUAfkAmwAA/tkCvAAAA/IAmwRa//UEzv/1BFoAUwTOAEoEWgBTBM4ASgRaAFMEzgBKBFoAUwTOAEoEWgBTBM4ASgRaAFMEzgBKBDUAcQQ1AK0EWgAPBM4ADwAABooHAQEBqwYGBgUFBgYGBgcHBgcHBgYGBgYGBgYGBgcHBwcHBgElBQwMDAwSHD4cBQZ1HBIcEhIFmhwfheCWEgcHB8IGBiY1BiMnZVM3OeVdOXE3JDVTBisSN8ak1cRjBv4GBwUFBgUGBwYGBgYGBgYGBgYGBgcHBwcGBgYGBgYGBgYGBgUGBgYGBhEGBgEGBgYBDAYGBgYGGAwMAQb/FhgBBSkM4QdSBgxNBgYBBQUHEQcGARQUBQUGAgYFAQYGBwEBBgcFFF8FBQUFBQcHBwcHBwcGBgb/BvwBAQEBBgEBAQEZBQYcY/4GBgUGBQYBBwYGBgwEDAESUz4BKy4LLgstBgYlJiUmLgEuDCcMJwE5ASUBAS43LjcSJC4BLgEBK5r+mgEuNy43HGMcYwESMAstJhIeHhQBJjIBAQEBAQEBGQEBGQEZGQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBGRkBMjIyMhkZGQEBKwEBAQEBMQEBEgEZARkxEhkBARkBJSYuCy4LDCcMJwwnElMSUxIBLjcuNz7/Pv8+/z45HOUBXRgBOS43AQESJBIkLgEBKxwSLjcuNy43LjeFpJXEOSUmAQEMKf+FpIWkhaSVxAEBAQwMDAwMAQEBASUBFhIBghTdJQENDBwuPgEQdS4fEi4cAZqVTScPPpULJindKQEeEikk3RgVGMYxJCQkKRUoKt0pJCkqDAElDAE+PhwBMTcMMRslJAElDAwYGQwMDBJ1LhIcHC6aMTFNGhwrFCUxFAExLiYxJCYBJygLNzcNNxY3JDc1CxTEMdUxASwxJAEqMQElJzcmMSs5/98kJDcNxDcBAQExDAEBAQEBAQEBAQEBAQEBAbMBAQEBAQwBAfcSAQz3AQEcAQH3DAwBECwMDB8BExbCwsIBAcr3AQEcHA8TExMTAQEBAQwBAQELDAEBARwBDAwQLAwfARMW9wEBLAEBAQEBAQEIAQEBFAEBIAEbBAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEbAQEBAQEBAQEBAQEBAQEsLAEBAQEBAQEBAQEJAQEjCQEBIwEBAQEBAQEBAQEBAQEBAQEBASsbGxsbAQEBAQEBAQEBAQEBAQEBAQEBDAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBLAEBAQEBAQEBAQEBLCwBAQEBLCwBAQEBLCwBASwsAQEBAQEBAQEBAQEBKSkpKQEBAQErKxERKysREQEBAQEBAQEBMjIyMjIyMjIjAQEBIwEBAQEBHQEyMh0BAQEBAQEBAQEBAQEBAQEsLAEBAQEBAQEBAQEsLCMBIwEjASMBAQEBAQEBAQQbAQEgFAEBARsbGxsbKwEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQERGQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBATklJiUmJSYlJiUmJSYlJiUmJSYlJiUmJSYMJwwnDCcMJwwnDCcMJwwnPgc+ORIkEiQSJBIkEiQSJBIkAREBEQERAREBERw3HDcZARkBGQEZARkBlsSWxJbEJSY+ORIkHDccNxw3HDccNwAAAAAlJgEBBwEHAS4UAQEBAQEBAQEBNwEBAQEBLB0BMiwsLCwsLCgBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEsLAEBLCwBASwsAQEsLAEBLCwBASwsAQEsLAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBASkpKSkpKSkpKSkpKSkpKSkpKQEBAQEBAQEBAQEBAQEBAQErKxERKysRESsrEREBAQEBAQEBATIyIwEBAQEBAR0BAQEdAQEBHQEBAR0BAQEdATIyMjIJAQEjCQEBIwEBAQEBAQEBAQEBAQkBASMJAQEjCQEBIwkBASMJAQEjAQEBAQEBAQEBAQEBAQEBAQEBLCwBAQEBAQEsLAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEsLAEBAQEsLAEBCQkJCQEBAQEBAQEBBQEBAQEBAQEyAQEBAQEBASsrEREBAQEBIwEBAQEBASwoLCwsLCwJAfcBFMIjASMBIwEjASMBIwEjAQEBIwEAAAAAAAMAAwEBAQEBBQMDAQIBAQAYBewLwAD4CP8ACAAI//4ACQAJ//0ACgAK//0ACwAL//0ADAAM//0ADQAN//0ADgAN//0ADwAO//0AEAAP//0AEQAP//wAEgAR//wAEwAS//wAFAAT//wAFQAT//sAFgAU//sAFwAV//sAGAAV//oAGQAX//sAGgAZ//oAGwAa//oAHAAa//oAHQAb//oAHgAc//kAHwAc//kAIAAd//kAIQAf//kAIgAg//kAIwAg//gAJAAh//gAJQAi//gAJgAi//cAJwAj//cAKAAk//cAKQAm//cAKgAm//cAKwAn//YALAAo//YALQAo//YALgAq//YALwAr//YAMAAt//YAMQAt//UAMgAu//UAMwAv//UANAAw//QANQAw//QANgAx//QANwAz//QAOAA0//MAOQA0//MAOgA1//MAOwA1//MAPAA2//MAPQA3//MAPgA4//MAPwA5//IAQAA6//IAQQA7//IAQgA8//IAQwA8//EARAA9//EARQA+//EARgA///AARwBA//AASABB//AASQBC//AASgBC//AASwBD//AATABE//AATQBG/+8ATgBG/+8ATwBH/+8AUABI/+8AUQBJ/+4AUgBJ/+4AUwBK/+4AVABL/+0AVQBN/+0AVgBN/+0AVwBO/+0AWABP/+wAWQBQ/+wAWgBQ/+0AWwBR/+wAXABT/+wAXQBU/+wAXgBU/+wAXwBV/+sAYABW/+sAYQBX/+sAYgBX/+oAYwBZ/+oAZABa/+oAZQBb/+oAZgBc/+kAZwBc/+kAaABd/+kAaQBe/+gAagBg/+kAawBg/+kAbABh/+kAbQBi/+gAbgBj/+gAbwBj/+gAcABk/+cAcQBl/+cAcgBn/+cAcwBn/+cAdABo/+YAdQBp/+YAdgBq/+YAdwBq/+UAeABr/+UAeQBt/+UAegBu/+UAewBu/+UAfABv/+UAfQBw/+UAfgBx/+QAfwBx/+QAgABz/+QAgQB0/+QAggB1/+MAgwB2/+MAhAB2/+MAhQB3/+IAhgB4/+IAhwB5/+IAiAB6/+IAiQB7/+EAigB8/+EAiwB9/+IAjAB9/+EAjQB+/+EAjgB//+EAjwCB/+EAkACB/+AAkQCC/+AAkgCD/+AAkwCE/98AlACE/98AlQCF/98AlgCH/98AlwCI/+AAmACI/98AmQCJ/98AmgCK/94AmwCL/94AnACM/94AnQCM/94AngCO/94AnwCP/94AoACQ/94AoQCQ/90AogCR/90AowCS/90ApACT/90ApQCU/9wApgCV/9sApwCW/9sAqACX/9sAqQCX/9sAqgCY/9sAqwCZ/9sArACb/9sArQCb/9sArgCc/9sArwCd/9sAsACe/9sAsQCe/9oAsgCf/9oAswCg/9oAtACi/9kAtQCj/9gAtgCj/9gAtwCk/9gAuACl/9gAuQCm/9gAugCm/9gAuwCo/9gAvACp/9cAvQCq/9cAvgCq/9cAvwCr/9cAwACs/9cAwQCt/9cAwgCu/9cAwwCv/9YAxACw/9YAxQCx/9UAxgCx/9UAxwCy/9UAyACz/9QAyQC0/9QAygC1/9QAywC2/9QAzAC3/9QAzQC4/9QAzgC5/9QAzwC5/9QA0AC6/9QA0QC8/9QA0gC9/9MA0wC9/9IA1AC+/9IA1QC//9IA1gDA/9EA1wDA/9EA2ADC/9EA2QDD/9EA2gDE/9EA2wDE/9EA3ADF/9EA3QDG/9EA3gDH/9AA3wDH/9AA4ADJ/88A4QDK/88A4gDL/88A4wDL/88A5ADM/88A5QDN/88A5gDO/88A5wDQ/84A6ADQ/84A6QDR/84A6gDS/80A6wDT/80A7ADT/80A7QDU/80A7gDW/8wA7wDX/8wA8ADX/8wA8QDY/8wA8gDZ/8wA8wDa/8wA9ADa/8wA9QDc/8sA9gDd/8sA9wDe/8sA+ADe/8oA+QDf/8oA+gDg/8oA+wDh/8oA/ADh/8oA/QDj/8kA/gDk/8kA/wDl/8kA+Aj/AAgACP/+AAkACf/9AAoACv/9AAsAC//9AAwADP/9AA0ADf/9AA4ADf/9AA8ADv/9ABAAD//9ABEAD//8ABIAEf/8ABMAEv/8ABQAE//8ABUAE//7ABYAFP/7ABcAFf/7ABgAFf/6ABkAF//7ABoAGf/6ABsAGv/6ABwAGv/6AB0AG//6AB4AHP/5AB8AHP/5ACAAHf/5ACEAH//5ACIAIP/5ACMAIP/4ACQAIf/4ACUAIv/4ACYAIv/3ACcAI//3ACgAJP/3ACkAJv/3ACoAJv/3ACsAJ//2ACwAKP/2AC0AKP/2AC4AKv/2AC8AK//2ADAALf/2ADEALf/1ADIALv/1ADMAL//1ADQAMP/0ADUAMP/0ADYAMf/0ADcAM//0ADgANP/zADkANP/zADoANf/zADsANf/zADwANv/zAD0AN//zAD4AOP/zAD8AOf/yAEAAOv/yAEEAO//yAEIAPP/xAEMAPP/xAEQAPf/xAEUAPv/xAEYAP//wAEcAQP/wAEgAQf/wAEkAQv/wAEoAQv/wAEsAQ//wAEwARP/wAE0ARv/vAE4ARv/vAE8AR//vAFAASP/vAFEASf/uAFIASf/uAFMASv/uAFQAS//tAFUATf/tAFYATf/tAFcATv/tAFgAT//sAFkAUP/sAFoAUP/tAFsAUf/sAFwAU//sAF0AVP/sAF4AVP/sAF8AVf/rAGAAVv/rAGEAV//rAGIAV//rAGMAWf/qAGQAWv/qAGUAW//qAGYAXP/pAGcAXP/pAGgAXf/pAGkAXv/pAGoAYP/pAGsAYP/pAGwAYf/pAG0AYv/pAG4AY//oAG8AY//oAHAAZP/oAHEAZf/nAHIAZ//nAHMAZ//nAHQAaP/nAHUAaf/mAHYAav/mAHcAav/mAHgAa//lAHkAbf/lAHoAbv/lAHsAbv/lAHwAb//lAH0AcP/kAH4Acf/kAH8Acv/kAIAAc//kAIEAdP/jAIIAdf/jAIMAdv/jAIQAdv/jAIUAd//jAIYAeP/jAIcAef/iAIgAev/iAIkAe//iAIoAfP/iAIsAff/iAIwAff/iAI0Afv/iAI4Af//iAI8Agf/hAJAAgf/hAJEAgv/gAJIAg//gAJMAhP/gAJQAhP/gAJUAhf/gAJYAh//fAJcAiP/gAJgAiP/fAJkAif/fAJoAiv/eAJsAi//eAJwAjP/eAJ0AjP/eAJ4Ajv/eAJ8Aj//eAKAAkP/eAKEAkP/dAKIAkf/dAKMAkv/dAKQAk//dAKUAlP/cAKYAlf/bAKcAlv/bAKgAl//bAKkAl//bAKoAmP/bAKsAmf/bAKwAm//bAK0Am//bAK4AnP/bAK8Anf/bALAAnv/bALEAnv/aALIAn//aALMAoP/ZALQAov/ZALUAo//YALYAo//YALcApP/YALgApf/YALkApv/YALoApv/YALsAqP/YALwAqf/XAL0Aqv/XAL4Aqv/XAL8Aq//XAMAArP/XAMEArf/XAMIArv/XAMMAr//WAMQAsP/WAMUAsf/VAMYAsf/VAMcAsv/UAMgAs//UAMkAtP/UAMoAtf/UAMsAtv/UAMwAt//UAM0AuP/UAM4Auf/UAM8Auf/UANAAuv/UANEAvP/UANIAvf/TANMAvf/SANQAvv/SANUAv//SANYAwP/RANcAwP/RANgAwv/RANkAw//RANoAxP/RANsAxP/RANwAxf/RAN0Axv/RAN4Ax//QAN8Ax//QAOAAyf/PAOEAyv/PAOIAy//PAOMAy//PAOQAzP/PAOUAzf/PAOYAzv/PAOcA0P/OAOgA0P/OAOkA0f/OAOoA0v/NAOsA0//NAOwA0//NAO0A1P/NAO4A1v/MAO8A1//MAPAA1//MAPEA2P/MAPIA2f/MAPMA2v/MAPQA2v/MAPUA3P/LAPYA3f/LAPcA3v/LAPgA3v/KAPkA3//KAPoA4P/KAPsA4f/KAPwA4f/KAP0A4//JAP4A5P/JAP8A5f/JAPgI/wAIAAj//gAJAAn//QAKAAr//QALAAv//QAMAAz//QANAA3//QAOAA3//QAPAA7//QAQAA///QARAA///AASABH//AATABL//AAUABP//AAVABP/+wAWABT/+wAXABX/+wAYABX/+gAZABf/+wAaABn/+gAbABr/+gAcABr/+gAdABv/+gAeABz/+QAfABz/+QAgAB3/+QAhAB//+QAiACD/+QAjACD/+AAkACH/+AAlACL/+AAmACL/9wAnACP/9wAoACT/9wApACb/9wAqACb/9wArACf/9gAsACj/9gAtACj/9gAuACr/9gAvACv/9gAwAC3/9gAxAC3/9QAyAC7/9QAzAC//9QA0ADD/9AA1ADD/9AA2ADH/9AA3ADP/9AA4ADT/8wA5ADT/8wA6ADX/8wA7ADX/8wA8ADb/8wA9ADf/8wA+ADj/8wA/ADn/8gBAADr/8gBBADv/8gBCADz/8gBDADz/8QBEAD3/8QBFAD7/8QBGAD//8ABHAED/8ABIAEH/8ABJAEL/8ABKAEL/8ABLAEP/8ABMAET/8ABNAEb/7wBOAEb/7wBPAEf/7wBQAEj/7wBRAEn/7gBSAEn/7gBTAEr/7gBUAEv/7QBVAE3/7QBWAE3/7QBXAE7/7QBYAE//7ABZAFD/7ABaAFD/7QBbAFH/7ABcAFP/7ABdAFT/7ABeAFT/7ABfAFX/6wBgAFb/6wBhAFf/6wBiAFf/6wBjAFn/6gBkAFr/6gBlAFv/6gBmAFz/6QBnAFz/6QBoAF3/6QBpAF7/6QBqAGD/6QBrAGD/6QBsAGH/6QBtAGL/6QBuAGP/6ABvAGP/6ABwAGT/6ABxAGX/5wByAGf/5wBzAGf/5wB0AGj/5wB1AGn/5gB2AGr/5gB3AGr/5gB4AGv/5QB5AG3/5QB6AG7/5QB7AG7/5QB8AG//5QB9AHD/5AB+AHH/5AB/AHL/5ACAAHP/5ACBAHT/5ACCAHX/4wCDAHb/4wCEAHb/4wCFAHf/4wCGAHj/4wCHAHn/4gCIAHr/4gCJAHv/4gCKAHz/4gCLAH3/4gCMAH3/4gCNAH7/4gCOAH//4gCPAIH/4QCQAIH/4QCRAIL/4ACSAIP/4ACTAIT/4ACUAIT/4ACVAIX/4ACWAIf/3wCXAIj/4ACYAIj/3wCZAIn/3wCaAIr/3gCbAIv/3gCcAIz/3gCdAIz/3gCeAI7/3gCfAI//3gCgAJD/3gChAJD/3QCiAJH/3QCjAJL/3QCkAJP/3QClAJT/3ACmAJX/2wCnAJb/2wCoAJf/2wCpAJf/2wCqAJj/2wCrAJn/2wCsAJv/2wCtAJv/2wCuAJz/2wCvAJ3/2wCwAJ7/2wCxAJ7/2gCyAJ//2gCzAKD/2QC0AKL/2QC1AKP/2AC2AKP/2AC3AKT/2AC4AKX/2AC5AKb/2AC6AKb/2AC7AKj/2AC8AKn/1wC9AKr/1wC+AKr/1wC/AKv/1wDAAKz/1wDBAK3/1wDCAK7/1wDDAK//1gDEALD/1gDFALH/1QDGALH/1QDHALL/1ADIALP/1ADJALT/1ADKALX/1ADLALb/1ADMALf/1ADNALj/1ADOALn/1ADPALn/1ADQALr/1ADRALz/1ADSAL3/0wDTAL3/0gDUAL7/0gDVAL//0gDWAMD/0QDXAMD/0QDYAML/0QDZAMP/0QDaAMT/0QDbAMT/0QDcAMX/0QDdAMb/0QDeAMf/0ADfAMj/0ADgAMn/zwDhAMr/zwDiAMv/zwDjAMv/zwDkAMz/zwDlAM3/zwDmAM7/zwDnAND/zgDoAND/zgDpANH/zgDqANL/zQDrANP/zQDsANP/zQDtANT/zQDuANb/zADvANf/zADwANf/zADxANj/zADyANn/zADzANr/zAD0ANr/zAD1ANz/ywD2AN3/ywD3AN7/ywD4AN7/ygD5AN//ygD6AOD/ygD7AOH/ygD8AOH/ygD9AOP/yQD+AOT/yQD/AOX/yQAAABgAAAaMCxYIAAMDAgQGBgoHAgQEBAYDBAMDBgYGBgYGBgYGBgMDBgYGBgsIBwcHBgYIBwIFBwYIBwgGCAcHBgcICgcIBwMDAwUGBAYGBgYGBAYGAgIFAggGBgYGBAYDBgYKBgYGBAIEBggIBwYHCAcGBgYGBgYGBgYGBgICAgIGBgYGBgYGBgYGBgQGBgYEBgcICAsEBAYLCAgGBgYGBgYHCQYDBAUICgYGAgYHBgcGBgYLCAgICwoGCwQEAgIGBQYIAgYEBAYGBgMCBAsIBggGBgICAgIICAgHBwcCBAQEBAQEBAQEBAYCBwYHBgIIBggGBwYGBgQEBAoJCgYIBgIHBgcGBwYGBgQIBggGBwcIBgYGBgYCBgQGBAcGBwYIBgcEBwQHBgYDBgQHBgcGBwYHBgYICAYGBQcEBwYGBAwLBgsGCwYGCwgGBwYHBwgHCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAcLCwsLCwcHBwsMCggIBgcHBgYICAUHAgQKBAcEBAcECAYHBgcGBgYGBgYGCAYIBggGBwYJBgICAgICAgICBQIHBQYGAgcGCAYIBggGBwQHBgYEBwYHBgcGCAYKCggGAggGCwoIBgMKCgoKCgoIBgIEBgYKCgoKBAQEBAgJCQUJCggCCAcIBgcHAgcICAcHCAcGBwYIBwgIAggGBQYCBgYGBQYGAgYGBgYFBgYFBgUICAIGBgYIBgoGBwcCAgUMCwkHBwcIBwcGCAYMBwkJBwcIBwgHBgcGBwgHBwYKCggJBwgLCAYGBwQGBggFBgYFBggGBgYGBgYGCAYGBggIBwgGBggGBgYEBgYCAgIKCQYFBgYFBQsNCQQEBAQEBAQEBAQEBAQEBAMEBAMGBgUGBwMDBwcDBgUFBwcDBQcGBgYGBgYGCQcGBgYDBQkDBgYFBQcJCQkJBgYGBgUGBwMFBwMGBQUHBQcGBgYGBgkHAwYFBgYAAAAABAQEBQICAwICAgICAgYGBgYGBgYGBgYGBAYICAMIBgYGCAgICAgCCAgICAgICAgHCAgICAgDAwgICAgGBgYGBQUJCgQGCQoEBgcGCAgICAgICAgICAgICAgICAICAgICCAgAAAAAAAAAAAAAAAAAAAAABwsICAgICAgICAgICAgICAgICAgICAgICAgICAgIBAQIBwUCAwIDBQUCAwcGAwMCAwgIAwMDBAgIAwMICAMDBgYGBgYGBgYGBgYGBAQEBAUFBQUJCQYGCQkGBgwMCQkMDAkJBgYGBgYGBgYGBQYEBgUGBAkJAwMGBgMDBwcEBAYGAgIEBAQEBgYDAwMEBQQFBQcGBwYDAwYHBgcGBwYHCAgCAggICAICCAgCAwgICAICAgICAggCAggICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAQEBAgICAgICAgICAgICAgICAgICAgICAgICAELFgkHCQcAAAAAAAAAAAAAAAAAAAAABgkJAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIIBggGCAYIBggGCAYIBggGCAYIBggGCAYGBgYGBgYGBgYGBgYGBgYGAgICAggGCAYIBggGCAYIBggGCQcJBwkHCQcJBwcGBwYJBwkHCQcJBwkHCAYIBggGCAYCAggGBwYHBgcGBwYHBgAAAAAGBAoHBgUGBQgGBgYGBgcGBwYHBggGCQYIAwMDBgMDAwMDAwMCAgQEAgICAAIDAgMCAwIDBQUFBQcGCAgDAwgIAwMICAMDCAgDAwgIAwMICAMDCAgDAwYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgQEBAQEBAQEBAQEBAQEBAQEBAUFBQUFBQUFBQUFBQUFBQUJCQYGCQkGBgkJBgYMDAkJDAwJCQYGBgUGBAkJCQMDCQkDAwkJAwMJCQMDCQkDAwYGBgYJCgQGCQoEBgcHBAQHBwQEBwcEBAkKBAYJCgQGCQoEBgkKBAYJCgQGBgYCAgYGAgIGBgICBgYCAgYGAwMGBgYGBgYDAwYGBgYGBgMEBAQEBQUFBQUFBQUFBQUFBQUFBQcGBwYDAwUFBwYDAwcGCQkJCQIAAAAAAAAADAwAAAAAAAACBAAABwAAAAkJBgYMDAkJBgUGBAUEAwMEAwMDAwMJCgMABAYGBwYHBgcGBwYHBgcGBwYGBgcMGAkAAwMDBAcHCwgCBAQFBwMEAwMHBwcHBwcHBwcHAwMHBwcHDAcICQkIBwkJAwYIBwkJCQgJCQgHCQcLBwcHAwMDBQcEBwcGBwcDBwcDAwYDCwcHBwcEBwMHBQkFBQUEAwQHBwcJCAkJCQcHBwcHBwYHBwcHAwMDAwcHBwcHBwcHBwcHBQcHBwQGCAkJDAQEBwwJCQcHBwcHBgkKBwMEBAkLBwcDBwcHBwcHBwwHBwkMCwcMBAQDAwcGBQcCBwQEBgYHAwMECwcIBwgIAwMDAwkJCQkJCQMEBAQEBAQEBAQEBwMIBwcFAwkHBwUIBwcHBAQECgoKBwkHAwgHCQYJBgcHBAcHBwcJBwkIBwgHBwMHBAcECQcJBwkHCQQJBAgHBwMHBQkHCQcHBQcFBwkJBwcFBwUIBwYEDQwGDAYMBgYMCQcHBwcHCQgJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJBwwMDAwMBwcHDA0LCQkGCAcGBgkJBQcCBAsEBwQEBwQHBwkGCQYIBwgHCAcJBwkHCQcJBwkHAwMDAwMDAwMGAwgGBwcDCQcJBwkHCQcJBAgHBwMJBwkHCQcJBwsJBwUDBwcMCwkHAwsJCwkLCQcFAwQHBwoKCgoEBAQEBwkKBAkJCQMHCAcIBwkDCAcJCQgJCQgHBwcHCQkDBwcFBwMHBwUFBwcDBwUHBQUHBwYHBgkJAwcHBwkICgcJCAMDBg0MCgcICQcICAcICAsHCQkHCAkJCQkICQcICQcJCAsLCgoICQwJBwcGBAcHCQYHBwYHCQcHBwcGBQUJBQcGCQkICQcGCQcHBwQGBwMDAwsKBwYFBwYFDA0IBAQEBAQEBAQEBAQEBAUEAwQEAwcHBQYHAwUHBwMGBgYHBwMEBwYHBwYGBwYICAYGBgMFCQMGBgYGBggICAgHBwcHBQYHAwUHAwYGBgcEBwcHBgcGCAgDBwYHBwAAAAAEBAQFAgIDAgIDAgMCBgYGBgYGBgYGBgYEBgkJAwkGBgYJCQkJCQMJCQkJCQkJCQgJCQkJCQMDCQkJCQcGBgYGBgoLBQYKCwUGCAcJCQkJCQkJCQkJCQkJCQkJAwMDAwMJCQAAAAAAAAAAAAAAAAAAAAAHDAkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkEBAkHBQIDAgMFBQIDCAcDAwIDCQkDAwMFCQkDAwkJAwMHBgYGBwYGBgcGBgYEBAQEBgYGBgoKBgYKCgYGDQ0KCg0NCgoHBwcHBwcHBwcFBgUHBQYFCQkDAwcHAwMHBwUFBgYCAgQEBQUGBgMDAwUFBQUFCAcIBwMDBwcHBwcHBwcJCQICCQkJAgMJCQMDCQkJAwMDAwMDCQICCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJBAQECQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJAgwYCggKCAAAAAAAAAAAAAAAAAAAAAAGCgoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwgHCAcIBwgHCAcIBwgHCAcDAwMDCQcJBwkHCQcJBwkHCQcKCAoICggKCAoICQcJBwoICggKCAoICggHBQcFBwUHBwMDCQcJBwkHCQcJBwkHAAAAAAcECwgHBQcFCQcHBgcGCAYIBggHCQcJBwkDAwMHAwMDAwMDAwICBAQCAgIAAgMCAwIDAgMFBQUFCAcJCQMDCQkDAwkJAwMJCQMDCQkDAwkJAwMJCQMDBwYGBgcGBgYHBgYGBwYGBgcGBgYHBgYGBAQEBAQEBAQEBAQEBAQEBAQEBgYGBgYGBgYGBgYGBgYGBgoKBgYKCgYGCgoGBg0NCgoNDQoKBwcHBQYFCQkJAwMJCQMDCQkDAwkJAwMJCQMDBwcHBwoLBQYKCwUGBwcFBQcHBQUHBwUFCgsFBgoLBQYKCwUGCgsFBgoLBQYGBgICBgYCAgYGAgIGBgICBgYDAwYGBgYGBgMDBgYHBgYGAwUFBQUFBQUFBQUFBQUFBQUFBQUFCAcIBwMDBQUIBwMDCAcKCgoKAgAAAAAAAAANDQAAAAAAAAIEAAAHAAAACgoGBg0NCgoHBQYFBQQDAwQDAwMDAwoLAwAEBgcHBwcHBwcHBwcHBwcHBgYHBw0aCgAEBAMFBwcMCQIEBAUIBAQEBAcHBwcHBwcHBwcEBAgICAcNCQkJCQkICgkDBgkHCwkKCQoJCQcJCQ0HCQcEBAQFBwQHBwcHBwMHBwMDBwMLBwcHBwQHBAcFCQcHBwQDBAgJCQkJCQoJBwcHBwcHBwcHBwcDAwMDBwcHBwcHBwcHBwcFBwcHBQcJCgoNBAQHDQoJBwcHBwcGCQsHAwQFCgwHCAMICAcHCAcHDQkJCg0MBw0EBAMDBwYHCQIHBAQHBwcEAwQOCQkJCQkDAwMDCgoKCQkJAwQEBAQEBAQEBAQHAwkHBwcDCQcJBwkHCAgEBAQLCwsHCgcDCQcJBwkHBwcECQcJBwkICQkHCQcHAwcEBwQJBwkHCgcJBAkECQcHAwcFCQcJBwcHBwcHCgkIBwYIBQgHBwUPDQcNBw0HBw0JCAgICAgJCAkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkIDQ0NDQ0ICAgNDgwKCgcJCAcHCgoGCAIFDAUIBQUIBQkHCQcJBwkHCQcJBwoHCgcKBwkHCQcDAwMDAwMDAwYDCQcHBwMJBwkHCgcKBwkECQcIBAkHCQcJBwkHDQkJBwMJBw0MCgcDDQkNCQ0JCQcDBAcICwsLCwQEBAQJCgsFCgsKAwkJCQkHCQMJCQsJCAoJCQgHCQcJCgMJCAYHAwcHBwYHBwMHBwcFBgcHBgcHCQkDBwcHCQkLBwkJAwMGDg0LCAgJCQkJBwkJCwgJCQgJCwkKCQkJBwgLBwoJCwsKCwgJDQkHBwcFCAcJBgcHBgcJBwcHBwcFBwkHBwcLDAgJBwcKBwcHBQcHAwMDDAsHBgcHBgUNDgkEBAQEBAQEBAQEBAQEBQQDBAQEBwcFBwgDBQgIAwcGBggIAwUHBwcHBgYHBwkIBgYGAwUJAwcHBgYHCQkJCQcHBwcFBwgEBQgEBwYGCAUHBwcGBwcJCAMHBgcHAAAAAAQEBQUDAwMDAwMDAwIHBwcHBwcHBwcHBwQHCgoECgcHBwoKCgoKAwoKCgoKCgoKCAoKCgkJAwMKCgoKBwcHBwYGCwwFBwsMBQcICAoKCgoKCgoKCgoKCgoKCgoDAwMDAwoKAAAAAAAAAAAAAAAAAAAAAAcNCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgQECggFAwMDAwYGAwMICAMDAwMJCQMDBAUJCQMDCQkDAwcHBwcHBwcHBwcHBwQEBAQGBgYGCwsHBwsLBwcODgsLDg4LCwgICAgICAgIBwYHBQcGBwUKCgMDCAgDAwgIBQUHBwMDBAQFBQcHAwMEBQYFBgYICAgIAwMHCAcIBwgHCAoKAwMKCgoCAwoKAwMKCgoDAwMDAwMKAwMKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoEBAQKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoCDRoLCQsJAAAAAAAAAAAAAAAAAAAAAAcLCwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADCQcJBwkHCQcJBwkHCQcJBwkHCQcJBwkHCQcJBwkHCQcJBwkHCQcJBwMDAwMKBwoHCgcKBwoHCgcKBwsJCwkLCQsJCwkJBwkHCwkLCQsJCwkLCQkHCQcJBwkHAwMKBwkHCQcJBwkHCQcAAAAABwUMCQgGCAYJBwcHBwcJBwkHCQcKBwoHCQMDAwgDAwMDAwMEAwMEBAMDAwADAwMDAwMDAwYGBgYICAkJAwMJCQMDCQkDAwkJAwMJCQMDCQkDAwkJAwMHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcEBAQEBAQEBAQEBAQEBAQEBAQGBgYGBgYGBgYGBgYGBgYGCwsHBwsLBwcLCwcHDg4LCw4OCwsICAcGBwUKCgoDAwoKAwMKCgMDCgoDAwoKAwMICAgICwwFBwsMBQcICAUFCAgFBQgIBQULDAUHCwwFBwsMBQcLDAUHCwwFBwcHAwMHBwMDBwcDAwcHAwMHBwMDBwcHBwcHAwMHBwcHBwcEBQUFBQYGBgYGBgYGBgYGBgYGBgYICAgIAwMGBggIAwMICAsLCwsDAAAAAAAAAA8OAAAAAAAAAwUAAAgAAAALCwcHDg4LCwcGBwUFBAQDBAMDAwMDCwwDAAQGBwgHCAcIBwgHCAcIBwgHBwcIDx4LAAQEBQUICA0KAwUFBgkEBQQECAgICAgICAgICAQECQkJCA8JCgsLCgkLCgMHCggLCgwKDAsKCQoJDwkJCAQEBAUIBQgICAgIBAgIAwMHAw0ICAgIBQgECAcLBwcIBQMFCQkJCwoKDAoICAgICAgICAgICAMDAwMICAgICAgICAgICAYICAgFCAkLCw8FBQgPDAsICAgICAcLDAgEBQUMDQgJBQkJCAgJCAgPCQkMDw4IDwUFAwMIBwcJAwgFBQgICAQDBQ4JCgkKCgMDAwMMDAwKCgoDBQQFBQUFBQUFBQgDCggICAMLCAkHCggJCQUFBQ0NDQgLCAMKCAsICwgICAUJCAkICwkLCggKCAgDCAQIBQoICggMCAsFCwUKCAkECQYKCAoICAgICAgMCwkIBwkFCggIBREPCA8IDwgIDwsJCQkJCQsJCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwkPDw8PDwkJCQ8QDgsLCAoJCAgLCwcJAwUNBQkFBQkFCQgLCAsICggKCAoICwgLCAsICggKCAMDAwMDAwMDBwMKBwgIAwoICwgMCAwICwUKCAkECggKCAoICwgPCwkHAwkIDw0MCAMPCw8LDwsJBwMFCAkNDQ0NBQUFBQkMDQYMDAsDCQoKCggKAwoLCwoKDAoKCQkJCQsLAwkJBwgDCAkHBwgIAwgHCAcHCAgHCAgLDAMICAgMCg0ICwoDAwcQDw0JCgsJCgoICgoOCQsLCQoLCgwKCgsJCgsJCwkODwwNCgsPCwgJCAUJCAkHCAgHCAoICAgICAcHCwcJCAsLCQsICAsICAgFCAgDAwMODAgHBwgHBg8QCgUFBQUFBQUFBQUFBQUGBQMFBQQICAYICQMGCQkDCAcHCQkDBQkICQgHBwgICgoGBgYEBgwDCAgHBwgKCgoKCAgICAYICQQGCQQIBwcJBQkJCAcICAoKAwgHCAkAAAAABQUFBgMDBAMDAwMDAggICAgICAgICAgIBQgLCwQLCAgICwsLCwsDCwsLCwsLCwsKCwsLCwsEBAsLCwsICAgIBwcMDgYIDA4GCAoJCwsLCwsLCwsLCwsLCwsLCwMDAwMDCwsAAAAAAAAAAAAAAAAAAAAACA8LCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLBQULCQYDAwMDBgYDAwoJBAQDAwsLBAQEBgsLBAQLCwQECAgICAgICAgICAgIBQUFBQcHBwcMDAgIDAwICBAQDQ0QEA0NCQkJCQkJCQkIBwgGCAcIBgwMBAQJCQQECQkGBggIAwMFBQYGCAgEBAQGBwYGBgoJCgkEBAgJCAkICQgJCwsDAwsLCwIDCwsDBAsLCwMDAwMDAwsDAwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwUFBQsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwIPHg0KDQoAAAAAAAAAAAAAAAAAAAAACA0NAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMJCAkICQgJCAkICQgJCAkICQgJCAkICQgKCAoICggKCAoICggKCAoIAwMDAwwIDAgMCAwIDAgMCAwIDQoNCg0KDQoNCgoICggNCg0KDQoNCg0KCQcJBwkHCQgDAwwICggKCAoICggKCAAAAAAIBQ4KCQcJBwsICAgICAoICggKCAsIDAgLBAQECQQEBAQEBAQDAwUFAwMDAAMDAwMDAwMDBgYGBgoJCwsEBAsLBAQLCwQECwsEBAsLBAQLCwQECwsEBAgICAgICAgICAgICAgICAgICAgICAgICAUFBQUFBQUFBQUFBQUFBQUFBQcHBwcHBwcHBwcHBwcHBwcMDAgIDAwICAwMCAgQEA0NEBANDQkJCAcIBgwMDAQEDAwEBAwMBAQMDAQEDAwEBAkJCQkMDgYIDA4GCAkJBgYJCQYGCQkGBgwOBggMDgYIDA4GCAwOBggMDgYICAgDAwgIAwMICAMDCAgDAwgIBAQICAgICAgEBAgICAgICAQGBgYGBgYGBgYGBgYGBgYGBgYGBgoJCgkEBAYGCgkEBAoJDAwMDAMAAAAAAAAAERAAAAAAAAADBgAACQAAAAwMCAgQEA0NCAcIBgYFBAQFBAQEBAQMDgMABQYICQgJCAkICQgJCAkICQgICAkQIAwABAQFBgkJDgsDBQUGCQQFBAQJCQkJCQkJCQkJBAQJCQkJEAsLDAwLCgwLAwgLCQ0LDAsMCwsJCwsPCwkJBAQEBwkFCQkICQkECQgEAwgDDQgJCQkFCAQIBwsHBwcFAwUJCwsMCwsMCwkJCQkJCQgJCQkJAwMDAwgJCQkJCQgICAgJBgkJCQYJCQwMEAUFCRAMCwkJCQkJCAsNCQQFBQwOCQoFCQkJCQkJCRALCwwRDwkQBQUEBAkIBwkDCQUFCAgJBAQFEQsLCwsLAwMDAwwMDAsLCwMFBAUFBQUFBQUFCQMLCAkHAwwJCQcLCQkJBQUFDQ0NCQwJAwsIDAgMCAkJBQsJCwkMCgwLCQsJCQMJBAkFCwgLCAwJCwULBQsICQQJBgsICwgJBwkHCQwLCQkHCgUKCQgGEhAIEAgQCAgQDAkKCQoKCwoLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsMChAQEBAQCgoKEBEPDAwJCwoICAwMBwoDBg4FCgYGCgYLCQwIDAgLCQsJCwkMCQwJDAkLCAsIAwMDAwMDAwQIAwsICAkDCwgMCQwJDAkLBQsICgQLCAsICwgMCA8LCQcDCwkQDgwJAw8LDwsPCwkHBAUJCg0NDQ0FBQUFCw0NBgwMDAMLCwsLCQsDCwsNCwoMCwsKCQkLCwwDCQkHCAMICQgHCAkDCAgJBwcJCQgICAsNAwgJCA0LDgkMCwMDCBEQDQkKDAsLCwkLCw4KDAwJCw0LDAsLDAkKCwsMCg0ODQ4LDBAMCQkJBgkJCgcICAcICwgJCAkIBwcNBwkICwsKDAkIDAkJCAYICAQDAw8NCAcHCAgHEBELBQUFBQUFBQUFBQUFBQYFAwUFBAkJBggKAwYKCQMIBwcKCgMGCQgJCQcICQgLCgYGBgQHDAMICAcHCQsLCwsJCQkJBggKBQcJBQgHBwoGCQkJCAkICwoDCQcJCQAAAAAFBQYHAwMEAwMDAwMDCAgICAgICAgICAgFCAwMBQwICAgMDAwMDAMMDAwMDAwMDAoMDAwLCwQEDAwMDAkICAgICA0PBggNDwYICgkMDAwMDAwMDAwMDAwMDAwMBAMDAwMMDAAAAAAAAAAAAAAAAAAAAAAJEAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwFBQwKBwMEAwQHBwMECgkEBAMECwsEBAUGCwsEBAsLBAQJCAgICQgICAkICAgFBQUFCAgICA0NCQkNDQkJEhIODhISDg4JCQkJCQkJCQkHCAYJBwgGDQ0EBAkJBAQKCgYGCAgDAwUFBgYICAQEBQYHBgcHCgkKCQQECQoJCgkKCQoMDAMDDAwMAwMMDAMEDAwMAwMDAwMEDAMDDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMBQUFDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMAhAgDgsOCwAAAAAAAAAAAAAAAAAAAAAIDQ0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAsJCwkLCQsJCwkLCQsJCwkLCQsJCwkLCQsJCwkLCQsJCwkLCQsJCwkDBAMEDAkMCQwJDAkMCQwJDAkOCw4LDgsOCw4LCwgLCA4LDgsOCw4LDgsJBwkHCQcLCQMEDAkLCAsICwgLCAsIAAAAAAkGDwsJBwkHDAkJCAkICwgLCAsIDAkMCQsEBAQJBAQEBAQEBAMDBQUDAwMAAwQDBAMEAwQHBwcHCgkLCwQECwsEBAsLBAQLCwQECwsEBAsLBAQLCwQECQgICAkICAgJCAgICQgICAkICAgJCAgIBQUFBQUFBQUFBQUFBQUFBQUFCAgICAgICAgICAgICAgICA0NCQkNDQkJDQ0JCRISDg4SEg4OCQkJBwgGDQ0NBAQNDQQEDQ0EBA0NBAQNDQQECQkJCQ0PBggNDwYICgoGBgoKBgYKCgYGDQ8GCA0PBggNDwYIDQ8GCA0PBggICAMDCAgDAwgIAwMICAMDCAgEBAgICAgICAQECAgJCAgIBQYGBgYHBwcHBwcHBwcHBwcHBwcHCgkKCQQEBwcKCQQECgkNDQ0NAwAAAAAAAAASEQAAAAAAAAMGAAAKAAAADQ0JCRISDg4JBwgGBwUFBAUEBAQEBA0PAwAFBgkKCQoJCgkKCQoJCgkKCAgJChEiDQAFBQUGCQkPCwMGBgcKBQYFBQkJCQkJCQkJCQkFBQoKCgkRCwsMDAsKDAsFCQsJDQsMCwwLCwkLCxELCwkFBQUHCQYJCQkJCQUJCQQDCAMNCQkJCQYIBAkHCwcJCAYFBgoLCwwLCwwLCQkJCQkJCQkJCQkFBQUFCQkJCQkJCQkJCQkHCQkJBgkKDQ0RBgYJEQ0MCQkJCQkIDA4JBAUFDQ8JCgUKCgkJCwkJEQsLDBEQCREGBgQECQgJCwMJBgYJCQkFBAYRCwsLCwsFBQUFDAwMCwsLBQYEBgYGBgYGBgYJAwsICQgFDAkLCQsJCgoGBgYODg4JDAkFCwgMCQwJCgkGCwkLCQwKDAsJCwkJAwkECQYLCQsJDAkLBgsGCwgJBAkGCwkLCQkICQgJDA0KCQgLBgsJCQYTEQkRCREJCREMCgoKCgoMCwwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwKEREREREKCgoREhANDQkLCgkJDQ0ICgMGDwYKBgYKBgsJDAkMCQsJCwkLCQwJDAkMCQsJCwkFBQUFBQUFBAkDCwgICQMLCQwJDAkMCQsGCwgKBAsJCwkLCQwJEQsLCQQLCREPDQkEEQsRCxELCwkEBgkKDg4ODgYGBgYLDQ0GDQ4NAwsLCwsJCwULCw0LCwwLCwsJCwsNDQULCggJAwkKCQgJCQMICQkHCAkJCAkJCw0DCQkJDQsPCQwLBQUJEhEPCgsLCwsLCQwLDwoMDAoKDQsMCwsMCQsNCw0KDw8NDgoMEAwJCQkGCgkLCAkJBwoLCQkJCQkICQ0HCgkODgsMCQkNCQkJBgkIBAUDDw4JBwkJCAcREgwGBgYGBgYGBgYGBgYGBwYDBgYFCgkHCQoDBwoKAwkICAoKAwYKCQoJCAgJCQwLBwcHBAcMAwkJCAgJDAwMDAoKCgkHCQoFBwoFCQgICgYKCgkICQkMCwMJCAkKAAAAAAUFBgcEAwQDAwQDBAMJCQkJCQkJCQkJCQUJDQ0FDQkJCQ0NDQ0NBA0NDQ0NDQ0NCw0NDQwMBAQNDQ0NCgkJCQgIDhAHCQ4QBwkLCg0NDQ0NDQ0NDQ0NDQ0NDQ0EBAQEBA0NAAAAAAAAAAAAAAAAAAAAAAkRDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQUFDQoHBAQEBAcHBAQLCgQEBAQMDAQEBQYMDAQEDAwEBAoJCQkKCQkJCgkJCQYGBgYICAgIDg4JCQ4OCQkTEw4OExMODgoKCgoKCgoKCQgJBwkICQcNDQUECgoFBAoKBwcJCQQEBgYHBwkJBAQFBggHBwcLCgsKBAQJCgkKCQoJCg0NAwMNDQ0DBA0NBAQNDQ0EBAQEBAQNAwMNDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0FBQUNDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0CESIPCw8LAAAAAAAAAAAAAAAAAAAAAAkODgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAECwkLCQsJCwkLCQsJCwkLCQsJCwkLCQsJCwkLCQsJCwkLCQsJCwkLCQUEBQQMCQwJDAkMCQwJDAkMCQ8LDwsPCw8LDwsLCQsJDwsPCw8LDwsPCwsJCwkLCQsJBQQMCQsJCwkLCQsJCwkAAAAACQYQCwoHCgcMCQkJCQkLCQsJCwkNCQ0JDAQFBAoEBAQEBAQFBAQGBgQEBAAEBAQEBAQEBAcHBwcLCgwMBAQMDAQEDAwEBAwMBAQMDAQEDAwEBAwMBAQKCQkJCgkJCQoJCQkKCQkJCgkJCQoJCQkGBgYGBgYGBgYGBgYGBgYGBgYICAgICAgICAgICAgICAgIDg4JCQ4OCQkODgkJExMODhMTDg4KCgkICQcNDQ0FBA0NBQQNDQUEDQ0FBA0NBQQKCgoKDhAHCQ4QBwkKCgcHCgoHBwoKBwcOEAcJDhAHCQ4QBwkOEAcJDhAHCQkJBAQJCQQECQkEBAkJBAQJCQQECQkJCQkJBAQJCQoJCQkFBgcHBwcHBwcHBwcHBwcHBwcHBwcLCgsKBAQHBwsKBAQLCg4ODg4EAAAAAAAAABMSAAAAAAAAAwYAAAoAAAAODgkJExMODgkICQcHBgUEBQQEBAQEDhADAAYHCQoJCgkKCQoJCgkKCQoJCQkKEyYOAAUFBgcLCxENBAYGBwsFBgUFCwsLCwsLCwsLCwUFCwsLCxMNDQ4ODQwPDQYKDQsPDQ8NDw4NDA0NEw0MDAUFBQcLBgoLCgsLBgsKBAQJBBAKCwsLBgoFCgkNCQkJBgYGCw0NDg0NDw0KCgoKCgoKCwsLCwYGBgYKCwsLCwsKCgoKCwgLCwsHCgwODhMGBgoTDw4KCgoLCwkOEAoEBwcPEQsMBgsLCwoMCwsTDQ0PExILEwcHBAQKCQkMAwsGBgoKCwUEBxENDQ0NDQYGBgYPDw8NDQ0GBgUGBgYGBgYGBgsEDQoMCQYOCwwJDQsLCwYGBhAQEAsPCwYNCg4KDgoLCwYNCg0KDgwODQsNCwsECwYLBg0KDQoPCw4GDgYNCgwGDAcNCg0KDAkMCQoPDgsLCAwIDAsKBxUTChMKEwoKEw4LCwsLCw0MDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDgsTExMTEwsLCxMUEQ4OCgwLCgoODggLBAcRBgsHBwsHDQoOCg4KDQsNCw0LDwsPCw8LDQoNCgYGBgYGBgYECgQNCQoLBA0KDgsPCw8LDgYNCgwFDQoNCg0KDQoTDQwJBA0KExEPCwYTDRMNEw0MCQQGCwsQEBAQBgYGBg0PDwcPEA8EDQ0NDQwNBg0NDw0MDw0NDAwMDQ4OBgwLCAoECgsKCAoLBAoJCwkJCwsJCgoOEAQKCwoQDRAKDg0GBgoUEw8LDA4NDA0KDQ0SCw4OCwwPDQ8NDQ4MDA4NDg0REQ8RDQ4TDgoLCgcLCw4JCgoICg0KCwkLCggJEAkLCg4ODA4LCg4KCwoHCgoEBgQRDwoICQoJCBMUDQYGBgYGBgYGBgYGBgYHBgQGBgULCggKCwQHCwsECgkJCwsEBwsKCwoJCQoKDQwJCQkECBAECgoJCQoNDQ0NCwsLCggKCwUICwUKCQkLBwsLCgkKCg0MBAoJCgsAAAAABgYHCAQEBQQEBAQEAwoKCgoKCgoKCgoKBgoODgUOCgoKDg4ODg4EDg4ODg4ODg4MDg4ODg4FBQ4ODg4LCgoKCQkPEgcKDxIHCgwLDg4ODg4ODg4ODg4ODg4ODgQEBAQEDg4AAAAAAAAAAAAAAAAAAAAACxMODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4OBgYODAgEBAQECAgEBAwLBQUEBA4OBQUFBw4OBQUODgUFCwoKCgsKCgoLCgoKBgYGBgkJCQkQEAoKEBAKChUVEBAVFRAQCwsLCwsLCwsKCQoHCgkKBw8PBQULCwUFCwsHBwoKBAQGBgcHCgoFBQUHCQcICAwLDAsFBQoLCgsKCwoLDg4EBA4ODgMEDg4EBQ4ODgQEBAQEBA4EBA4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODgYGBg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODgITJhAMEA0AAAAAAAAAAAAAAAAAAAAAChAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQNCg0KDQoNCg0KDQoNCg0KDQoNCg0KDQoNCw0LDQsNCw0LDQsNCw0LBgQGBA8LDwsPCw8LDwsPCw8LEAwQDBAMEAwQDA0KDQoQDRANEA0QDRANDAkMCQwJDQoGBA8LDQoNCg0KDQoNCgAAAAAKBxINCwgLCA4LCwoLCg0KDQoNCg4LDwsOBQUFCwUFBQUFBQUEBAYGBAQEAAQEBAQEBAQECAgICAwLDg4FBQ4OBQUODgUFDg4FBQ4OBQUODgUFDg4FBQsKCgoLCgoKCwoKCgsKCgoLCgoKCwoKCgYGBgYGBgYGBgYGBgYGBgYGBgkJCQkJCQkJCQkJCQkJCQkQEAoKEBAKChAQCgoVFRAQFRUQEAsLCgkKBw8PDwUFDw8FBQ8PBQUPDwUFDw8FBQsLCwsPEgcKDxIHCgsLBwcLCwcHCwsHBw8SBwoPEgcKDxIHCg8SBwoPEgcKCgoEBAoKBAQKCgQECgoEBAoKBQUKCgoKCgoFBQoKCwoKCgUHBwcHCAgICAgICAgICAgICAgICAwLDAsFBQgIDAsFBQwLDw8PDwQAAAAAAAAAFRUAAAAAAAAEBwAACwAAABAQCgoVFRAQCgkKBwgGBQUGBQUFBQUPEgQABwkKCwoLCgsKCwoLCgsKCwoKCgsVKhAABgYGBwwMEw4EBwcIDAYHBgYMDAwMDAwMDAwMBgYMDAwMFQ0ODw8ODRAOBgsODBEOEA4QDw4MDg0VDg4NBgYGCAwHDAsLCwwGCwsFBAoEEAsMCwsHCwYLCw8KCwkHBgcMDQ0PDg4QDgwMDAwMDAsMDAwMBgYGBgsMDAwMDAsLCwsMCAwMDAcLDQ8PFQcHDBUQDwwMDAwLCg8RDAYHCBATDA0GDAwMDA0MDBUNDRAVFAwVBwcFBQwKCw4EDAcHCwsMBgUHFQ0ODQ4OBgYGBhAQEA4ODgYHBwcHBwcHBwcHDAQOCw0JBg8MDgsODAwMBwcHEhISDBALBg4LDwsPCwwMBw0MDQwPDQ8ODA4MDAQMBgwHDgsOCxAMDwcPBw4LDAYMCA4LDgsNCQ0JDBAQDAwJDQgODAsIFxULFQsVCwsVDwwNDA0NDw0PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDw8PDRUVFRUVDQ0NFRYTEBALDgwLCxAPCQ0EBxMHDQcHDQcNDA8LDwsODA4MDgwQCxALEAwOCw4LBgYGBgYGBgULBA4KCgwEDgsPDBAMEAwPBw4LDQYOCw4LDgsOCxUPDgsFDQwVExAMBhUPFQ8VDw4LBQcMDRISEhIHBwcHDRARCBAREAQNDg4ODQ4GDg4RDg4QDg4NDA4OEhAGDgwJCwQKDAoJCwwECgsLCwkMDAoKCw4QBAoMChAOEgsPDgYGCxYVEgwNDw0ODgsODhMNDw8MDhEOEA4ODwwNEA4QDhIUERMODxUPDAwLCAwMDgoLCwkLDQsMCgsLCgsRCgwLEBANDwsLEAsMDAgLCwUGBBMRDAkLCwoJFRcOBwcHBwcHBwcHBwcHBwgHBgcHBgwLCAsNBggNDAYLCgoNDQYHDAsMCwoKDAsPDgsLCwUJEgYLCwoKCw8PDw8MDAwLCAsNBgkMBgsKCg0HDAwLCgwLDw4GCwoLDAAAAAAHBwcJBAQFBAQEBAQDCwsLCwsLCwsLCwsHCxAQBhALCwsQEBAQEAQQEBAQEBAQEA0QEBAPDwUFEBAQEAwLCwsKChEUCAsRFAgLDQwQEBAQEBAQEBAQEBAQEBAQBQQEBAQQEAAAAAAAAAAAAAAAAAAAAAAMFRAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAHBxANCQQFBAUJCQQFDQwFBQQFDw8FBQYIDw8FBQ8PBQUMCwsLDAsLCwwLCwsHBwcHCgoKChERCwsREQsLFxcSEhcXEhIMDAwMDAwMDAsJCwgLCQsIEREGBgwMBgYNDQgICwsEBAcHCAgLCwUFBggJCAkJDQwNDAUFCw0LDQsNCw0QEAQEEBAQAwQQEAQFEBAQBAQEBAQFEAQEEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQBwcHEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQAxUqEg4SDgAAAAAAAAAAAAAAAAAAAAALEhIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQ0MDQwNDA0MDQwNDA0MDQwNDA0MDQwNDA4MDgwODA4MDgwODA4MDgwGBQYFEAwQDBAMEAwQDBAMEAwSDhIOEg4SDhIODgsOCxIOEg4SDhIOEg4OCw4LDgsNDAYFEAwOCw4LDgsOCw4LAAAAAAsIEw4MCQwJDwwMCwwLDgsOCw4LEAwQDA8FBgYMBQUFBQUFBgQEBwcEBAQABAUEBQQFBAUJCQkJDQwPDwUFDw8FBQ8PBQUPDwUFDw8FBQ8PBQUPDwUFDAsLCwwLCwsMCwsLDAsLCwwLCwsMCwsLBwcHBwcHBwcHBwcHBwcHBwcHCgoKCgoKCgoKCgoKCgoKChERCwsREQsLERELCxcXEhIXFxISDAwLCQsIERERBgYREQYGEREGBhERBgYREQYGDAwMDBEUCAsRFAgLDQ0ICA0NCAgNDQgIERQICxEUCAsRFAgLERQICxEUCAsLCwQECwsEBAsLBAQLCwQECwsFBQsLCwsLCwUFCwsMCwsLBggICAgJCQkJCQkJCQkJCQkJCQkJDQwNDAUFCQkNDAUFDQwRERERBAAAAAAAAAAYFwAAAAAAAAQIAAANAAAAERELCxcXEhILCQsICQcGBQcFBQUFBREUBgAHCwsNCw0LDQsNCw0LDQsNCwsLDRgwEgAHBwgJDQ0VEAUICAkOBwgHBw0NDQ0NDQ0NDQ0HBw4ODg0YDxARERAPExEGDBANExETEBMREA4RDxcPEA8HBwcMDQgNDgwODQcODgUGDAYUDg0ODggMBw4LEQsMDAgGCA4PDxEQERMRDQ0NDQ0NDA0NDQ0GBgYGDg0NDQ0NDg4ODg0KDQ0NCA0PEhIYCAgNGBMRDQ0NDQ4MERQNBgkJEhUPDwgODQ0NDw0NGA8PExgXDRgICAUFDQwMEAQNCAgMDA0HBQgaDxAPEBAGBgYGExMTERERBggICAgICAgICAgNBhAMDwwGEQ0QDBANDg4ICAgUFBQNEw4GEAwRDBEMDQ0IDw0PDREPERANEA0NBg0HDQgRDhEOEw0RCBEIEAwOBw4JEQ4RDg8MDwwNExIODQsPCRANDAkaGAwYDBgMDBgRDg8ODw8RDxERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERIPGBgYGBgPDw8ZGRYSEg0QDgwMEhILDwUJFQgPCQkPCQ8NEQwRDBANEA0QDRMOEw4TDREOEQ4GBgYGBgYGBQwGEAwMDQYRDhENEw0TDREIEAwPBxEOEQ4RDhEOFxEQDAUPDRgVEw8GFxEXERcREAwFCA0OFBQUFAgICAgPExQJExQSBg8QEBAPEQYQEBMREBMREA8OEA8TEgYQDgsOBg4OCwsODQYMDA4LCw0ODA4NEhIGDg0OEhAVDREQBgYMGRgWDg8RDxAQDRAQFg8REQ4QExETERARDg8SDxIQFhcTFRARGBENDg0JDg0QCw0NCw4RDQ0NDgwLDBQLDg0TFA8RDQwSDQ0OCQwMBQYGFhQOCwwNDAoYGhEICAgICAgICAgICAgICQgGCAgHDg0KDA4GCQ4OBgwLCw4OBggODQ4NCwsNDBEPDAwMBgoVBgwMCwsNEREREQ4ODg0KDA4HCg4HDAsLDggODg0LDQwRDwYNCw0OAAAAAAgICQoFBQYFBQUFBQQNDQ0NDQ0NDQ0NDQgNEhIHEg0NDRISEhISBRISEhISEhISDxISEhERBgYSEhISDg0NDQwMExYJDBMWCQwPDhISEhISEhISEhISEhISEhIFBQUFBRISAAAAAAAAAAAAAAAAAAAAAA0YEhISEhISEhISEhISEhISEhISEhISEhISEhISEggIEg8KBQYFBgoKBQYPDgYGBQYREQYGBwkREQYGEREGBg4NDQ0ODQ0NDg0NDQgICAgMDAwMFBQNDRQUDQ0aGhQUGhoUFA4ODg4ODg4ODQsNCQ0LDQkTEwYGDg4GBg4OCQkMDAUFCAgJCQ0NBgYHCQsJCgoPDg8OBgYNDg0ODQ4NDhISBQUSEhIEBRISBQYSEhIFBQUFBQUSBQUSEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhIICAgSEhISEhISEhISEhISEhISEhISEhISEhIDGDAVEBUQAAAAAAAAAAAAAAAAAAAAAAwUFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFDw0PDQ8NDw0PDQ8NDw0PDQ8NDw0PDQ8NEA0QDRANEA0QDRANEA0QDQYFBgUTDRMNEw0TDRMNEw0TDRUQFRAVEBUQFRARDhEOFRAVEBUQFRAVEBAMEAwQDA8NBgUTDREOEQ4RDhEOEQ4AAAAADQkWEA4LDgsRDQ0MDQwQDBANEA4SDRMNEQYGBg4GBgYGBgYGBQUICAUFBQAFBgUGBQYFBgoKCgoPDhERBgYREQYGEREGBhERBgYREQYGEREGBhERBgYODQ0NDg0NDQ4NDQ0ODQ0NDg0NDQ4NDQ0ICAgICAgICAgICAgICAgICAgMDAwMDAwMDAwMDAwMDAwMFBQNDRQUDQ0UFA0NGhoUFBoaFBQODg0LDQkTExMGBhMTBgYTEwYGExMGBhMTBgYODg4OExYJDBMWCQwODgkJDg4JCQ4OCQkTFgkMExYJDBMWCQwTFgkMExYJDAwMBQUMDAUFDAwFBQwMBQUNDQYGDQ0NDQ0NBgYNDQ4NDQ0HCQkJCQoKCgoKCgoKCgoKCgoKCgoPDg8OBgYKCg8OBgYPDhMTExMFAAAAAAAAABsaAAAAAAAABQkAAA4AAAAUFA0NGhoUFA0LDQkKCAcGCAYGBgYGExYGAAgMDQ4NDg0ODQ4NDg0ODQ4NDQ0OGzYUAAgICAoPDxgSBQkJCxAICQgIDw8PDw8PDw8PDwgIEBAQDxsSEhQUEhEVEwgNEg8XExURFRQSEBMRHBESEQgICAwPCQ8PDg8PBw8PBgYOBhYPDw8PCQ4IDw0TDA4NCQYJEBISFBITFRMPDw8PDw8ODw8PDwYGBgYPDw8PDw8PDw8PDwsPDw8JDxEUFBsJCQ8bFRMPDw8PEA0TFg8HCgoVGBERCBAPDw8RDw8bEhIVGxkPGwkJBgYPDQ4SBQ8JCQ4ODwgGCR0SEhISEggICAgVFRUTExMGCQgJCQkJCQkJCQ8GEg4RDQYUDxIOEg8QEAkJCRcXFw8VDwgSDhQOFA4PDwkSDxIPFBEUEg8SDw8GDwgPCRMPEw8VDxQJFAkSDhAHEAoTDxMPEQ0RDQ8VFhAPDBELEg8OCh0bDhsOGw4OGhMQEBAQEBMRExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTFBAbGxsbGxAQEBwcGRQUDhIQDg4UFAwQBQoYCRAKChAKEg8UDhQOEg8SDxIPFQ8VDxUPEw8TDwgGCAYIBggGDQYSDg4PBhMPFA8VDxUPFAkSDhEIEw8TDxMPFA8cExIOBhIPGxgVEQYcExwTHBMSDgYJDxAXFxcXCQkJCRIVFwoVFhQGEhISEhETCBISFxMSFRMRERASERYUCBIQDA8GDxAODA8PBg4OEA0MDw8NDw4SFQYPDw8VEhcPExIICA0dGxcQERMSEhIPEhIZEBMTEBIXExUSERQQERQRFBEZGRUYEhMbFA8PDgoQDxIMDw8MEBMPDw8PDgwOFgwPDhYWERMODhQPDw8KDg4GBgYYFg8MDg8NCxsdEwkJCQkJCQkJCQkJCQkKCQYJCQgPDwsOEAYKEBAGDgwNEBAGChAODw8MDQ8OExEMDAwGCxUGDg4NDQ4TExMTDw8PDwsOEAgLEAgODA0QChAPDw0PDhMRBg8MDxAAAAAACQkKCwYFBgUFBgUGBA4ODg4ODg4ODg4OCQ4UFAgUDg4OFBQUFBQGFBQUFBQUFBQRFBQUExMHBxQUFBQPDg4ODQ0WGQsOFhkLDhEQFBQUFBQUFBQUFBQUFBQUFAYGBgYGFBQAAAAAAAAAAAAAAAAAAAAADxsUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUCQkUEQsGBgYGDAwGBhEQBwcGBhMTBwcIChMTBwcTEwcHDw4ODg8ODg4PDg4OCQkJCQ0NDQ0WFg4OFhYODh4eFxceHhcXEBAQEBAQEBAPDA4LDwwOCxUVBwcQEAcHEBALCw4OBgYJCQsLDg4HBwgKDAsMDBEQERAHBw8QDxAPEA8QFBQFBRQUFAQGFBQGBhQUFAYGBgYGBhQFBRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFAkJCRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFAMbNhcSFxIAAAAAAAAAAAAAAAAAAAAADhcXAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAYSDxIPEg8SDxIPEg8SDxIPEg8SDxIPEg8SDxIPEg8SDxIPEg8SDxIPCAYIBhUPFQ8VDxUPFQ8VDxUPFxIXEhcSFxIXEhMPEw8XEhcSFxIXEhcSEg4SDhIOEg8IBhUPEw8TDxMPEw8TDwAAAAAPChkSEAwQDBQPDw4PDhIOEg4SDxQPFQ8TBwcHEAcHBwcHBwcGBgkJBgYGAAYGBgYGBgYGDAwMDBEQExMHBxMTBwcTEwcHExMHBxMTBwcTEwcHExMHBw8ODg4PDg4ODw4ODg8ODg4PDg4ODw4ODgkJCQkJCQkJCQkJCQkJCQkJCQ0NDQ0NDQ0NDQ0NDQ0NDQ0WFg4OFhYODhYWDg4eHhcXHh4XFxAQDwwOCxUVFQcHFRUHBxUVBwcVFQcHFRUHBxAQEBAWGQsOFhkLDhAQCwsQEAsLEBALCxYZCw4WGQsOFhkLDhYZCw4WGQsODg4GBg4OBgYODgYGDg4GBg4OBwcODg4ODg4HBw4ODw4ODggKCgoKDAwMDAwMDAwMDAwMDAwMDBEQERAHBwwMERAHBxEQFhYWFgYAAAAAAAAAHh0AAAAAAAAFCgAAEAAAABYWDg4eHhcXDwwOCwsJCAcJBwcHBwcWGQYACQwPEA8QDxAPEA8QDxAPEA4ODxAdOhYACAgJChAQGhMGCgoLEQgKCAgQEBAQEBAQEBAQCAgREREQHRMTFRUTEhcVBw8TEBcVFxMXFRMTFRMeExMSCAgIDhAKEBAPEBAIEBAHBw4HGRAQEBAKDwgQDRUNDQ4KCAoRExMVExUXFRAQEBAQEA8QEBAQCQkJCRAQEBAQEBAQEBAQDBAQEAoQEhUVHQoKEB0XFRAQEBARDhUYEAcLCxYaEhIJERAQEBIQEB0TExcdGxAdCgoGBhAODRMFEAoKDw8QCAYKHRMTExMTBwcHBxcXFxUVFQkKCQoKCgoKCgoKEAYTDxIOCBUQEw0TEBERCgoKGBgYEBcQBxMPFQ8VDxAQChMQExAVEhUTEBMQEAcQCRAKFRAVEBcQFQoVChMPEwgTCxUQFRASDhIOEBcWERANEgwSEA8LHx0PHQ8dDw8cFRESERISFRIVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVEh0dHR0dEhISHh8bFhYPExEPDxYVDRIFChoJEgoKEgoTEBUPFQ8TEBMQExAXEBcQFxAVEBUQBwkHCQcJBwcPBxMODxAHFRAVEBcQFxAVChMPEggVEBUQFRAVEB4VEw0HExAdGhcSCB4VHhUeFRMNBgoQERgYGBgKCgoKExcYCxYYFgcTExMTEhUHExMXFRMXFRMSExMTFxYHExENEAcQEQ4NEBAHDw8RDQ0QEQ4QDxUXBxAQEBcTGRAVEwcHDx8dGRESFRMTExAUExsSFRURExcVFxUTFRMSFhMVExsbFxoTFR0VEBEPCxEQFA0QEA0RFBAQEBAPDQ0YDREPFxgSFQ8PFhAQEAsPDwcJBxoYEA0NEA4MHR8UCgoKCgoKCgoKCgoKCgsKBwoKCBAQDA8RBwsREQcPDQ0REQcKEQ8QEA0OEA8UEw4ODgcMGQcPDw0NEBQUFBQQEBAQDA8RCAwRCA8NDREKERAQDhAPFBMHEA0QEQAAAAAJCQoMBgYHBgYGBgYFDw8PDw8PDw8PDw8JDxYWCBYPDw8WFhYWFgYWFhYWFhYWFhMWFhYVFQcHFhYWFhAPDw8ODhgbCw8YGwsPExEWFhYWFhYWFhYWFhYWFhYWBwYGBgYWFgAAAAAAAAAAAAAAAAAAAAAQHRYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYJCRYSDAYHBgcNDQYHExEHBwYHFRUHBwgLFRUHBxUVBwcQDw8PEA8PDxAPDw8KCgoKDg4ODhgYDw8YGA8PICAZGSAgGRkRERERERERERANDwsQDQ8LFxcICBERCAgREQsLDw8GBgoKCwsPDwcHCAsNCw0NExETEQcHEBEQERAREBEWFgYGFhYWBQYWFgYHFhYWBgYGBgYHFgYGFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWCQkJFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWBB06GRMZEwAAAAAAAAAAAAAAAAAAAAAPGBgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABxMQExATEBMQExATEBMQExATEBMQExATEBMQExATEBMQExATEBMQExAHBwcHFxAXEBcQFxAXEBcQFxAZExkTGRMZExkTFRAVEBkTGRMZExkTGRMTDRMNEw0TEAcHFxAVEBUQFRAVEBUQAAAAABALGxMRDRENFRAQDxAPEw8TDxMQFhAXEBUHCAgRBwcHBwcHCAYGCgoGBgYABgcGBwYHBgcNDQ0NExEVFQcHFRUHBxUVBwcVFQcHFRUHBxUVBwcVFQcHEA8PDxAPDw8QDw8PEA8PDxAPDw8QDw8PCgoKCgoKCgoKCgoKCgoKCgoKDg4ODg4ODg4ODg4ODg4ODhgYDw8YGA8PGBgPDyAgGRkgIBkZEREQDQ8LFxcXCAgXFwgIFxcICBcXCAgXFwgIERERERgbCw8YGwsPERELCxERCwsREQsLGBsLDxgbCw8YGwsPGBsLDxgbCw8PDwYGDw8GBg8PBgYPDwYGDw8HBw8PDw8PDwcHDw8QDw8PCAsLCwsNDQ0NDQ0NDQ0NDQ0NDQ0NExETEQcHDQ0TEQcHExEYGBgYBgAAAAAAAAAhHwAAAAAAAAYLAAARAAAAGBgPDyAgGRkQDQ8LDAoIBwkHBwcHBxgbBwAKDhAREBEQERAREBEQERARDw8QESBAGAAJCQsLEhIcFQYLCwwTCQsJCRISEhISEhISEhIJCRMTExIgFRUXFxUUGRcJEBUSGxcZFRkXFRMXFSAVFRQJCQkOEgsRERAREQoREgcHEAcbEhEREQsQCRIPFw4PDwsICxMVFRcVFxkXEREREREREBEREREJCQkJEhEREREREhISEhINEhISCxEUGBggCwsSIBkXEhISEhIQFxoSBwwMGRwUFAsTERISFBISIBUVGSAeEiALCwcHEhAPFQUSCwsQEBIJBwsgFRUVFRUJCQkJGRkZFxcXCQsJCwsLCwsLCwsSBxUQFA8IFxIVDxUSExMLCwsbGxsSGREJFRAXEBcQEhILFREVERcUFxURFRESBxIJEgsXEhcSGREXCxcLFRATCRMMFxIXEhQPFA8SGRoTEg4UDRUSEAwjIBAgECAQEB8XExMTExMXFBcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcTICAgICATExMhIh0YGBEVExAQGBgOEwYLHAoTCwsTCxURFxAXEBURFREVERkRGREZEhcSFxIJCQkJCQkJBxAHFRAQEgcXEhcSGREZERcLFRAUCRcSFxIXEhcSIBcVDwcVESAcGRQJIBcgFyAXFQ8HCxITGxsbGwsLCwsVGRsMGRoYBxUVFRUUFwkVFRsXFRkXFRQTFRUbGAkVEw4SBxISEA4SEQcQEBIPDhERDhIRFxgHEhESGBUcERcVCQkQIiAbExQXFRQVERYVHhMXFxMVGxcZFxUXExQYFRgVHR4ZHBUXIBcRERAMExEWDxISDhMWEhERERAPDxkOEhEaGhMXERAYERESDBAQBwkHHBkSDg8SEA0gIhYLCwsLCwsLCwsLCwsLDAsHCwsJEhENEBMHDBMTBxAPDxMTBwsSERIRDw8SEBYVDw8PCA0cBxAQDw8RFhYWFhISEhENEBMJDRMJEA8PEwsSEhEPEhAWFQcRDxESAAAAAAoKCw0HBggGBgcGBwUREREREREREREREQoRGBgJGBERERgYGBgYBxgYGBgYGBgYFBgYGBcXCAgYGBgYEhERERAQGh4NEBoeDRAUExgYGBgYGBgYGBgYGBgYGBgHBwcHBxgYAAAAAAAAAAAAAAAAAAAAABIgGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGAoKGBQNBwcHBw4OBwcUEwgIBwcXFwgICQwXFwgIFxcICBIRERESEREREhEREQsLCwsQEBAQGhoRERoaEREjIxsbIyMbGxMTExMTExMTEQ4RDREOEQ0ZGQkIExMJCBMTDQ0QEAcHCwsNDRERCAgJDA4NDg4UExQTCAgRExETERMRExgYBgYYGBgFBxgYBwgYGBgHBwcHBwcYBgYYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgKCgoYGBgYGBgYGBgYGBgYGBgYGBgYGBgYGBgEIEAbFRsVAAAAAAAAAAAAAAAAAAAAABAbGwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHFREVERURFREVERURFREVERURFREVERURFREVERURFREVERURFREVEQkHCQcZERkRGREZERkRGREZERsVGxUbFRsVGxUXEhcSGxUbFRsVGxUbFRUPFQ8VDxURCQcZERcSFxIXEhcSFxIAAAAAEQweFRMOEw4XEhIQEhAVEBURFRIYEhkSFwgJCBMICAgICAgJBwcLCwcHBwAHBwcHBwcHBw4ODg4UExcXCAgXFwgIFxcICBcXCAgXFwgIFxcICBcXCAgSEREREhERERIRERESEREREhERERIRERELCwsLCwsLCwsLCwsLCwsLCwsQEBAQEBAQEBAQEBAQEBAQGhoRERoaEREaGhERIyMbGyMjGxsTExEOEQ0ZGRkJCBkZCQgZGQkIGRkJCBkZCQgTExMTGh4NEBoeDRATEw0NExMNDRMTDQ0aHg0QGh4NEBoeDRAaHg0QGh4NEBAQBwcQEAcHEBAHBxAQBwcREQgIERERERERCAgRERIREREJDAwMDA4ODg4ODg4ODg4ODg4ODg4UExQTCAgODhQTCAgUExoaGhoHAAAAAAAAACQjAAAAAAAABgwAABMAAAAaGhERIyMbGxEOEQ0NCwkICggICAgIGh4HAAsPERMRExETERMRExETERMRERETIUIZAAkJCwwSEh0WBgsLDRMJCwkJEhISEhISEhISEgkJExMTEiIWFhgYFhQaGAkRFhIbGBoWGhgWFRgWIhUVFAkJCQ4SCxESERIRChISBwcQBxsSERISCxEJEg8XDw8QCwgLExYWGBYYGhgREREREREREREREQkJCQkSERERERESEhISEg0SEhIMEhQYGCELCxIhGhgSEhISExAYGxIHDAwZHRQUCxMTEhIUEhIhFhYaIR8SIQsLBwcSEA8VBhILCxEREgkHCyAWFhYWFgkJCQkaGhoYGBgJCwkLCwsLCwsLCxIHFhEUEAgYEhUPFhITEwsLCxwcHBIaEgkWERgRGBESEgsWERYRGBQYFhEWERIHEgoSCxgSGBIaERgLGAsWERUJFQwYEhgSFBAUEBIaGhMSDhQNFRIRDCQhESERIRERIBgTFBMUFBcVFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXFxcXGBQhISEhIRQUFCIjHhkZEhYUEREZGA8UBgwdCxQMDBQMFhEYERgRFhEWERYRGhIaEhoSGBIYEgkJCQkJCQkHEQcWEBESBxgSGBIaERoRGAsWERQJGBIYEhgSGBIiFxUPBxYRIR0aFAkiFyIXIhcVDwcLEhQcHBwcCwsLCxYaHA0aGxkHFhYWFhQYCRYWGxgVGhgWFBUVFRsZCRUTDhIHEhMRDxIRBxEREw8PERIPEhEXGQcSERIZFh0SGBYJCREjIRwTFRgWFRYSFhYeFBgYExYbGBoYFhgVFRkVGBYeHxodFhghGBESEQwTERUPEhIOExcSERISEQ8PGg8TERobFBgSERkSERIMEREHCQcdGhIODxIQDiEjFwsLCwsLCwsLCwsLCwsNCwcLCwkTEg0RFAcNFBMHEQ8PFBQHDBMRExIPEBIRFxUPDw8IDhwHEREPDxIXFxcXExMTEg0RFAkOEwkRDw8UDBMTEhASERcVBxIPEhMAAAAACwsMDgcHCAcHBwcHBRERERERERERERERCxEZGQkZERERGRkZGRkHGRkZGRkZGRkVGRkZGBgICBkZGRkTEREREBAbHw0RGx8NERUTGRkZGRkZGRkZGRkZGRkZGQcHBwcHGRkAAAAAAAAAAAAAAAAAAAAAEiEZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZCwsZFA4HCAcIDg4HCBUTCAgHCBgYCAgJDBgYCAgYGAgIExERERMRERETERERCwsLCxAQEBAbGxISGxsSEiQkHBwkJBwcExMTExMTExMSDxENEg8RDRoaCQkTEwkJFBQNDRERBwcLCw0NEREICAkMDw0ODhUTFRMICBIUEhQSFBIUGRkHBxkZGQUHGRkHCBkZGQcHBwcHBxkHBxkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGQsLCxkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGQQhQhwWHBYAAAAAAAAAAAAAAAAAAAAAERwcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAcWERYRFhEWERYRFhEWERYRFhEWERYRFhEWERYRFhEWERYRFhEWERYRCQcJBxoRGhEaERoRGhEaERoRHBYcFhwWHBYcFhgSGBIcFhwWHBYcFhwWFQ8VDxUPFhEJBxoRGBIYEhgSGBIYEgAAAAASDB4WEw4TDhgSEhESERYRFhEWEhkSGhIYCAkJEwgICAgICAkHBwsLBwcHAAcIBwgHCAcIDg4ODhUTGBgICBgYCAgYGAgIGBgICBgYCAgYGAgIGBgICBMRERETERERExERERMRERETERERExEREQsLCwsLCwsLCwsLCwsLCwsLCxAQEBAQEBAQEBAQEBAQEBAbGxISGxsSEhsbEhIkJBwcJCQcHBMTEg8RDRoaGgkJGhoJCRoaCQkaGgkJGhoJCRMTExMbHw0RGx8NERQUDQ0UFA0NFBQNDRsfDREbHw0RGx8NERsfDREbHw0REREHBxERBwcREQcHEREHBxERCAgREREREREICBERExEREQkMDQ0NDg4ODg4ODg4ODg4ODg4ODhUTFRMICA4OFRMICBUTGxsbGwcAAAAAAAAAJSQAAAAAAAAGDAAAFAAAABsbEhIkJBwcEg8RDQ4LCQgLCAgICAgbHwcACw8SFBIUEhQSFBIUEhQSFBEREhQlShwACgoLDRUVIRkHDAwOFgoMCgoVFRUVFRUVFRUVCgoWFhYVJhkZGxsZFx0bCRMZFR8bHRkdGxkXGxkmGRcXCgoKERUMFBUTFRQKFRUHCRMHHxUVFRUMEgoVERsRERIMCQwWGRkbGRsdGxQUFBQUFBMUFBQUCQkJCRUVFRUVFRUVFRUVDxUVFQ0UFxsbJQwMFCUdGhQUFBUVEhoeFAkODhwhFxcLFhUVFBcVFSUZGR0lIxUlDAwICBQSERcGFQwMExMVCggMJRkZGRkZCQkJCR0dHRsbGwkMCwwMDAwMDAwMFQgZEhcSCRsVFxEZFRYWDAwMHx8fFR0VCRkSGxMbExUUDBkUGRQbFxsZFBkUFQcVCxUMGxUbFR0VGwwbDBkSFwoXDhsVGxUXEhcSFB0eFRURFw8YFBMOKCUTJRMlExMkGxYWFhYWGhcaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhobFiUlJSUlFhYWJiciHBwUGBYTExwbEBYHDSEMFg0NFg0ZFBsTGxMZFBkUGRQdFR0VHRUbFRsVCQkJCQkJCQcTCRkTExUHGxUbFR0VHRUbDBkSFwobFRsVGxUbFSYbFxEHGRQlIR0XCSYbJhsmGxcRCAwVFh8fHx8MDAwMGR0fDh0eHAgZGRkZFxsJGRkfGxgdGxkXFxcZHhwJFxURFQgUFRMQFRUIExMVEREVFRIUExodCBQVFB0ZIBQbGQkJEyclIBYYGxkYGRQZGSIWGxsWGB8bHRsZGxcYHBkbGSIjHSEYGyUbFBUUDhYUGREVFRAWGRQVFBUTEREeERUTHh4XGxQTHBQUFQ4TEgcJCSIeFRARFBIPJSgaDAwMDAwMDAwMDAwMDA4MCQwMChUUDxMWCQ4WFgkTEREWFgkNFRQVFBESFBMaGBISEgkPHgkTExERFBoaGhoVFRUUDxMWCw8WCxMRERYNFRUUEhQTGhgJFBEUFQAAAAAMDA0PCAcJBwcIBwgGExMTExMTExMTExMMExwcChwTExMcHBwcHAgcHBwcHBwcHBgcHBwaGgkJHBwcHBUTFBQSEh4jDxMeIw8TGBYcHBwcHBwcHBwcHBwcHBwcCAgICAgcHAAAAAAAAAAAAAAAAAAAAAAVJRwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwMDBwXDwgICAgQEAgIGBYJCQgIGhoJCQoOGhoJCRoaCQkVExQUFRMUFBUTFBQMDAwMEhISEh4eFBQeHhQUKSkfHykpHx8WFhYWFhYWFhQREw8UERMPHR0KChYWCgoWFg8PExMICA0NDw8TEwkJCg4RDxAQGBYYFgkJFBYUFhQWFBYcHAcHHBwcBggcHAgJHBwcCAgICAgIHAcHHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcDAwMHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcBSVKIBggGQAAAAAAAAAAAAAAAAAAAAATHx8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABxkUGRQZFBkUGRQZFBkUGRQZFBkUGRQZFBkUGRQZFBkUGRQZFBkUGRQJBwkHHRUdFR0VHRUdFR0VHRUgGCAYIBggGCAYGxUbFSAZIBkgGSAZIBkXERcRFxEZFAkHHRUbFRsVGxUbFRsVAAAAABQOIhkWEBYQGxQVExUTGRMZExkVHBUdFRoJCgoWCQkJCQkJCggIDAwICAgACAgICAgICAgQEBAQGBYaGgkJGhoJCRoaCQkaGgkJGhoJCRoaCQkaGgkJFRMUFBUTFBQVExQUFRMUFBUTFBQVExQUDAwMDAwMDAwMDAwMDAwMDAwMEhISEhISEhISEhISEhISEh4eFBQeHhQUHh4UFCkpHx8pKR8fFhYUERMPHR0dCgodHQoKHR0KCh0dCgodHQoKFhYWFh4jDxMeIw8TFhYPDxYWDw8WFg8PHiMPEx4jDxMeIw8THiMPEx4jDxMTEwgIExMICBMTCAgTEwgIExMJCRMTExMTEwkJExMVExQUCg4ODg4QEBAQEBAQEBAQEBAQEBAQGBYYFgkJEBAYFgkJGBYeHh4eCAAAAAAAAAAqKAAAAAAAAAcOAAAWAAAAHh4UFCkpHx8UERMPDw0KCQwJCQkJCR4jCQANEhQWFBYUFhQWFBYUFhQWExMUFipUIAAMDA4PFxclHAgODhAZDA4MDBcXFxcXFxcXFxcMDBkZGRcrHBweHhwaIR4MFRwXIx4hHCEeHBoeHCobHBoMDAwTFw4XFxUXFw0XFwoKFQokFxcXFw4UDBcXHRYVFQ4LDhkcHB4cHiEeFxcXFxcXFRcXFxcMDAwMFxcXFxcXFxcXFxcRFxcXDxcaHx8qDg4XKiEeFxcXFxgVHiMXChAPICUaGg4ZFxcXGhcXKhwcISooFyoODgkJFxUVHAcXDg4VFRcMCQ4rHBwcHBwMDAwMISEhHh4eDA4NDg4ODg4ODg4XCRwUGhULHhccFRwXGRkODg4jIyMXIRcMHBQeFR4VFxcOHBccFx4aHhwXHBcXChcMFw4eFx4XIRceDh4OHBQaDBoQHhceFxoVGhUXISIYFxMaERsXFQ8uKhUqFSoVFSkeGRkZGRkeGh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh8ZKioqKioZGRkrLCcgIBYcGRUVIB8TGQgPJQ4ZDw8ZDxwXHhUeFRwXHBccFyEXIRchFx4XHhcMDAwMDAwMChUKHBUVFwoeFx4XIRchFx4OHBQaDB4XHhceFx4XKh0cFQocFyolIRoMKh0qHSodHBUJDhcZIyMjIw4ODg4cISMQISMgChwcHBwaHgwcHCMeGyEeHBoaHBsjHwwcGBMXChcYFRMXFwoVFRgXExcYFBcWHiEKFxcXIRwkFx4cDAwVLCokGBseHBwcFxwcJxkeHhgcIx4hHhweGhsgGx8cJychJRweKh4XGBYPGRccExcXEhkdFxcXFxUTFSMWGBYiIxoeFhUgFxcXDxUUCgwKJiIXEhUXFREqLR0ODg4ODg4ODg4ODg4OEA4KDg4MGBcRFRkKEBkZChUTExkZCg8YFhgXExQXFR0bFBQUChIlChUVExMWHR0dHRgYGBcRFRkMERkMFRMTGQ8YGBcUFxUdGwoXExcYAAAAAA0NDxEJCAoICAkICQcWFhYWFhYWFhYWFg0WICAMIBYWFiAgICAgCSAgICAgICAgGyAgIB4eCgogICAgGBYWFhUVIicRFiInERYbGSAgICAgICAgICAgICAgICAKCQkJCSAgAAAAAAAAAAAAAAAAAAAAABcqICAgICAgICAgICAgICAgICAgICAgICAgICAgIA0NIBoRCQoJChISCQobGQoKCQoeHgoKDBAeHgoKHh4KChgWFhYYFhYWGBYWFg4ODg4VFRUVIyMWFiMjFhYuLiQkLi4kJBgYGBgYGBgYFxMWERcTFhEhIQsLGBgLCxkZEREVFQkJDg4RERYWCgoMEBMREhIbGRsZCgoXGRcZFxkXGSAgCAggICAHCSAgCQogICAJCQkJCQogCAggICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICANDQ0gICAgICAgICAgICAgICAgICAgICAgICAFKlQkHCQcAAAAAAAAAAAAAAAAAAAAABYjIwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAKHBccFxwXHBccFxwXHBccFxwXHBccFxwXHBccFxwXHBccFxwXHBccFwwKDAohFyEXIRchFyEXIRchFyQcJBwkHCQcJBweFx4XJBwkHCQcJBwkHBwVHBUcFRwXDAohFx4XHhceFx4XHhcAAAAAFw8nHBgSGBIeFxcVFxUcFRwWHBcgFyEXHgoLCxgKCgoKCgoLCQkODgkJCQAJCgkKCQoJChISEhIbGR4eCgoeHgoKHh4KCh4eCgoeHgoKHh4KCh4eCgoYFhYWGBYWFhgWFhYYFhYWGBYWFhgWFhYODg4ODg4ODg4ODg4ODg4ODg4VFRUVFRUVFRUVFRUVFRUVIyMWFiMjFhYjIxYWLi4kJC4uJCQYGBcTFhEhISELCyEhCwshIQsLISELCyEhCwsYGBgYIicRFiInERYZGRERGRkRERkZEREiJxEWIicRFiInERYiJxEWIicRFhUVCQkVFQkJFRUJCRUVCQkWFgoKFhYWFhYWCgoWFhgWFhYMEBAQEBISEhISEhISEhISEhISEhIbGRsZCgoSEhsZCgobGSIiIiIJAAAAAAAAAC8uAAAAAAAACBAAABkAAAAjIxYWLi4kJBcTFhERDgwKDQoKCgoKIicKAA4UFxkXGRcZFxkXGRcZFxkWFhcZLlwjAA0NDhAaGikfCQ8PEhsNDw0NGhoaGhoaGhoaGg0NGxsbGi8fHyEhHxwkIQwXHxolISQfJCEfHCEfLh8eHA0NDRUaDxoaFxoaDhoaCgoXCiYaGhoaDxcNGhchFxcXDwsPGx8fIR8hJCEaGhoaGhoXGhoaGgwMDAwaGhoaGhoaGhoaGhIaGhoQGRwiIi4PDxkuJCEZGRkaGxchJhkPEREjKRwcDhsZGhkcGhouHx8kLisaLg8PCgoZFxceCBoPDxcXGg0KDy4fHx8fHwwMDAwkJCQhISEMDw0PDw8PDw8PDxoKHxccFwshGh4XHxobGw8PDyYmJhokGgwfFyEXIRcaGQ8fGh8aIRwhHxofGhoKGg0aDyEaIRokGiEPIQ8fFxwNHBEhGiEaHBccFxkkJRsaFRwSHhkXETIuFy4XLhcXLSEbHBscHCEdISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhISEhIhwuLi4uLhwcHC8wKiMjGB4bFxcjIhQcCRApDxwQEBwQHxohFyEXHxofGh8aJBokGiQaIRohGgwMDAwMDAwKFwofFxcaCiEaIRokGiQaIQ8fFxwNIRohGiEaIRouIR4XCh8aLikkHAwuIS4hLiEeFwoPGhwmJiYmDw8PDx8kJxIkJiMKHx8fHxwhDB8fJSEeJCEfHBweHyYiDB4bFRoKGRoXFBoaChcXGxcVGhoWGRghJAoZGhkkHygZIR8MDBcxLicbHSEfHh8ZHx8qHCEhGx4lISQhHyEcHSMfIh8qKyQpHiEuIRoaGBEbGh8VGhoUGyAZGhkaFxUXJhcaGCUmHSEYFyMZGhoRFxcKDAoqJRoUFxkWEy4xIA8PDw8PDw8PDw8PDw8SDwoPDw0aGRIXHAoSHBsKFxUVHBwKEBoYGhkVFhkXIB4VFRULEyUKFxcVFRkgICAgGhoaGRIXHA0TGw0XFRUcEBoaGRYZFyAeChkVGRoAAAAADw8QEwoJCwkJCgkKCBgYGBgYGBgYGBgYDxgjIw0jGBgYIyMjIyMKIyMjIyMjIyMdIyMjISELCyMjIyMaGBgYFhYlKxIYJSsSGB0bIyMjIyMjIyMjIyMjIyMjIwoKCgoKIyMAAAAAAAAAAAAAAAAAAAAAGi4jIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjDw8jHBMKCwoLFBQKCx0bCwsKCyEhCwsNESEhCwshIQsLGhgYGBoYGBgaGBgYEBAQEBYWFhYmJhgYJiYYGDMzJyczMycnGxsbGxsbGxsZFRgSGRUYEiQkDAwbGwwMHBwSEhcXCgoQEBISGBgLCw0RFRIUFB0bHRsLCxkcGRwZHBkcIyMJCSMjIwgKIyMKCyMjIwoKCgoKCiMJCSMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIw8PDyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIwYuXCceJx8AAAAAAAAAAAAAAAAAAAAAGCYmAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAofGh8aHxofGh8aHxofGh8aHxofGh8aHxofGh8aHxofGh8aHxofGh8aDAoMCiQaJBokGiQaJBokGiQaJx4nHiceJx4nHiEaIRonHycfJx8nHycfHhceFx4XHxoMCiQaIRohGiEaIRohGgAAAAAZESofGxQbFCEZGhcaFx8XHxgfGiMaJBohCwwMGwsLCwsLCwwKCg8PCgoKAAoLCgsKCwoLFBQUFB0bISELCyEhCwshIQsLISELCyEhCwshIQsLISELCxoYGBgaGBgYGhgYGBoYGBgaGBgYGhgYGBAQEBAQEBAQEBAQEBAQEBAQEBYWFhYWFhYWFhYWFhYWFhYmJhgYJiYYGCYmGBgzMycnMzMnJxsbGRUYEiQkJAwMJCQMDCQkDAwkJAwMJCQMDBsbGxslKxIYJSsSGBwcEhIcHBISHBwSEiUrEhglKxIYJSsSGCUrEhglKxIYFxcKChcXCgoXFwoKFxcKChgYCwsYGBgYGBgLCxgYGhgYGA0REhISFBQUFBQUFBQUFBQUFBQUFB0bHRsLCxQUHRsLCx0bJSUlJQoAAAAAAAAANDIAAAAAAAAJEQAAHAAAACYmGBgzMycnGRUYEhMQDQsPCwsLCwslKwoAEBUZHBkcGRwZHBkcGRwZHBgYGRwyZCYADg4QEhwcLCEKERETHQ4RDg4cHBwcHBwcHBwcDg4dHR0cMyEhJCQhHyckDhkhHCkkJyEnJCEfJCEyISEfDg4OFhwRHBwZHBwOHBwMChkMKBwcHBwRGQ4cGSMYGRkRDBEdISEkISQnJBwcHBwcHBkcHBwcDg4ODhwcHBwcHBwcHBwcFBwcHBIbHyUlMhERGzInJBsbGxwdGSQpGxATEiYsHx8QHRscGx4cHDIhIScyLxwyERELCxsZGSEIHBERGRkcDgsRMiEhISEhDg4ODicnJyQkJA4RDxERERERERERHAshGR8ZDCQcIRkhHB0dERERKioqHCccDiEZJBkkGRwcESEcIRwkHyQhHCEcHAwcDxwRJBwkHCccJBEkESEZHw0fEyQcJBwfGR8ZHCcoHRwWHxQgHBkSNjIZMhkyGRkxJB0eHR4eIx8jIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMkHjIxMTExHh4eMzUuJiYbIR4aGSYlFh4JEiwQHhISHhIhHCQZJBkhHCEcIRwnHCccJxwkHCQcDg4ODg4ODgwZCiEZGRwMJBwkHCccJxwkESEZHw4kHCQcJBwkHDIjIRkMIRwyLCcfDDIjMiMyIyEZCxEcHioqKioRERERIScqEycqJgohISEhHyQOISEpJCEnJCEfHyEhKiUOIR0WHAobHRkWHBwKGRkdGRYcHBgbGiQnChscGychKxskIQ4OGTUzKx0gJCEhIRsiIS4eJCQdISkkJyQhJB8gJiElIS4vKCwhJDMkHB0bEh0cIRccHBYdIhwcGxwZFxkpGB0aKCkfJBoaJhscHBIaGQwOCi0pHBYZHBgVMjYjERERERERERERERERERMRDRERDhwbFBkeDRMeHg0ZFxceHg0SHRocGxcYGxkjIBoaGgwVKg0ZGRcXGyMjIyMcHBwbFBkeDhUeDhkXFx4SHRwbGBsZIyANGxcbHQAAAAAQEBIVCgoMCgoKCgsIGhoaGhoaGhoaGhoQGiYmDiYaGhomJiYmJgsmJiYmJiYmJiAmJiYkJAwMJiYmJhwaGhoYGCkvFBopLxQaIB0mJiYmJiYmJiYmJiYmJiYmCwsLCwsmJgAAAAAAAAAAAAAAAAAAAAAcMiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYQECYfFQoLCgsWFgoLIB0MDAoLJCQMDA4TJCQMDCQkDAwcGhoaHBoaGhwaGhoRERERGBgYGCkpGxspKRsbNzcqKjc3KiodHR0dHR0dHRsXGhQbFxoUJycNDR0dDQ0eHhQUGRkKChERFBQaGgwMDhMXFBYWIB0gHQwMGx4bHhseGx4mJgoKJiYmCAsmJgoMJiYmCwsLCwsLJgoKJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmEBAQJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmBjJkKyErIQAAAAAAAAAAAAAAAAAAAAAaKioAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADCEcIRwhHCEcIRwhHCEcIRwhHCEcIRwhHCEcIRwhHCEcIRwhHCEcIRwODA4MJxwnHCccJxwnHCccJxwrISshKyErISshJBwkHCshKyErISshKyEhGSEZIRkhHA4MJxwkHCQcJBwkHCQcAAAAABsSLiEdFh0WJBwcGRwZIRkhGiEcJhwnHCQMDQ0dDAwMDAwMDQoKEREKCgoACgsKCwoLCgsWFhYWIB0kJAwMJCQMDCQkDAwkJAwMJCQMDCQkDAwkJAwMHBoaGhwaGhocGhoaHBoaGhwaGhocGhoaERERERERERERERERERERERERGBgYGBgYGBgYGBgYGBgYGCkpGxspKRsbKSkbGzc3Kio3NyoqHR0bFxoUJycnDQ0nJw0NJycNDScnDQ0nJw0NHR0dHSkvFBopLxQaHh4UFB4eFBQeHhQUKS8UGikvFBopLxQaKS8UGikvFBoZGQoKGRkKChkZCgoZGQoKGhoMDBoaGhoaGgwMGhocGhoaDhMTExMWFhYWFhYWFhYWFhYWFhYWIB0gHQwMFhYgHQwMIB0pKSkpCgAAAAAAAAA4NgAAAAAAAAoTAAAeAAAAKSkbGzc3KiobFxoUFREODBAMDAwMDCkvDQARGhseGx4bHhseGx4bHhseGhobHjZsKQAPDxETHh4wJAoSEhUgDxIPDx4eHh4eHh4eHh4PDyAgIB43JCQnJyQhKicPGyQeLScqJConJCEnJDYjIyEPDw8YHhIeHhseHg8eHQ0NGw0tHR4eHhIbDx0bJxobGhIOEiAkJCckJyonHh4eHh4eGx4eHh4PDw8PHR4eHh4eHR0dHR4WHh4eEx0hKCg2EhIeNionHh4eHh8bJyweEBQUKTAhIREgHR4eIh4eNiQkKjYzHjYSEgwMHhsbIwkeEhIbGx4PDBI1JCQkJCQPDw8PKioqJycnDxIQEhISEhISEhIeDCQbIRoOJx4jGyQeICASEhItLS0eKh4PJBsnGycbHh4SJB4kHichJyQeJB4eDR4QHhInHScdKh4nEicSJBshDyEUJx0nHSEaIRoeKisfHhghFSMeGxQ7Nhs2GzYbGzUnICEgISEmIiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJichNjU1NTUhISE3OTEpKR0jIBwbKSgYIQoTMBEhExMhEyQeJxsnGyQeJB4kHioeKh4qHicdJx0PDw8PDw8PDRsNJBsbHg0nHSceKh4qHicSJBshDycdJx0nHScdNicjGw0kHjYwKiEPNic2JzYnIxsMEh4gLS0tLRISEhIkKi0VKiwpDSQkJCQhJw8kJC0nIyonJCEhIyMtKA8jHxgeDR4fGxgeHg0bGx8bGB4fGh4cJyoNHh4eKiQvHSckDw8bOTctHyInJCMkHSUkMiEnJx8jLScqJyQnISIpIygkMTMrMCMnNyceHx0UIB4kGR0dGB8lHR4cHhsZGywaHxwrLCInHBwpHR4dFBwbDQ8NMSwdGBsdGhY2OiUSEhISEhISEhISEhISFRINEhIPHh0WGyENFSAgDRwZGSAgDRMfHR8eGRoeHCYjGhoaDRcqDRwcGRkdJiYmJh4eHh0WGyEPFiAPHBkZIBMfHx4aHhwmIw0dGR4fAAAAABERExYLCw0LCwsLCwkcHBwcHBwcHBwcHBEcKSkPKRwcHCkpKSkpCykpKSkpKSkpIikpKScnDQ0pKSkpHhwdHRoaLDIVHCwyFRwiICkpKSkpKSkpKSkpKSkpKSkMCwsLCykpAAAAAAAAAAAAAAAAAAAAAB42KSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKRERKSEWCwwLDBcXCwwiIA0NCwwnJw0NDxQnJw0NJycNDR4cHR0eHB0dHhwdHRISEhIaGhoaLCwdHSwsHR07Oy4uOzsuLh8fHx8fHx8fHRgcFR0YHBUrKw4OHx8ODiAgFRUbGwsLEhIVFRwcDQ0PFBgVFxciICIgDQ0dIB0gHSAdICkpCwspKSkJCykpCw0pKSkLCwsLCwwpCwspKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkREREpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkHNmwuIy4kAAAAAAAAAAAAAAAAAAAAABwtLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANJB4kHiQeJB4kHiQeJB4kHiQeJB4kHiQeJB4kHiQeJB4kHiQeJB4kHg8NDw0qHioeKh4qHioeKh4qHi4jLiMuIy4jLiMnHScdLiQuJC4kLiQuJCMbIxsjGyQeDw0qHicdJx0nHScdJx0AAAAAHRQyJB8YHxgnHh4bHhskGyQcJB0pHioeJw0ODh8NDQ0NDQ0PCwsSEgsLCwALDAsMCwwLDBcXFxciICcnDQ0nJw0NJycNDScnDQ0nJw0NJycNDScnDQ0eHB0dHhwdHR4cHR0eHB0dHhwdHR4cHR0SEhISEhISEhISEhISEhISEhIaGhoaGhoaGhoaGhoaGhoaLCwdHSwsHR0sLB0dOzsuLjs7Li4fHx0YHBUrKysODisrDg4rKw4OKysODisrDg4fHx8fLDIVHCwyFRwgIBUVICAVFSAgFRUsMhUcLDIVHCwyFRwsMhUcLDIVHBsbCwsbGwsLGxsLCxsbCwscHA0NHBwcHBwcDQ0cHB4cHR0PFBUVFRcXFxcXFxcXFxcXFxcXFxciICIgDQ0XFyIgDQ0iICwsLCwLAAAAAAAAAD07AAAAAAAAChQAACAAAAAsLB0dOzsuLh0YHBUWEg8NEQ0NDQ0NLDINABIaHSAdIB0gHSAdIB0gHSAcHB0gOnQsABAQExUgIDQnCxMTFyIQExAQICAgICAgICAgIBAQIiIiIDsnJyoqJyMtKg8dJyAvKi0nLSonJConOiUmIxAQEBggEyAgHSAgECAgDQ0eDTEgICAgEx0QIB0pHBscEw4TIicnKicqLSogICAgICAdICAgIA8PDw8gICAgICAgICAgIBcgICAUHyMrKzoTEyA6LSkgICAgIR0pMCARFRUtNCMjEyIgICAkICA6JyctOjcgOhMTDQ0gHRsmCiATEx0dIBANEzknJycnJw8PDw8tLS0qKioPExETExMTExMTEyANJx0jHA4qICYbJyAiIhMTEzAwMCAtIA8nHSodKh0gIBMnICcgKiQqJyAnICANIBEgEyogKiAtICoTKhMnHSQQJBYqICogIxwjHCAtLiIgGiQXJiAdFT86HTodOh0dOSoiIyIjIykkKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKiM6OTk5OSMjIzs9NSwsHyYiHh0sKxojCxUzEyMVFSMVJyAqHSodJyAnICcgLSAtIC0gKiAqIA8PDw8PDw8NHQ0nHh0gDSogKiAtIC0gKhMnHSMQKiAqICogKiA6KSYbDScgOjQtIw86KTopOikmGw0TICMwMDAwExMTEyctMRYtMCwNJycnJyMqDycnLyomLSonJCQmJTArDyYiGiANICEdGiAgDR0dIR0aICEcIB4pLQ0gICAtJzIfKicPDx09OzIiJSonJicfJyc2IyoqIiYvKi0qJyokJSwlKyc1Ni4zJio7KiAhHxUiICcbICAZIiggIB8gHRsbMBwhHi8wJCoeHiwfICAVHh0NDw01LyAZGyAcGDo+KBMTExMTExMTExMTExMWEw4TExAhHxcdIw4WIyIOHhsbIyMOFCEfISAbHCAeKCUdHR0OGC4OHh4bGx8oKCgoISEhHxcdIxEYIhEeGxsjFCEhIBwgHiglDh8bICEAAAAAExMVGAwMDgwMDAwMCh8fHx8fHx8fHx8fEx8sLBAsHx8fLCwsLCwMLCwsLCwsLCwlLCwsKSkODiwsLCwhHx8fHBwvNhceLzYXHiUiLCwsLCwsLCwsLCwsLCwsLA0MDAwMLCwAAAAAAAAAAAAAAAAAAAAAIDosLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsExMsJBgMDQwNGRkMDSUiDg4MDSkpDg4QFikpDg4pKQ4OIR8fHyEfHx8hHx8fFBQUFBwcHBwwMB8fMDAfH0BAMTFAQDExIiIiIiIiIiIgGh8XIBofFy4uEA8iIhAPIyMXFx0dDAwUFBcXHx8ODhAWGhcZGSUiJSIODiAjICMgIyAjLCwMDCwsLAoMLCwMDiwsLAwMDAwMDSwMDCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLBMTEywsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLAc6dDImMicAAAAAAAAAAAAAAAAAAAAAHjAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA0nICcgJyAnICcgJyAnICcgJyAnICcgJyAnICcgJyAnICcgJyAnICcgDw0PDS0gLSAtIC0gLSAtIC0gMiYyJjImMiYyJiogKiAyJzInMicyJzInJhsmGyYbJyAPDS0gKiAqICogKiAqIAAAAAAfFTYnIhkiGSogIB0gHScdJx4nICwgLSApDhAPIg4ODg4ODhAMDBMTDAwMAAwNDA0MDQwNGRkZGSUiKSkODikpDg4pKQ4OKSkODikpDg4pKQ4OKSkODiEfHx8hHx8fIR8fHyEfHx8hHx8fIR8fHxQUFBQUFBQUFBQUFBQUFBQUFBwcHBwcHBwcHBwcHBwcHBwwMB8fMDAfHzAwHx9AQDExQEAxMSIiIBofFy4uLhAPLi4QDy4uEA8uLhAPLi4QDyIiIiIvNhceLzYXHiMjFxcjIxcXIyMXFy82Fx4vNhceLzYXHi82Fx4vNhceHR0MDB0dDAwdHQwMHR0MDB8fDg4fHx8fHx8ODh8fIR8fHxAWFhYWGRkZGRkZGRkZGRkZGRkZGSUiJSIODhkZJSIODiUiLy8vLwwAAAAAAAAAQT8AAAAAAAALFQAAIwAAADAwHx9AQDExIBofFxgUEA4TDg4ODg4vNg4AFB0gIyAjICMgIyAjICMgIx8fICNDhjIAExMWGCUlPC0NFhYaJxMWExMlJSUlJSUlJSUlExMnJyclRC0tMDAtKTQwEyItJTcwNC00MC0pMC1CKy0pExMTHiUWJSUiJSUTJSUPDyIPOSUlJSUWIhMlIS8gISEWERYnLS0wLTA0MCUlJSUlJSIlJSUlEhISEiUlJSUlJSUlJSUlGyUlJRckKTExQxYWJUM0MCUlJSUnITA3JRQZGDM8KSkWJyUlJSklJUMtLTRDPyVDFhYPDyUhIS0LJRYWIiIlEw8WQy0tLS0tExMTEzQ0NDAwMBIWFBYWFhYWFhYWJQ8tIikhETAlLSEtJScnFhYWODg4JTQlEy0iMCIwIiUlFi0lLSUwKTAtJS0lJQ8lFCUWMCUwJTQlMBYwFi0iKRMpGTAlMCUpISkhJTQ1JyUeKRorJSIYSUMiQyJDIiJCMCcoJygoLyovLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8xKENCQkJCKCgoREc9MjIkLCgiIjIxHigNGDsWKBgYKBgtJTAiMCItJS0lLSU0JTQlNCUwJTAlExITEhMSEw8iDy0iIiUPMCUwJTQlNCUwFi0iKRMwJTAlMCUwJUIvLSEPLSVDPDQpEkIvQi9CLy0hDxYlKDg4ODgWFhYWLTU4GjQ4Mg8tLS0tKTATLS03MCw0MC0pKS0rODITLSceJQ8lJyIeJSUPIiInIR4lJiAlIzA0DyUlJTQtOiQwLRMTIkdEOScrMC0sLSQtLT4oMDAnLDcwNDAtMCkrMysyLT0/NTssMEQwJSYkGCclLR8lJR0nLiUlJCUiHyE3ICYjNjcqMCMiMiQlJRgiIg8SDz02JR0hJSEcQ0guFhYWFhYWFhYWFhYWFhoWERYWEyYkGyIoERooKBEiHx8oKBEYJiMmJR8gJSIvKyIiIhAcNxEiIh8fJC8vLy8mJiYkGyIoExwoEyIfHygYJiYlICUiLysRJB8lJwAAAAAVFRgcDg0QDQ0ODQ4LIyMjIyMjIyMjIyMVIzIyEzIjIyMyMjIyMg4yMjIyMjIyMisyMjIwMBAQMjIyMiYjJCQhITY+GiI2PhoiKycyMjIyMjIyMjIyMjIyMjIyDw4ODg4yMgAAAAAAAAAAAAAAAAAAAAAlQzIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIVFTIpHA4PDg8dHQ4PKycQEA4PMDAQEBMZMDAQEDAwEBAmIyQkJiMkJCYjJCQXFxcXISEhITc3JCQ3NyQkSko5OUpKOTknJycnJycnJyQeIxokHiMaNTUSEicnEhIoKBoaIiIODhcXGhojIxAQExkeGh0dKycrJxAQJCgkKCQoJCgyMg0NMjIyCw4yMg4QMjIyDg4ODg4PMg0NMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyFRUVMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyCEOGOSw5LQAAAAAAAAAAAAAAAAAAAAAiODgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADy0lLSUtJS0lLSUtJS0lLSUtJS0lLSUtJS0lLSUtJS0lLSUtJS0lLSUTDxMPNCU0JTQlNCU0JTQlNCU5LDksOSw5LDksMCUwJTktOS05LTktOS0tIS0hLSEtJRMPNCUwJTAlMCUwJTAlAAAAACQYPi0nHScdMCUlIiUiLSItIy0lMiU0JTAQEhInEBAQEBAQEg4OFhYODg4ADg8ODw4PDg8dHR0dKycwMBAQMDAQEDAwEBAwMBAQMDAQEDAwEBAwMBAQJiMkJCYjJCQmIyQkJiMkJCYjJCQmIyQkFxcXFxcXFxcXFxcXFxcXFxcXISEhISEhISEhISEhISEhITc3JCQ3NyQkNzckJEpKOTlKSjk5JyckHiMaNTU1EhI1NRISNTUSEjU1EhI1NRISJycnJzY+GiI2PhoiKCgaGigoGhooKBoaNj4aIjY+GiI2PhoiNj4aIjY+GiIiIg4OIiIODiIiDg4iIg4OIyMQECMjIyMjIxAQIyMmIyQkExkaGhodHR0dHR0dHR0dHR0dHR0dKycrJxAQHR0rJxAQKyc2NjY2DgAAAAAAAABLSQAAAAAAAA0ZAAAoAAAANzckJEpKOTkkHiMaHBcTEBUQEBAQEDY+EQAXIiQoJCgkKCQoJCgkKCQoIyMkKEuWOAAVFRcbKipDMg4ZGR0sFRkVFSoqKioqKioqKioVFSwsLCpMMjI2NjIuOjYVJjIqPTY6Mjo2Mi02MksxMS4VFRUiKhkqKiYqKhUqKhERJhE/KioqKhkmFSolNSQlJRkUGSwyMjYyNjo2KioqKioqJioqKioVFRUVKioqKioqKioqKioeKioqGiguNzdLGRkpSzo1KSkpKislNT4pFxwbOkMuLhcsKSopLioqSzIyOktHKksZGRERKSUlMQ0qGRkmJioVERlMMjIyMjIVFRUVOjo6NjY2FRkYGRkZGRkZGRkqETImLiUUNioxJTIqLCwZGRk/Pz8qOioVMiY2JjYmKikZMioyKjYuNjIqMioqESoWKhk2KjYqOio2GTYZMiYtFS0cNio2Ki4lLiUpOjwrKiEuHjEpJhtSSyZLJksmJkk2LC0sLS01LzU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTctS0pKSkotLS1NT0U4OCgxLSYmODchLQ4bQhgtGxstGzIqNiY2JjIqMioyKjoqOio6KjYqNioVFRUVFRUVESYRMiYmKhE2KjYqOio6KjYZMiYuFTYqNio2KjYqSzUxJREyKktDOi4VSzVLNUs1MSURGSotPz8/PxkZGRkyOz8dOj44ETIyMjIuNhUyMj02MTo2Mi4tMTE/OBUxKyEqESkrJiEqKhEmJislIiorJCknNTsRKSopOzJBKTYyFRUmT0xALDA2MjEyKTMyRS02NiwxPTY6NjI2LTA5MTcyRUY7QjE2TDYqKygbLCoyIioqISw0KSopKiYiJT4kKyc8Pi82JyY4KSoqGyYmERURRD0qISUpJR9LUDQZGRkZGRkZGRkZGRkZHRkTGRkVKikeJi0THS0sEyYjIy0tExorKCopIyQpJjQwJSUlEh88EyYmIyMoNDQ0NCoqKikeJi0WHywWJiMjLRorKikkKSY0MBMpIykrAAAAABgYGx8QDxIPDxAPEAwnJycnJycnJycnJxgnODgVOCcnJzg4ODg4EDg4ODg4ODg4MDg4ODY2EhI4ODg4KicoKCUlPUYeJz1GHicwLDg4ODg4ODg4ODg4ODg4ODgREBAQEDg4AAAAAAAAAAAAAAAAAAAAACpLODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4OBgYOC4fEBEQESAgEBEwLBISEBE2NhISFRw2NhISNjYSEionKCgqJygoKicoKBkZGRklJSUlPj4oKD4+KChSUj8/UlI/PywsLCwsLCwsKSInHikiJx47OxQULCwUFC0tHh4mJhAQGRkeHicnEhIVHCIeICAwLDAsEhIpLSktKS0pLTg4Dw84ODgMEDg4EBI4ODgQEBAQEBE4Dw84ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODgYGBg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODgJS5ZAMUAyAAAAAAAAAAAAAAAAAAAAACY/PwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAARMioyKjIqMioyKjIqMioyKjIqMioyKjIqMioyKjIqMioyKjIqMioyKhURFRE6KjoqOio6KjoqOio6KkAxQDFAMUAxQDE2KjYqQDJAMkAyQDJAMjElMSUxJTIqFRE6KjYqNio2KjYqNioAAAAAKRtFMiwhLCE2KSomKiYyJjInMio4KjoqNhIUFCwSEhISEhIUEBAZGRAQEAAQERAREBEQESAgICAwLDY2EhI2NhISNjYSEjY2EhI2NhISNjYSEjY2EhIqJygoKicoKConKCgqJygoKicoKConKCgZGRkZGRkZGRkZGRkZGRkZGRklJSUlJSUlJSUlJSUlJSUlPj4oKD4+KCg+PigoUlI/P1JSPz8sLCkiJx47OzsUFDs7FBQ7OxQUOzsUFDs7FBQsLCwsPUYeJz1GHictLR4eLS0eHi0tHh49Rh4nPUYeJz1GHic9Rh4nPUYeJyYmEBAmJhAQJiYQECYmEBAnJxISJycnJycnEhInJyonKCgVHB0dHSAgICAgICAgICAgICAgICAwLDAsEhIgIDAsEhIwLD09PT0QAAAAAAAAAFRRAAAAAAAADxwAAC0AAAA+PigoUlI/PykiJx4fGRUSGBISEhISPUYTABolKS0pLSktKS0pLSktKS0nJyktU6Y+ABcXGR0uLko3EBwcIDAXHBcXLi4uLi4uLi4uLhcXMDAwLlQ3Nzw8NzNBPBcqNy5FPEE3QTw3NDw3Uzg2MxcXFyYuHC4uKi4uGC4uEhMrEkcuLi4uHCoXLik7KicoHBQcMDc3PDc8QTwuLi4uLi4qLi4uLhcXFxcuLi4uLi4uLi4uLiEuLi4dLTM9PVMcHC5TQTsuLi4uMCk7RC4XHx5ASjMzGTAuLi4yLi5TNzdBU04uUxwcEhIuKSc2Di4cHCoqLhcSHFM3Nzc3NxcXFxdBQUE8PDwXHBgcHBwcHBwcHC4SNyozKBQ8LjYnNy4wMBwcHEVFRS5BLhc3KjwqPCouLhw3LjcuPDM8Ny43Li4SLhguHDwuPC5BLjwcPBw3KjQYNB88LjwuMygzKC5BQjAuJTMhNi4qHlpTKlMqUyoqUTwwMjAyMjs0Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7PTJTUlJSUjIyMlVXTD4+LDYxKio+PSUyEB1JGzIdHTIdNy48KjwqNy43LjcuQS5BLkEuPC48LhcXFxcXFxcSKhM3KyouEjwuPC5BLkEuPBw3KjMXPC48LjwuPC5TOzYnEjcuU0pBMxVTO1M7Uzs2JxIcLjJFRUVFHBwcHDdBRiBARD4SNzc3NzM8Fzc3RTw2QTw3MzQ2OEU+FzYwJS4SLTAqJS4uEioqMCklLi8oLSw7QRItLi1BN0gtPDcXFypYVEcwNTw3NjctODdNMjw8MDZFPEE8Nzw0NT84PTdMTkJJNjxUPC4wLB4wLjgmLi4kMDkuLi0uKiYnRCowK0NENDwrKj4tLi4eKioSFxNLQy4kJy4pIlNZORwcHBwcHBwcHBwcHBwgHBQcHBcvLSEqMhQgMjEUKiYmMjIUHTAsLy0mKC4qOjUpKSkUI0MUKiomJiw6Ojo6Ly8vLSEqMhgiMRgqJiYyHTAvLSguKjo1FC0mLTAAAAAAGhoeIhERFBERERESDiwsLCwsLCwsLCwsGiw+Phc+LCwsPj4+Pj4SPj4+Pj4+Pj41Pj4+OzsUFD4+Pj4vLCwsKSlDTSErQ00hKzUxPj4+Pj4+Pj4+Pj4+Pj4+PhMSEhISPj4AAAAAAAAAAAAAAAAAAAAALlM+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Gho+MyIRExETJCQREzUxFBQREzs7FBQXHzs7FBQ7OxQULywsLC8sLCwvLCwsHBwcHCkpKSlERCwsREQsLFtbRkZbW0ZGMDAwMDAwMDAtJSwhLSUsIUFBFhYwMBYWMjIhISoqEREcHCEhLCwUFBcfJSEkJDUxNTEUFC0yLTItMi0yPj4RET4+Pg4SPj4RFD4+PhISEhISEz4RET4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+PhoaGj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+Pj4+PgpTpkc2RzgAAAAAAAAAAAAAAAAAAAAAK0VFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABI3LjcuNy43LjcuNy43LjcuNy43LjcuNy43LjcuNy43LjcuNy43LjcuFxIXEkEuQS5BLkEuQS5BLkEuRzZHNkc2RzZHNjwuPC5HOEc4RzhHOEc4Nic2JzYnNy4XEkEuPC48LjwuPC48LgAAAAAtHk04MCQwJDwuLiouKjcqNys3Lj4uQS47FBYWMBQUFBQUFBYRERwcERERABETERMRExETJCQkJDUxOzsUFDs7FBQ7OxQUOzsUFDs7FBQ7OxQUOzsUFC8sLCwvLCwsLywsLC8sLCwvLCwsLywsLBwcHBwcHBwcHBwcHBwcHBwcHCkpKSkpKSkpKSkpKSkpKSlERCwsREQsLERELCxbW0ZGW1tGRjAwLSUsIUFBQRYWQUEWFkFBFhZBQRYWQUEWFjAwMDBDTSErQ00hKzIyISEyMiEhMjIhIUNNIStDTSErQ00hK0NNIStDTSErKioRESoqEREqKhERKioRESwsFBQsLCwsLCwUFCwsLywsLBcfICAgJCQkJCQkJCQkJCQkJCQkJDUxNTEUFCQkNTEUFDUxQ0NDQxEAAAAAAAAAXVoAAAAAAAAQHwAAMgAAAERELCxbW0ZGLSUsISIcFxQbFBQUFBRDTRQAHCktMi0yLTItMi0yLTItMiwsLTJcuEUAGhoaITMzUj0SHx8kNhofGhozMzMzMzMzMzMzGho2NjYzXT09QkI9OEhCGi49M0tCSD1IQj05Qj1bPj04GhoaKTMfMzMuMzMbNDMUFS8UTTMzMzQfLhozLUEtLS0fFx82PT1CPUJIQjMzMzMzMy4zMzMzGBgYGDMzMzMzMzMzMzMzJTMzMyAxOEREXB8fM1xIQjMzMzM1LUJMMxkiIkdSODgcNjIzMzkzM1w9PUhcVzNcHx8UFDMtLT0PMx8fLi4zGhQfXT09PT09GhoaGkhISEJCQhgfHB8fHx8fHx8fMxQ9LjgtF0IzPS09MzY2Hx8fTU1NM0g0Gj0uQi5CLjMzHz0zPTNCOUI9Mz0zMxQzGzMfQjNCM0gzQh9CHz0uORo5I0IzQjM4LTgtM0hJNTMpOSQ8My4iZFwuXC5cLi5aQjY4Njg4QTpBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFDOFxbW1tbODg4XmFURUUxPDcvLkVEKTgRIVEeOCEhOCE9M0IuQi49Mz0zPTNINEg0SDNCM0IzGhgaGBoYGhQuFT0vLjMUQjNDM0gzSDNCHz0uOBpCM0IzQjNCM1tBPS0UPTNcUkg4GFtBW0FbQT0tFB8zN01NTU0fHx8fPUhNI0dNRRQ9PT09OEIaPT1LQjxIQj05OT0+TUUaPTUpMxQyNS4pMzMULi41LSkzNCwyMEJIFDIzMkg9UDJCPRoaLmFdTzY6Qj08PTI+PVU4QkI2PEtCSEI9Qjk6Rj5EPVRWSVE8Ql1CMzUxIjYzPiozMyg2PzMzMjMuKi1MLTUwSkw6QjAvRTIzMyIvLhQYFVNLMygtMy0mXGM/Hx8fHx8fHx8fHx8fHyMfFB8fGjQyJS83FyM3NhcvKis3NxcgNTE0MiosMy9AOy4uLhYmTBcvLysrMUBAQEA0NDQyJS83GiY2Gi8qKzcgNTQyLDMvQDsXMioyNQAAAAAdHSEmExMWExMTExMPMDAwMDAwMDAwMDAdMEVFGkUwMDBFRUVFRRNFRUVFRUVFRTtFRUVCQhYWRUVFRTQwMTEtLUtWJC9LViQvOzZFRUVFRUVFRUVFRUVFRUVFFRMTExNFRQAAAAAAAAAAAAAAAAAAAAAzXEVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUUdHUU5JhMVExUoKBMVOzYWFhMVQkIWFhojQkIWFkJCFhY0MDExNDAxMTQwMTEfHx8fLS0tLUxMMTFMTDExZWVOTmVlTk42NjY2NjY2NjIpMCQyKTAkSUkZGDY2GRg3NyQkLy8TEx8fJCQwMBYWGiMpJCgoOzY7NhYWMjcyNzI3MjdFRRMTRUVFDxNFRRMWRUVFExMTExMVRRMTRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFHR0dRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFDFy4TzxPPgAAAAAAAAAAAAAAAAAAAAAvTU0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFD0zPTM9Mz0zPTM9Mz0zPTM9Mz0zPTM9Mz0zPTM9Mz0zPTM9Mz0zPTMaFBoUSDNIM0gzSDNIM0gzSDNPPE88TzxPPE88QjNCM08+Tz5PPk8+Tz49LT0tPS09MxoUSDNCM0IzQjNCM0IzAAAAADIiVT42KDYoQjMzLjMuPS49MD0zRTNIM0IWGRg2FhYWFhYWGRMTHx8TExMAExUTFRMVExUoKCgoOzZCQhYWQkIWFkJCFhZCQhYWQkIWFkJCFhZCQhYWNDAxMTQwMTE0MDExNDAxMTQwMTE0MDExHx8fHx8fHx8fHx8fHx8fHx8fLS0tLS0tLS0tLS0tLS0tLUxMMTFMTDExTEwxMWVlTk5lZU5ONjYyKTAkSUlJGRhJSRkYSUkZGElJGRhJSRkYNjY2NktWJC9LViQvNzckJDc3JCQ3NyQkS1YkL0tWJC9LViQvS1YkL0tWJC8vLxMTLy8TEy8vExMvLxMTMDAWFjAwMDAwMBYWMDA0MDExGiMkJCQoKCgoKCgoKCgoKCgoKCgoOzY7NhYWKCg7NhYWOzZLS0tLEwAAAAAAAABnZAAAAAAAABIiAAA3AAAATEwxMWVlTk4yKTAkJh8aFh0WFhYWFktWFwAfLjI3MjcyNzI3MjcyNzI3MDAyN2TISwAcHBwkODhZQxMhISc6HCEcHDg4ODg4ODg4ODgcHDo6OjhmQ0NISEM9TkgcMkM4U0hOQ05IQz5IQ2NCQj0cHBwrOCE4ODI4OB03OBYWMhZUODg4NyEyHDgxRzExMSEaITpDQ0hDSE5IODg4ODg4Mjg4ODgbGxsbODg4ODg4ODg4ODgoODg4IzY9SkpkISE3ZE5HNzc3ODoxR1I3HSUlTVk9PR86Nzg3PTg4ZENDTmReOGQhIRYWNzExQhE4ISEyMjgcFiFkQ0NDQ0McHBwcTk5OSEhIGyEfISEhISEhISE4FkMyPTEaSDhCMUM4OjohISFTU1M4TjccQzJIMkgyODchQzhDOEg9SEM4Qzg4FjgdOCFIOEg4TjhIIUghQzI+HT4mSDhIOD0xPTE3TlA6OC0+KEE3MiRtZDJkMmQyMmJIOjw6PDxHP0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0dHR0k8ZGNjY2M8PDxmaVxLSzVCOzMyS0ksPBMjWSA8IyM8I0M4SDJIMkM4QzhDOE43TjdOOEg4SDgcGxwbHBscFjIWQzIyOBZIOEg4TjhOOEghQzI9HEg4SDhIOEg4Y0dCMRZDOGRZTj0bY0djR2NHQjEWITg8U1NTUyEhISFDTlQmTVNLF0NDQ0M9SBxDQ1NIQU5IQz4+QkJUSxxCOi04Fzc6Miw4OBcyMjoxLTg5MDc0R04XNzg3TkNWNkhDHBwyamVVOkBIQ0JDNkRDXDxISDpCU0hOSENIPkBMQkpDXF5PWUJIZUg4OTUkOjhDLjg4LDpFNzg2ODIuMVIxOTRQUj9INDNLNjg4JDMyFhsWW1E4LDE3MSlka0UhISEhISEhISEhISEhJiEbISEcODYoMzwYJjw7GDMuLjw8GCM5NTk3LjA3M0VAMTExGCpPGDMzLi42RUVFRTg4ODYoMzwdKTsdMy4uPCM5OTcwNzNFQBg2Ljc6AAAAACAgJCkVFBgUFBUUFRA1NTU1NTU1NTU1NSA1S0scSzU1NUtLS0tLFUtLS0tLS0tLQEtLS0dHGBhLS0tLODU1NTExUV0nM1FdJzNAO0tLS0tLS0tLS0tLS0tLS0sXFRUVFUtLAAAAAAAAAAAAAAAAAAAAADhkS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSyAgSz4pFRcVFysrFRdAOxgYFRdHRxgYHCZHRxgYR0cYGDg1NTU4NTU1ODU1NSIiIiIxMTExUlI1NVJSNTVublVVbm5VVTo6Ojo6Ojo6Ni01JzYtNSdPTxsaOjobGjw8JyczMxUVIiInJzU1GBgcJi0nKytAO0A7GBg2PDY8Njw2PEtLFBRLS0sQFUtLFRhLS0sVFRUVFRdLFBRLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0sgICBLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0sNZMhWQlVDAAAAAAAAAAAAAAAAAAAAADNTUwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWQzhDOEM4QzhDOEM4QzhDOEM4QzhDOEM4QzhDOEM4QzhDOEM4QzhDOBwWHBZOOE44TjhOOE44TjhOOFZCVkJWQlZCVkJIOEg4VUNVQ1VDVUNVQ0IxQjFCMUM4HBZOOEg4SDhIOEg4SDgAAAAANiRcQzosOixINzgyODJDMkM0QzhLOE44RxgbGjoYGBgYGBgbFRUhIRUVFQAVFxUXFRcVFysrKytAO0dHGBhHRxgYR0cYGEdHGBhHRxgYR0cYGEdHGBg4NTU1ODU1NTg1NTU4NTU1ODU1NTg1NTUiIiIiIiIiIiIiIiIiIiIiIiIxMTExMTExMTExMTExMTExUlI1NVJSNTVSUjU1bm5VVW5uVVU6OjYtNSdPT08bGk9PGxpPTxsaT08bGk9PGxo6Ojo6UV0nM1FdJzM8PCcnPDwnJzw8JydRXSczUV0nM1FdJzNRXSczUV0nMzMzFRUzMxUVMzMVFTMzFRU1NRgYNTU1NTU1GBg1NTg1NTUcJicnJysrKysrKysrKysrKysrKytAO0A7GBgrK0A7GBhAO1FRUVEVAAAAAAAAAHBsAAAAAAAAEyUAADwAAABSUjU1bm5VVTYtNScpIhwYIBgYGBgYUV0YACIxNjw2PDY8Njw2PDY8Njw1NTY8AAAAAwAAAAMAAAAcAAEAAAAAC0AAAwABAAAMRgAECyQAAAEcAQAABwAcAH4BfwGPAZIBoQGwAdwB/wJZAscCyQLdAwEDAwMJAyMDfgOKA4wDoQPOBAwETwRcBF8EkwSXBJ0EowSzBLsE2QTpBcMF6gX0BgwGGwYfBjoGVQbtBv4ehR75IA8gFSAeICIgJiAuIDAgMyA6IDwgPiBEIG8gfyCkIKcgrCEFIRMhFiEiISYhLiFUIV4hlSGoIgIiBiIPIhIiFSIaIh8iKSIrIkgiYSJlIwIjECMhJQAlAiUMJRAlFCUYJRwlJCUsJTQlPCVsJYAlhCWIJYwlkyWhJawlsiW6JbwlxCXLJc8l2SXmJjwmQCZCJmAmYyZmJmvoBegY6DrwAvAx+wL7IPs2+zz7PvtB+0T7sfvn+//8Yv0//fL+/P/8//8AAAAgAKABjwGSAaABrwHNAfoCWQLGAskC2AMAAwMDCQMjA34DhAOMA44DowQBBA4EUQReBJAElgSaBKIErgS4BNgE6AWwBdAF8AYMBhsGHwYhBkAGYAbwHoAeoCAMIBMgFyAgICYgKiAwIDIgOSA8ID4gRCBqIH8goyCnIKohBSETIRYhIiEmIS4hUyFbIZAhqCICIgYiDyIRIhUiGSIeIikiKyJIImAiZCMCIxAjICUAJQIlDCUQJRQlGCUcJSQlLCU0JTwlUCWAJYQliCWMJZAloCWqJbIluiW8JcQlyiXPJdgl5iY6JkAmQiZgJmMmZSZq6AHoGOg68AHwBPsB+x37Kvs4+z77QPtD+0b70/v8/F79Pv3y/oD//P///+MAAAOV/xQCygK9Ay//3ALMAAD+DwAAAZIBdwFrAXL8oAAA/mkAAAAA/iv+Kv4p/igAAAB8AHoAdgBsAGgATAA+AAD80PzL/OD80vzPAAAAAAAAAADjXQAA4twAAAAAAADghQAA4JXhW+CE4PnhqOB3AADgtwAA4JAAAOCK4H3hdd9q33nguuMs4I7fqN+W3pbeot6LAADepgAAAADfF95x3l8AAN4w3kDeM94k3EbcRdw83DncNtwz3DDcKdwi3BvcFNwB2+7b69vo2+Xb4gAAAADbxtu/277btwAA28Xbpduv20XbQttB2yTbItsh2x4awBr6GuEQvgAABb4AAAedB5wHmweaB5kAAAAAAAAG6QY+BY0FAANjAAEAAAEaAAAAAAAAAAAAAAAAAAACygAAAsoAAAAAAAAAAAAAAsoAAALUAvoAAAAAAAAAAANIAAAAAAAAAAAAAAAAAAADQAAAAAAAAAAAAAADXAOOA7gE0gAABOwAAAWcBaAFrgAABbAAAAAAAAAAAAAAAAAFrAAABbQAAAW0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFngAABZ4FoAAAAAAAAAWcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABXQFdgAAAAAAAAAABXIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABVgAAAWwAAAAAAAAAAAAAAWsBoIGqgAAAAAAAAAAAAAAAAADAKMAhACFA14AlgDmAIYAjgCLAJ0AqQCkABAAigEAAIMAkwDwAPEAjQCXAIgAwgDcAO8AngCqAPMA8gD0AKIArADIAMYArQBiAGMAkABkAMoAZQDHAMkAzgDLAMwAzQDnAGYA0QDPANAArgBnAO4AkQDUANIA0wBoAOkA6wCJAGoAaQBrAG0AbABuAKAAbwBxAHAAcgBzAHUAdAB2AHcA6AB4AHoAeQB7AH0AfAC3AKEAfwB+AIAAgQDqAOwAuQGWAZcBAgEDAQQBBQD7APwBmAGZAZoBmwD9AP4BBgEHAQgA/wGcAZ0BngGfAaABoQEJAQoBCwEMAaIBowD2APcBpAGlAaYBpwGoAakBqgGrAawBrQGuAa8BsAGxAbIBswD4ANUBigGLAbQBtQG2AbcBuAENAQ4BuQG6AQ8BEAERARIA4ADhARMBFAG7AbwBFQEWAYwBvQG+Ab8BwAHBAcIBFwEYAK8AsAEZARoBwwHEARsBHAEdAR4BxQHGAPkA+gDiAOMBHwEgASEBIgHHAcgByQHKAcsBzAHNAc4BIwEkASUBJgHPAdAB0QHSAdMB1AC6AScBKAEpASoA5ADlAdUA1gDfANkA2gDbAN4A1wDdAe8B8AHxAdwB8gHzAfQB9gH3AfgB+QH6ASsB+wH8Af0B/gEsAf8CAAIBAgICAwIEAgUCBgIHAggCCQIKAS0CCwIMAg0CDgIPAhACEQISAhMCFAEuAhUCFgEvATACFwIYAhkCGgIbAhwCHQIeAh8CIAKMAiECIgExATICIwEzAiQCJQImAicCKAIpAioCKwKIAokFEAURAo0CjgKPApACkQKSApMClAKVApYClgKXApgCmQKaApsCnAKdAp4CnwLvA4EDgwOFA4cDiQONA48DkwOVA5kDnQOhA6UDqQOrA60DrwOxA7UDuQO9A8EDxQPJA80C8APRA9UD2QPdA+ED5QPpA+0D7wPxAvEC8gLzAvQC9QL2AvcC+AU4BTkFOgL5AvoC+wL8Av0C/gL/AwADAQMCAwMDBALsAwUFKAUsBTsFPAU+BUAFOQVCBUQFRgVIBUoFTgVSBVYFWgMfBV4FYgVmBWoFbgVyBXYDJwV6BX4FgAWCBYQFhgWIBYoFjAWOBZAFkgWUBZYFmAWaBZwDKwWeBaAFpAWoBawFsAW0BbYFugW7Bb8FwwXHBcsFzwXRAy0F0wXXBdsF3wXjAzEF5wXrBe8F8wX3BfsF/wYDBgcGCwYPBhEGEwYXA+sGGQYdBh8GIAYhBiIGJAYmBigGKgYsBi4GMAM1BjIGNAY4BjoGPgZABkIGRAMIBkUGRgZHBkgGSQZKBksGTAZNBk4GTwZQBlEGUgZTBlQGVQZWBlcGWAZZBloGTgZbAvkC+gL7AvwDCgMLAwwDAAMBAwIGXAZgBmQGaAZpBKQEpQSmBKcEqASpBKoEqwSsBK0ErgSvBLAEsQSyBLMEtAS1BLYEtwS4BLkEugS7BLwEvQS+BL8EwATBBMIEwwTEBMUExgTHBMgEyQTKBMsEzATNBM4EzwTQBNEE0gTTBNQE1QTWBNcE2ATZBNoE2wTcBN0E3gTfBOAE4QTiBOME5ATlBOYE5wToBOkE6gTrBOwE7QTuBO8E8ATxBPIE8wT0BPUB4wHkBPYE9wT4BPkE+gT7ALEAsgKKATQAtQC2AMMB5QCzALQAxACCAMEAhwNOA08DUgNQA1EDVQNWA1cDWANTA1QA9QHnAsAEfgC8AJkA7QDCAKUAkgE/AI8BQQF2AZEBkgGTAXcAuAF8Ae0B7gRxBHIEgQRzA1kDWgNbA1wDXQSEBHUEdwSFBHYEhgR5BIcEiASJBIoEiwSMBHgElASNBI4EjwSQBJEElgSaBJsEnASdBJ4ElwSYBJkEfQSfBKAEoQSiBKMGdAZ1BncCxgLeAt8C4ALhAuIC4wLkAuUC5gLnBTwFPQVSBVMFVAVVAx8DIAMhAyIFYgVjBWQFZQVOBU8FUAVRBV4FXwVgBWEFSgVLBUwFTQXDBcQFxQXGBcsFzAXNBc4FcgVzBXQFdQVuBW8FcAVxAycDKAMpAyoFegV7BXwFfQWIBYkFhgWHBYoFiwV+BX8DKwMsBZAFkQMtAy4DLwMwAzEDMgMzAzQF8wX0BfUF9gXrBewF7QXuBg8GEAYRBhIFTAVNBh0GHgZqBh8GawZsA+sD6gPrA+wGQAZBBkIGQwXfBeAF4QXiBigGKQYmBicGKgYrBUYGMAYxBiQGJQYsBi0GOgY7BjwGPQM1AzYD8wP0AAABBgAAAQAAAAAAAAABAgAAAAIAAAAAAAAAAAAAAAAAAAABAAADBAUGBwgJCgsMDQ4PEBESExQVFhcYGRobHB0eH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NkA2gDbAN4A1wDdAe8B8AHxAdwB8gHzAfQB9gH3AfgB+QH6ASsB+wH8Af0B/gEsAf8CAAIBAgICAwIEAgUCBgIHAggCCQIKAS0CCwIMAg0CDgIPAhACEQISAhMCFAEuAhUCFgEvATACFwIYAhkCGgIbAhwCHQIeAh8CIAKMAiECIgExATICIwEzAiQCJQImAicCKAIpAioCKwKIAokFEAURAo0CjgKPApACkQKSApMClAKVApYClgKXApgCmQKaApsCnAKdAp4CnwLvA4EDgwOFA4cDiQONA48DkwOVA5kDnQOhA6UDqQOrA60DrwOxA7UDuQO9A8EDxQPJA80C8APRA9UD2QPdA+ED5QPpA+0D7wPxAvEC8gLzAvQC9QL2AvcC+AU4BTkFOgL5AvoC+wL8Av0C/gL/AwADAQMCAwMDBALsAwUFKAUsBTsFPAU+BUAFOQVCBUQFRgVIBUoFTgVSBVYFWgMfBV4FYgVmBWoFbgVyBXYDJwV6BX4FgAWCBYQFhgWIBYoFjAWOBZAFkgWUBZYFmAWaBZwDKwWeBaAFpAWoBawFsAW0BbYFugW7Bb8FwwXHBcsFzwXRAy0F0wXXBdsF3wXjAzEF5wXrBe8F8wX3BfsF/wYDBgcGCwYPBhEGEwYXA+sGGQYdBh8GIAYhBiIGJAYmBigGKgYsBi4GMAM1BjIGNAY4BjoGPgZABkIGRAMIBkUGRgZHBkgGSQZKBksGTAZNBk4GTwZQBlEGUgZTBlQGVQZWBlcGWAZZBloGTgZbAvkC+gL7AvwDCgMLAwwDAAMBAwIGXAZgBmQGaAZpBKQEpQSmBKcEqASpBKoEqwSsBK0ErgSvBLAEsQSyBLMEtAS1BLYEtwS4BLkEugS7BLwEvQS+BL8EwATBBMIEwwTEBMUExgTHBMgEyQTKBMsEzATNBM4EzwTQBNEE0gTTBNQE1QTWBNcE2ATZBNoE2wTcBN0E3gTfBOAE4QTiBOME5ATlBOYE5wToBOkE6gTrBOwE7QTuBO8E8ATxBPIE8wT0BPUB4wHkBPYE9wT4BPkE+gT7ALEAsgKKATQAtQC2AMMB5QCzALQAxACCAMEAhwNOA08DUgNQA1EDVQNWA1cDWANTA1QA9QHnAsAEfgC8AJkA7QDCAKUAkgE/AI8BQQF2AZEBkgGTAXcAuAF8Ae0B7gRxBHIEgQRzA1kDWgNbA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FMEcwSDBYYFiwbo/lj+xP7R/vf/Mv+GAFEAfACBAJEAlQCeALQAuQDPANkA2QDfAOIBBQELAQ4BDgEgASEBVQF7AXsBfgGNAaIBqAGpAbQB0AHQAeIB6QHyAfUB+wIAAgACBgIbAiECIgIiAiMCcgJ3ApQCnALPAs8C0ALsAvkDFwMiAysDNQM8A1kDbwNxA4cDkAOQA7UD4QQaBM8E/wUyBTIFlgWfBagFqwXCBfAGDAeCCAAIzPyj/Sr93v4A/oj+lv6y/rT/4QAVABkAGgAcAB8APABRAGEAYQBqAHgAlgClAK8A0wEMARgBGgEqAT4BTAFRAV8BagFxAXgBggGEAZoBpQGoAakBrgG8Ac0B1wHvAgACDQIcAiECIgIuAjUCQgJPAk8CXgJlAnECkAKSArQC1gL6AwcDCwMPAxUDKgNHA10DZQN0A3kDlgOwA8wD3QPiA/YD/AP8A/8ECgQfBCIEJgQrBEcEXwR1BJ4E5wTnBVwFywXlBgoGbQaGBrgG8Qc2Bz4HUAdRB10Hjwe2B9QIYAC2AMMAtQC3AAAAAAAAAAAAAAAAAeADgQNFA7UAjgIzBBkCzgLOAC0AXwBkA00CPwAAAqgBiAJ9AbQCJAV4BjsCOwFOAPAEJgKUAsYCnwL2AjsDTQFLAVMAagIxAAAAAAAABhQEqgAAADwEwwDtBLwCZQLOA7UAeAYMAX4C7wYMALIBAAI5AAABxQMwBCsDywDaA98BBwShANsECgEXAe0CpwNQAQsBvQQ+BVgAIQOcAK4DcQF9ALUCRQAACvsIjAErAU4BqgCHAFQBMgH4A/8AAwJOALQANwPjAIMAawLYAO0AdwCIAJcBZARnAI4AMwF8AOcApgKeAykFbgYqBhUByQJpBIoCEwG0AAIEqQAAAjkBJAEDBRQAhAFdA5oG7wLZAHUAzwQKAN4DrAS8As8CrgNNBPAFUgFoAG0AfQCGAHH/gQB5BVgE0gFnAAMBVgAlBOAAlAB8AzIEIQCUAH8AcgBcAC8AtgAYALoAuABBA00AcgAYAB8ATAFqAVUAmQCaAJoAmACyAAQAeABpABQAVwBuAM4AtAZUArgAZwUOAWUA5wAABMv+UgBa/6YAmf9nAG7/kgAt/9QAh/98ALgAqADlAI8AqAGF/nsAcAAeANkA3gFMBUYCzwVG/y0CigLZAlMClgC3AAAAAAAAAAAAAAAAAAABJQEYAOoA6gCuAAAAPgW7AIoE1wBTAD//jP/VABUAKAAiAJkAYgBKAOQAbQDuAOUASAPAADP+TgKx/0YDcAB5Bd8AUf+n/x8BCgBo/2wATwC8AKUHBQBhBysAAAAAAAAAKgAAACoAAAAqAAAAKgAAANYAAAF+AAADIAAABaYAAAdOAAAJOAAACX4AAAn+AAAKpAAAC4QAAAvsAAAMZAAADKoAAAzmAAANVgAADxIAAA/uAAASGAAAE/IAABVSAAAXDAAAGOIAABmOAAAcIgAAHlYAAB6yAAAfcAAAH/IAACBiAAAg6AAAIdoAACPaAAAlhAAAJxwAAChWAAApngAAKmIAACsYAAAsqAAALa4AAC6SAAAvegAAMbAAADI6AAA1ZAAANw4AADhCAAA5SAAAOzwAAD2oAABAUgAAQQAAAEIkAABDmAAARdYAAEjiAABKiAAAS8gAAEwyAABMnAAATQAAAE2IAABNvAAATjgAAFEKAABS6AAAVJwAAFZQAABYDgAAWWIAAFtSAABc9gAAXeoAAF8CAABhmgAAYpYAAGTGAABmjAAAaE4AAGoSAABrqAAAbK4AAHBWAABxegAAcxgAAHU2AAB5oAAAe8QAAH4cAACABAAAgQIAAIFOAACCUAAAgvAAAIM8AACDcAAAg6wAAIPuAACEVAAAhJoAAITOAACFBAAAhToAAIWKAACFzAAAhh4AAIZWAACGqAAAht4AAIceAACHYAAAh54AAIfoAACIKAAAiFYAAIiOAACI3gAAiRQAAIlUAACJjgAAidIAAIocAACKWAAAiogAAIrMAACLBAAAi5QAAIwaAACOKAAAj7wAAJFsAACRuAAAkkwAAJRwAACWxAAAmLQAAJmgAACaIgAAmowAAJuqAACdBgAAn04AAKCwAAChPgAAoegAAKKsAACj9AAApZ4AAKaMAACnUgAAp7YAAKgkAACpTgAAqnIAAKsCAACs5AAArz4AALKQAACzhgAAtCwAALR8AAC1MgAAtlIAALfwAAC4igAAuU4AALoOAAC6dgAAurIAALsKAAC7WAAAvXAAAL+2AAC/7gAAwCAAAMFKAADCdgAAwyQAAMPIAADEagAAxTwAAMWQAADFxgAAxh4AAMdwAADH4gAAyDwAAMm0AADLIAAAzAAAAMwyAADMzgAAzfIAANBoAADQogAA0OYAANEiAADRhAAA0cYAANIMAADSWAAA0ooAANLeAADTHAAA00wAANOKAADT0AAA1BIAANRQAADU0gAA1UAAANYmAADWYgAA1uIAANcWAADXuAAA2EAAANisAADZOAAA2aQAANqQAADbggAA27YAANvqAADcGgAA3F4AANzWAADeUAAA4GoAAOCcAADg1gAA4dAAAONeAADjlAAA5PgAAOV0AADmVAAA50oAAOjaAADqRAAA7DIAAO0uAADtdAAA7agAAO3qAADuJAAA7ngAAO7AAADvCgAA7zoAAO9qAADxUgAA8ZAAAPHKAADx+gAA8i4AAPJeAADyigAA8tIAAPSIAAD2AgAA9i4AAPZwAAD2tAAA9uQAAPcUAAD3agAA+EgAAPlaAAD5ngAA+dQAAPouAAD6bAAA+qAAAPrQAAD7DAAA+0wAAPuKAAD7xgAA/AgAAPw+AAD8egAA/LoAAP3IAAD/NAAA/4QAAQDgAAEBNgABAWoAAQG4AAECBAABAkYAAQJ+AAECtAABAvwAAQOeAAEFOgABBwIAAQiEAAEKdgABC8gAAQ1MAAEOLgABD8gAARAyAAEQWgABEPgAARN6AAETugABE/oAARQ6AAEUeAABFNYAARU0AAEVogABFcIAARasAAEXTAABF4IAARfQAAEYGgABGGQAARiAAAEYnAABGLwAARjcAAEY/AABGRwAARlCAAEZaAABGY4AARm0AAEZ5AABGgwAARo0AAEaYAABGowAARrAAAEa6gABGxYAARtMAAEbdgABG6IAARvYAAEcAgABHCwAARxgAAEckAABHMQAAR0IAAEdOAABHWwAAR2uAAEd4gABHhQAAR5WAAEeigABHroAAR78AAEfQAABH4YAAR/iAAEf/gABIBoAASA2AAEgUgABIG4AASHcAAEkrAABJxwAASc4AAEnUgABJ24AASeKAAEnpgABJ8IAASgeAAEoWAABKMIAASmMAAEqLAABKwIAASuCAAEsCgABLHoAAS0QAAEtbgABLbQAAS4SAAEudAABLywAAS/qAAEwFgABMHIAATC2AAEyIgABMxYAATNAAAEzXAABM4gAATPAAAE0DAABNEwAATSAAAE0sAABNOAAATUQAAE1VAABNYQAATW0AAE19AABNiQAATZUAAE2hAABNsQAATb0AAE3JAABN1QAATeCAAE5hgABObYAATnmAAE7NgABPOwAAT0cAAE9SgABPXoAAT2oAAE92AABPgYAAT80AAFAYgABQJIAAUICAAFCOgABQmoAAUP8AAFEKgABRFgAAUSGAAFErgABRgwAAUekAAFH3AABSBwAAUhYAAFIiAABSLYAAUjSAAFJAgABSTIAAUoiAAFLigABS7oAAUv0AAFMNAABTGQAAUyUAAFM1gABTnYAAVBWAAFQlgABUNYAAVEGAAFRRgABUjAAAVKwAAFTlAABU8QAAVP0AAFUJAABVFQAAVSQAAFUwgABVPQAAVUkAAFVVAABVZoAAVXMAAFV/AABVjIAAVakAAFW2AABWKYAAVmoAAFbOAABXWgAAV+4AAFhSgABYa4AAWI4AAFiSAABYtYAAWTUAAFmAAABZ2wAAWhcAAFp4AABa/oAAW4mAAFvGAABbygAAW84AAFwUAABcGAAAXBwAAFwgAABcJAAAXCgAAFxvgABcc4AAXHeAAFyUgABcmIAAXMyAAFzQgABdFQAAXRkAAF0dAABdIQAAXXiAAF3wAABeAIAAXg4AAF4bgABeJ4AAXjOAAF5IgABeUoAAXrUAAF8HAABfXAAAX7YAAGAXAABgMAAAYJSAAGDbgABg34AAYOOAAGFFAABhSQAAYaKAAGH5AABiRgAAYp2AAGL5AABjaoAAY3qAAGOIgABjlgAAY5+AAGOrgABjtQAAZBKAAGQegABkbAAAZHAAAGR0AABkhIAAZIiAAGTtgABlWIAAZbsAAGXFAABl0QAAZigAAGYsAABmegAAZn4AAGakgABm/IAAZwCAAGeaAABn/IAAaFaAAGhigABowAAAaQyAAGkQgABpFIAAaRiAAGlPAABpUwAAaVcAAGlbAABpmQAAafeAAGn7gABqRYAAapKAAGrnAABrTAAAa5OAAGv2gABsOwAAbEiAAGzWAABs/gAAbQIAAG1ngABt0AAAbfEAAG5RgABuVYAAbu+AAG9PgABvr4AAb7uAAHAjgABwhQAAcPYAAHFBAABxRQAAcZEAAHGVAABxmQAAcckAAHHNAAByRoAAckqAAHKYAABy24AAc0aAAHO0AAB0BIAAdGCAAHSygAB0xwAAdT+AAHWegAB1rgAAdheAAHYggAB2cIAAdnSAAHZ4gAB2hoAAdoqAAHbtgAB3SQAAd6YAAHevAAB3uwAAeBaAAHhDAAB4coAAeH4AAHjrgAB5KYAAeU0AAHmYAAB5xQAAefuAAHoOAAB6LYAAel8AAHppAAB6e4AAepEAAHrMAAB63oAAeuuAAHr1gAB6/4AAewyAAHsdgAB7LoAAez4AAHuNgAB7u4AAfAOAAHwhAAB8VIAAfGkAAHyNgAB8uYAAfPaAAH0LgAB9MQAAfWCAAH2bAAB9x4AAfg+AAH4kAAB+ToAAfpwAAH7SAAB/C4AAf00AAH+GgAB/vwAAf/wAAIAjgACAZQAAgKOAAIDBgACA34AAgP0AAIEKgACBIYAAgVOAAIF2gACBhIAAgZYAAIGiAACBvIAAgeyAAIH5gACCBYAAghKAAIIegACCKoAAgjaAAIKegACCrIAAgryAAILKgACC2IAAgv+AAIM+AACDSgAAg3MAAIN+gACDjoAAg6KAAIOugACDwYAAhCeAAISBAACE2QAAhOqAAIT/gACFDYAAhWoAAIV3gACFnAAAhauAAIW3AACFxoAAhhKAAIYcgACGa4AAho+AAIa6AACG2oAAhwmAAIdPgACHkwAAh6AAAIfBgACIGIAAiDkAAIhLgACIjgAAiKAAAIjhAACJAAAAiRYAAIk3AACJcYAAibcAAIn2AACKIIAAilyAAIqRAACKy4AAiwWAAIsxgACLUgAAi+mAAIv0AACL/oAAjCyAAIw3AACMh4AAjMkAAI0DgACNDgAAjRiAAI0jAACNLYAAjTgAAI2YAACNooAAja0AAI23gACNwgAAjcyAAI3XAACN4YAAjewAAI35AACOA4AAjg4AAI4YgACOdwAAjnsAAI7BgACOxYAAjtAAAI7agACO5QAAju+AAI9aAACP4QAAkCyAAJAwgACQj4AAkJOAAJDlAACRWAAAkZmAAJH5gACSYYAAkuqAAJNBAACTuYAAlAqAAJRWAACUYIAAlGsAAJR1gACUgAAAlIqAAJSVAACUn4AAlKoAAJS0gACUvwAAlMmAAJTUAACU3oAAlOkAAJTzgACU/gAAlY0AAJXsAACWPQAAlrcAAJcJAACXE4AAlx4AAJcqAACXNgAAl0oAAJdeAACXbgAAl4qAAJefgACXtwAAl8yAAJfaAACX6oAAl/wAAJgOgACYGoAAmCiAAJg0gACYgoAAmVQAAJlegACZaQAAmXOAAJl+AACZiIAAmZMAAJmdgACZqAAAmbKAAJm9AACZx4AAmdIAAJncgACZ5wAAmfGAAJn8AACaBoAAmhEAAJobgACaJgAAmjCAAJo7AACaRYAAmlAAAJpagACaZQAAmm+AAJp6AACaoYAAmqcAAJqxgACbaYAAm22AAJu0AACb/IAAnEwAAJycgACdBgAAnQoAAJ1agACdroAAniqAAJ6fgACe5YAAnumAAJ8KAACfLYAAn22AAJ9xgACfmYAAn52AAJ/jAACgN4AAoIOAAKCHgACguwAAoL8AAKEcgAChIIAAoWWAAKFpgAChtoAAohwAAKJLAACiTwAAoo6AAKLlAACjCAAAowwAAKNWgACjuYAAo+iAAKPsgACkE4AApBeAAKRLAACkTwAApIUAAKSJAACkywAApM8AAKVAgAClRIAApZqAAKWegACmOQAApj0AAKa7gACmv4AApxoAAKceAACnWgAAp14AAKfEAACnyAAAqA+AAKgTgACoY4AAqGeAAKhrgACob4AAqM2AAKjRgACo1YAAqNmAAKkuAACpgYAAqbUAAKnuAACqTgAAqq6AAKrugACrM4AAq4SAAKuIgACrxAAAq/qAAKxhgACsZYAArK0AAKzugACtbgAArXIAAK12AACtegAArcyAAK3QgACt/oAArgKAAK5GAACuSgAAroUAAK6JAACu0IAArtSAAK78AACvAAAArwQAAK8/gACvnIAAr+eAALAmAACwKgAAsC4AALAyAACwmYAAsQgAALE7gACxP4AAsdeAALJpAACzCoAAs6OAALREgAC04QAAtVUAALXCgAC1zQAAtdeAALXbgAC134AAteoAALX0gAC1/wAAtgMAALYHAAC2EYAAthwAALYgAAC2JAAAti6AALY5AAC2Q4AAtkeAALZLgAC2T4AAtlOAALZXgAC2W4AAtmYAALZqAAC2bgAAtniAALaDAAC2jYAAtpgAALaigAC2rQAAtreAALbCAAC2zIAAttcAALbhgAC27AAAtvaAALcBAAC3C4AAtxYAALcggAC3KwAAtzWAALdAAAC3SoAAt1UAALdfgAC3agAAt3SAALd/AAC3iYAAt5QAALeegAC3qQAAt7OAALe+AAC3yIAAt9MAALfdgAC36AAAt/KAALf9AAC4B4AAuBIAALgcgAC4JwAAuDGAALg8AAC4RoAAuFEAALhbgAC4ZgAAuHCAALh7AAC4hYAAuJAAALiagAC4pQAAuM0AALjeAAC4+4AAuQYAALkQgAC5GwAAuSWAALkwAAC5OoAAuUUAALlPgAC5WgAAuWSAALlvAAC5eYAAuYQAALmOgAC5mQAAuaOAALmuAAC5uIAAucMAALnNgAC52AAAueKAALntAAC594AAugSAALoRgAC6HoAAuoMAALrqAAC7UQAAu7QAALvFgAC71wAAu/KAALwJgAC8HgAAvDoAALxwAAC8owAAvNkAAL0MAAC9NAAAvXqAAL2ngAC9yAAAvd6AAL3ugAC+NgAAvoiAAL7ugAC/BYAAvx0AAL80AAC/SwAAv3gAAL+lgAC/0IAAv/uAAMAmgADAVIAAwIKAAMCwgADAtQAAwLmAAMC+AADAwoAAwMcAAMDigADA/gAAwSwAAMEwgADBNQAAwTmAAME9gADBQgAAwUaAAMFLAADBT4AAwVQAAMFYgADBhAAAwa8AAMHagADCBYAAwiuAAMI6AADCRIAAwk8AAMJkAADCeIAAwpeAAMKqAADCyQAAwt4AAML/AADDE4AAwzEAAMNHAADDYIAAw3YAAMOMgADDrAAAw78AAMPWgADD74AAxAMAAMQWgADELIAAxD6AAMRJAADEVIAAxF4AAMRrAADEdwAAxIMAAMSXgADEswAAxMiAAMTlgADE+oAAxReAAMUpAADFQwAAxVSAAMVrgADFd4AAxYYAAMWPgADFm4AAxaUAAMWugADFuwAAxccAAMXbgADF9QAAxgqAAMYkAADGOQAAxlSAAMZlAADGfQAAxo2AAMaggADGrwAAxr4AAMbMgADG24AAxuiAAMb1AADHAQAAxw0AAMcXgADHIQAAxyuAAMc3AADHQYAAx1SAAMdlgADHcwAAx4IAAMePAADHmoAAx6oAAMe2AADHxIAAx88AAMfagADH5AAAx+2AAMf4gADID4AAyBuAAMgngADIM4AAyEGAAMhOgADIWgAAyGYAAMhyAADIfgAAyIoAAMiXAADIrIAAyLmAAMjRgADI3oAAyPSAAMkBgADJGIAAyUAAAMlzgADJu4AAye2AAMoRgADKNwAAyrIAAMsxAADLjwAAy+4AAMxYgADMxQAAzP8AAM1MgADNioAAzc8AAM4WgADOZAAAzr6AAM8aAADPf4AAz96AANAigADQJoAA0HGAANDAgADREQAA0XIAANGogADRxgAA0fOAANIdAADSeQAA0ocAANKlgADS1gAA0wSAANMegADTYAAA062AANPhAADUOIAA1FcAANR1gADUp4AA1NYAANUDAADVGgAA1TCAANVCgADVXoAA1X2AANWQAADVnoAA1bAAANXBAADV1YAA1eoAANYKgADWKwAA1juAANZLgADWWQAA1maAANZyAADWfYAA1oqAANaXgADWqAAA1riAANbHgADW1oAA1uUAANbzgADXAAAA1wyAANcZAADXJYAA1zQAANdCgADXUwAA12OAANd0AADXhIAA15gAANergADXvAAA18yAANfcgADX7IAA1/sAANgJgADYHIAA2C+AANg/AADYTwAA2GCAANhyAADYgQAA2JaAANilgADYtIAA2MSAANjUgADY44AA2PKAANkCgADZEoAA2SOAANk0gADZSYAA2W0AANl9gADZjgAA2agAANnCAADZzoAA2dsAANnpAADZ9wAA2hyAANpCAADaVIAA2mcAANp2AADahQAA2pqAANqwAADawoAA2tUAANrrAADbAQAA2xEAANshAADbLwAA2z0AANtPgADbYgAA23GAANuBAADbkYAA26IAANu3AADbzAAA292AANvvAADcAIAA3BIAANwngADcPQAA3FKAANxoAADcewAA3I4AANyhAADctAAA3NEAANzuAADdCwAA3SgAAN03gADdRwAA3VaAAN1mAADddYAA3YUAAN2WAADdpwAA3boAAN3NAADd5QAA3fgAAN4HgADeGwAA3l8AAN5zAADehwAA3pUAAN6jAADeuIAA3s4AAN7rAADfBAAA3xSAAN8lAADfOoAA304AAN9hAADfdAAA34QAAN+UAADfpgAA37gAAN/TAADf6YAA3/eAAOAFgADgFYAA4CWAAOBsAADgvYAA4PmAAOE9gADhUwAA4WiAAOF9AADhkgAA4asAAOHEAADh2YAA4e8AAOIMgADiKgAA4jqAAOJLAADiW4AA4mwAAOJ8gADijQAA4qKAAOK4AADizIAA4uGAAOMDgADjJAAA40wAAON0gADjhAAA45OAAOOjAADjsgAA48GAAOPRAADj4IAA4++AAOQogADkY4AA5KkAAOTwAADlIoAA5VUAAOWTgADl0gAA5hGAAOZRAADmmIAA5uAAAOcpgADncwAA57OAAOf0AADoGIAA6D0AAOhMgADoXAAA6HKAAOiJAADo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EgAAAEHANYA3wAAACdAGAIgQDs1IEAtMjQPIJ8gAgAgIwoKQQIBJLkCIgApACsBK3IrKzUA//8AS//oBB4FwwImAEgAAAEHAI4A3wAAACNAFAMCIkALCwJVryIBIgpkSCsCAwIluQIiACkAKwErXSs1NQD//wC9AAACLgXCAiYA1QAAAQYAjd8AADK3AQdACwsGVQe4/8CzFxk0B7j/wEAOIiU0LwcBBwFaSCsBAQe5AiIAKQArAStdKysrNf//ACMAAAGbBcICJgDVAAABBgBDygAAKEAQAQVAFxk0BUAiJTQgBQEFArj/prRIKwEBBbkCIgApACsBK10rKzX////vAAACaAXCAiYA1QAAAQYA1tYAABZACgEABgkBAkEBAQq5AiIAKQArASs1//8ACQAAAjoFwwImANUAAAEGAI7MAAAfQBECAQggCwsGVQgCAEgrAQICC7kCIgApACsBKys1NQD//wCHAAAD5gWqAiYAUQAAAQcA1wD/AAAANbMBAQEmuQIiACkAKwGxBgJDVFi1ABcjAQtBKxu5ACj/wLciJDRPKAEoErj/4rFIKytdK1k1AP//AET/6AQnBcICJgBSAAABBwCNAPQAAAAbQA4C4B3wHQIdBABIKwIBHbkCIgApACsBK101AP//AET/6AQnBcICJgBSAAABBwBDAN4AAAAmsQIbuP/AQBELDTQPGwFwGwEbBABIKwIBG7kCIgApACsBK11xKzX//wBE/+gEJwXCAiYAUgAAAQcA1gDgAAAAIEASAhxALjI0nxwBABwfAAdBAgEguQIiACkAKwErcis1//8ARP/oBCcFwwImAFIAAAEHAI4A4AAAACpACQMCHkAWFgZVHrj/wEANCgsGVR4EbkgrAgMCIbkCIgApACsBKysrNTX//wBE/+gEJwWqAiYAUgAAAQcA1wDgAAAAMEAXAi8rPysCfyv/KwJPK48rAi8rPysCKwS4/+y0SCsCASm5AiIAKQArAStdXV1xNf//AIP/6APgBcICJgBYAAABBwCNAOcAAAAhQBMBHEAOEDQfHE8cAhwRPEgrAQEcuQIiACkAKwErcSs1AP//AIP/6APgBcICJgBYAAABBwBDAQcAAAAVQAoBARoRAEgnAQEauQIiACkAKwErAP//AIP/6APgBcICJgBYAAABBwDWANwAAAApswEBAR+5AiIAKQArA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REFIREhAlL+MQGD/skBNwOM/jEBz0z+yQAAAQCyAIkEIwP6AA0AAAEyFhYVFAAjIgA1NDY2Amtu1Hb+/ra3/v521AP6ctRyt/7+AQK3c9NyAAACAHABqgJmA6AACwAXAAABMhYVFAYjIiY1NDYXIgYVFBYzMjY1NCYBa2iTk2hok5JpSWZnSEhnZgOgk2hok5NoaJNMZ0hJZmZJSGf////9AAAFWQa+AiYAJAAAAQcA2AFKAV8AJkAXAgAPARAP0A8CIA8wDwIADxIMDEECAQ+5AiEAKQArAStdcXI1//8ASv/oBBwFXwImAEQAAAEHANgA9QAAABpADQJwOAEAODsCAkECATi5AsMAKQArAStdNf//AGb/5wV2ByYCJgAmAAABBwDWAbABZAAWQAoBACAjCA9BAQEguQIhACkAKwErNf//AFD/6APtBcICJgBGAAABBwDWAPoAAAAWQAoBAB0gBw5BAQEduQIiACkAKwErNf//AGb/5wV2BxoCJgAmAAABBwDaAbABkAAVQAkBHgtkSCsBAR65AiEAKQArASs1AP//AFD/6APtBYoCJgBGAAABBwDaAPAAAAApswEBARu5AiIAKQArAbEGAkNUWLUAGx4LC0ErG7dvGwEbEyhIKytdWTUA//8AogAABOgGyQImACgAAAEHANgBgQFqABZACgEADA8BAkEBAQy5AiEAKQArASs1//8AS//oBB4FXwImAEgAAAEHANgA4AAAABZACgIAHiEHD0ECAR65AsMAKQArASs1//8AogAABOgHIgImACgAAAEHANkBawFqACWzAQEBELkCIQApACsBsQYCQ1RYtQATDQECQSsbtBMFRkgrK1k1AP//AEv/6AQeBbgCJgBIAAABBwDZAPQAAAAVQAoCASUWAEgnAgEiuQIiACkAKwErAP//AKIAAAToBvQCJgAoAAABBwDaAYEBagAWQAoBAAwPAQJBAQEMuQIhACkAKwErNf//AEv/6AQeBYoCJgBIAAABBwDaAPoAAAAWQAoCAB4hBw9BAgEeuQIiACkAKwErNf//AG3/5wW5ByECJgAqAAABBwDWAg4BXwAlswEBASi5AiEAKQArAbEGAkNUWLUAKCsODkErG7QmDgBIKytZNQD//wBC/lED6gXCAiYASgAAAQcA1gDIAAAAFkAKAgAtMA8XQQIBLbkCIgApACsBK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Q3+8/71AAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADAAcAACERIRElIREhAQAEAPwgA8D8QAUA+wAgBMAAAAIBAAAABQAFAAADA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/8AQATZAbEHLgAWA0oAAP//ADAE1wHPB3cAFgNHAAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAD//wBG/9UBnACkABYC9gAA//8ARv72AZwAWwAWAvMAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAQAAAAUABQAAAwAHAAAhESERJSERIQEABAD8IAPA/EAFAPsAIATAAAACAMoBGAHJBbcAEgAeAD65ABAC8rcHQAkKNAcHHLwC7gAWAuwABgLxtAcHExkAuALtsg0NE7kC7QAZL+0zL+0REjkv7QA//TIvKz8xMAEUBwYHBhUjNCcmJyY1NDYzMhYDFAYjIiY1NDYzMhYByRorBRo5GQolGkY3OUkGSDQySEg0MkgFHUN2wxySiH6ZOrZ+LT1dXPw3MkhIMjNKSgAAAQDHARgBzwIiAAsAFr4ACQLuAAMC7AAAAu0ABi/tAD/tMTABFAYjIiY1NDYzMhYBz083NkxNNThOAZ02T043Nk9OAAACAMYBGAHNBFcACwAXACq5AAkC7rIDAxW8Au4ADwLsAAAC7bIGBgy5Au0AEi/tMy/tAD/9Mi/tMTABFAYjIiY1NDYzMhYRFAYjIiY1NDYzMhYBzU44NUxKNzhOTzc1TEs2OE4D0jhOTjg3Tk79lDZPTjc2T04AAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEwAAAAgEAAAAFAAUAAAMABwAAIREhESUhESEBAAQA/CADwPxABQD7ACAEw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AP/tAAvADf/aAAvADn/aAAvADr/aAAvADz/aAAvAFz/tAAvALb/jwAzAAP/2wAzAA/++AAzABH++AAzACT/aAA1ADf/2wA1ADn/2wA1ADr/2wA1ADz/2wA3AAP/2wA3AA//HQA3ABD/jwA3ABH/HQA3AB3/HQA3AB7/HQA3ACT/aAA3ADL/2wA3AET/HQA3AEb/HQA3AEj/HQA3AEz/tAA3AFL/HQA3AFX/tAA3AFb/HQA3AFj/tAA3AFr/jwA3AFz/jwA5AA//RAA5ABD/jwA5ABH/RAA5AB3/tAA5AB7/tAA5ACT/aAA5AET/aAA5AEj/jwA5AEz/2wA5AFL/jwA5AFX/tAA5AFj/tAA5AFz/tAA6AA//jwA6ABD/2wA6ABH/jwA6AB3/2wA6AB7/2wA6ACT/tAA6AET/tAA6AEj/2wA6AEwAAAA6AFL/2wA6AFX/2wA6AFj/2wA6AFz/7gA8AAP/2wA8AA/++AA8ABD/RAA8ABH++AA8AB3/jwA8AB7/ewA8ACT/aAA8AET/aAA8AEj/RAA8AEz/tAA8AFL/RAA8AFP/aAA8AFT/RAA8AFj/jwA8AFn/jwBJAEn/2wBJALYAJQBVAA//jwBVABH/jwBVALYATABZAA//aABZABH/aABaAA//jwBaABH/jwBcAA//aABcABH/aAC1ALX/2wC2AAP/tAC2AFb/2wC2ALb/2wDEAi3/YADEAjb/YADEAkz/YADEAlH/vADEAlT/vAErAA//HwErABH/HwErAfgApAErAfn/RAErAfv/RAErAgH/RAErAhr/qAErAicAWAEsAfn/2wEsAfv/2wEsAgH/2wEsAgr/vgEsAg//vgEtAfn/xQEtAgr/vgEtAg//vgEvATL/4wEvAhz/2QEvAiT/yQEvAoz/4wEyAS7/4wEyAS//4wEyATH/4wEyATP/4wEyAhD/4wEyAhf/4wEyAiD/4wEyAiL/4wEyAib/4wEyAiv/4wEzATL/4wEzAhz/2QEzAiT/yQEzAoz/4wHxASz/1QHxAS3/xQHxAgX/1QHxAgn/aAHxAgr/aAHxAg//aAHxAhb/2wHxAh7/2wHxAiT/2wH1Agr/vgH2ASz/jQH2AS3/jQH2AS7/RgH2ATH/RgH2ATP/RgH2AfgAqgH2Afn/aAH2Afv/aAH2AgH/aAH2AgX/jQH2Ag3/ngH2AhL/aAH2AhP/tAH2Ahj/aAH2Ahr/tAH2Ahv/aAH2Ah3/aAH2AiD/RgH2AicAYgH2Ain/RgH3Agr/0QH3Ag//0QH5AAP/jwH5ALb/aAH5ASz/1QH5AS3/xQH5AgX/1QH5Agn/aAH5Agr/aAH5Ag//aAH5Ahb/2wH5Ah7/2wH5AiT/2wH7AAP/jwH7ASz/1QH7AgX/1QH7Agn/iQH7Agr/aAH7Ag//aAIAASz/wQIAAS3/jwIAAS7/5wIAAS//5wIAATH/5wIAATP/5wIAAgX/wQIAAhD/5wIAAhf/5wIAAhn/5wIAAh//5wIAAiD/5wIAAib/5wIAAin/5wIAAiv/5wIBAAP/jwIBASz/1QIBAgX/1QIBAgn/aAIBAgr/aAIBAg//aAIFAfn/2wIFAfv/1QIFAgH/2wIFAgr/vgIFAg//vgIHAAP/2wIHAA/++gIHABH++gIHAfn/aAIHAfv/aAIHAgH/aAIIATL/ngIIAoz/ngIJAAP/2wIJAA//HwIJABH/HwIJAB3/HwIJAB7/HwIJASz/2wIJAS3/2wIJAS7/HwIJATD/HwIJATH/HwIJATP/HwIJAfgAvAIJAfn/aAIJAfv/aAIJAgH/aAIJAgX/2wIJAg3/2wIJAhD/HwIJAhH/HwIJAhT/TgIJAhb/TgIJAhj/agIJAhr/tAIJAh3/agIJAh7/jwIJAiD/HwIJAiP/UAIJAiT/jwIJAiX/agIJAicAvAIJAij/TgIJAin/HwIJAir/TgIKAAP/2wIKAA/++gIKABD/RgIKABH++gIKAB3/jwIKAB7/jwIKASz/jQIKAS3/jQIKAS7/RgIKATH/RgIKATP/RgIKAfgAvAIKAfn/aAIKAfv/aAIKAgH/aAIKAgX/jQIKAg3/ngIKAhL/aAIKAhP/tAIKAhb/ngIKAhj/aAIKAhr/tAIKAhv/aAIKAh3/aAIKAiD/RgIKAicAeQIKAin/RgIMAS7/sgIMAS//sgIMATH/sgIMATP/sgIMAhD/sgIMAhn/2QIMAiD/sgIMAib/sgIMAin/sgIMAiv/sgINAgr/0QINAg//0QIPAAP/2wIPASz/jQIPAS3/jQIPAS7/RgIPATH/RgIPATP/RgIPAfgAqgIPAfn/aAIPAfv/aAIPAgH/aAIPAgX/jQIPAg3/ngIPAhL/aAIPAhP/tAIPAhj/aAIPAhr/tAIPAhv/aAIPAh3/aAIPAiD/RgIPAicAYgIPAin/RgIXAS7/dwIXAS//tAIXATH/dwIXATL/qgIXATP/dwIXAhD/dwIXAhL/2wIXAhb/qgIXAhj/2wIXAhn/ngIXAhr/2wIXAhv/2wIXAh7/qgIXAiD/dwIXAib/dwIXAin/dwIXAiv/dwIXAoz/qgIZAhz/2QIbAS7/5wIbAS//5wIbATH/5wIbATP/5wIbAhD/5wIbAhf/5wIbAhn/5wIbAh//5wIbAiD/5wIbAiL/5wIbAib/5wIbAin/5wIbAiv/5wIcAS7/4QIcAS//4QIcATH/4QIcATP/2wIcAhD/4QIcAh//4QIcAiD/4QIcAiL/0QIcAiP/zwIcAib/4QIcAin/4QIcAir/zwIcAiv/4QIfAS7/yQIfAS//yQIfATH/yQIfATP/yQIfAhD/yQIfAhf/yQIfAh//yQIfAiD/yQIfAiL/yQIfAin/yQIgATL/4wIgAhz/2QIgAiT/yQIgAoz/4wIhATL/4wIhAhz/2QIhAoz/4wIkAS7/yQIkAS//yQIkATH/yQIkATP/yQIkAhD/yQIkAhf/yQIkAiD/yQIkAiL/yQIkAib/yQIkAin/yQIkAiv/yQImATL/4wImAhz/2QImAiT/yQImAoz/4wIpATL/4wIpAhz/2QIpAiT/yQIpAoz/4wIrATL/4wIrAhz/2QIrAiT/yQIrAoz/4wIuAA//BgIuABH/BgIuAKn/dwIuAKr/dwIuALL/0wI0ALb/YAI1ALb/dwI6ALb/jQI6Aj4ARAI6AkH/6QI6AkUALQI6Akj/0wI6Akn/6QI6Akv/0wI6Akz/YAI6Ak3/pgI6Ak7/vAI6AlH/YAI6Alf/0wI6AloAFwI6Amz/0wI6Am3/6QI6Am4AFwI6AncALQI7Ajr/0wI7AkH/6QI7Akj/6QI7Akv/6QI7Akz/pAI7Ak3/0QI7Ak7/6QI7Ak//0wI7AlH/pAI7AlT/vAI7Alf/6QI7Aln/6QI7AmX/6QI7Am3/0wI8Ajr/vAI8Aj7/0wI8AkD/0wI8AkH/vAI8AkX/6QI8Akj/vAI8Akv/vAI8Akz/dwI8Ak3/vAI8Ak7/vAI8Ak//pgI8AlH/pAI8AlT/jQI8Aln/vAI8Al7/6QI8Amb/6QI8Amz/vAI8Am3/6QI8Am//6QI8AnH/vAI8Ann/6QI9AA//BgI9ABH/BgI9AKn/dwI9AKr/dwI9ALL/0wI9Ajr/dwI9Aj7/dwI9AkH/0wI9AkX/jQI9Akb/0QI9Akj/jQI9Akv/pAI9Aln/vAI9Alr/jQI9Alz/jQI9Al7/dwI9Al//dwI9AmL/jQI9AmX/jQI9Amb/jQI9Amf/jQI9Amj/dwI9Amr/jQI9Am3/dwI9AnX/jQI9Anb/jQI9Anj/jQI9Ann/dwI+Ak0AFwI+Ak7/0wI+AlH/ugI+AmEARAI+AmgAFwI+Am0ALQI/AkH/0wI/Amv/6QJAAkH/6QJAAkj/0wJAAkv/6QJAAkwAFwJAAk0ALQJAAlQALQJAAloAFwJAAl//5wJAAmj/6QJAAm3/6QJBAkX/6QJBAkj/6QJBAkv/6QJBAkz/0wJBAk3/6QJBAk7/6QJBAlH/0wJBAln/6QJEAkH/6QJEAkj/6QJEAkv/6QJEAk0AFwJEAk7/ugJFAk7/6QJFAlsAFwJFAm0AFwJGAk7/6QJGAlH/6QJGAloAFwJGAl8AFwJGAmgAFwJGAmsAFwJGAm0AFwJGAnH/6QJGAncAFwJIAjr/0wJIAj7/0wJIAkD/0wJIAkX/6QJIAk3/0wJIAk//pAJIAlH/0wJIAln/0wJIAl7/0wJIAmX/6QJIAm//6QJKAA/+fQJKABH+fQJKAB3/0wJKAB7/0wJKAKr/jQJKAjr/dwJKAj7/dwJKAkD/6QJKAkH/0wJKAkX/jQJKAkb/6QJKAkj/0wJKAkv/6QJKAkz/pAJKAk3/0wJKAk7/6QJKAk//pAJKAln/0wJKAlr/vAJKAl7/YAJKAl//pgJKAmj/pgJKAnf/0wJKAnn/vAJLAjr/0wJLAj7/0wJLAkH/6QJLAkX/vAJLAkb/6QJLAkj/0wJLAkz/vAJLAk3/vAJLAk//jQJLAlH/vAJLAlT/ugJLAlf/6QJLAloAFwJLAmAALQJLAnH/6QJMAA//HQJMABH/HQJMAKn/pgJMAKr/pgJMALL/0wJMAjr/vAJMAj7/vAJMAkAAFwJMAkH/6QJMAkX/0wJMAkj/pAJMAk7/vAJMAln/0wJMAlr/pAJMAlz/pgJMAl//jQJMAmL/pgJMAmT/pgJMAmX/pAJMAmb/pgJMAmj/YAJMAmn/pgJMAmr/jQJMAmv/jQJMAm3/jQJMAm//pgJMAnP/pgJMAnX/pgJMAnb/pgJMAnj/pgJMAnn/jQJNAA/+8AJNABH+8AJNAB3/0wJNAB7/0wJNAKn/pgJNAKr/pAJNALL/6QJNAjr/dwJNAj7/pAJNAkH/0wJNAkX/vAJNAkj/vAJNAk7/vAJNAlf/0wJNAln/0wJNAlv/0wJNAlz/jQJNAl3/pAJNAl7/YAJNAl//dwJNAmD/vAJNAmH/jQJNAmL/pAJNAmP/vAJNAmT/pAJNAmX/dwJNAmb/pAJNAmf/pAJNAmj/dwJNAmn/pAJNAmr/pAJNAmv/dwJNAm//pAJNAnD/pAJNAnL/pAJNAnP/pAJNAnj/pAJNAnn/dwJOAjr/0wJOAj7/vAJOAkX/vAJOAkz/jQJOAk3/pAJOAlH/0wJOAln/ugJOAmX/vAJPAkH/0wJPAkj/vAJPAkv/vAJPAk7/vAJPAlf/ugJPAmj/6QJPAm3/0wJQAkj/0wJQAloALQJTAloAFwJTAm0ALQJUALb/dwJUAln/vAJWALb/YAJWAjr/0wJWAj7/0wJWAkD/vAJWAkH/6QJWAkX/ugJWAkb/0wJWAkj/0wJWAkv/0wJWAkz/MwJWAk//pAJWAlH/YAJWAlf/6QJWAln/pAJXAj7/vAJXAkD/5wJXAkH/6QJXAkX/vAJXAk//ugJXAln/0wJXAl7/vAJXAmAAFwJXAmX/vAJXAmb/6QJXAnn/6QJYAjr/vAJYAj7/pgJYAkD/0wJYAkX/pAJYAkj/6QJYAkv/6QJYAkz/jQJYAk//pAJYAlH/vAJYAl7/pAJYAmX/pAJYAmb/6QJaAmH/6QJaAmz/0wJaAm3/6QJaAnH/0wJbAlr/0QJbAl7/pAJbAl//6QJbAmD/6QJbAmH/0wJbAmX/pAJbAmb/0wJbAmv/6QJbAm3/0wJbAm7/6QJbAm//vAJbAnH/vAJbAnT/vAJbAnf/6QJbAnn/0wJcAlr/6QJcAlv/6QJcAl7/6QJcAl//6QJcAmD/6QJcAmH/6QJcAmX/0QJcAmb/6QJcAmj/6QJcAmv/6QJcAmz/0wJcAm3/0wJcAm7/6QJcAnH/pAJcAnT/vAJcAnn/6QJdAA//BgJdABH/BgJdAlr/0wJdAl7/pAJdAl//0wJdAmH/6QJdAmX/0wJdAmj/0wJdAmv/0wJdAnn/6QJeAnT/0wJeAncAFwJfAlv/6QJfAl7/0wJfAmD/6QJfAmH/0wJfAmX/vAJfAmz/vAJfAm3/6QJfAm//0wJfAnH/vAJgAlsAFwJgAm0AFwJgAnH/6QJgAnQALQJhAlv/6QJhAl7/0wJhAl//6QJhAmH/6QJhAmX/6QJhAmj/6QJhAmv/6QJhAm3/6QJhAm7/6QJhAnH/vAJhAnT/0wJkAloALQJkAlsALQJkAl8AFwJkAmEAFwJkAmUAFwJkAmgAFwJkAmsAFwJkAmwAFwJkAm0AFwJkAncAFwJlAmgAFwJlAnH/0wJmAlv/6QJmAmH/6QJmAm0AFwJoAl7/0wJoAmD/6QJoAmH/6QJoAmX/0wJoAmz/0wJoAm3/6QJoAm//6QJoAnH/0wJqAl7/0QJqAmH/6QJqAmX/ugJqAmz/0wJqAm3/6QJqAm//6QJqAnH/0wJqAnn/6QJrAmAAFwJrAmgAFwJrAnH/6QJrAncAFwJsAA//HQJsABH/HQJsAlr/6QJsAl7/vAJsAl//6QJsAmAARAJsAmX/0wJsAmj/6QJsAmv/6QJsAm0AFwJtAA//MwJtABH/MwJtAKoAFwJtAlr/6QJtAlsAFwJtAl7/vAJtAl//6QJtAmAAFwJtAmX/0wJtAmb/6QJtAmj/5wJtAmr/6QJtAmv/6QJtAm7/6QJtAnf/6QJtAnn/6QJuAlv/6QJuAl7/0wJuAmX/0wJuAmz/0wJuAm3/6QJuAnH/0wJuAnn/6QJvAlr/6QJvAlv/6QJvAl//6QJvAmH/6QJvAmj/6QJvAmv/6QJvAmz/6QJvAm7/6QJvAnH/0wJwAl//6QJwAmH/6QJwAmj/6QJwAmv/6QJzAl//6QJzAmj/6QJzAm0AFwJ2Amz/YAJ2AnH/dwJ3Al7/0wJ3Al8AFwJ3AmH/6QJ3AmX/0wJ3AmgAFwJ3Amz/0wJ3Am//6QJ3Ann/6QJ4Al7/0wJ4AmD/6QJ4AmX/0wJ4Amb/6QJ4Amz/0wJ4Am//6QJ4AnH/0wKGAA//MwKGABH/MwKIAA//BgKIABH/BgKIAB3/0wKIAB7/0wKIAKn/YAKIAKr/YAKIALL/0wKMAS7/4wKMATH/4wKMATP/4wKMAhD/4wKMAhf/4wKMAiD/4wKMAiL/4wKMAib/4wKMAiv/4wAAAEYDTgAAAAMAAAAAAP4AAAAAAAMAAAABAAoBPgAAAAMAAAACAA4F3gAAAAMAAAADAF4FwAAAAAMAAAAEAAoBPgAAAAMAAAAFABgF7gAAAAMAAAAGAA4GHgAAAAMAAAAHAMQGLAAAAAMAAAAIACYHfAAAAAMAAAAJAIoNpAAAAAMAAAAKBMIA/gAAAAMAAAALAGIOLgAAAAMAAAAMAGYOkAAAAAMAAAANBrQG8AAAAAMAAAAOAFwO9gABAAAAAAAAAH8PUgABAAAAAAABAAUP8QABAAAAAAACAAcSQQABAAAAAAADAC8SMgABAAAAAAAEAAUP8QABAAAAAAAFAAwSSQABAAAAAAAGAAcSYQABAAAAAAAHAGISaAABAAAAAAAIABMTEAABAAAAAAAJAEUWJAABAAAAAAAKAmEP0QABAAAAAAALADEWaQABAAAAAAAMADMWmgABAAAAAAANA1oSygABAAAAAAAOAC4WzQADAAEEAwACAAwW+wADAAEEBQACABAXCwADAAEEBgACAAwXGwADAAEEBwACABAXJwADAAEECAACABAXNwADAAEECQAAAP4AAAADAAEECQABAAoBPgADAAEECQACAA4F3gADAAEECQADAF4FwAADAAEECQAEAAoBPgADAAEECQAFABgF7gADAAEECQAGAA4GHgADAAEECQAHAMQGLAADAAEECQAIACYHfAADAAEECQAJAIoNpAADAAEECQAKBMIA/gADAAEECQALAGIOLgADAAEECQAMAGYOkAADAAEECQANBrQG8AADAAEECQAOAFwO9gADAAEECgACAAwW+wADAAEECwACABAXRwADAAEEDAACAAwW+wADAAEEDgACAAwXVwADAAEEEAACAA4XZwADAAEEEwACABIXdQADAAEEFAACAAwW+wADAAEEFQACABAW+wADAAEEFgACAAwW+wADAAEEGQACAA4XhwADAAEEGwACABAXVwADAAEEHQACAAwW+wADAAEEHwACAAwW+wADAAEEJAACAA4XlQADAAEEKgACAA4XowADAAEELQACAA4XsQADAAEICgACAAwW+wADAAEIFgACAAwW+wADAAEMCgACAAwW+wADAAEMDAACAAwW+wBUAHkAcABlAGYAYQBjAGUAIACpACAAVABoAGUAIABNAG8AbgBvAHQAeQBwAGUAIABDAG8AcgBwAG8AcgBhAHQAaQBvAG4AIABwAGwAYwAuACAARABhAHQAYQAgAKkAIABUAGgAZQAgAE0AbwBuAG8AdAB5AHAAZQAgAEMAbwByAHAAbwByAGEAdABpAG8AbgAgAHAAbABjAC8AVAB5AHAAZQAgAFMAbwBsAHUAdABpAG8AbgBzACAASQBuAGMALgAgADEAOQA5ADAALQAxADkAOQAyAC4AIABBAGwAbAAgAFIAaQBnAGgAdABzACAAUgBlAHMAZQByAHYAZQBkAEMAbwBuAHQAZQBtAHAAbwByAGEAcgB5ACAAcwBhAG4AcwAgAHMAZQByAGkAZgAgAGQAZQBzAGkAZwBuACwAIABBAHIAaQBhAGwAIABjAG8AbgB0AGEAaQBuAHMAIABtAG8AcgBlACAAaAB1AG0AYQBuAGkAcwB0ACAAYwBoAGEAcgBhAGMAdABlAHIAaQBzAHQAaQBjAHMAIAB0AGgAYQBuACAAbQBhAG4AeQAgAG8AZgAgAGkAdABzACAAcAByAGUAZABlAGMAZQBzAHMAbwByAHMAIABhAG4AZAAgAGEAcwAgAHMAdQBjAGgAIABpAHMAIABtAG8AcgBlACAAaQBuACAAdAB1AG4AZQAgAHcAaQB0AGgAIAB0AGgAZQAgAG0AbwBvAGQAIABvAGYAIAB0AGgAZQAgAGwAYQBzAHQAIABkAGUAYwBhAGQAZQBzACAAbwBmACAAdABoAGUAIAB0AHcAZQBuAHQAaQBlAHQAaAAgAGMAZQBuAHQAdQByAHkALgAgACAAVABoAGUAIABvAHYAZQByAGEAbABsACAAdAByAGUAYQB0AG0AZQBuAHQAIABvAGYAIABjAHUAcgB2AGUAcwAgAGkAcwAgAHMAbwBmAHQAZQByACAAYQBuAGQAIABmAHUAbABsAGUAcgAgAHQAaABhAG4AIABpAG4AIABtAG8AcwB0ACAAaQBuAGQAdQBzAHQAcgBpAGEAbAAgAHMAdAB5AGwAZQAgAHMAYQBuAHMAIABzAGUAcgBpAGYAIABmAGEAYwBlAHMALgAgACAAVABlAHIAbQBpAG4AYQBsACAAcwB0AHIAbwBrAGUAcwAgAGEAcgBlACAAYwB1AHQAIABvAG4AIAB0AGgAZQAgAGQAaQBhAGcAbwBuAGEAbAAgAHcAaABpAGMAaAAgAGgAZQBsAHAAcwAgAHQAbwAgAGcAaQB2AGUAIAB0AGgAZQAgAGYAYQBjAGUAIABhACAAbABlAHMAcwAgAG0AZQBjAGgAYQBuAGkAYwBhAGwAIABhAHAAcABlAGEAcgBhAG4AYwBlAC4AIAAgAEEAcgBpAGEAbAAgAGkAcwAgAGEAbgAgAGUAeAB0AHIAZQBtAGUAbAB5ACAAdgBlAHIAcwBhAHQAaQBsAGUAIABmAGEAbQBpAGwAeQAgAG8AZgAgAHQAeQBwAGUAZgBhAGMAZQBzACAAdwBoAGkAYwBoACAAYwBhAG4AIABiAGUAIAB1AHMAZQBkACAAdwBpAHQAaAAgAGUAcQB1AGEAbAAgAHMAdQBjAGMAZQBzAHMAIABmAG8AcgAgAHQAZQB4AHQAIABzAGUAdAB0AGkAbgBnACAAaQBuACAAcgBlAHAAbwByAHQAcwAsACAAcAByAGUAcwBlAG4AdABhAHQAaQBvAG4AcwAsACAAbQBhAGcAYQB6AGkAbgBlAHMAIABlAHQAYwAsACAAYQBuAGQAIABmAG8AcgAgAGQAaQBzAHAAbABhAHkAIAB1AHMAZQAgAGkAbgAgAG4AZQB3AHMAcABhAHAAZQByAHMALAAgAGEAZAB2AGUAcgB0AGkAcwBpAG4AZwAgAGEAbgBkACAAcAByAG8AbQBvAHQAaQBvAG4AcwAuAE0AbwBuAG8AdAB5AHAAZQA6AEEAcgBpAGEAbAAgAFIAZQBnAHUAbABhAHIAOgBWAGUAcgBzAGkAbwBuACAAMwAuADAAMAAgACgATQBpAGMAcgBvAHMAbwBmAHQAKQBBAHIAaQBhAGwATQBUAEEAcgBpAGEAbACuACAAVAByAGEAZABlAG0AYQByAGsAIABvAGYAIABUAGgAZQAgAE0AbwBuAG8AdAB5AHAAZQAgAEMAbwByAHAAbwByAGEAdABpAG8AbgAgAHAAbABjACAAcgBlAGcAaQBzAHQAZQByAGUAZAAgAGkAbgAgAHQAaABlACAAVQBTACAAUABhAHQAIAAmACAAVABNACAATwBmAGYALgAgAGEAbgBkACAAZQBsAHMAZQB3AGgAZQByAGUALgBOAE8AVABJAEYASQBDAEEAVABJAE8ATgAgAE8ARgAgAEwASQBDAEUATgBTAEUAIABBAEcAUgBFAEUATQBFAE4AVAANAAoADQAKAFQAaABpAHMAIAB0AHkAcABlAGYAYQBjAGUAIABpAHMAIAB0AGgAZQAgAHAAcgBvAHAAZQByAHQAeQAgAG8AZgAgAE0AbwBuAG8AdAB5AHAAZQAgAFQAeQBwAG8AZwByAGEAcABoAHkAIABhAG4AZAAgAGkAdABzACAAdQBzAGUAIABiAHkAIAB5AG8AdQAgAGkAcwAgAGMAbwB2AGUAcgBlAGQAIAB1AG4AZABlAHIAIAB0AGgAZQAgAHQAZQByAG0AcwAgAG8AZgAgAGEAIABsAGkAYwBlAG4AcwBlACAAYQBnAHIAZQBlAG0AZQBuAHQALgAgAFkAbwB1ACAAaABhAHYAZQAgAG8AYgB0AGEAaQBuAGUAZAAgAHQAaABpAHMAIAB0AHkAcABlAGYAYQBjAGUAIABzAG8AZgB0AHcAYQByAGUAIABlAGkAdABoAGUAcgAgAGQAaQByAGUAYwB0AGwAeQAgAGYAcgBvAG0AIABNAG8AbgBvAHQAeQBwAGUAIABvAHIAIAB0AG8AZwBlAHQAaABlAHIAIAB3AGkAdABoACAAcwBvAGYAdAB3AGEAcgBlACAAZABpAHMAdAByAGkAYgB1AHQAZQBkACAAYgB5ACAAbwBuAGUAIABvAGYAIABNAG8AbgBvAHQAeQBwAGUAJwBzACAAbABpAGMAZQBuAHMAZQBlAHMALgANAAoADQAKAFQAaABpAHMAIABzAG8AZgB0AHcAYQByAGUAIABpAHMAIABhACAAdgBhAGwAdQBhAGIAbABlACAAYQBzAHMAZQB0ACAAbwBmACAATQBvAG4AbwB0AHkAcABlAC4AIABVAG4AbABlAHMAcwAgAHkAbwB1ACAAaABhAHYAZQAgAGUAbgB0AGUAcgBlAGQAIABpAG4AdABvACAAYQAgAHMAcABlAGMAaQBmAGkAYwAgAGwAaQBjAGUAbgBzAGUAIABhAGcAcgBlAGUAbQBlAG4AdAAgAGcAcgBhAG4AdABpAG4AZwAgAHkAbwB1ACAAYQBkAGQAaQB0AGkAbwBuAGEAbAAgAHIAaQBnAGgAdABzACwAIAB5AG8AdQByACAAdQBzAGUAIABvAGYAIAB0AGgAaQBzACAAcwBvAGYAdAB3AGEAcgBlACAAaQBzACAAbABpAG0AaQB0AGUAZAAgAHQAbwAgAHkAbwB1AHIAIAB3AG8AcgBrAHMAdABhAHQAaQBvAG4AIABmAG8AcgAgAHkAbwB1AHIAIABvAHcAbgAgAHAAdQBiAGwAaQBzAGgAaQBuAGcAIAB1AHMAZQAuACAAWQBvAHUAIABtAGEAeQAgAG4AbwB0ACAAYwBvAHAAeQAgAG8AcgAgAGQAaQBzAHQAcgBpAGIAdQB0AGUAIAB0AGgAaQBzACAAcwBvAGYAdAB3AGEAcgBlAC4ADQAKAA0ACgBJAGYAIAB5AG8AdQAgAGgAYQB2AGUAIABhAG4AeQAgAHEAdQBlAHMAdABpAG8AbgAgAGMAbwBuAGMAZQByAG4AaQBuAGcAIAB5AG8AdQByACAAcgBpAGcAaAB0AHMAIAB5AG8AdQAgAHMAaABvAHUAbABkACAAcgBlAHYAaQBlAHcAIAB0AGgAZQAgAGwAaQBjAGUAbgBzAGUAIABhAGcAcgBlAGUAbQBlAG4AdAAgAHkAbwB1ACAAcgBlAGMAZQBpAHYAZQBkACAAdwBpAHQAaAAgAHQAaABlACAAcwBvAGYAdAB3AGEAcgBlACAAbwByACAAYwBvAG4AdABhAGMAdAAgAE0AbwBuAG8AdAB5AHAAZQAgAGYAbwByACAAYQAgAGMAbwBwAHkAIABvAGYAIAB0AGgAZQAgAGwAaQBjAGUAbgBzAGUAIABhAGcAcgBlAGUAbQBlAG4AdAAuAA0ACgANAAoATQBvAG4AbwB0AHkAcABlACAAYwBhAG4AIABiAGUAIABjAG8AbgB0AGEAYwB0AGUAZAAgAGEAdAA6AA0ACgANAAoAVQBTAEEAIAAtACAAKAA4ADQANwApACAANwAxADgALQAwADQAMAAwAAkACQBVAEsAIAAtACAAMAAxADEANAA0ACAAMAAxADcAMwA3ACAANwA2ADUAOQA1ADkADQAKAGgAdAB0AHAAOgAvAC8AdwB3AHcALgBtAG8AbgBvAHQAeQBwAGUALgBjAG8AbQBNAG8AbgBvAHQAeQBwAGUAIABUAHkAcABlACAARAByAGEAdwBpAG4AZwAgAE8AZgBmAGkAYwBlACAALQAgAFIAbwBiAGkAbgAgAE4AaQBjAGgAbwBsAGEAcwAsACAAUABhAHQAcgBpAGMAaQBhACAAUwBhAHUAbgBkAGUAcgBzACAAMQA5ADgAMgBoAHQAdABwADoALwAvAHcAdwB3AC4AbQBvAG4AbwB0AHkAcABlAC4AYwBvAG0ALwBoAHQAbQBsAC8AbQB0AG4AYQBtAGUALwBtAHMAXwBhAHIAaQBhAGwALgBoAHQAbQBsAGgAdAB0AHAAOgAvAC8AdwB3AHcALgBtAG8AbgBvAHQAeQBwAGUALgBjAG8AbQAvAGgAdABtAGwALwBtAHQAbgBhAG0AZQAvAG0AcwBfAHcAZQBsAGMAbwBtAGUALgBoAHQAbQBsAGgAdAB0AHAAOgAvAC8AdwB3AHcALgBtAG8AbgBvAHQAeQBwAGUALgBjAG8AbQAvAGgAdABtAGwALwB0AHkAcABlAC8AbABpAGMAZQBuAHMAZQAuAGgAdABtAGxUeXBlZmFjZSCpIFRoZSBNb25vdHlwZSBDb3Jwb3JhdGlvbiBwbGMuIERhdGEgqSBUaGUgTW9ub3R5cGUgQ29ycG9yYXRpb24gcGxjL1R5cGUgU29sdXRpb25zIEluYy4gMTk5MC0xOTkyLiBBbGwgUmlnaHRzIFJlc2VydmVkQ29udGVtcG9yYXJ5IHNhbnMgc2VyaWYgZGVzaWduLCBBcmlhbCBjb250YWlucyBtb3JlIGh1bWFuaXN0IGNoYXJhY3RlcmlzdGljcyB0aGFuIG1hbnkgb2YgaXRzIHByZWRlY2Vzc29ycyBhbmQgYXMgc3VjaCBpcyBtb3JlIGluIHR1bmUgd2l0aCB0aGUgbW9vZCBvZiB0aGUgbGFzdCBkZWNhZGVzIG9mIHRoZSB0d2VudGlldGggY2VudHVyeS4gIFRoZSBvdmVyYWxsIHRyZWF0bWVudCBvZiBjdXJ2ZXMgaXMgc29mdGVyIGFuZCBmdWxsZXIgdGhhbiBpbiBtb3N0IGluZHVzdHJpYWwgc3R5bGUgc2FucyBzZXJpZiBmYWNlcy4gIFRlcm1pbmFsIHN0cm9rZXMgYXJlIGN1dCBvbiB0aGUgZGlhZ29uYWwgd2hpY2ggaGVscHMgdG8gZ2l2ZSB0aGUgZmFjZSBhIGxlc3MgbWVjaGFuaWNhbCBhcHBlYXJhbmNlLiAgQXJpYWwgaXMgYW4gZXh0cmVtZWx5IHZlcnNhdGlsZSBmYW1pbHkgb2YgdHlwZWZhY2VzIHdoaWNoIGNhbiBiZSB1c2VkIHdpdGggZXF1YWwgc3VjY2VzcyBmb3IgdGV4dCBzZXR0aW5nIGluIHJlcG9ydHMsIHByZXNlbnRhdGlvbnMsIG1hZ2F6aW5lcyBldGMsIGFuZCBmb3IgZGlzcGxheSB1c2UgaW4gbmV3c3BhcGVycywgYWR2ZXJ0aXNpbmcgYW5kIHByb21vdGlvbnMuTW9ub3R5cGU6QXJpYWwgUmVndWxhcjpWZXJzaW9uIDMuMDAgKE1pY3Jvc29mdClBcmlhbE1UQXJpYWyoIFRyYWRlbWFyayBvZiBUaGUgTW9ub3R5cGUgQ29ycG9yYXRpb24gcGxjIHJlZ2lzdGVyZWQgaW4gdGhlIFVTIFBhdCAmIFRNIE9mZi4gYW5kIGVsc2V3aGVyZS5OT1RJRklDQVRJT04gT0YgTElDRU5TRSBBR1JFRU1FTlQNCg0KVGhpcyB0eXBlZmFjZSBpcyB0aGUgcHJvcGVydHkgb2YgTW9ub3R5cGUgVHlwb2dyYXBoeSBhbmQgaXRzIHVzZSBieSB5b3UgaXMgY292ZXJlZCB1bmRlciB0aGUgdGVybXMgb2YgYSBsaWNlbnNlIGFncmVlbWVudC4gWW91IGhhdmUgb2J0YWluZWQgdGhpcyB0eXBlZmFjZSBzb2Z0d2FyZSBlaXRoZXIgZGlyZWN0bHkgZnJvbSBNb25vdHlwZSBvciB0b2dldGhlciB3aXRoIHNvZnR3YXJlIGRpc3RyaWJ1dGVkIGJ5IG9uZSBvZiBNb25vdHlwZSdzIGxpY2Vuc2Vlcy4NCg0KVGhpcyBzb2Z0d2FyZSBpcyBhIHZhbHVhYmxlIGFzc2V0IG9mIE1vbm90eXBlLiBVbmxlc3MgeW91IGhhdmUgZW50ZXJlZCBpbnRvIGEgc3BlY2lmaWMgbGljZW5zZSBhZ3JlZW1lbnQgZ3JhbnRpbmcgeW91IGFkZGl0aW9uYWwgcmlnaHRzLCB5b3VyIHVzZSBvZiB0aGlzIHNvZnR3YXJlIGlzIGxpbWl0ZWQgdG8geW91ciB3b3Jrc3RhdGlvbiBmb3IgeW91ciBvd24gcHVibGlzaGluZyB1c2UuIFlvdSBtYXkgbm90IGNvcHkgb3IgZGlzdHJpYnV0ZSB0aGlzIHNvZnR3YXJlLg0KDQpJZiB5b3UgaGF2ZSBhbnkgcXVlc3Rpb24gY29uY2VybmluZyB5b3VyIHJpZ2h0cyB5b3Ugc2hvdWxkIHJldmlldyB0aGUgbGljZW5zZSBhZ3JlZW1lbnQgeW91IHJlY2VpdmVkIHdpdGggdGhlIHNvZnR3YXJlIG9yIGNvbnRhY3QgTW9ub3R5cGUgZm9yIGEgY29weSBvZiB0aGUgbGljZW5zZSBhZ3JlZW1lbnQuDQoNCk1vbm90eXBlIGNhbiBiZSBjb250YWN0ZWQgYXQ6DQoNClVTQSAtICg4NDcpIDcxOC0wNDAwCQlVSyAtIDAxMTQ0IDAxNzM3IDc2NTk1OQ0KaHR0cDovL3d3dy5tb25vdHlwZS5jb21Nb25vdHlwZSBUeXBlIERyYXdpbmcgT2ZmaWNlIC0gUm9iaW4gTmljaG9sYXMsIFBhdHJpY2lhIFNhdW5kZXJzIDE5ODJodHRwOi8vd3d3Lm1vbm90eXBlLmNvbS9odG1sL210bmFtZS9tc19hcmlhbC5odG1saHR0cDovL3d3dy5tb25vdHlwZS5jb20vaHRtbC9tdG5hbWUvbXNfd2VsY29tZS5odG1saHR0cDovL3d3dy5tb25vdHlwZS5jb20vaHRtbC90eXBlL2xpY2Vuc2UuaHRtbABOAG8AcgBtAGEAbABuAHkAbwBiAHkBDQBlAGoAbgDpAG4AbwByAG0AYQBsAFMAdABhAG4AZABhAHIAZAOaA7EDvQO/A70DuQO6A6wATgBvAHIAbQBhAGEAbABpAE4AbwByAG0A4QBsAG4AZQBOAG8AcgBtAGEAbABlAFMAdABhAG4AZABhAGEAcgBkBB4EMQRLBEcEPQRLBDkATgBhAHYAYQBkAG4AbwB0AGgBsAGhAwAAbgBnAEEAcgByAHUAbgB0AGEAAAAAAgAAAAAAAP8nAJYAAAAAAAAAAAAAAAAAAAAAAAAAAAaKAAABAgEDAAMABAAFAAYABwAIAAkACgALAAwADQAOAA8AEAARABIAEwAUABUAFgAXABgAGQAaABsAHAAdAB4AHwAgACEAIgAjACQAJQAmACcAKAApACoAKwAsAC0ALgAvADAAMQAyADMANAA1ADYANwA4ADkAOgA7ADwAPQA+AD8AQABBAEIAQwBEAEUARgBHAEgASQBKAEsATABNAE4ATwBQAFEAUgBTAFQAVQBWAFcAWABZAFoAWwBcAF0AXgBfAGAAYQBiAGMAZABlAGYAZwBoAGkAagBrAGwAbQBuAG8AcABxAHIAcwB0AHUAdgB3AHgAeQB6AHsAfAB9AH4AfwCAAIEAggCDAIQAhQCGAIcAiACJAIoAiwCMAI0AjgCPAJAAkQCSAJMAlACVAJYBBACYAJkAmgEFAJwAnQCeAQYAoAChAKIAowCkAKUApgCnAKgAqQCqAKsArQCuAK8AsACxALIAswC0ALUAtgC3ALgAuQC6ALsAvAEHAL4AvwDAAMEAwgDDAMQAxQDGAMcAyADJAMoAywDMAM0AzgDPANAA0QDTANQA1QDWANcA2ADZANoA2wDcAN0A3gDfAOAA4QDiAOMA5ADlAOYA5wDoAOkA6gDrAOwA7QDuAO8A8ADxAPIA8wD0APUA9gD3APgA+QD6APsA/AD9AP4A/wEAAQgBCQEKAQsBDAENAQ4BDwEQAREBEgETARQBFQEWARcBGAEZARoBGwEcAR0BHgEfASABIQEiASMBJAElASYBJwEoASkBKgErASwBLQEuAS8BMAExATIBMwE0ATUBNgE3ATgBOQE6ATsBPAE9AT4BPwFAAUEBQgFDAUQBRQFGAUcBSAFJAUoBSwFMAU0BTgFPAVABUQFSAVMBVAFVAVYBVwFYAVkBWgFbAVwBXQFeAV8BYAFhAWIBYwFkAWUBZgFnAWgBaQFqAWsBbAFtAW4BbwFwAXEBcgFzAXQBdQF2AXcBeAF5AXoBewF8AX0BfgF/AYABgQGCAYMBhAGFAYYBhwGIAYkBigGLAYwBjQGOAY8BkAGRAZIBkwGUAZUBlgGXAZgBmQGaAZsBnAGdAZ4BnwGgAaEBogGjAaQBpQGmAacBqAGpAaoBqwGsAa0BrgGvAbABsQGyAbMBtAG1AbYBtwG4AbkBugG7AbwBvQG+Ab8BwAHBAcIBwwHEAcUBxgHHAcgByQHKAcsBzAHNAc4BzwHQAdEB0gHTAdQB1QHWAdcB2AHZAdoB2wHcAd0B3gHfAeAB4QHiAeMB5AHlAeYB5wHoAekB6gHrAewB7QHuAe8B8AHxAfIB8wH0AfUB9gH3AfgB+QH6AfsB/AH9Af4B/wIAAgECAgIDAgQCBQIGAgcCCAIJAgoCCwIMAg0CDgIPAhACEQISAhMCFAIVAJ8CFgIXAhgCGQIaAhsCHAIdAh4CHwIgAiECIgIjAiQAlwIlAiYCJwIoAikCKgIrAiwCLQIuAi8CMAIxAjICMwI0AjUCNgI3AjgCOQI6AjsCPAI9Aj4CPwJAAkECQgJDAkQCRQJGAkcCSAJJAkoCSwJMAk0CTgJPAlACUQJSAlMCVAJVAlYCVwJYAlkCWgJbAlwCXQJeAl8CYAJhAmICYwJkAmUCZgJnAmgCaQJqAmsCbAJtAm4CbwJwAnECcgJzAnQCdQJ2AncCeAJ5AnoCewJ8An0CfgJ/AoACgQKCAoMChAKFAoYChwKIAokCigKLAowCjQKOAo8CkAKRApIAmwKTApQClQKWApcCmAKZApoCmwKcAp0CngKfAqACoQKiAqMCpAKlAqYCpwKoAqkCqgKrAqwCrQKuAq8CsAKxArICswK0ArUCtgK3ArgCuQK6ArsCvAK9Ar4CvwLAAsECwgLDAsQCxQLGAscCyALJAsoCywLMAs0CzgLPAtAC0QLSAtMC1ALVAtYC1wLYAtkC2gLbAtwC3QLeAt8C4ALhAuIC4wLkAuUC5gLnAugC6QLqAusC7ALtAu4C7wLwAvEC8gLzAvQC9QL2AvcC+AL5AvoC+wL8Av0C/gL/AwADAQMCAwMDBAMFAwYDBwMIAwkDCgMLAwwDDQMOAw8DEAMRAxIDEwMUAxUDFgMXAxgDGQMaAxsDHAMdAx4DHwMgAyEDIgMjAyQDJQMmAycDKAMpAyoDKwMsAy0DLgMvAzADMQMyAzMDNAM1AzYDNwM4AzkDOgM7AzwDPQM+Az8DQANBA0IDQwNEA0UDRgNHA0gDSQNKA0sDTANNA04DTwNQA1EDUgNTA1QDVQNWA1cDWANZA1oDWwNcA10DXgNfA2ADYQNiA2MAvQNkA2UDZgNnA2gDaQNqA2sDbANtA24DbwNwA3EDcgNzA3QDdQN2A3cDeAN5A3oDewN8A30DfgN/A4ADgQOCA4MDhAOFA4YDhwOIA4kDigOLA4wDjQOOA48DkAORA5IDkwOUA5UDlgOXA5gDmQOaA5sDnAOdA54DnwOgA6EDogOjA6QDpQOmA6cDqAOpA6oDqwOsA60DrgOvA7ADsQOyA7MDtAO1A7YDtwO4A7kDugO7A7wDvQO+A78DwAPBA8IDwwPEA8UDxgPHA8gDyQPKA8sDzAPNA84DzwPQA9ED0gPTA9QD1QPWA9cD2APZA9oD2wPcA90D3gPfA+AD4QPiA+MD5APlA+YD5wPoA+kD6gPrA+wD7QPuA+8D8APxA/ID8wP0A/UD9gP3A/gD+QP6A/sD/AP9A/4D/wQABAEEAgQDBAQEBQQGBAcECAQJBAoECwQMBA0EDgQPBBAEEQQSBBMEFAQVBBYEFwQYBBkEGgQbBBwEHQQeBB8EIAQhBCIEIwQkBCUEJgQnBCgEKQQqBCsELAQtBC4ELwQwBDEEMgQzBDQENQQ2BDcEOAQ5BDoEOwQ8BD0EPgQ/BEAEQQRCBEMERARFBEYERwRIBEkESgRLBEwETQROBE8EUARRBFIEUwRUBFUEVgRXBFgEWQRaBFsEXARdBF4EXwRgBGEEYgRjBGQEZQRmBGcEaARpBGoEawRsBG0EbgRvBHAEcQRyBHMEdAR1BHYEdwR4BHkEegR7BHwEfQR+BH8EgASBBIIEgwSEBIUEhgSHBIgEiQSKBIsEjASNBI4EjwSQBJEEkgSTBJQElQSWBJcEmASZBJoEmwScBJ0EngSfBKAEoQSiBKMEpASlBKYEpwSoBKkEqgSrBKwErQSuBK8EsASxBLIEswS0BLUEtgS3BLgEuQS6BLsEvAS9BL4EvwTABMEEwgTDBMQExQTGBMcEyATJBMoEywTMBM0EzgTPBNAE0QTSBNME1ATVBNYE1wTYBNkE2gTbBNwE3QTeBN8E4AThBOIE4wTkBOUE5gTnBOgE6QTqBOsE7ATtBO4E7wTwBPEE8gTzBPQE9QT2BPcE+AT5BPoE+wT8BP0E/gT/BQAFAQUCBQMFBAUFBQYFBwUIBQkFCgULBQwFDQUOBQ8FEAURBRIFEwUUBRUFFgUXBRgFGQUaBRsFHAUdBR4FHwUgBSEFIgUjBSQFJQUmBScFKAUpBSoFKwUsBS0FLgUvBTAFMQUyBTMFNAU1BTYFNwU4BTkFOgU7BTwFPQU+BT8FQAVBBUIFQwVEBUUFRgVHBUgFSQVKBUsFTAVNBU4FTwVQBVEFUgVTBVQFVQVWBVcFWAVZBVoFWwVcBV0FXgVfBWAFYQViBWMFZAVlBWYFZwVoBWkFagVrBWwFbQVuBW8FcAVxBXIFcwV0BXUFdgV3BXgFeQV6BXsFfAV9BX4FfwWABYEFggWDBYQFhQWGBYcFiAWJBYoFiwWMBY0FjgWPBZAFkQWSBZMFlAWVBZYFlwWYBZkFmgWbBZwFnQWeBZ8FoAWhBaIFowWkBaUFpgWnBagFqQWqBasFrAWtBa4FrwWwBbEFsgWzBbQFtQW2BbcFuAW5BboFuwW8Bb0FvgW/BcAFwQXCBcMFxAXFBcYFxwXIBckFygXLBcwFzQXOBc8F0AXRBdIF0wXUBdUF1gXXBdgF2QXaBdsF3AXdBd4F3wXgBeEF4gXjBeQF5QXmBecF6AXpBeoF6wXsBe0F7gXvBfAF8QXyBfMF9AX1BfYF9wX4BfkF+gX7BfwF/QX+Bf8GAAYBBgIGAwYEBgUGBgYHBggGCQYKBgsGDAYNBg4GDwYQBhEGEgYTBhQGFQYWBhcGGAYZBhoGGwYcBh0GHgYfBiAGIQYiBiMGJAYlBiYGJwYoBikGKgYrBiwGLQYuBi8GMAYxBjIGMwY0BjUGNgY3BjgGOQY6BjsGPAY9Bj4GPwZABkEGQgZDBkQGRQZGBkcGSAZJBkoGSwZMBk0GTgZPBlAGUQZSBlMGVAZVBlYGVwZYBlkGWgZbBlwGXQZeBl8GYAZhBmIGYwZkBmUGZgZnBmgGaQZqBmsGbAZtBm4GbwZwBnEGcgZzBnQGdQZ2BncGeAZ5BnoGewZ8Bn0GfgZ/BoAGgQaCBoMGhAaFBoYGhwaIBokGigaLBowGjQaOBS5udWxsEG5vbm1hcmtpbmdyZXR1cm4DbXUxA3BpMQNPaG0ERXVybwdkbWFjcm9uCW92ZXJzY29yZQZtaWRkb3QGQWJyZXZlBmFicmV2ZQdBb2dvbmVrB2FvZ29uZWsGRGNhcm9uBmRjYXJvbgZEc2xhc2gHRW9nb25lawdlb2dvbmVrBkVjYXJvbgZlY2Fyb24GTGFjdXRlBmxhY3V0ZQZMY2Fyb24GbGNhcm9uBExkb3QEbGRvdAZOYWN1dGUGbmFjdXRlBk5jYXJvbgZuY2Fyb24JT2RibGFjdXRlCW9kYmxhY3V0ZQZSYWN1dGUGcmFjdXRlBlJjYXJvbgZyY2Fyb24GU2FjdXRlBnNhY3V0ZQhUY2VkaWxsYQh0Y2VkaWxsYQZUY2Fyb24GdGNhcm9uBVVyaW5nBXVyaW5nCVVkYmxhY3V0ZQl1ZGJsYWN1dGUGWmFjdXRlBnphY3V0ZQRaZG90BHpkb3QFR2FtbWEFVGhldGEDUGhpBWFscGhhBWRlbHRhB2Vwc2lsb24Fc2lnbWEDdGF1A3BoaQ11bmRlcnNjb3JlZGJsCWV4Y2xhbWRibAluc3VwZXJpb3IGcGVzZXRhCWFycm93bGVmdAdhcnJvd3VwCmFycm93cmlnaHQJYXJyb3dkb3duCWFycm93Ym90aAlhcnJvd3VwZG4MYXJyb3d1cGRuYnNlCm9ydGhvZ29uYWwMaW50ZXJzZWN0aW9uC2VxdWl2YWxlbmNlBWhvdXNlDXJldmxvZ2ljYWxub3QKaW50ZWdyYWx0cAppbnRlZ3JhbGJ0CFNGMTAwMDAwCFNGMTEwMDAwCFNGMDEwMDAwCFNGMDMwMDAwCFNGMDIwMDAwCFNGMDQwMDAwCFNGMDgwMDAwCFNGMDkwMDAwCFNGMDYwMDAwCFNGMDcwMDAwCFNGMDUwMDAwCFNGNDMwMDAwCFNGMjQwMDAwCFNGNTEwMDAwCFNGNTIwMDAwCFNGMzkwMDAwCFNGMjIwMDAwCFNGMjEwMDAwCFNGMjUwMDAwCFNGNTAwMDAwCFNGNDkwMDAwCFNGMzgwMDAwCFNGMjgwMDAwCFNGMjcwMDAwCFNGMjYwMDAwCFNGMzYwMDAwCFNGMzcwMDAwCFNGNDIwMDAwCFNGMTkwMDAwCFNGMjAwMDAwCFNGMjMwMDAwCFNGNDcwMDAwCFNGNDgwMDAwCFNGNDEwMDAwCFNGNDUwMDAwCFNGNDYwMDAwCFNGNDAwMDAwCFNGNTQwMDAwCFNGNTMwMDAwCFNGNDQwMDAwB3VwYmxvY2sHZG5ibG9jawVibG9jawdsZmJsb2NrB3J0YmxvY2sHbHRzaGFkZQVzaGFkZQdka3NoYWRlCWZpbGxlZGJveApmaWxsZWRyZWN0B3RyaWFndXAHdHJpYWdydAd0cmlhZ2RuB3RyaWFnbGYGY2lyY2xlCWludmJ1bGxldAlpbnZjaXJjbGUJc21pbGVmYWNlDGludnNtaWxlZmFjZQNzdW4GZmVtYWxlBG1hbGUFc3BhZGUEY2x1YgVoZWFydAdkaWFtb25kC211c2ljYWxub3RlDm11c2ljYWxub3RlZGJsAklKAmlqC25hcG9zdHJvcGhlBm1pbnV0ZQZzZWNvbmQJYWZpaTYxMjQ4CWFmaWk2MTI4OQZIMjIwNzMGSDE4NTQzBkgxODU1MQZIMTg1MzMKb3BlbmJ1bGxldAdBbWFjcm9uB2FtYWNyb24LQ2NpcmN1bWZsZXgLY2NpcmN1bWZsZXgEQ2RvdARjZG90B0VtYWNyb24HZW1hY3JvbgZFYnJldmUGZWJyZXZlBEVkb3QEZWRvdAtHY2lyY3VtZmxleAtnY2lyY3VtZmxleARHZG90BGdkb3QIR2NlZGlsbGEIZ2NlZGlsbGELSGNpcmN1bWZsZXgLaGNpcmN1bWZsZXgESGJhcgRoYmFyBkl0aWxkZQZpdGlsZGUHSW1hY3JvbgdpbWFjcm9uBklicmV2ZQZpYnJldmUHSW9nb25lawdpb2dvbmVrC0pjaXJjdW1mbGV4C2pjaXJjdW1mbGV4CEtjZWRpbGxhCGtjZWRpbGxhDGtncmVlbmxhbmRpYwhMY2VkaWxsYQhsY2VkaWxsYQhOY2VkaWxsYQhuY2VkaWxsYQNFbmcDZW5nB09tYWNyb24Hb21hY3JvbgZPYnJldmUGb2JyZXZlCFJjZWRpbGxhCHJjZWRpbGxhC1NjaXJjdW1mbGV4C3NjaXJjdW1mbGV4BFRiYXIEdGJhcgZVdGlsZGUGdXRpbGRlB1VtYWNyb24HdW1hY3JvbgZVYnJldmUGdWJyZXZlB1VvZ29uZWsHdW9nb25lawtXY2lyY3VtZmxleAt3Y2lyY3VtZmxleAtZY2lyY3VtZmxleAt5Y2lyY3VtZmxleAVsb25ncwpBcmluZ2FjdXRlCmFyaW5nYWN1dGUHQUVhY3V0ZQdhZWFjdXRlC09zbGFzaGFjdXRlC29zbGFzaGFjdXRlCWFub3RlbGVpYQZXZ3JhdmUGd2dyYXZlBldhY3V0ZQZ3YWN1dGUJV2RpZXJlc2lzCXdkaWVyZXNpcwZZZ3JhdmUGeWdyYXZlDXF1b3RlcmV2ZXJzZWQJcmFkaWNhbGV4CWFmaWkwODk0MQllc3RpbWF0ZWQJb25lZWlnaHRoDHRocmVlZWlnaHRocwtmaXZlZWlnaHRocwxzZXZlbmVpZ2h0aHMLY29tbWFhY2NlbnQQdW5kZXJjb21tYWFjY2VudAV0b25vcw1kaWVyZXNpc3Rvbm9zCkFscGhhdG9ub3MMRXBzaWxvbnRvbm9zCEV0YXRvbm9zCUlvdGF0b25vcwxPbWljcm9udG9ub3MMVXBzaWxvbnRvbm9zCk9tZWdhdG9ub3MRaW90YWRpZXJlc2lzdG9ub3MFQWxwaGEEQmV0YQVEZWx0YQdFcHNpbG9uBFpldGEDRXRhBElvdGEFS2FwcGEGTGFtYmRhAk11Ak51AlhpB09taWNyb24CUGkDUmhvBVNpZ21hA1RhdQdVcHNpbG9uA0NoaQNQc2kMSW90YWRpZXJlc2lzD1Vwc2lsb25kaWVyZXNpcwphbHBoYXRvbm9zDGVwc2lsb250b25vcwhldGF0b25vcwlpb3RhdG9ub3MUdXBzaWxvbmRpZXJlc2lzdG9ub3MEYmV0YQVnYW1tYQR6ZXRhA2V0YQV0aGV0YQRpb3RhBWthcHBhBmxhbWJkYQJudQJ4aQdvbWljcm9uA3JobwZzaWdtYTEHdXBzaWxvbgNjaGkDcHNpBW9tZWdhDGlvdGFkaWVyZXNpcw91cHNpbG9uZGllcmVzaXMMb21pY3JvbnRvbm9zDHVwc2lsb250b25vcwpvbWVnYXRvbm9zCWFmaWkxMDAyMwlhZmlpMTAwNTEJYWZpaTEwMDUyCWFmaWkxMDA1MwlhZmlpMTAwNTQJYWZpaTEwMDU1CWFmaWkxMDA1NglhZmlpMTAwNTcJYWZpaTEwMDU4CWFmaWkxMDA1OQlhZmlpMTAwNjAJYWZpaTEwMDYxCWFmaWkxMDA2MglhZmlpMTAxNDUJYWZpaTEwMDE3CWFmaWkxMDAxOAlhZmlpMTAwMTkJYWZpaTEwMDIwCWFmaWkxMDAyMQlhZmlpMTAwMjIJYWZpaTEwMDI0CWFmaWkxMDAyNQlhZmlpMTAwMjYJYWZpaTEwMDI3CWFmaWkxMDAyOAlhZmlpMTAwMjkJYWZpaTEwMDMwCWFmaWkxMDAzMQlhZmlpMTAwMzIJYWZpaTEwMDMzCWFmaWkxMDAzNAlhZmlpMTAwMzUJYWZpaTEwMDM2CWFmaWkxMDAzNwlhZmlpMTAwMzgJYWZpaTEwMDM5CWFmaWkxMDA0MAlhZmlpMTAwNDEJYWZpaTEwMDQyCWFmaWkxMDA0MwlhZmlpMTAwNDQJYWZpaTEwMDQ1CWFmaWkxMDA0NglhZmlpMTAwNDcJYWZpaTEwMDQ4CWFmaWkxMDA0OQlhZmlpMTAwNjUJYWZpaTEwMDY2CWFmaWkxMDA2NwlhZmlpMTAwNjgJYWZpaTEwMDY5CWFmaWkxMDA3MAlhZmlpMTAwNzIJYWZpaTEwMDczCWFmaWkxMDA3NAlhZmlpMTAwNzUJYWZpaTEwMDc2CWFmaWkxMDA3NwlhZmlpMTAwNzgJYWZpaTEwMDc5CWFmaWkxMDA4MAlhZmlpMTAwODEJYWZpaTEwMDgyCWFmaWkxMDA4MwlhZmlpMTAwODQJYWZpaTEwMDg1CWFmaWkxMDA4NglhZmlpMTAwODcJYWZpaTEwMDg4CWFmaWkxMDA4OQlhZmlpMTAwOTAJYWZpaTEwMDkxCWFmaWkxMDA5MglhZmlpMTAwOTMJYWZpaTEwMDk0CWFmaWkxMDA5NQlhZmlpMTAwOTYJYWZpaTEwMDk3CWFmaWkxMDA3MQlhZmlpMTAwOTkJYWZpaTEwMTAwCWFmaWkxMDEwMQlhZmlpMTAxMDIJYWZpaTEwMTAzCWFmaWkxMDEwNAlhZmlpMTAxMDUJYWZpaTEwMTA2CWFmaWkxMDEwNwlhZmlpMTAxMDgJYWZpaTEwMTA5CWFmaWkxMDExMAlhZmlpMTAxOTMJYWZpaTEwMDUwCWFmaWkxMDA5OAlhZmlpMDAyMDgJYWZpaTYxMzUyBXNoZXZhCmhhdGFmc2Vnb2wKaGF0YWZwYXRhaAtoYXRhZnFhbWF0cwVoaXJpcQV0c2VyZQVzZWdvbAVwYXRhaAZxYW1hdHMFaG9sYW0GcXVidXRzBmRhZ2VzaAVtZXRlZwVtYXFhZgRyYWZlBXBhc2VxB3NoaW5kb3QGc2luZG90CHNvZnBhc3VxBGFsZWYDYmV0BWdpbWVsBWRhbGV0AmhlA3ZhdgV6YXlpbgNoZXQDdGV0A3lvZAhmaW5hbGthZgNrYWYFbGFtZWQIZmluYWxtZW0DbWVtCGZpbmFsbnVuA251bgZzYW1la2gEYXlpbgdmaW5hbHBlAnBlCmZpbmFsdHNhZGkFdHNhZGkDcW9mBHJlc2gEc2hpbgN0YXYJZG91YmxldmF2BnZhdnlvZAlkb3VibGV5b2QGZ2VyZXNoCWdlcnNoYXlpbQ1uZXdzaGVxZWxzaWduCnZhdnNoaW5kb3QNZmluYWxrYWZzaGV2YQ5maW5hbGthZnFhbWF0cwpsYW1lZGhvbGFtEGxhbWVkaG9sYW1kYWdlc2gHYWx0YXlpbgtzaGluc2hpbmRvdApzaGluc2luZG90EXNoaW5kYWdlc2hzaGluZG90EHNoaW5kYWdlc2hzaW5kb3QJYWxlZnBhdGFoCmFsZWZxYW1hdHMJYWxlZm1hcGlxCWJldGRhZ2VzaAtnaW1lbGRhZ2VzaAtkYWxldGRhZ2VzaAhoZWRhZ2VzaAl2YXZkYWdlc2gLemF5aW5kYWdlc2gJdGV0ZGFnZXNoCXlvZGRhZ2VzaA5maW5hbGthZmRhZ2VzaAlrYWZkYWdlc2gLbGFtZWRkYWdlc2gJbWVtZGFnZXNoCW51bmRhZ2VzaAxzYW1la2hkYWdlc2gNZmluYWxwZWRhZ2VzaAhwZWRhZ2VzaAt0c2FkaWRhZ2VzaAlxb2ZkYWdlc2gKcmVzaGRhZ2VzaApzaGluZGFnZXNoCHRhdmRhZ2VzCHZhdmhvbGFtB2JldHJhZmUHa2FmcmFmZQZwZXJhZmUJYWxlZmxhbWVkEnplcm93aWR0aG5vbmpvaW5lcg96ZXJvd2lkdGhqb2luZXIPbGVmdHRvcmlnaHRtYXJrD3JpZ2h0dG9sZWZ0bWFyawlhZmlpNTczODgJYWZpaTU3NDAzCWFmaWk1NzQwNwlhZmlpNTc0MDkJYWZpaTU3NDQwCWFmaWk1NzQ1MQlhZmlpNTc0NTIJYWZpaTU3NDUzCWFmaWk1NzQ1NAlhZmlpNTc0NTUJYWZpaTU3NDU2CWFmaWk1NzQ1NwlhZmlpNTc0NTgJYWZpaTU3MzkyCWFmaWk1NzM5MwlhZmlpNTczOTQJYWZpaTU3Mzk1CWFmaWk1NzM5NglhZmlpNTczOTcJYWZpaTU3Mzk4CWFmaWk1NzM5OQlhZmlpNTc0MDAJYWZpaTU3NDAxCWFmaWk1NzM4MQlhZmlpNTc0NjEJYWZpaTYzMTY3CWFmaWk1NzQ1OQlhZmlpNTc1NDMJYWZpaTU3NTM0CWFmaWk1NzQ5NAlhZmlpNjI4NDMJYWZpaTYyODQ0CWFmaWk2Mjg0NQlhZmlpNjQyNDAJYWZpaTY0MjQxCWFmaWk2Mzk1NAlhZmlpNTczODIJYWZpaTY0MjQyCWFmaWk2Mjg4MQlhZmlpNTc1MDQJYWZpaTU3MzY5CWFmaWk1NzM3MAlhZmlpNTczNzEJYWZpaTU3MzcyCWFmaWk1NzM3MwlhZmlpNTczNzQJYWZpaTU3Mzc1CWFmaWk1NzM5MQlhZmlpNTc0NzEJYWZpaTU3NDYwCWFmaWk1MjI1OAlhZmlpNTc1MDYJYWZpaTYyOTU4CWFmaWk2Mjk1NglhZmlpNTI5NTcJYWZpaTU3NTA1CWFmaWk2Mjg4OQlhZmlpNjI4ODcJYWZpaTYyODg4CWFmaWk1NzUwNwlhZmlpNjI5NjEJYWZpaTYyOTU5CWFmaWk2Mjk2MAlhZmlpNTc1MDgJYWZpaTYyOTYyCWFmaWk1NzU2NwlhZmlpNjI5NjQJYWZpaTUyMzA1CWFmaWk1MjMwNglhZmlpNTc1MDkJYWZpaTYyOTY3CWFmaWk2Mjk2NQlhZmlpNjI5NjYJYWZpaTU3NTU1CWFmaWk1MjM2NAlhZmlpNjM3NTMJYWZpaTYzNzU0CWFmaWk2Mzc1OQlhZmlpNjM3NjMJYWZpaTYzNzk1CWFmaWk2Mjg5MQlhZmlpNjM4MDgJYWZpaTYyOTM4CWFmaWk2MzgxMAlhZmlpNjI5NDIJYWZpaTYyOTQ3CWFmaWk2MzgxMwlhZmlpNjM4MjMJYWZpaTYzODI0CWFmaWk2MzgzMwlhZmlpNjM4NDQJYWZpaTYyODgyCWFmaWk2Mjg4MwlhZmlpNjI4ODQJYWZpaTYyODg1CWFmaWk2Mjg4NglhZmlpNjM4NDYJYWZpaTYzODQ5B3VuaTIwMkEHdW5pMjAyQgd1bmkyMDJEB3VuaTIwMkUHdW5pMjAyQwd1bmkyMDZFCHVuaTIwNkY7B3VuaTIwNkEHdW5pMjA2Qgh1bmkyMDZDOwd1bmkyMDZEB3VuaUYwMEEHdW5pRjAwQgd1bmlGMDBDB3VuaUYwMEQHdW5pRjAwRQd1bmlGRkZDCWFmaWk2MzkwNAlhZmlpNjM5MDUJYWZpaTYzOTA2CWFmaWk2MzkwOAlhZmlpNjM5MTAJYWZpaTYzOTEyCWFmaWk2MjkyNwlhZmlpNjM5NDEJYWZpaTYyOTM5CWFmaWk2Mzk0MwlhZmlpNjI5NDMJYWZpaTYyOTQ2CWFmaWk2Mzk0NglhZmlpNjI5NTEJYWZpaTYzOTQ4CWFmaWk2Mjk1MwlhZmlpNjM5NTAJYWZpaTYzOTUxCWFmaWk2Mzk1MglhZmlpNjM5NTMJYWZpaTYzOTU2CWFmaWk2Mzk1OAlhZmlpNjM5NTkJYWZpaTYzOTYwCWFmaWk2Mzk2MQlhZmlpNjQwNDYJYWZpaTY0MDU4CWFmaWk2NDA1OQlhZmlpNjQwNjAJYWZpaTY0MDYxCWFmaWk2Mjk0NQlhZmlpNjQxODQJYWZpaTUyMzk5CWFmaWk1MjQwMAlhZmlpNjI3NTMJYWZpaTU3NDExCWFmaWk2Mjc1NAlhZmlpNTc0MTIJYWZpaTYyNzU1CWFmaWk1NzQxMwlhZmlpNjI3NTYJYWZpaTU3NDE0CWFmaWk2Mjc1OQlhZmlpNjI3NTcJYWZpaTYyNzU4CWFmaWk1NzQxNQlhZmlpNjI3NjAJYWZpaTU3NDE2CWFmaWk2Mjc2MwlhZmlpNjI3NjEJYWZpaTYyNzYyCWFmaWk1NzQxNwlhZmlpNjI3NjQJYWZpaTU3NDE4CWFmaWk2Mjc2NwlhZmlpNjI3NjUJYWZpaTYyNzY2CWFmaWk1NzQxOQlhZmlpNjI3NzAJYWZpaTYyNzY4CWFmaWk2Mjc2OQlhZmlpNTc0MjAJYWZpaTYyNzczCWFmaWk2Mjc3MQlhZmlpNjI3NzIJYWZpaTU3NDIxCWFmaWk2Mjc3NglhZmlpNjI3NzQJYWZpaTYyNzc1CWFmaWk1NzQyMglhZmlpNjI3NzkJYWZpaTYyNzc3CWFmaWk2Mjc3OAlhZmlpNTc0MjMJYWZpaTYyNzgwCWFmaWk1NzQyNAlhZmlpNjI3ODEJYWZpaTU3NDI1CWFmaWk2Mjc4MglhZmlpNTc0MjYJYWZpaTYyNzgzCWFmaWk1NzQyNwlhZmlpNjI3ODYJYWZpaTYyNzg0CWFmaWk2Mjc4NQlhZmlpNTc0MjgJYWZpaTYyNzg5CWFmaWk2Mjc4NwlhZmlpNjI3ODgJYWZpaTU3NDI5CWFmaWk2Mjc5MglhZmlpNjI3OTAJYWZpaTYyNzkxCWFmaWk1NzQzMAlhZmlpNjI3OTUJYWZpaTYyNzkzCWFmaWk2Mjc5NAlhZmlpNTc0MzEJYWZpaTYyNzk4CWFmaWk2Mjc5NglhZmlpNjI3OTcJYWZpaTU3NDMyCWFmaWk2MjgwMQlhZmlpNjI3OTkJYWZpaTYyODAwCWFmaWk1NzQzMwlhZmlpNjI4MDQJYWZpaTYyODAyCWFmaWk2MjgwMwlhZmlpNTc0MzQJYWZpaTYyODA3CWFmaWk2MjgwNQlhZmlpNjI4MDYJYWZpaTU3NDQxCWFmaWk2MjgxMAlhZmlpNjI4MDgJYWZpaTYyODA5CWFmaWk1NzQ0MglhZmlpNjI4MTMJYWZpaTYyODExCWFmaWk2MjgxMglhZmlpNTc0NDMJYWZpaTYyODE2CWFmaWk1NzQxMAlhZmlpNjI4MTUJYWZpaTU3NDQ0CWFmaWk2MjgxOQlhZmlpNjI4MTcJYWZpaTYyODE4CWFmaWk1NzQ0NQlhZmlpNjI4MjIJYWZpaTYyODIwCWFmaWk2MjgyMQlhZmlpNTc0NDYJYWZpaTYyODI1CWFmaWk2MjgyMwlhZmlpNjI4MjQJYWZpaTU3NDQ3CWFmaWk2MjgyOAlhZmlpNTc0NzAJYWZpaTYyODI3CWFmaWk1NzQ0OAlhZmlpNjI4MjkJYWZpaTU3NDQ5CWFmaWk2MjgzMAlhZmlpNTc0NTAJYWZpaTYyODMzCWFmaWk2MjgzMQlhZmlpNjI4MzIJYWZpaTYyODM0CWFmaWk2MjgzNQlhZmlpNjI4MzYJYWZpaTYyODM3CWFmaWk2MjgzOAlhZmlpNjI4MzkJYWZpaTYyODQwCWFmaWk2Mjg0MQlnbHlwaDEwMjELYWZpaTU3NTQzLTILYWZpaTU3NDU0LTILYWZpaTU3NDUxLTIJZ2x5cGgxMDI1CWdseXBoMTAyNgthZmlpNTc0NzEtMgthZmlpNTc0NTgtMgthZmlpNTc0NTctMgthZmlpNTc0OTQtMgthZmlpNTc0NTktMgthZmlpNTc0NTUtMgthZmlpNTc0NTItMglnbHlwaDEwMzQJZ2x5cGgxMDM1CWdseXBoMTAzNgthZmlpNjI4ODQtMgthZmlpNjI4ODEtMgthZmlpNjI4ODYtMgthZmlpNjI4ODMtMgthZmlpNjI4ODUtMgthZmlpNjI4ODItMgthZmlpNTc1MDQtMgthZmlpNTc0NTYtMgthZmlpNTc0NTMtMglnbHlwaDEwNDYJZ2x5cGgxMDQ3C2FmaWk1NzU0My0zC2FmaWk1NzQ1NC0zC2FmaWk1NzQ1MS0zCWdseXBoMTA1MQlnbHlwaDEwNTILYWZpaTU3NDcxLTMLYWZpaTU3NDU4LTMLYWZpaTU3NDU3LTMLYWZpaTU3NDk0LTMLYWZpaTU3NDU5LTMLYWZpaTU3NDU1LTMLYWZpaTU3NDUyLTMJZ2x5cGgxMDYwCWdseXBoMTA2MQlnbHlwaDEwNjILYWZpaTYyODg0LTMLYWZpaTYyODgxLTMLYWZpaTYyODg2LTMLYWZpaTYyODgzLTMLYWZpaTYyODg1LTMLYWZpaTYyODgyLTMLYWZpaTU3NTA0LTMLYWZpaTU3NDU2LTMLYWZpaTU3NDUzLTMJZ2x5cGgxMDcyCWdseXBoMTA3MwthZmlpNTc1NDMtNAthZmlpNTc0NTQtNAthZmlpNTc0NTEtNAlnbHlwaDEwNzcJZ2x5cGgxMDc4C2FmaWk1NzQ3MS00C2FmaWk1NzQ1OC00C2FmaWk1NzQ1Ny00C2FmaWk1NzQ5NC00C2FmaWk1NzQ1OS00C2FmaWk1NzQ1NS00C2FmaWk1NzQ1Mi00CWdseXBoMTA4NglnbHlwaDEwODcJZ2x5cGgxMDg4C2FmaWk2Mjg4NC00C2FmaWk2Mjg4MS00C2FmaWk2Mjg4Ni00C2FmaWk2Mjg4My00C2FmaWk2Mjg4NS00C2FmaWk2Mjg4Mi00C2FmaWk1NzUwNC00C2FmaWk1NzQ1Ni00C2FmaWk1NzQ1My00CWdseXBoMTA5OAlnbHlwaDEwOTkJZ2x5cGgxMTAwCWdseXBoMTEwMQlnbHlwaDExMDIJZ2x5cGgxMTAzCWdseXBoMTEwNAlnbHlwaDExMDUJZ2x5cGgxMTA2CWdseXBoMTEwNwlnbHlwaDExMDgJZ2x5cGgxMTA5CWdseXBoMTExMAlnbHlwaDExMTEJZ2x5cGgxMTEyCWdseXBoMTExMwlnbHlwaDExMTQJZ2x5cGgxMTE1CWdseXBoMTExNglnbHlwaDExMTcJZ2x5cGgxMTE4CWdseXBoMTExOQlnbHlwaDExMjAJZ2x5cGgxMTIxCWdseXBoMTEyMglnbHlwaDExMjMJZ2x5cGgxMTI0CWdseXBoMTEyNQlnbHlwaDExMjYLYWZpaTU3NDQwLTILYWZpaTU3NDQwLTMLYWZpaTU3NDQwLTQFT2hvcm4Fb2hvcm4FVWhvcm4FdWhvcm4JZ2x5cGgxMTM0CWdseXBoMTEzNQlnbHlwaDExMzYHdW5pRjAwNgd1bmlGMDA3B3VuaUYwMDkSY29tYmluaW5naG9va2Fib3ZlB3VuaUYwMTAHdW5pRjAxMwd1bmlGMDExB3VuaUYwMUMHdW5pRjAxNRRjb21iaW5pbmd0aWxkZWFjY2VudAlnbHlwaDExNDcJZ2x5cGgxMTQ4B3VuaUYwMkMIZG9uZ3NpZ24Ib25ldGhpcmQJdHdvdGhpcmRzB3VuaUYwMDgJZ2x5cGgxMTU0CWdseXBoMTE1NQd1bmlGMDBGB3VuaUYwMTIHdW5pRjAxNAd1bmlGMDE2B3VuaUYwMTcHdW5pRjAxOAd1bmlGMDE5B3VuaUYwMUEHdW5pRjAxQgd1bmlGMDFFB3VuaUYwMUYHdW5pRjAyMAd1bmlGMDIxB3VuaUYwMjIUY29tYmluaW5nZ3JhdmVhY2NlbnQUY29tYmluaW5nYWN1dGVhY2NlbnQHdW5pRjAxRBFjb21iaW5pbmdkb3RiZWxvdwd1bmlGMDIzB3VuaUYwMjkHdW5pRjAyQQd1bmlGMDJCB3VuaUYwMjQHdW5pRjAyNQd1bmlGMDI2B3VuaUYwMjcHdW5pRjAyOAd1bmlGMDJEB3VuaUYwMkUHdW5pRjAyRgd1bmlGMDMwB3VuaUYwMzEJQWRvdGJlbG93CWFkb3RiZWxvdwpBaG9va2Fib3ZlCmFob29rYWJvdmUQQWNpcmN1bWZsZXhhY3V0ZRBhY2lyY3VtZmxleGFjdXRlEEFjaXJjdW1mbGV4Z3JhdmUQYWNpcmN1bWZsZXhncmF2ZRRBY2lyY3VtZmxleGhvb2thYm92ZRRhY2lyY3VtZmxleGhvb2thYm92ZRBBY2lyY3VtZmxleHRpbGRlEGFjaXJjdW1mbGV4dGlsZGUTQWNpcmN1bWZsZXhkb3RiZWxvdxNhY2lyY3VtZmxleGRvdGJlbG93C0FicmV2ZWFjdXRlC2FicmV2ZWFjdXRlC0FicmV2ZWdyYXZlC2FicmV2ZWdyYXZlD0FicmV2ZWhvb2thYm92ZQ9hYnJldmVob29rYWJvdmULQWJyZXZldGlsZGULYWJyZXZldGlsZGUOQWJyZXZlZG90YmVsb3cOYWJyZXZlZG90YmVsb3cJRWRvdGJlbG93CWVkb3RiZWxvdwpFaG9va2Fib3ZlCmVob29rYWJvdmUGRXRpbGRlBmV0aWxkZRBFY2lyY3VtZmxleGFjdXRlEGVjaXJjdW1mbGV4YWN1dGUQRWNpcmN1bWZsZXhncmF2ZRBlY2lyY3VtZmxleGdyYXZlFEVjaXJjdW1mbGV4aG9va2Fib3ZlFGVjaXJjdW1mbGV4aG9va2Fib3ZlEEVjaXJjdW1mbGV4dGlsZGUQZWNpcmN1bWZsZXh0aWxkZRNFY2lyY3VtZmxleGRvdGJlbG93E2VjaXJjdW1mbGV4ZG90YmVsb3cKSWhvb2thYm92ZQppaG9va2Fib3ZlCUlkb3RiZWxvdwlpZG90YmVsb3cJT2RvdGJlbG93CW9kb3RiZWxvdwpPaG9va2Fib3ZlCm9ob29rYWJvdmUQT2NpcmN1bWZsZXhhY3V0ZRBvY2lyY3VtZmxleGFjdXRlEE9jaXJjdW1mbGV4Z3JhdmUQb2NpcmN1bWZsZXhncmF2ZRRPY2lyY3VtZmxleGhvb2thYm92ZRRvY2lyY3VtZmxleGhvb2thYm92ZRBPY2lyY3VtZmxleHRpbGRlEG9jaXJjdW1mbGV4dGlsZGUTT2NpcmN1bWZsZXhkb3RiZWxvdxNvY2lyY3VtZmxleGRvdGJlbG93Ck9ob3JuYWN1dGUKb2hvcm5hY3V0ZQpPaG9ybmdyYXZlCm9ob3JuZ3JhdmUOT2hvcm5ob29rYWJvdmUOb2hvcm5ob29rYWJvdmUKT2hvcm50aWxkZQpvaG9ybnRpbGRlDU9ob3JuZG90YmVsb3cNb2hvcm5kb3RiZWxvdwlVZG90YmVsb3cJdWRvdGJlbG93ClVob29rYWJvdmUKdWhvb2thYm92ZQpVaG9ybmFjdXRlCnVob3JuYWN1dGUKVWhvcm5ncmF2ZQp1aG9ybmdyYXZlDlVob3JuaG9va2Fib3ZlDnVob3JuaG9va2Fib3ZlClVob3JudGlsZGUKdWhvcm50aWxkZQ1VaG9ybmRvdGJlbG93DXVob3JuZG90YmVsb3cJWWRvdGJlbG93CXlkb3RiZWxvdwpZaG9va2Fib3ZlCnlob29rYWJvdmUGWXRpbGRlBnl0aWxkZQd1bmkwMUNEB3VuaTAxQ0UHdW5pMDFDRgd1bmkwMUQwB3VuaTAxRDEHdW5pMDFEMgd1bmkwMUQzB3VuaTAxRDQHdW5pMDFENQd1bmkwMUQ2B3VuaTAxRDcHdW5pMDFEOAd1bmkwMUQ5B3VuaTAxREEHdW5pMDFEQgd1bmkwMURDCWdseXBoMTI5MglnbHlwaDEyOTMJZ2x5cGgxMjk0CWdseXBoMTI5NQd1bmkwNDkyB3VuaTA0OTMHdW5pMDQ5Ngd1bmkwNDk3B3VuaTA0OUEHdW5pMDQ5Qgd1bmkwNDlDB3VuaTA0OUQHdW5pMDRBMgd1bmkwNEEzB3VuaTA0QUUHdW5pMDRBRgd1bmkwNEIwB3VuaTA0QjEHdW5pMDRCMgd1bmkwNEIzB3VuaTA0QjgHdW5pMDRCOQd1bmkwNEJBB3VuaTA0QkIHdW5pMDE4Rgd1bmkwMjU5B3VuaTA0RTgHdW5pMDRFOQlnbHlwaDEzMjAJZ2x5cGgxMzIxCWdseXBoMTMyMglnbHlwaDEzMjMJZ2x5cGgxMzI0CWdseXBoMTMyNQlnbHlwaDEzMjYJZ2x5cGgxMzI3CWdseXBoMTMyOAlnbHlwaDEzMjkJZ2x5cGgxMzMwCWdseXBoMTMzMQlnbHlwaDEzMzIJZ2x5cGgxMzMzCWdseXBoMTMzNAlnbHlwaDEzMzUHdW5pMDY1Mwd1bmkwNjU0B3VuaTA2NTUHdW5pMDY3MAd1bmkwNjcxB3VuaUZCNTEHdW5pMDY3MglnbHlwaDEzNDMHdW5pMDY3MwlnbHlwaDEzNDUHdW5pMDY3NQdnbHlwaDQ3B3VuaTA2NzYJZ2x5cGgxMzQ5B3VuaTA2NzcJZ2x5cGgxMzUxB3VuaTA2NzgFZ2x5cGgHdW5pMDY3OQd1bmlGQjY3B3VuaUZCNjgHdW5pRkI2OQd1bmkwNjdBB3VuaUZCNUYHdW5pRkI2MAd1bmlGQjYxB3VuaTA2N0IHdW5pRkI1Mwd1bmlGQjU0B3VuaUZCNTUHdW5pMDY3QwlnbHlwaDEzNjcJZ2x5cGgxMzY4CWdseXBoMTM2OQd1bmkwNjdECWdseXBoMTM3MQlnbHlwaDEzNzIJZ2x5cGgxMzczB3VuaTA2N0YHdW5pRkI2Mwd1bmlGQjY0B3VuaUZCNjUHdW5pMDY4MAd1bmlGQjVCB3VuaUZCNUMHdW5pRkI1RAd1bmkwNjgxCWdseXBoMTM4MwlnbHlwaDEzODQJZ2x5cGgxMzg1B3VuaTA2ODIJZ2x5cGgxMzg3CWdseXBoMTM4OAlnbHlwaDEzODkHdW5pMDY4Mwd1bmlGQjc3B3VuaUZCNzgHdW5pRkI3OQd1bmkwNjg0B3VuaUZCNzMHdW5pRkI3NAd1bmlGQjc1B3VuaTA2ODUJZ2x5cGgxMzk5CWdseXBoMTQwMAlnbHlwaDE0MDEHdW5pMDY4Nwd1bmlGQjdmB3VuaUZCODAHdW5pRkI4MQd1bmkwNjg4B3VuaUZCODkHdW5pMDY4OQlnbHlwaDE0MDkHdW5pMDY4QQlnbHlwaDE0MTEHdW5pMDY4QglnbHlwaDE0MTMHdW5pMDY4Qwd1bmlGQjg1B3VuaTA2OEQHdW5pRkI4Mwd1bmkwNjhFB3VuaUZCODcHdW5pMDY4RglnbHlwaDE0MjEHdW5pMDY5MAlnbHlwaDE0MjMHdW5pMDY5MQd1bmlGQjhEB3VuaTA2OTIJZ2x5cGgxNDI2B3VuaTA2OTMJZ2x5cGgxNDI5B3VuaTA2OTQJZ2x5cGgxNDMxB3VuaTA2OTUJZ2x5cGgxNDMzB3VuaTA2OTYJZ2x5cGgxNDM1B3VuaTA2OTcJZ2x5cGgxNDM3B3VuaTA2OTkJZ2x5cGgxNDM5B3VuaTA2OUEJZ2x5cGgxNDQxCWdseXBoMTQ0MglnbHlwaDE0NDMHdW5pMDY5QglnbHlwaDE0NDUJZ2x5cGgxNDQ2CWdseXBoMTQ0Nwd1bmkwNjlDCWdseXBoMTQ0OQlnbHlwaDE0NTAJZ2x5cGgxNDUxB3VuaTA2OUQJZ2x5cGgxNDUzCWdseXBoMTQ1NAlnbHlwaDE0NTUHdW5pMDY5RQlnbHlwaDE0NTcJZ2x5cGgxNDU4CWdseXBoMTQ1OQd1bmkwNjlGCWdseXBoMTQ2MQd1bmkwNkEwCWdseXBoMTQ2MwlnbHlwaDE0NjQJZ2x5cGgxNDY1B3VuaTA2QTEHdW5pMDZBMglnbHlwaDE0NjgJZ2x5cGgxNDY5CWdseXBoMTQ3MAd1bmkwNkEzCWdseXBoMTQ3MglnbHlwaDE0NzMJZ2x5cGgxNDc0B3VuaTA2QTQHdW5pRkI2Qgd1bmlGQjZDB3VuaUZCNkQHdW5pMDZBNQlnbHlwaDE0ODAJZ2x5cGgxNDgxCWdseXBoMTQ4Mgd1bmkwNkE2B3VuaUZCNkYHdW5pRkI3MAd1bmlGQjcxB3VuaTA2QTcJZ2x5cGgxNDg4B3VuaTA2QTgJZ2x5cGgxNDkwB3VuaTA2QUEJZ2x5cGgxNDkyCWdseXBoMTQ5MwlnbHlwaDE0OTQHdW5pMDZBQglnbHlwaDE0OTYJZ2x5cGgxNDk3CWdseXBoMTQ5OAd1bmkwNkFDCWdseXBoMTUwMAlnbHlwaDE1MDEJZ2x5cGgxNTAyB3VuaTA2QUQHdW5pRkJENAd1bmlGQkQ1B3VuaUZCRDYHdW5pMDZBRQlnbHlwaDE1MDgJZ2x5cGgxNTA5CWdseXBoMTUxMAd1bmkwNkIwCWdseXBoMTUxMglnbHlwaDE1MTMJZ2x5cGgxNTE0B3VuaTA2QjEHdW5pRkI5Qgd1bmlGQjlDB3VuaUZCOUQHdW5pMDZCMglnbHlwaDE1MjAJZ2x5cGgxNTIxCWdseXBoMTUyMgd1bmkwNkIzB3VuaUZCOTcHdW5pRkI5OAd1bmlGQjk5B3VuaTA2QjQJZ2x5cGgxNTI4CWdseXBoMTUyOQlnbHlwaDE1MzAHdW5pMDZCNQlnbHlwaDE1MzIJZ2x5cGgxNTMzCWdseXBoMTUzNAd1bmkwNkI2CWdseXBoMTUzNglnbHlwaDE1MzcJZ2x5cGgxNTM4B3VuaTA2QjcJZ2x5cGgxNTQwCWdseXBoMTU0MQlnbHlwaDE1NDIHdW5pMDZCOAlnbHlwaDE1NDQJZ2x5cGgxNTQ1CWdseXBoMTU0Ngd1bmkwNkI5CWdseXBoMTU0OAlnbHlwaDE1NDkJZ2x5cGgxNTUwB3VuaTA2QkEHdW5pRkI5Rgd1bmkwNkJCB3VuaUZCQTEHdW5pMDZCQwlnbHlwaDE1NTYJZ2x5cGgxNTU3CWdseXBoMTU1OAd1bmkwNkJECWdseXBoMTU2MAd1bmkwNkJGCWdseXBoMTU2MglnbHlwaDE1NjMJZ2x5cGgxNTY0B3VuaTA2QzAHdW5pRkJBNQd1bmkwNkMxB3VuaTA2QzIHdW5pMDZDMwd1bmkwNkM0CWdseXBoMTU3MQd1bmkwNkM1B3VuaUZCRTEHdW5pMDZDNgd1bmlGQkRBB3VuaTA2QzcHdW5pRkJEOAd1bmkwNkM4B3VuaUZCREMHdW5pMDZDOQd1bmlGQkUzB3VuaTA2Q0EJZ2x5cGgxNTgzB3VuaTA2Q0IHdW5pRkJERgd1bmkwNkNECWdseXBoMTU4Nwd1bmkwNkNFCWdseXBoMTU4OQlnbHlwaDE1OTAJZ2x5cGgxNTkxB3VuaTA2Q0YJZ2x5cGgxNTkzB3VuaTA2RDAHdW5pRkJFNQd1bmlGQkU2B3VuaUZCRTcHdW5pMDZEMQlnbHlwaDE1OTkHdW5pMDZEMgd1bmlGQkFGB3VuaTA2RDMHdW5pRkJCMQd1bmkwNkQ0B3VuaTA2RDYHdW5pMDZENwd1bmkwNkQ4B3VuaTA2RDkHdW5pMDZEQQd1bmkwNkRCB3VuaTA2REMHdW5pMDZERAd1bmkwNkRFB3VuaTA2REYHdW5pMDZFMAd1bmkwNkUxB3VuaTA2RTIHdW5pMDZFMwd1bmkwNkU0B3VuaTA2RTUHdW5pMDZFNgd1bmkwNkU3B3VuaTA2RTgHdW5pMDZFOQd1bmkwNkVBB3VuaTA2RUIHdW5pMDZFRAd1bmkwNkZBCWdseXBoMTYyOQlnbHlwaDE2MzAJZ2x5cGgxNjMxB3VuaTA2RkIJZ2x5cGgxNjMzCWdseXBoMTYzNAlnbHlwaDE2MzUHdW5pMDZGQwlnbHlwaDE2MzcJZ2x5cGgxNjM4CWdseXBoMTYzOQd1bmkwNkZEB3VuaTA2RkUHdW5pRkJBNgd1bmlGQkE4B3VuaUZCQTkJZ2x5cGgxNjQ1CWdseXBoMTY0NglnbHlwaDE2NDcJZ2x5cGgxNjQ4CWdseXBoMTY0OQlnbHlwaDE2NTAJZ2x5cGgxNjUxB3VuaUZCMUQHdW5pRkIxRQlnbHlwaDE2NTQHdW5pRkIxRglnbHlwaDE2NTYJZ2x5cGgxNjU3CWdseXBoMTY1OAlnbHlwaDE2NTkJZ2x5cGgxNjYwCWdseXBoMTY2MQlnbHlwaDE2NjIJZ2x5cGgxNjYzCWdseXBoMTY2NAlnbHlwaDE2NjUJZ2x5cGgxNjY2CWdseXBoMTY2NwlnbHlwaDE2NjgJZ2x5cGgxNjY5CWdseXBoMTY3MAlnbHlwaDE2NzEJZ2x5cGgxNjcyCWdseXBoMTY3MwAAAAADAAgAAgARAAH//wADAAEAAE0CvyICOQQmAABA2gW6AABNIEFyaWFsICAgICAgICAg/////wA///5BUkxSMDAAAEAAAAAAAQAAAAwAAAAAAAAAAgAZAugC8AABAvEC+AADAvkDBQABAwgDCAABAwoDDAABAxIDEgADAxsDGwABAx8DIgABAycDNgABA0cDSwADA3wDfQABA38DfwACA4ADgAABA4EDjAACA40D9AABA/UD/AACA/8EAAADBAQEBQADBAgECQADBA0EEgADBBQEFQADBEwETgABBGcEaQABBSoGbAABBnIGiQABAAAAAQAAAAoAPgCiAAFhcmFiAAgACgABTUFSIAAaAAAABwAFAAEAAgADAAUABgAAAAcABgAAAAEAAgADAAQABgAIaXNvbAAyaXNvbAA4aW5pdAA+bWVkaQBEZmluYQBKZmluYQBQbGlnYQBWcmxpZwBeAAAAAQAAAAAAAQABAAAAAQACAAAAAQADAAAAAQAEAAAAAQAFAAAAAgAGAAcAAAABAAYACAASACgARgGoAwoFVAeeCMAAAQABAAEACAACAAgAAQZyAAEAAQXfAAEAAQABAAgAAgAMAAMGagYdA5MAAQADBh8GIAYhAAEAAQABAAgAAgCuAFQDIQMpAy8DMwPzA4sDkQOXA5sDnwOjA6cDswO3A7sDvwPDA8cDywPPA9MD1wPbA98D4wPnA+sD6wPzBSkFKgVMBVAFVAVYBVwFYAVkBWgFbAVwBXQFeAV8BaIFpgWqBa4FsgW0BbgFKgW9BcEFxQXJBc0D0wXFBdUF2QXdBeEF5QXpBe0F8QX1BfkF/QYBBgUGCQYNBUwGFQMhBhsGawY2BjwGXgZiBmYAAQBUAx8DJwMtAzEDNQOJA48DlQOZA50DoQOlA7EDtQO5A70DwQPFA8kDzQPRA9UD2QPdA+ED5QPpA+sD8QUoBSwFSgVOBVIFVgVaBV4FYgVmBWoFbgVyBXYFegWgBaQFqAWsBbAFtAW2BboFuwW/BcMFxwXLBc8F0QXTBdcF2wXfBeMF5wXrBe8F8wX3BfsF/wYDBgcGCwYRBhMGFwYZBh8GNAY6BlwGYAZkAAEAAQABAAgAAgCuAFQDIgMqAzADNAP0A4wDkgOYA5wDoAOkA6gDtAO4A7wDwAPEA8gDzAPQA9QD2APcA+AD5APoA+wD7AP0BSkFKwVNBVEFVQVZBV0FYQVlBWkFbQVxBXUFeQV9BaMFpwWrBa8FswW1BbkFKwW+BcIFxgXKBc4D1AXGBdYF2gXeBeIF5gXqBe4F8gX2BfoF/gYCBgYGCgYOBUwGFgMiBhwGbAY3Bj0GXwZjBmcAAQBUAx8DJwMtAzEDNQOJA48DlQOZA50DoQOlA7EDtQO5A70DwQPFA8kDzQPRA9UD2QPdA+ED5QPpA+sD8QUoBSwFSgVOBVIFVgVaBV4FYgVmBWoFbgVyBXYFegWgBaQFqAWsBbAFtAW2BboFuwW/BcMFxwXLBc8F0QXTBdcF2wXfBeMF5wXrBe8F8wX3BfsF/wYDBgcGCwYRBhMGFwYZBh8GNAY6BlwGYAZkAAEAAQABAAgAAgEiAI4DIAMoAywDLgMyAzYDggOEA4YDiAOKA44DkAOUA5YDmgOeA6IDpgOqA6wDrgOwA7IDtgO6A74DwgPGA8oDzgPSA9YD2gPeA+ID5gPqA+oD7gPwA/ID9gP4A/oD/AUoBSwFPQU/BUEFQwVFBUcFSQVLBU8FUwVXBVsFXwVjBWcFawVvBXMFdwV7BX8FgQWDBYUFhwWJBYsFjQWPBZEFkwWVBZcFmQWbBZ0FnwWhBaUFqQWtBbEFtQW3BboFvAXABcQFyAXMBdAF0gXUBdgF3AZzBeQF6AXsBfAF9AX4BfwGAAYEBggGDAYQBhIGFAYYBhoGHgYfBiAGIQYjBiUGJwYpBisGLQYvBjEGMwY1BjkGOwY/BkEGQwZdBmEGZQABAI4DHwMnAysDLQMxAzUDgQODA4UDhwOJA40DjwOTA5UDmQOdA6EDpQOpA6sDrQOvA7EDtQO5A70DwQPFA8kDzQPRA9UD2QPdA+ED5QPpA+sD7QPvA/ED9QP3A/kD+wUoBSwFPAU+BUAFQgVEBUYFSAVKBU4FUgVWBVoFXgViBWYFagVuBXIFdgV6BX4FgAWCBYQFhgWIBYoFjAWOBZAFkgWUBZYFmAWaBZwFngWgBaQFqAWsBbAFtAW2BboFuwW/BcMFxwXLBc8F0QXTBdcF2wXfBeMF5wXrBe8F8wX3BfsF/wYDBgcGCwYPBhEGEwYXBhkGHQYfBiAGIQYiBiQGJgYoBioGLAYuBjAGMgY0BjgGOgY+BkAGQgZcBmAGZAABAAEAAQAIAAIBIgCOAyADKAMsAy4DMgM2A4IDhAOGA4gDigOOA5ADlAOWA5oDngOiA6YDqgOsA64DsAOyA7YDugO+A8IDxgPKA84D0gPWA9oD3gPiA+YD6gPqA+4D8APyA/YD+AP6A/wFKAUsBT0FPwVBBUMFRQVHBUkFSwVPBVMFVwVbBV8FYwVnBWsFbwVzBXcFewV/BYEFgwWFBYcFiQWLBY0FjwWRBZMFlQWXBZkFmwWdBZ8FoQWlBakFrQWxBbUFtwW6BbwFwAXEBcgFzAXQBdIF1AXYBdwF4AXkBegF7AXwBfQF+AX8BgAGBAYIBgwGEAYSBhQGGAYaBh4GHwYgBiEGIwYlBicGKQYrBi0GLwYxBjMGNQY5BjsGPwZBBkMGXQZhBmUAAQCOAx8DJwMrAy0DMQM1A4EDgwOFA4cDiQONA48DkwOVA5kDnQOhA6UDqQOrA60DrwOxA7UDuQO9A8EDxQPJA80D0QPVA9kD3QPhA+UD6QPrA+0D7wPxA/UD9wP5A/sFKAUsBTwFPgVABUIFRAVGBUgFSgVOBVIFVgVaBV4FYgVmBWoFbgVyBXYFegV+BYAFggWEBYYFiAWKBYwFjgWQBZIFlAWWBZgFmgWcBZ4FoAWkBagFrAWwBbQFtgW6BbsFvwXDBccFywXPBdEF0wXXBdsF3wXjBecF6wXvBfMF9wX7Bf8GAwYHBgsGDwYRBhMGFwYZBh0GHwYgBiEGIgYkBiYGKAYqBiwGLgYwBjIGNAY4BjoGPgZABkIGXAZgBmQABAAJAAEACAABA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<?php
 
/*
  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  :: Formerly known as:::
  :: GIFEncoder Version 2.0 by L�szl� Zsidi, http://gifs.hu
  ::
  :: This class is a rewritten 'GifMerge.class.php' version.
  :: 
  :: Modification:
  :: - Simplified and easy code,
  :: - Ultra fast encoding,
  :: - Built-in errors,
  :: - Stable working
  ::
  ::
  :: Updated at 2007. 02. 13. '00.05.AM'
  ::
  ::
  ::
  :: Try on-line GIFBuilder Form demo based on GIFEncoder.
  ::
  :: http://gifs.hu/phpclasses/demos/GifBuilder/
  ::
  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 */
 
/**
 * Encode animated gifs
 */
class AnimatedGif {
 
    /**
     * The built gif image
     * @var resource
     */
    private $image = '';
 
    /**
     * The array of images to stack
     * @var array
     */
    private $buffer = Array();
 
    /**
     * How many times to loop? 0 = infinite
     * @var int
     */
    private $number_of_loops = 0;
 
    /**
     * 
     * @var int 
     */
    private $DIS = 2;
 
    /**
     * Which colour is transparent
     * @var int
     */
    private $transparent_colour = -1;
 
    /**
     * Is this the first frame
     * @var int
     */
    private $first_frame = TRUE;
 
    /**
     * Encode an animated gif
     * @param array $source_images An array of binary source images
     * @param array $image_delays The delays associated with the source images
     * @param type $number_of_loops The number of times to loop
     * @param int $transparent_colour_red
     * @param int $transparent_colour_green
     * @param int $transparent_colour_blue 
     */
    function __construct(array $source_images, array $image_delays, $number_of_loops, $transparent_colour_red = -1, $transparent_colour_green = -1, $transparent_colour_blue = -1) {
        /**
         * I have no idea what these even do, they appear to do nothing to the image so far 
         */
        $transparent_colour_red = 0;
        $transparent_colour_green = 0;
        $transparent_colour_blue = 0;
 
        $this->number_of_loops = ( $number_of_loops > -1 ) ? $number_of_loops : 0;
        $this->set_transparent_colour($transparent_colour_red, $transparent_colour_green, $transparent_colour_blue);
        $this->buffer_images($source_images);
 
        $this->addHeader();
        for ($i = 0; $i < count($this->buffer); $i++) {
            $this->addFrame($i, $image_delays [$i]);
        }
    }
    
    /**
     * Set the transparent colour
     * @param int $red
     * @param int $green
     * @param int $blue 
     */
    private function set_transparent_colour($red, $green, $blue){
        $this->transparent_colour = ( $red > -1 && $green > -1 && $blue > -1 ) ?
                ( $red | ( $green << 8 ) | ( $blue << 16 ) ) : -1;
    }
 
    /**
     * Buffer the images and check to make sure they are vaild
     * @param array $source_images the array of source images
     * @throws Exception 
     */
    private function buffer_images($source_images) {
        for ($i = 0; $i < count($source_images); $i++) {
            $this->buffer [] = $source_images [$i];
            if (substr($this->buffer [$i], 0, 6) != "GIF87a" && substr($this->buffer [$i], 0, 6) != "GIF89a") {
                throw new Exception('Image at position ' . $i. ' is not a gif');
            }
            for ($j = ( 13 + 3 * ( 2 << ( ord($this->buffer [$i] [10 ]) & 0x07 ) ) ), $k = TRUE; $k; $j++) {
                switch ($this->buffer [$i] [ $j ]) {
                    case "!":
                        if (( substr($this->buffer [$i], ( $j + 3), 8) ) == "NETSCAPE") {
                            throw new Exception('You cannot make an animation from an animated gif.');
                        }
                        break;
                    case ";":
                        $k = FALSE;
                        break;
                }
            }
        }
    }
 
    /**
     * Add the gif header to the image
     */
    private function addHeader() {
        $cmap = 0;
        $this->image = 'GIF89a';
        if (ord($this->buffer [0] [10]) & 0x80) {
            $cmap = 3 * ( 2 << ( ord($this->buffer [0] [10]) & 0x07 ) );
            $this->image .= substr($this->buffer [0], 6, 7);
            $this->image .= substr($this->buffer [0], 13, $cmap);
            $this->image .= "!\377\13NETSCAPE2.0\3\1" . $this->word($this->number_of_loops) . "\0";
        }
    }
 
    /**
     * Add a frame to the animation
     * @param int $frame The frame to be added
     * @param int $delay The delay associated with the frame
     */
    private function addFrame($frame, $delay) {
        $Locals_str = 13 + 3 * ( 2 << ( ord($this->buffer [$frame] [10]) & 0x07 ) );
 
        $Locals_end = strlen($this->buffer [$frame]) - $Locals_str - 1;
        $Locals_tmp = substr($this->buffer [$frame], $Locals_str, $Locals_end);
 
        $Global_len = 2 << ( ord($this->buffer [0] [10]) & 0x07 );
        $Locals_len = 2 << ( ord($this->buffer [$frame] [10]) & 0x07 );
 
        $Global_rgb = substr($this->buffer [0], 13, 3 * ( 2 << ( ord($this->buffer [0] [10]) & 0x07 ) ));
        $Locals_rgb = substr($this->buffer [$frame], 13, 3 * ( 2 << ( ord($this->buffer [$frame] [10]) & 0x07 ) ));
 
        $Locals_ext = "!\xF9\x04" . chr(( $this->DIS << 2 ) + 0) .
                chr(( $delay >> 0 ) & 0xFF) . chr(( $delay >> 8 ) & 0xFF) . "\x0\x0";
 
        if ($this->transparent_colour > -1 && ord($this->buffer [$frame] [10]) & 0x80) {
            for ($j = 0; $j < ( 2 << ( ord($this->buffer [$frame] [10]) & 0x07 ) ); $j++) {
                if (
                        ord($Locals_rgb [ 3 * $j + 0 ]) == ( ( $this->transparent_colour >> 16 ) & 0xFF ) &&
                        ord($Locals_rgb [ 3 * $j + 1 ]) == ( ( $this->transparent_colour >> 8 ) & 0xFF ) &&
                        ord($Locals_rgb [ 3 * $j + 2 ]) == ( ( $this->transparent_colour >> 0 ) & 0xFF )
                ) {
                    $Locals_ext = "!\xF9\x04" . chr(( $this->DIS << 2 ) + 1) .
                            chr(( $delay >> 0 ) & 0xFF) . chr(( $delay >> 8 ) & 0xFF) . chr($j) . "\x0";
                    break;
                }
            }
        }
        switch ($Locals_tmp [0]) {
            case "!":
                $Locals_img = substr($Locals_tmp, 8, 10);
                $Locals_tmp = substr($Locals_tmp, 18, strlen($Locals_tmp) - 18);
                break;
            case ",":
                $Locals_img = substr($Locals_tmp, 0, 10);
                $Locals_tmp = substr($Locals_tmp, 10, strlen($Locals_tmp) - 10);
                break;
        }
        if (ord($this->buffer [$frame] [10]) & 0x80 && $this->first_frame === FALSE) {
            if ($Global_len == $Locals_len) {
                if ($this->blockCompare($Global_rgb, $Locals_rgb, $Global_len)) {
                    $this->image .= ( $Locals_ext . $Locals_img . $Locals_tmp );
                } else {
                    $byte = ord($Locals_img [9]);
                    $byte |= 0x80;
                    $byte &= 0xF8;
                    $byte |= ( ord($this->buffer [0] [10]) & 0x07 );
                    $Locals_img [9] = chr($byte);
                    $this->image .= ( $Locals_ext . $Locals_img . $Locals_rgb . $Locals_tmp );
                }
            } else {
                $byte = ord($Locals_img [9]);
                $byte |= 0x80;
                $byte &= 0xF8;
                $byte |= ( ord($this->buffer [$frame] [10]) & 0x07 );
                $Locals_img [9] = chr($byte);
                $this->image .= ( $Locals_ext . $Locals_img . $Locals_rgb . $Locals_tmp );
            }
        } else {
            $this->image .= ( $Locals_ext . $Locals_img . $Locals_tmp );
        }
        $this->first_frame = FALSE;
    }
 
    /**
     * Add the gif footer 
     */
    private function addFooter() {
        $this->image .= ";";
    }
 
    /**
     * Compare gif blocks? What is a block?
     * @param type $GlobalBlock
     * @param type $LocalBlock
     * @param type $Len
     * @return type 
     */
    private function blockCompare($GlobalBlock, $LocalBlock, $Len) {
        for ($i = 0; $i < $Len; $i++) {
            if (
                    $GlobalBlock [ 3 * $i + 0 ] != $LocalBlock [ 3 * $i + 0 ] ||
                    $GlobalBlock [ 3 * $i + 1 ]!= $LocalBlock [ 3 * $i + 1 ] ||
                    $GlobalBlock [ 3 * $i + 2 ] != $LocalBlock [ 3 * $i + 2 ]
            ) {
                return ( 0 );
            }
        }
 
        return ( 1 );
    }
 
    /**
     * No clue
     * @param int $int
     * @return string the char you meant? 
     */
    private function word($int) {
        return ( chr($int & 0xFF) . chr(( $int >> 8 ) & 0xFF) );
    }
 
    /**
     * Return the animated gif
     * @return type 
     */
    function getAnimation() {
        return $this->image;
    }
 
    /**
     * Return the animated gif
     * @return type 
     */
    function display() {
        //late footer add
        $this->addFooter();
        header('Content-type:image/gif');
        echo $this->image;
    }
 
}
?>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz<?php
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

</html>zzzzabczzzzzzzzzzzzCzMARCzCONRADzzzzzzzzzzMARYHADALITTLELAMBzzzzzzzzzzz