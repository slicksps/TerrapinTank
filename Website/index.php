<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Simon's Terrapins</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/cmGauge.css">
    <link rel="stylesheet" href="css/onoffswitch.css">
    <style>
     .imagepad {padding-bottom:20px;}
        h1 {font-size:17px; margin-top:30px; border-bottom:1px solid red;}
        h2 {font-size:16px; margin-top: 20px; margin-bottom: 20px;}
        p, .relayText {font-size:12px;}
        .relayText {position:relative;top:-20px; margin-left:20px;}
        .terrapintemp, .officetemp {display:block;}
        .center {text-align:center;}
        .serverdata {display:inline-block; margin:0 auto;text-align:left;}
        .switches {margin-top:30px;}
     @media only screen and (max-width: 1024px) {
         .gauge.gauge-big {
             font-size: 95px;
         }
     }
     @media only screen and (max-width: 767px) {
         .gauge.gauge-big {
             font-size: 80px;
         }
     }
    </style>
</head>
<body>

<div class="container">
    <div class="col-12">
        <h1>Dials</h1>
    </div>
<div class="row">
    <div class="col-6 col-sm-6 col-md-6 center">
        <h2>Office</h2>
        <div id="" class="gauge gauge-big">
            <div class="gauge-arrow arrow1" data-percentage="0"
                 style="transform: rotate(0deg);"></div>
        </div>
        <span class="officetemp"></span>
    </div>
    <div class="col-6 col-sm-6 col-md-6 center">
        <h2>Terrapin</h2>
        <div id="" class="gauge gauge-big">
            <div class="gauge-arrow arrow2" data-percentage="0"
                 style="transform: rotate(0deg);"></div>
        </div>
        <span class="terrapintemp"></span>
    </div>


    <div class="col-12 order-sm-6 col-sm-6 col-md-6 center"><div class="serverdata switches">
        <?PHP
        $filename = "terrapin-relaystate.json";
        $relaystates = json_decode(file_get_contents($filename),true);
        foreach($relaystates as $relay => $data) {
            if($data['state'] == 1) {$image = 'checked';} else {$image = '';}
            $image = '

<div class="onoffswitch">
    <input type="checkbox" name="onoffswitch_'.$relay.'" data-switch="'.$relay.'" class="onoffswitch-checkbox" id="myonoffswitch_'.$relay.'" '.$image.'>
    <label class="onoffswitch-label" for="myonoffswitch_'.$relay.'">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</div>';
    echo $image.'<span class="relayText">'.str_replace("_", " ", $relay).'</span><br>';

    }

        ?>
            </div>
    </div>

    <div class="col-12 order-sm-1 col-sm-6 col-md-6">
        <h2 class="center">Pi Status</h2>
        <p class="center"><span class="serverdata">
        <?php
        $data = nl2br(file_get_contents('terrapin-pi-state.txt'));
        echo $data;




        ?></span></p>

    </div>
</div>


    <div class="col-12">
        <h1>Temperatures</h1>
    </div>

<div class="col chart-container">
    <canvas id="mycanvas"></canvas>
</div>
    <div class="col-12">
        <h1>Humidity</h1>
    </div>
    <div class="col chart-container">
        <canvas id="mycanvas2"></canvas>
    </div>
<div class="row">
<div class="col-12">
<h1>Setup (not live)</h1>
<img src="fulltank.jpg" class="img-fluid">
</div>
</div>

    <div class="row">
        <div class="col-12">
        <h1>Basking Area (Timed Photos)</h1>
    </div>
    <?PHP
    $path = "images";
    $i = 0;
    $imagelimit = 10;
    $deleteextras = true;
$imagearray = array();
$delmearray = array();
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ('.' === $file) continue;
            if ('..' === $file) continue;
            $name = explode(".",$file);
            $name = $name[0];
            $sortno = substr($name,0,4).substr($name,5,2).substr($name,8,2).substr($name,11,2).substr($name,14,2);
            $name = substr($name,8,2).'/'.substr($name,5,2).'/'.substr($name,0,4).' @ '.substr($name,11,5);
            $imagearray[$sortno] = '<div class="col-12 col-sm-6"><div class="imagepad"><img src="images/'.$file.'" class="img-fluid" /><span class="caption">'.$name.'</span></div></div>';
            $delmearray[$sortno] = 'images/'.$file;
        }
        closedir($handle);
    }
    krsort($imagearray);
    $i = 0;
    foreach($imagearray as $k => $image) {
        $i++;
        if($i <= $imagelimit) {
            echo $image;
        } elseif($deleteextras) {
            unlink($delmearray[$k]);
        }
    }


    ?>
    </div>
    <div class="row">
        <div class="col-12 center">
            <?PHP
            $data = file_get_contents('topprocesses.txt');
            $data = explode("\n",$data);
            $go = false;
            $count = 0;
            foreach ($data as $line) {
            if(strstr($line,"Cpu(s):")) {
            $cpu = explode('Cpu(s):',$line);
            //echo $cpu[1];
            $cpu = explode('us,',$cpu[1]);
            $cpu = trim($cpu[0]);
            echo "CPU: ".$cpu.'%<br>';
            }
            if(strstr($line,"PID USER")) {
            $go = true;
            } elseif ($go == true) {
            $oldline = explode(" ",$line);
            $line = array();
            foreach($oldline as $l) {
            if(strlen(trim($l))>1) {$line[] = $l;}
            }
            $process = $line[count($line)-1];
            $time = $line[count($line)-2];
            $perc = $line[count($line)-4];
            echo $process.' '.$perc.' '.$time.'<br>';
            $count++;
            if($count == 10) {$go = false;}

            }
            }

?><br /><br />
        </div>
    </div>
</div>
<!-- javascript -->
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/Chart.js"></script>
<script type="text/javascript" src="js/linegraph.js"></script>
<script src="js/cmGauge.js"></script>
<script>
$(document).ready(function(){
    $('body').find('input').click(function(e){
        e.preventDefault();
        var pw = prompt("Password");
        $.ajax({
            url: "actonpi.php?password="+pw+"&switch="+$(this).data('switch'),
            context: document.body
        }).done(function() {
            $( this ).addClass( "done" );
        });

    });})

</script>
</body>
</html>
