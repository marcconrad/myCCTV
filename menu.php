<!DOCTYPE html>
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
            <button class="button" onClick="goTo('index.php?t='+Date.now()+'&showclarifai=1')">Manage</button> <br>

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
            <button class="button" onClick="goTo('index.php?t='+Date.now()+'&showstats=1&setupcontrolA=1')">Control</button>
            <div class="dropdownsub">
                <button class="button">Version &raquo;</button>
                <div class="dropdownsub-content">
                    <button class="button" onClick="goTo('updatefromgit.php?t='+Date.now()+'&frommenu=1')">Check for Update</button>
                </div>
            </div>
            <div class="dropdownsub">
                <button class="button">Animate &raquo;</button>
                <div class="dropdownsub-content">
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+'&savecurrentasgifs=1')">No Date</button>
                    <br><button class="button" onClick="goTo('index.php?t='+Date.now()+'&showdate=1&savecurrentasgifs=1')">With Date</button>
                    <br><button class="button" onClick="goTo('img/agif/?t='+Date.now()+'')">Stored Gifs</button> <br>
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

        $cams[intval($_GET["id"] ?? 1)] = intval($_GET["id"] ?? 1);



        foreach ($cams as $j) {
            echo "<option name=$j value=$j ";
            if (intval($_GET["id"] ?? 1) == $j) {
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
                    <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&howmany=12')">Today</button>
                    <div class="dropdownsub-content">
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=300&addlast=1&howmany=12')">Last 5 min</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=1800&addlast=1&howmany=12')">&frac12; hr ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=3600&addlast=1&howmany=12')">1 hr ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=7200&howmany=12')">2 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=21600&howmany=12')">6 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=43200&howmany=12')">12 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=7200&minimumage=1800&howmany=12')">&frac12;-2 hrs ago</button>
                        <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=today&agelimit=5400&minimumage=1800&howmany=12')">&frac12;-1&frac12; hrs ago</button>

                    </div>
                </div>
                <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=yesterday&howmany=12')">Yesterday</button>
                <button class="button" onClick="goTo('index.php?t='+Date.now()+'&day=todayyesterday&howmany=12')">Today & Yesterday</button>

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
        if ($_SERVER['SERVER_NAME'] === "localhost") {
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

                newsrc = httpx + "://" + window.location.host + "" + dir + "/" + str + '&id=' + id + '&a=1' + includeFL + "&" + getDirection() + "=1&" + getSort() + "=1";
                console.log("(b)" + newsrc);
                document.getElementById("mainframe").src = newsrc;
            }
            console.log("fr=" + document.getElementById("mainframe").src);
        }

        function resetstatsframe() {
            var pn = window.location.pathname;
            var dir = pn.substring(0, pn.lastIndexOf('/'));
            console.log(dir);
            document.getElementById("statsframe").src = httpx + "://" + window.location.host + "" + dir + "/index.php?time=" + Date.now() + "&showstats&id=" + id;
            setTimeout(updateStatusEmoji, 10);

        }
        // var to = setInterval(updateStatusEmoji, 60000); 


        function updateStatusEmoji() {
            console.log("update Status Emoji called");
            var pn = window.location.pathname;
            var dir = pn.substring(0, pn.lastIndexOf('/'));
            var xmlhttp = new XMLHttpRequest();
            var url = httpx + "://" + window.location.host + "" + dir + "/info.php?t=s" + Date.now() + "&id=" + id + "&statusemoji=1";

            xmlhttp.onreadystatechange = function() {
                console.log("url = " + url);
                console.log("Hello from bla: " + this.readyState);
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Hello from bla: " + this.responseText);
                    console.log("url = " + url);
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

</html>