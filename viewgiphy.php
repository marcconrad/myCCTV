<!DOCTYPE HTML>

<html>

<head>
	<script>
		if (document.location.href.indexOf("perisic.com") > 0) {
			document.location.href = "https://cat.sanfoh.com/"
		}
	</script>
	<title>Fifty Shades of Cats</title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<style>
		body {
			background-color: #8c7D70;
			overflow: hidden;

			background-image: url('./bg1.jpg');
		}

		#delta {
			position: absolute;
			left: 0px;
			top: 0px;
			z-index: 2;
			color: yellow;
			background-color: black;
			opacity: 0.8;
		}

		#err {
			position: absolute;
			left: 0px;
			top: 50px;
			z-index: 2;
			color: red;
			background-color: black;

			opacity: 0.8;
		}

		#ontop {
			position: absolute;
			left: 0px;
			top: 0px;
			z-index: 1;
			color: yellow;
			background-color: black;
			opacity: 0.5;
		}
	</style>
	<script>
		// https://stackoverflow.com/questions/22428484/get-element-from-point-when-you-have-overlapping-elements
		var catqueue = [];
		var n_uid = 10000;

		function getRandomInt(min, max) {
			min = Math.ceil(min);
			max = Math.floor(max);
			if (min > max) {
				return min;
			}
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}


		async function remove_video(n = -1) {
			addLI("ontop", "remove video called n=" + n);
			if (n > 0) {
				var a = document.getElementById("i" + n);
				if (a != null) {
					a.parentNode.removeChild(a);
				}
				delete a;
				return;
			}
			for (i = catqueue.length; i > 0; i--) {
				var nn = catqueue.shift();
				var a = document.getElementById("i" + nn);
				if (a != null) {
					a.parentNode.removeChild(a);
					delete a;
					return;
				}
			}

			var videos = document.getElementsByTagName('video');

			idx = Math.floor(Math.random() * videos.length);

			if (videos.length > 2) {
				var a = videos[idx];
				a.parentNode.removeChild(a);
				delete a;
				delete videos;
			}
		}
		var errcount = 1;

		function srcError(str, n) {
			remove_video(parseInt(n, 10));
			document.getElementById('err').innerHTML = errcount;
			errcount++;
			if (document.getElementsByTagName('video').length < 2 && errcount > 10) {
				window.location.href = "viewgiphy.php?t=" + n;
			}
			//alert("SRC ERROR: "+str+" n="+n); 

		}
		async function add_video() {
			// console.log("Hello"); 
			addLI("ontop", "add_video() called: ");
			var ww = window.innerWidth;
			var wh = window.innerHeight;

			var w = getRandomInt(200, 480);
			var h = Math.floor(w * 36.0 / 48.0);

			var ax = getRandomInt(ww / 2 - 1, ww / 2 + 1);
			var ay = getRandomInt(wh / 2 - 1, wh / 2 + 1);
			var d = 25;
			iw = ww / 2;
			ih = wh / 2;
			var r = 0.1;
			for (i = 98;; i--) {
				var elem = document.elementFromPoint(ax, ay);
				var item = (elem == null ? "VIDEO" : elem.tagName);
				var elemA = document.elementFromPoint(ax + d, ay + d);
				var itemA = (elemA == null ? "VIDEO" : elemA.tagName);
				var elemB = document.elementFromPoint(ax - d, ay - d);
				var itemB = (elemB == null ? "VIDEO" : elemB.tagName);

				if (item == "VIDEO" || itemA == "VIDEO" || itemB == "VIDEO") {
					if (i <= 0) {
						addLI("ontop", "no space found");
						return;
					}
					var ax = getRandomInt(0 + iw, ww - iw);
					iw = ww / 2 * i / 100;
					var ay = getRandomInt(0 + ih, wh - ih);
					ih = wh / 2 * i / 100;

				} else {
					// console.log("ax="+ax+"; ay="+ay); 
					break;
				}
			}

			var px = Math.floor(100 * ax / ww);
			var py = Math.floor(100 * ay / wh);
			addLI("ontop", "px=" + px + "; py=" + py);
			var v = document.createElement("video");
			v.setAttribute("autoplay", "");
			v.setAttribute("loop", "");
			v.setAttribute("width", w);
			v.setAttribute("height", h);
			v.setAttribute("muted", "");
			n = n_uid++; // getRandomInt(10001, 99999); 
			v.setAttribute("id", "i" + n);
			v.setAttribute("onmouseenter", "change_video(" + n + ")");
			var styleinfo = "margin:0;padding:0; position: absolute; left: " + px + "%; top: " + py + "%; transform: translate(-50%, -50%); ";

			v.setAttribute("style", styleinfo);


			var src = document.createElement("source");
			const randomUid = uids[Math.floor(Math.random() * uids.length)];
			// var x = "kyLUECfmwz4rlwOLfp";
			var x = randomUid;
			src.setAttribute("src", "https://media.giphy.com/media/" + x + "/giphy.mp4");
			src.setAttribute("type", "video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"");
			src.setAttribute("onerror", "srcError('s'," + n + ")");
			v.appendChild(src);
			v.setAttribute("onerror", "srcError('v'," + n + ")");

			var element = document.getElementById("bd");
			if (delta_time < 50 || document.getElementsByTagName('video').length < 4) {
				element.appendChild(v);
				catqueue.push(n);
				addLI("ontop", "cat ok delta=" + delta_time);

			} else {
				console.log("(a) delta=" + delta_time);
				addLI("ontop", "not added delta=" + delta_time);
				delete v;
				delete src;
			}
		}

		var startup = 5;

		function change_video(n = -1) {
			if (startup > 0) {
				startup--;
			} else {
				remove_video(n);
			}
			if (delta_time < 10) {
				var t = setTimeout('add_video()', 2000);
				var t1 = setTimeout('add_video()', 8000);
				// console.log("t="+t+" t1="+t1); 
			} else {
				console.log("(b) delta=" + delta_time);
			}
		}
/**
* Can use this for debugging. 
 */
		function addLI(listId, what, maxNumber = 50) {
			return;/*
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
			*/
		}

		var last_time = Date.now()
		var delta_time = 0;
		var checkTime = setInterval('check_time()', 5000);
		var checkDelta = setTimeout('check_delta()', 50);

		function check_time() {

			if (delta_time > 20 || delta_time < 10) {
				change_video();
			}
		}

		function check_delta() {
			delta_time = Math.abs(Date.now() - last_time - 50);
			last_time = Date.now();
			document.getElementById('delta').innerHTML = document.getElementsByTagName('video').length;
			checkDelta = setTimeout('check_delta()', 50);
		}
	</script>
</head>

<body id="bd" onclick="change_video()">
	<ol id="ontop"></ol>
	<a href="viewgiphy_h.php"><h1 id="delta" title="Click for more information.">.</h1></a>
	<h1 id="err">.</h1>
	<script>
		// var intv = setInterval('change_video()', 60000); 


		// var av = add_video(); 
		<?php
		$html5links = array(

			"https://media.giphy.com/media/gdeobe2eouDgyzFCk6/giphy.gif",
			"https://media.giphy.com/media/Xf7j7HNc1XkDX6cocF/giphy.gif",
			"https://media.giphy.com/media/QBde0ttwmO8uJyQDQC/giphy.gif",
			"https://media.giphy.com/media/iGGUiv0GdHUYCxvlkp/giphy.gif",
			"https://media.giphy.com/media/frSHThI3S3UCliYwRu/giphy.gif",
			"https://media.giphy.com/media/eNeOthF9WnoXNeLhNC/giphy.gif",
			"https://media.giphy.com/media/W5qpwq4xhN6zzSABtz/giphy.gif",
			"https://media.giphy.com/media/JSXo9Qsqp1kayubdAp/giphy.gif",
			"https://media.giphy.com/media/RNQFn8zlgnEPvQC7Z3/giphy.gif",
			"https://media.giphy.com/media/iDCUJkp5J20YXP4MV0/giphy.gif",
			"https://media.giphy.com/media/TIjfQvCgyXmWEpBONN/giphy.gif",
			"https://media.giphy.com/media/MdFTcBRChDsDy68mOJ/giphy.gif",
			"https://media.giphy.com/media/IdxQaL4g195OAqTtsK/giphy.gif",
			"https://media.giphy.com/media/XHRbpCvNjXPDWTpphC/giphy.gif",
			"https://media.giphy.com/media/hWkA8lBZjsMvR8I7F4/giphy.gif",
			"https://media.giphy.com/media/jokvxpoS0xgSCXQ6r0/giphy.gif",
			"https://media.giphy.com/media/kyLlRt3tBhRCV4yLML/giphy.gif",
			"https://media.giphy.com/media/JTngTKSIZ6y0NWLQzr/giphy.gif",
			"https://media.giphy.com/media/S72RQMAh0L9HlizIHV/giphy.gif",
			"https://media.giphy.com/media/YTEMCyX1EA3pNvTDer/giphy.gif",
			"https://media.giphy.com/media/kyLUECfmwz4rlwOLfp/giphy.gif",
			"https://media.giphy.com/media/LqlLTridibty7bcOaf/giphy.gif",
			"https://media.giphy.com/media/WTF2F1tzBKxZxwaACr/giphy.gif",
			"https://media.giphy.com/media/Pja6z2Req7oTC5quhF/giphy.gif",
			"https://media.giphy.com/media/YMdgBVNqktCrASehJQ/giphy.gif",
			"https://media.giphy.com/media/jnEa83x730avhq0Fm7/giphy.gif",
			"https://media.giphy.com/media/l3PQBGu03DcPpFW3ft/giphy.gif",
			"https://media.giphy.com/media/huJWfpNtFXMVgdC946/giphy.gif",
			"https://media.giphy.com/media/YOeE3BcwImzlN2rGPi/giphy.gif",
			"https://media.giphy.com/media/j3o5cUsZooCVEOVulG/giphy.gif",
			"https://media.giphy.com/media/H1kxaXau1C0mUd5pvb/giphy.gif",
			"https://media.giphy.com/media/S5Q7M9ySqkN9Oz6gkX/giphy.gif",
			"https://media.giphy.com/media/RjqeVMr2uHi7s9oeTZ/giphy.gif",
			"https://media.giphy.com/media/mBS2kBzDHTXCHumCCH/giphy.gif",
			"https://media.giphy.com/media/husFKD6jWPDksGllqD/giphy.gif",
			"https://media.giphy.com/media/iGAptXUZuT5587JzSF/giphy.gif",
			"https://media.giphy.com/media/kf8gwpektMvP3CKRRy/giphy.gif",


			"https://media.giphy.com/media/dxCikFrrVmoARNdfxu/giphy.gif",
			"https://media.giphy.com/media/Id09s09vdk7bJbPzWu/giphy.gif",
			"https://media.giphy.com/media/l3UBYJ5FRwfrdn7Yxy/giphy.gif",
			"https://media.giphy.com/media/kfXKWgt3cTdiReg4YA/giphy.gif",
			"https://media.giphy.com/media/UpDAYYUpeFnKD5uzUh/giphy.gif",
			"https://media.giphy.com/media/WQyAQz5HaPqGXAsDDS/giphy.gif",
			"https://media.giphy.com/media/Kan6WECiHQZ7Vva3he/giphy.gif",
			"https://media.giphy.com/media/kdRKljpbhbM3C40XBg/giphy.gif",
			"https://media.giphy.com/media/W5TX4uO4o7WcBdOWDI/giphy.gif",
			"https://media.giphy.com/media/IhVBZtaTMjwl7B5Ula/giphy.gif",
			"https://media.giphy.com/media/iFxda3M9gXg2vzpCyR/giphy.gif",
			"https://media.giphy.com/media/gFnXEiRTujnvJpKeX4/giphy.gif",
			"https://media.giphy.com/media/LoHuFX4cCA7PDrZg0Q/giphy.gif",
			"https://media.giphy.com/media/VEtMohE58xOc5vzgH9/giphy.gif",
			"https://media.giphy.com/media/hVgObftRmLdU0YsWQO/giphy.gif",
			"https://media.giphy.com/media/WoLuCNTDhgTk9gt3s6/giphy.gif",
			"https://media.giphy.com/media/TH09ceSR2FwDr3ADyw/giphy.gif",
			"https://media.giphy.com/media/XBXk0Bd4aOiheEPong/giphy.gif",
			"https://media.giphy.com/media/kcrBFg1Y6VTNZYltYP/giphy.gif",
			"https://media.giphy.com/media/kC2l6VIYiOBfnNFFzu/giphy.gif",
			"https://media.giphy.com/media/JQFLhpFGlSHC8ffp0f/giphy.gif",
			"https://media.giphy.com/media/KxzBqIBxb73Xcf981F/giphy.gif",
			"https://media.giphy.com/media/RHPnEs1m0xPsVzw4o9/giphy.gif",
			"https://media.giphy.com/media/hpcUpadpwnSyHDdzUb/giphy.gif",
			"https://media.giphy.com/media/S5u3RUeEtCKi0cSl1q/giphy.gif",
			"https://media.giphy.com/media/QX1sVP0IWlN6qj6EWl/giphy.gif",
			"https://media.giphy.com/media/JsPnJDGU1W9XFZgTAD/giphy.gif",
			"https://media.giphy.com/media/VJCD7PpHym9DiNwUt8/giphy.gif",
			"https://media.giphy.com/media/Q7X93lW5AzUhYKsBCa/giphy.gif",
			"https://media.giphy.com/media/SRvk2IQQcnb2iQpLJy/giphy.gif",
			"https://media.giphy.com/media/fA7vaErxwy0iFiGx96/giphy.gif",
			"https://media.giphy.com/media/kEE985pTRXbSD56uuc/giphy.gif",
			"https://media.giphy.com/media/WQrOe3yJd2jeK8gTR3/giphy.gif",
			"https://media.giphy.com/media/YTEMCyX1EA3pNvTDer/giphy.gif",
			"https://media.giphy.com/media/kyLUECfmwz4rlwOLfp/giphy.gif",
			"https://media.giphy.com/media/YMdgBVNqktCrASehJQ/giphy.gif",
			"https://media.giphy.com/media/WTF2F1tzBKxZxwaACr/giphy.gif",
			"https://media.giphy.com/media/Pja6z2Req7oTC5quhF/giphy.gif",
			"https://media.giphy.com/media/huJWfpNtFXMVgdC946/giphy.gif",
			"https://media.giphy.com/media/jnEa83x730avhq0Fm7/giphy.gif",
			"https://media.giphy.com/media/l3PQBGu03DcPpFW3ft/giphy.gif",
			"https://media.giphy.com/media/j3o5cUsZooCVEOVulG/giphy.gif",
			"https://media.giphy.com/media/H1kxaXau1C0mUd5pvb/giphy.gif",
			"https://media.giphy.com/media/YOeE3BcwImzlN2rGPi/giphy.gif",

		);





		echo 'var uids = [ ';
		foreach ($html5links as $url) {
			$x = explode("/", $url)[4];
			echo '"' . $x . '", ';
		}
		echo ' ]; ';

		?>
	</script>

	<?php


	$w = 48;
	$h = 36;


	echo '<script>';
	for ($i = 0; $i < 50; $i++) {
	
		echo 'var a' . $i . ' = setTimeout(\'add_video()\', 500 * ' . $i . '); ';
	}
	echo '</script>';
	?>



</html>