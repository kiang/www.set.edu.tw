<?php
$targetPath = __DIR__ . '/report/students';
if(!file_exists($targetPath)) {
    mkdir($targetPath, 0777, true);
}
$fh = fopen(__DIR__ . '/report/report_students.csv', 'r');
$result = array();
$header = fgetcsv($fh, 2048);
for($i = 2; $i <= 13; $i++) {
    $result[$header[$i]] = array();
}
while($line = fgetcsv($fh, 2048)) {
    $data = array_combine($header, $line);
    for($i = 2; $i <= 13; $i++) {
        if(!isset($result[$header[$i]][$data['縣市']])) {
            $result[$header[$i]][$data['縣市']] = array();
        }
        $result[$header[$i]][$data['縣市']][$data['日期']] = $data[$header[$i]];
    }
}
foreach($result AS $fn => $lv1) {
    $fh = fopen($targetPath . '/' . $fn . '.csv', 'w');
    $headerDone = false;
    foreach($lv1 AS $city => $lv2) {
        if(false === $headerDone) {
            fputcsv($fh, array_merge(array('縣市'), array_keys($lv2)));
            $headerDone = true;
        }
        fputcsv($fh, array_merge(array($city), $lv2));
    }
}