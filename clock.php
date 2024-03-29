<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Cam and Clock</title>
    <script>
       function toggleFullscreen(elem) {
            elem = elem || document.documentElement;

            if (!document.fullscreenElement && !document.mozFullScreenElement &&
                !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }
        var opnew = 0.3;

        var btnVisible = function() {
            opnew = 0.99;
            var btn = document.getElementById('config');
            btn.style.opacity = opnew;
        }
        document.addEventListener('mousemove', btnVisible, true);



        var addZero = i => {
            return (i < 10 ? "0" + i : i);
        }
        var pi = [0, 1, 2];

        var changeBackground = function() {
            Number.prototype.hex = function() {
                var s = this.toString(16);
                while (s.length < 2) {
                    s = "0" + s;
                }
                return s;
            }
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            if (hours % 2 == 1) {
                minutes = 59 - minutes;
            }
            if (minutes % 2 == 1) {
                seconds = 59 - seconds;
            }
            if (hours == seconds && Math.random() > 0.8) {
                var tmp = pi[0];
                pi[0] = pi[2];
                pi[2] = tmp;
            } else if (minutes == seconds && Math.random() > 0.7) {
                var tmp = pi[1];
                pi[1] = pi[2];
                pi[2] = tmp;
            }
            var rgb = Array();
            rgb[pi[0]] = hours;
            rgb[pi[1]] = minutes;
            rgb[pi[2]] = seconds;

            var color = '#' + rgb[0].hex() + rgb[1].hex() + rgb[2].hex();
            // console.log(color);
            document.body.style.background = color;
        }

        var fontsizecount = 400;

        var clockmode = "default"; 

        var count = function() {
            var now = new Date();
            var hours = addZero(now.getHours());
            var minutes = addZero(now.getMinutes());
            var seconds = addZero(now.getSeconds());

            changeBackground();


            var elem = document.getElementById('time');
          
            if (clockmode == "noclock") { 
            elem.innerHTML = " "; 
            } else if( clockmode == "noseconds") { 
                elem.innerHTML = hours + ':' + minutes;
            } else { 
                elem.innerHTML = hours + ':' + minutes + ':' + seconds;
            }

            var video = document.getElementById("thecam");
            // var wVideo = 0.9 * video.offsetWidth; 

            var wSoll = Math.floor(0.9 * window.innerWidth);
            var tIst = elem.offsetWidth;

            if( clockmode == "noclock") { 
                    // do nothing
            } else if (tIst > wSoll) {
                fontsizecount--;
            } else if (tIst < wSoll - 30) {
               fontsizecount++;
            }


            elem.style.fontSize = fontsizecount + "px";

            var btn = document.getElementById('config')
            if (opnew > 0) {
                opnew = opnew - 0.001;
            }

            btn.style.opacity = opnew;
        }

        // From: https://www.htmlgoodies.com/html5/display-iframe-contents-without-scrollbars/
        var setIframeSize = function(iframe = null) {
            if (iframe === null) {
                iframe = document.getElementById("thecam");
            }
            var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
            var iframeBody;
            if (iframeDocument) {
                iframeBody = iframeDocument.querySelector('body');
                iframeBody.style.overflowY = 'hidden';
                iframeBody.style.overflowX = 'hidden';
            }
        }
        var setIframeSize2 = function(iframe = null) {
            if (iframe === null) {
                iframe = document.getElementById("thecam2");
            }
            var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
            var iframeBody;
            if (iframeDocument) {
                iframeBody = iframeDocument.querySelector('body');
                iframeBody.style.overflowY = 'hidden';
                iframeBody.style.overflowX = 'hidden';
            }
        }

        var startUp = function() {
            <?php 
            if(($_GET["noclock"] ?? "no") !== "yes" ) { 
            echo "setInterval(count, 20);";
            }
            ?>

        }

        var toggleClock = function() { 
            if( clockmode == "noclock") { 
                clockmode = "noseconds"; 
            } else if( clockmode == "default") { 
                clockmode = "noclock"; 
            } else { 
                clockmode = "default"; 
            }
        }
        window.addEventListener("DOMContentLoaded", startUp, false);
    </script>
    <style>
        #thecam {
            width: 98%;
            height: 98%;
            position: absolute;
            top: 50%;
            /* position the top  edge of the element at the middle of the parent */
            left: 50%;
            /* position the left edge of the element at the middle of the parent */

            transform: translate(-50%, -50%);
            z-index: 1;
            opacity: 0.5;

        }

        #thecam2 {
            width: 30%;
            height: 30%;
            position: absolute;
            top: 0%;
            /* position the top  edge of the element at the middle of the parent */
            right: 0%;
            /* position the left edge of the element at the middle of the parent */

          
            z-index: 2;
            opacity: 0.5;

        }

        #time {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -62%);
            z-index: 10;
            font-size: 400px;
            font-weight: bold;
            color: red;
            font-family: Helvetica, monospace;
            letter-spacing: -20px;
            font-stretch: ultra-condensed;
        }

        #config {
            position: absolute;
            top: 0.51%;
            left: 0.51%;
            height: 25%;
            width: 25%;
            z-index: 20;
            opacity: 0.3;
            font-size: 15px;
            font-weight: bold;
            color: yellow;
            font-family: Helvetica, monospace;
            /* background-color: darkolivegreen; */
            border: none;
            cursor: none;

        }

        .btnConfig {
            height: 50%;
            width: 100%;
            z-index: 10;
            opacity: 0.3;
            font-size: 15px;
            font-weight: bold;
            color: yellow;
            font-family: Helvetica, monospace;
            background-color: red; 
            border: none;
            cursor: none;

        }

        .btnConfig:hover {
            background-color: lightgoldenrodyellow;
            color: brown;
        }

    </style>
</head>

<body>
    <div id="config">
<input class="btnConfig" id="btnFullscreen" type="button" value="Full Screen On/Off" onclick="toggleFullscreen()" /><br>
<input class="btnConfig" id="btnSecondsToggle" type="button" value="Clock On/Off/Seconds" onclick="toggleClock()" />
    </div>

    <div id="thecamdiv">
        <?php 
        if( isset($_GET["deviceid"])) { 
            echo ' <iframe onload="setIframeSize()" id="thecam" src="cam.php?inputmode=cam&deviceid='.($_GET["deviceid"]).'&h='.($_GET["h"] ?? 99).'&w='.($_GET["w"] ?? 99).'&id='.($_GET["id"] ?? 3)
            .'&videoonly=yes" frameborder="0"></iframe>';
        }

        else if(isset($_GET["name"]) === false ) { // legacy
            echo ' <iframe onload="setIframeSize()" id="thecam" src="cam.php?inputmode='.($_GET["facingmode"] ?? "cam").'&facingmode=user&id='.($_GET["id"] ?? 3)
            .'&videoonly=yes" frameborder="0"></iframe>';
        } else { 
            echo ' <iframe onload="setIframeSize()" id="thecam" src="cam.php?inputmode=cam&name='.($_GET["name"]).'&h='.($_GET["h"] ?? 99).'&w='.($_GET["w"] ?? 99).'&id='.($_GET["id"] ?? 3)
            .'&videoonly=yes" frameborder="0"></iframe>';

            if(isset($_GET["name2"]) ) { 
                echo ' <iframe onload="setIframeSize2()" id="thecam2" src="cam.php?inputmode=cam&name='.($_GET["name2"]).'&id='.($_GET["id2"] ?? 4)
                .'&videoonly=yes" frameborder="0"></iframe>';
            } 
     
        }
        ?>
           </div>
    <div id="time">!</div>

</body>

</html>