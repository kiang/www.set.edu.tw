<?php
$reportPath = __DIR__ . '/report';
if(!file_exists($reportPath)) {
    mkdir($reportPath, 0777, true);
}
$result = array();
$keyPool = false;
$oFh = fopen(__DIR__ . '/report/report_schools.csv', 'w');
foreach(glob(__DIR__ . '/csv/schools/*.csv') AS $csvFile) {
    $p = pathinfo($csvFile);
    $fh = fopen($csvFile, 'r');
    $header = fgetcsv($fh, 2048);
    if(false === $keyPool) {
        $keyPool = array('日期');
        foreach($header AS $k => $v) {
            $keyPool[] = $v;
        }
        fputcsv($oFh, $keyPool);
    }
    while($line = fgetcsv($fh, 2048)) {
        $data = array_combine($header, $line);
        if($data['縣市'] === '總計' || false !== strpos($data['縣市'], '地區')) {
            continue;
        }
        $data['日期'] = $p['filename'];
        $result = array();
        foreach($keyPool AS $key) {
            if(isset($data[$key])) {
                $result[] = $data[$key];
            } else {
                $result[] = '';
            }
        }
        fputcsv($oFh, $result);
    }
}