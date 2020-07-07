<!DOCTYPE HTML>

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
                        document.getElementsByTagName("canvas")[0].setAttribute("width", twidth);
                        document.getElementsByTagName("canvas")[0].setAttribute("height", theight);

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
                                twidth = (parsed.twidth ? parsed.twidth : 640); // targeted image width
                                theight= (parsed.theight? parsed.theight : 480); // targeted image height 

                               // document.getElementsByTagName("canvas")[0].setAttribute("width", twidth);
                                // document.getElementsByTagName("canvas")[0].setAttribute("height", theight);
                                
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

</html>