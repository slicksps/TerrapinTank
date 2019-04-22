<?PHP
header('Content-Type: application/json');
$tempofficefile = "tempsensor-office.csv";
$tempterrapinfile = "tempsensor-terrapin.csv";

$temp1 = array_map('str_getcsv', file($tempofficefile));
$temp2 = array_map('str_getcsv', file($tempterrapinfile));
foreach($temp1 as $k => $v) {
    $temp1[$k] = array("date"=>$v[0],
        "time"=>$v[1],
        "Otemp"=>$v[2],
        "Ohum"=>$v[3]
    );
}
foreach($temp2 as $k => $v) {
    $temp2[$k] = array("date"=>$v[0],
        "time"=>$v[1],
        "Ttemp"=>$v[2],
        "Thum"=>$v[3]
    );
}
$temp = array_merge($temp1,$temp2);


$showlastXdays = 3;
$notbefore = strtotime('-'.$showlastXdays.' day', time());


foreach($temp as $k => $v) {
    $dtest = $v['date'].' '.$v['time'];
    $timestamp = mktime(substr($dtest,11,2),substr($dtest,14,2),0,substr($dtest,3,2),substr($dtest,0,2),substr($dtest,6,4));
    $v['timestamp'] = $timestamp;
    if($timestamp >= $notbefore) {$nop[$k] = $v;}
}


function sortByTime($a, $b) {
    return $a['timestamp'] - $b['timestamp'];
}

usort($nop, 'sortByTime');


$op = json_encode($nop);
$op = str_replace("\u0000","",$op);
$op = str_replace('\/',"-",$op);
echo $op;

?>
