<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Cam and Clock</title>
    <script>
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

        var count = function() {
            var now = new Date();
            var hours = addZero(now.getHours());
            var minutes = addZero(now.getMinutes());
            var seconds = addZero(now.getSeconds());

            changeBackground();


            var elem = document.getElementById('time');
            elem.innerHTML = hours + ':' + minutes + ':' + seconds;

            var video = document.getElementById("thecam");
            // var wVideo = 0.9 * video.offsetWidth; 

            var wSoll = Math.floor(0.9 * window.innerWidth);
            var tIst = elem.offsetWidth;


            if (tIst > wSoll) {
                fontsizecount--;
            } else if (tIst < wSoll - 30) {
               fontsizecount++;
            }


            elem.style.fontSize = fontsizecount + "px";
        }
/*
        window.addEventListener('resize', function(event) {
            var elem = document.getElementById('time');
            elem.style.fontSize = fontsizecount = 1000;
            // do stuff here
        });
        */

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
            setInterval(count, 20);

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
            left: 0%;
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
    </style>
</head>

<body>
    <div id="thecamdiv">
        <?php 
// legacy: 
        if(isset($_GET["name"]) === false ) {
            echo ' <iframe onload="setIframeSize()" id="thecam" src="cam.php?inputmode='.($_GET["facingmode"] ?? "cam").'&facingmode=user&id='.($_GET["id"] ?? 3)
            .'&videoonly=yes" frameborder="0"></iframe>';
        } else { 
            echo ' <iframe onload="setIframeSize()" id="thecam" src="cam.php?inputmode=cam&name='.($_GET["name"]).'&id='.($_GET["id"] ?? 3)
            .'&videoonly=yes" frameborder="0"></iframe>';

            if(isset($_GET["name2"]) ) { 
                echo ' <iframe onload="setIframeSize2()" id="thecam2" src="cam.php?inputmode=cam&name='.($_GET["name2"]).'&id='.($_GET["id2"] ?? 4)
                .'&videoonly=yes" frameborder="0"></iframe>';
            } 
     
        }
        ?>
           </div>
    <div id="time">tbc</div>

</body>

</html>