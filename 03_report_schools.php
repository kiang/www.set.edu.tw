<?php
$targetPath = __DIR__ . '/report/schools';
if(!file_exists($targetPath)) {
    mkdir($targetPath, 0777, true);
}
$fh = fopen(__DIR__ . '/report/report_schools.csv', 'r');
$result = array();
$header = fgetcsv($fh, 2048);
for($i = 3; $i <= 34; $i++) {
    $result[$header[$i]] = array();
}
$dateLine = array();
while($line = fgetcsv($fh, 2048)) {
    $data = array_combine($header, $line);
    if(!isset($dateLine[$data['日期']])) {
        $dateLine[$data['日期']] = 0;
    }
    for($i = 3; $i <= 34; $i++) {
        if(!isset($result[$header[$i]][$data['縣市']])) {
            $result[$header[$i]][$data['縣市']] = array();
        }
        $result[$header[$i]][$data['縣市']][$data['日期']] = $data[$header[$i]];
    }
}
ksort($dateLine);
foreach($result AS $fn => $lv1) {
    $fh = fopen($targetPath . '/' . $fn . '.csv', 'w');
    fputcsv($fh, array_merge(array('縣市'), array_keys($dateLine)));
    foreach($lv1 AS $city => $lv2) {
        $theLine = $dateLine;
        foreach($lv2 AS $day => $val) {
            $theLine[$day] = $val;
        }
        fputcsv($fh, array_merge(array($city), $theLine));
    }
}