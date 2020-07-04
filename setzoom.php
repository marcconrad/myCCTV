<!DOCTYPE HTML>
<html>

<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<title>Set Zoom</title>
	<style>
		.inputx {
			background-color: lightcoral;
			border-style: none;
			border-radius: 3px;
			width: 4ex;
		}

		body {
			background-color: lightseagreen;
		}

		.accept {

			background-color: lightslategray;
			/* Green */
			border: none;
			color: black;
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;

		}
	</style>
	<?php
	error_reporting(-1);

	$zoomsourcefile =  "tmp/zoomsource" . ($_GET["b"] ?? "x") . "A.jpg";
	if (!isset($_GET["preview"])) {
		$imgs = glob("./img/*/" . ($_GET["b"] ?? "x"));
		if (count($imgs) > 0) {
			copy($imgs[0], $zoomsourcefile);
		} else {
			copy("nopic.jpg", $zoomsourcefile);
		}
	}

	if (!isset($_GET["videoinfo"])) {
		echo "<p>No video information available from Camera; assume 640 x 480. <p>";
	}
	// var_dump($_GET); 

	$dimensions = explode(',', $_GET["videoinfo"] ?? "640,480");
	// var_dump($dimensions);

	$w = $owidth = $dimensions[0];
	$h = $oheight = $dimensions[1];

	$imfull = null;
	$zoomX = abs($_GET["zoomx"] ?? 0.5);
	$zoomY = abs($_GET["zoomy"] ?? 0.5);

	// $aX = abs( ($_GET["x2"] ?? 0.1) - ($_GET["x1"] ?? 0) );  
	// $aY = abs( ($_GET["y2"] ?? 0.1) - ($_GET["y1"] ?? 0) ); 

	/*
$maxZoomX = $owidth / 640; 
$maxZoomY = $oheight / 480; 

echo "<p>Max Zoom X = $maxZoomX; Max Zoom Y = $maxZoomY;<p>"; 
*/

	$croppedVideoHeight =  $owidth * 480 / 640.0;
	if ($croppedVideoHeight > $oheight) {
		$croppedVideoHeight = $oheight;
	} // adjustment if video is too wide 
	$croppedVideoWidth = $croppedVideoHeight * 640.0 / 480.0;

	$maxZoomXC = $croppedVideoWidth / 640;
	$maxZoomYC = $croppedVideoHeight / 480;

	if (round($maxZoomXC - $maxZoomYC, 2) > 0) {
		echo "Error maxZoom: $maxZoomXC != $mazZoomYC <p>";
		die();
	}
	/*
echo "<p>Max Zoom XC = $maxZoomXC; Max Zoom YC = $maxZoomYC;<p>"; 
*/

	$zoom = $_GET["zoom"] ?? 1 ;
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
	if ($startX < 0) {
		$startX = 0;
	}
	if ($startX >  $w - $croppedVideoWidth) {
		$startX = $w - $croppedVideoWidth;
	}
	$startX = round($startX);

	$startY = $offsetY + $h / 2.0 - $croppedVideoHeight / 2.0;
	if ($startY < 0) {
		$startY = 0;
	}
	if ($startY >=  $h - $croppedVideoHeight) {
		$startY =  $h - $croppedVideoHeight;
	}
	$startY = round($startY);

	// echo "<p>startxX=$startX startY=$startY <p>";
	if (isset($_GET["b"]) && $_GET["b"] != "x") {
		$imfull = imagecreatetruecolor($owidth, $oheight);
		$mauve  = imagecolorallocate($imfull, 177, 156, 217);
		imagefilledrectangle($imfull, 0, 0, $owidth - 1, $oheight - 1, $mauve);

		$src = $zoomsourcefile;
		$im = @imagecreatefromjpeg($src);



		if ($imfull && $im) {
			$w = imagesx($imfull);
			$h = imagesy($imfull);

			// echo "<p>w=$w h=$h <p>";
			$yy = explode("y", $src);
			if (count($yy) != 3) {
				echo "An error occured src=$src <p>.";
				var_dump($_GET);
				die("<p>Thank you<p>");
			}
			$zd = $yy[1];
			$mzoomY = ($zd % 100) / 100.0;
			$zd -= $mzoomY;
			$zd /= 100.0;
			$mzoomX = ($zd % 100) / 100.0;
			$zd -= $mzoomX;
			$zd /= 100.0;
			$mzoom = $zd / 100;

			$mcroppedVideoHeight =  $owidth * 480 / 640.0;
			if ($mcroppedVideoHeight > $oheight) {
				$mcroppedVideoHeight = $oheight;
			} // adjustment if video is too wide 
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
			if ($mstartX < 0) {
				$mstartX = 0;
			}
			if ($mstartX >  $w - $mcroppedVideoWidth) {
				$mstartX = $w - $mcroppedVideoWidth;
			}
			$mstartX = round($mstartX);

			$mstartY = $moffsetY + $h / 2.0 - $mcroppedVideoHeight / 2.0;
			if ($mstartY < 0) {
				$mstartY = 0;
			}
			if ($mstartY >=  $h - $mcroppedVideoHeight) {
				$mstartY =  $h - $mcroppedVideoHeight;
			}
			$mstartY = round($mstartY);

			// echo "<p>mstartxX=$mstartX mstartY=$mstartY <p>";



			// imagecopyresized($imfull, $im, $startX, $startY, 0, 0, $croppedVideoWidth, $croppedVideoHeight, imagesx($im), imagesy($im)); 
			imagecopyresized($imfull, $im, $mstartX, $mstartY, 0, 0, $mcroppedVideoWidth, $mcroppedVideoHeight, imagesx($im), imagesy($im));

			$col_ellipse = imagecolorallocate($imfull, 255, 0, 255);
			imagefilledellipse($imfull, $startX, $startY, 5, 5, $col_ellipse);




			// imagejpeg($imfull, "tmp/zoomnozoom.jpg");
			if (!isset($_GET["preview"])) {
				imagejpeg($imfull, "tmp/zoomnozoomsource.jpg");
			}
		} else {
			die("Something went wrong! (AAA)");
		}
	}

	$src = "tmp/zoomnozoomsource.jpg";
	$im = @imagecreatefromjpeg($src);



	if ($im) {
		$w = imagesx($im);
		$h = imagesy($im);
		// echo "<p>(a) w=$w h=$h <p>"; 

		$inc = 0.0146;

		$lbx = min($_GET["x1"] ?? 0, $_GET["x2"] ?? 1);
		$ubx = max($_GET["x1"] ?? 0, $_GET["x2"] ?? 1);
		$lby = min($_GET["y1"] ?? 0, $_GET["y2"] ?? 1);
		$uby = max($_GET["y1"] ?? 0, $_GET["y2"] ?? 1);

		for ($y = 0; $y < $h; $y = $y + ($h * $inc)) {
			for ($x = 0; $x < $w; $x = $x + ($w * $inc)) {
				if ($lbx <= $x / $w && $x / $w < $ubx && $lby <= $y / $h  && $y / $h < $uby) {
					// do nothing
				} else {
					$colorB = imagecolorallocate($im, 255, 255, 0);

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

		$colorF = imagecolorallocate($im, 255, 0, 255);
		@imagefilledellipse($im, $startX, $startY, 15, 15, $colorF);

		$y0 = max(0, $lbyA - 0.02);
		$y1 = min($h, $ubyA + 0.02);

		$x0 = max(0, $lbxA - 0.02);
		$x1 = min($w, $ubxA + 0.02);

		for ($y = $y0 * $h; $y < $y1 * $h; $y = $y + ($h * $inc)) {
			for ($x = $x0 * $w; $x < $x1 * $w; $x = $x + ($w * $inc)) {
				if ($lbxA <= $x / $w && $x / $w < $ubxA && $lbyA <= $y / $h  && $y / $h < $ubyA) {
					// do nothing
				} else {
					$colorA = imagecolorallocate($im, 255, 0, 0);
					@imagefilledellipse($im, intval(floor($x)), intval(floor($y)), ceil($w * $inc / 2), ceil($h * $inc / 2), $colorA);
				}
			}
		}
		$col_ellipse = imagecolorallocate($im, 255, 200, 255);
		imagefilledellipse($im, $w * $xC, $h * $yC, 15, 15, $col_ellipse);
		imagejpeg($im, "tmp/zoomnozoom.jpg");
	}




	?>
	<script type="text/javascript">
		<!--
		// Source: https://www.chestysoft.com/imagefile/javascript/get-rectangle.asp
		var Point = 1;
		var X1, Y1, X2, Y2;

		var maxZoom = <?php echo $maxZoomXC ?>;




		function FindPosition(oElement) {
			if (typeof(oElement.offsetParent) != "undefined") {
				for (var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent) {
					posX += oElement.offsetLeft;
					posY += oElement.offsetTop;
				}
				return [posX, posY];
			} else {
				return [oElement.x, oElement.y];
			}
		}

		function setXCYC() {

			var abtn = document.getElementById("acceptbtn"); 
			abtn.removeAttribute("disabled");
			abtn.style.backgroundColor = 'lime';  
			var oheight = <?php echo $oheight; ?>;
			var owidth = <?php echo $owidth; ?>;

			var x1 = document.getElementById("x1").value;
			var x2 = document.getElementById("x2").value;
			var y1 = document.getElementById("y1").value;
			var y2 = document.getElementById("y2").value;

			if (y1 < 0 || x1 < 0) {
				document.getElementById("zoom").value = <?php echo ($_GET["zoom"] ?? 1) ?>;
				document.getElementById("xC").value = <?php echo ($_GET["zoomx"]?? 0.5) ?>;
				document.getElementById("yC").value = <?php echo ($_GET["zoomy"] ?? 0.5) ?>;
			} else {


				// if( Math.abs(y2- y1) < 0.01 ) { y2 += 0.01; } 
				// if( x2 == x1 ) { x2 += 0.01; } 
				var sollheight = Math.max(1, Math.abs(y2 - y1) * oheight);
				var sollwidth = Math.max(1, Math.abs(x2 - x1) * owidth);

				var croppedVideoHeight = owidth * 480 / 640.0;
				if (croppedVideoHeight > oheight) {
					croppedVideoHeight = oheight;
				} // adjustment if video is too wide 
				croppedVideoWidth = croppedVideoHeight * 640.0 / 480.0;

				var z1 = croppedVideoHeight / sollheight;
				var z2 = croppedVideoWidth / sollwidth;
				var z3 = croppedVideoWidth / owidth;
				var z4 = croppedVideoHeight / oheight;

				zoom2 = Math.max(z3, z4, Math.min(z1, z2));
				if (document.getElementById("keepMaxZoom").checked && zoom2 > maxZoom) {
					zoom2 = maxZoom;
				}
				document.getElementById("zoom").value = zoom2.toFixed(2);



				var xC = findCenter(x1, x2);
				xC = (xC > 0.99 ? 0.99 : xC);

				document.getElementById("xC").value = xC.toFixed(2);

				var yC = findCenter(y1, y2);
				yC = (yC > 0.99 ? 0.99 : yC);
				document.getElementById("yC").value = yC.toFixed(2);
			}
		}

		function findCenter(a1, a2) {
			// return a1 + (a2 - a1) / 2;
			return (a1 * 1.0 + a2 * 1.0) / 2;
		}

		function GetCoordinates(e) {
			var PosX = 0;
			var PosY = 0;
			var ImgPos;
			ImgPos = FindPosition(myImg);
			if (!e) var e = window.event;
			if (e.pageX || e.pageY) {
				PosX = e.pageX;
				PosY = e.pageY;
			} else if (e.clientX || e.clientY) {
				PosX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
				PosY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
			}
			PosX = PosX - ImgPos[0];
			PosY = PosY - ImgPos[1];
			<?php
			$ha =  640 *  $oheight / $owidth;
			echo "ha = $ha ;";
			?>

			if (Point == 1) {
				X1 = PosX;
				Y1 = PosY;
				Point = 2;
				document.getElementById("x1").value = (PosX / 640).toFixed(2);
				document.getElementById("y1").value = (PosY / ha).toFixed(2);
				setXCYC()
			} else {

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

		function Clear() {
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

			zz = "<?php echo ($_GET["videoinfo"] ?? "640,480") ?>";
			document.location.href = "setzoom.php?preview=1&videoinfo=" + zz + "&zoomx=" + xC + "&zoomy=" + yC +
				"&x1=" + document.getElementById("x1").value +
				"&y1=" + document.getElementById("y1").value +
				"&zoom=" + document.getElementById("zoom").value +
				"&x2=" + document.getElementById("x2").value +
				"&y2=" + document.getElementById("y2").value +
				"&id=<?php echo ($_GET["id"] ?? 0) ?>&b=<?php echo $_GET["b"] ?? "x" ?>";

		}


		function Initialisation() {
			// document.form1.drawbutton.disabled = true
			setTimeout(setXCYC, 1000);
		}

		//
		-->
	</script>

</head>

<body onload="Initialisation();">


	<form action="index.php">
		<h2>Click on two points in the image to set the coordinates. Accept when happy with choice.</h2>
		<p>
			<!--  Do not zoom more than it makes sense: --> <input hidden type="checkbox" checked="true" id="keepMaxZoom">

			<p>
				<?php
				$ha =  640 *  $oheight / $owidth;
				echo '<img src="tmp/zoomnozoom.jpg?t=' . time() . '" width="640" height="' . intval($ha) . '" alt="" id="myImgId" />';
				?>
				<!--
<input type="button" name="submitbutton" value="Preview" onclick="Preview();" /> 
&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  
-->
				<p>
					<input disabled id="acceptbtn" type="submit" class="accept" name="Submitcenter" value="Accept these settings." />
					<p>




						<input type="hidden" name="x1" id="x1" value="<?php echo ($_GET['x1'] ?? -1); ?>"></input>
						<input type="hidden" name="y1" id="y1" value="<?php echo ($_GET['y1'] ?? -1); ?>"></input>

						<input type="hidden" name="x2" id="x2" value="<?php echo ($_GET['x2'] ?? -1); ?>"></input>
						<input type="hidden" name="y2" id="y2" value="<?php echo ($_GET['y2'] ?? -1); ?>"></input>



						<p>Center at (
							<input class="inputx" readonly type="text" name="xC" id="xC" value="wait"></input>
							<input class="inputx" readonly type="text" name="yC" id="yC" value="wait"></input>);
							<br>
							Zoom is <input class="inputx" readonly type="text" name="zoom" id="zoom" value="<?php echo $zoom; ?>"></input> x.
							<br> Max Zoom possible is
							<input class="inputx" readonly type="text" name="maxzoomxc" id="maxzoomxc" value="<?php echo $maxZoomXC; ?>"></input> x.

							<p><input type="hidden" name="id" id="id" value="<?php echo ($_GET['id'] ?? 0); ?>"></input>


	</form>

	<a href="index.php">Home (no change)</a>;
	<a href="setzoom.php?videoinfo=<?php echo ($_GET["videoinfo"] ?? "640,480") ?>&id=<?php echo ($_GET['id'] ?? 0); ?>&b=<?php echo ($_GET['b'] ?? 0); ?>">
	Reset Zoom </a>
	<script type="text/javascript">
		
		var myImg = document.getElementById("myImgId");
		myImg.onmousedown = GetCoordinates;
		
	</script>

</body>

</html>