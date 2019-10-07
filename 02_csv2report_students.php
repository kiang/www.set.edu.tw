<?php
$reportPath = __DIR__ . '/report';
if(!file_exists($reportPath)) {
    mkdir($reportPath, 0777, true);
}
$result = array();
foreach(glob(__DIR__ . '/csv/students/*/*.csv') AS $csvFile) {
    $p = pathinfo($csvFile);
    $c = pathinfo($p['dirname']);
    if(!isset($result[$p['filename']])) {
        $result[$p['filename']] = array();
    }
    if(!isset($result[$p['filename']][$c['filename']])) {
        $result[$p['filename']][$c['filename']] = array();
    }
    $fh = fopen($csvFile, 'r');
    $header = false;
    while($line = fgetcsv($fh, 2048)) {
        if(count($line) < 5) {
            continue;
        }
        if(false !== $header) {
            $data = array_combine($header, $line);
            if($data['學校'] === '總計') {
                $result[$p['filename']][$c['filename']][] = isset($data['智能障礙']) ? $data['智能障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['視覺障礙']) ? $data['視覺障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['聽覺障礙']) ? $data['聽覺障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['語言障礙']) ? $data['語言障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['肢體障礙']) ? $data['肢體障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['身體病弱']) ? $data['身體病弱'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['嚴重情緒障礙']) ? $data['嚴重情緒障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['學習障礙']) ? $data['學習障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['多重障礙']) ? $data['多重障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['自閉症']) ? $data['自閉症'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['發展遲緩']) ? $data['發展遲緩'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['其他顯著障礙']) ? $data['其他顯著障礙'] : 0;
                $result[$p['filename']][$c['filename']][] = isset($data['小計']) ? $data['小計'] : 0;
            }
        } else {
            $header = $line;
            $header[1] = '學校';
        }
    }
}
ksort($result);
$fh = fopen($reportPath . '/report_students.csv', 'w');
fputcsv($fh, array('日期', '縣市', '智能障礙', '視覺障礙', '聽覺障礙', '語言障礙', '肢體障礙', '身體病弱', '嚴重情緒障礙', '學習障礙', '多重障礙', '自閉症', '發展遲緩', '其他顯著障礙', '小計'));
foreach($result AS $theDate => $set1) {
    foreach($set1 AS $city => $data) {
        fputcsv($fh, array_merge(array($theDate, $city), $data));
    }
}