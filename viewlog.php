<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

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
        echo "<p>No Clarifai actions recorded (the file $logfile does not exist)."; 
        echo '<p> <a href="index.php?t='.time().'&src=fromlog" >Home </a></p>';
    }

        ?>
    </p>
</body>

</html>