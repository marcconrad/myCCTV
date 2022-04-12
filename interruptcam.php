<!DOCTYPE html>
<html>

<head>
    <title>Camera - Interruption</title>


    <script>
        var phpdelay = <?php echo '"' . ($_GET["interruptdelay"] ?? -1) . '"'; ?>;
        var phpId = <?php echo '"' . ($_GET["id"] ?? -1) . '"'; ?>;
        var phpReturnTo = <?php echo '"' . ($_GET["returnto"] ?? "invalid") . '"';  ?>;

        var timestarted = 0;
        var maxseconds = 72;
        var addZero = i => {
            return (i < 10 ? "0" + i : i);
        }
        var pi = [0, 1, 2];

        var changeBackground = function() {
            document.body.style.background = "black";
        }

        var checkIfContinue = function() {
            document.getElementById("checkreturn").innerHTML = "Check for early return...";
            fetch("index.php?id="+phpId+"&checkinterrupt=1")
                .then(response => {
                    document.getElementById("checkreturn").innerHTML = "OK.";
                    // console.log(response);
                    response.text().then(
                        data => {
                            console.log(data);
                            if(parseInt(data) == -1) { 
                                startnow(); 
                            } else { 
                               
                            }
                        });

                    // console.log(phpId);
                })

                .catch(error => {
                    // console.log(error)
                });
            return;
        }

        var loadNewPage = function() {
            // console.log( phpReturnTo.startWith('interrupt=true&')); 
            if (!phpReturnTo.startsWith('interrupt=true&')) {
                phpReturnTo = 'interrupt=true&' + phpReturnTo; // tell the camera that it was called from interrupt. But only first time
            }
            document.location.href = "cam.php?" + phpReturnTo;
        }
        var count = function() {

            var returnTo = phpReturnTo;
            var rt = document.getElementById("returnto");
            rt.innerHTML = returnTo;


            var now = new Date();
            var hours = addZero(now.getHours());
            var minutes = addZero(now.getMinutes());
            var seconds = addZero(now.getSeconds());

            changeBackground();

            var elem = document.getElementById('time');
            elem.innerHTML = hours + ':' + minutes + ':' + seconds; // + ':' + seconds;

            var cc = document.getElementById("countdown");
            var secondsElapsed = Math.round((now.getTime() - timestarted) / 1000);
            cc.innerHTML = maxseconds - secondsElapsed;
            if (secondsElapsed >= maxseconds) {
                console.log("Fire now");
                loadNewPage();
            }
        }

        var startnow = function() {
            var t = document.getElementById("startnowmsg");
            t.style.display = "block";
            maxseconds = 0;
        }
        var startinterrupt = function() {

            timestarted = Date.now();
            var hh = (new Date()).getHours();
            if (phpdelay === "lakunoc") {
                var mm = (new Date()).getMinutes();

                var mmm = 60 * hh + mm;
                var t4_30 = 4 * 60 + 30;
                maxseconds = (t4_30 - mmm) * 60;
                if (maxseconds < 0) {
                    maxseconds += 60 * 60 * 24;
                }
            } else if (phpdelay >= 0) {
                maxseconds = parseInt(phpdelay);
            } else {
                maxseconds = (hh == 4 ? 30 * 60 : 1300);
            }
            console.log("Function started. timestarted=" + timestarted);
            setInterval(count, 900);
            setInterval(checkIfContinue, 180 * 1000);
        }
        window.addEventListener("DOMContentLoaded", startinterrupt, false);
    </script>
    <style>
        h1 {
            color: red;
        }

        h3 {
            color: white;
        }
        h2 {
            color: green;
        }
    </style>
</head>

<body>
    <h1>Interruption - Normality will resume in <span id="countdown">tbc</span> seconds.</h1>
    <h1>Time now: <span id="time">tbc</span></h1>
    <h2><span id="checkreturn">[wait]</span></h2>
    <input id="startnow" type="button" value="Start Now" onclick="startnow()" />

    <div> Return with parameters: <span id="returnto">tbc</span> </div>

    <div id="startnowmsg" style="display:none">
        <h3>Starting now</hr>
    </div>


</body>

</html>