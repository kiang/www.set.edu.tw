<?php
$days = array(
    '2019/5/28',
    '2019/3/20',
    '2018/10/20',
    '2018/5/28',
    '2018/3/20',
    '2017/10/20',
    '2017/3/20',
    '2016/10/20',
    '2016/5/30',
    '2016/3/21',
    '2015/10/21',
    '2015/5/28',
    '2015/3/20',
    '2014/10/20',
    '2014/5/28',
    '2014/3/20',
    '2013/10/21',
    '2013/5/28',
    '2013/3/20',
    '2012/10/20',
    '2012/5/28',
    '2012/3/20',
    '2011/10/20',
    '2011/5/30',
    '2011/3/21',
    '2011/3/1',
    '2010/10/20',
    '2010/5/28',
    '2010/3/22',
    '2009/10/20',
    '2009/6/1',
);
$baseUrl = 'https://www.set.edu.tw/Stastic_WEB/sta2/doc/Esch_tea_cls_stu_AB/Esch_tea_cls_stu_AB_20091020.asp';
$header = array(
    '',
    '縣市',
'附設校數總計',
'附設校數身心障礙類',
'附設校數資賦優異類',
'老師數總計',
'老師數身心障礙類',
'老師數資賦優異類',
'班級數總計',
'班級數身心障礙類小計',
'班級數身心障礙類集中式特教班',
'班級數身心障礙類分散式資源班',
'班級數身心障礙類巡迴輔導',
'班級數資賦優異類小計',
'班級數資賦優異類資優班(集中式)',
'班級數資賦優異類美術班',
'班級數資賦優異類音樂班',
'班級數資賦優異類舞蹈班',
'班級數資賦優異類數理資源班',
'班級數資賦優異類資優巡迴輔導',
'學生人數總計',
'學生人數身心障礙類小計',
'學生人數身心障礙類集中式特教班',
'學生人數身心障礙類分散式資源班',
'學生人數身心障礙類巡迴輔導',
'學生人數身心障礙類接受特教服務',
'學生人數資賦優異類小計',
'學生人數資賦優異類資優班(集中式)',
'學生人數資賦優異類美術班',
'學生人數資賦優異類音樂班',
'學生人數資賦優異類舞蹈班',
'學生人數資賦優異類數理資源班',
'學生人數資賦優異類資優巡迴輔導',
'學生人數資賦優異類資優方案',);
$rawPath = __DIR__ . '/raw/schools/';
if(!file_exists($rawPath)) {
    mkdir($rawPath, 0777, true);
}
$targetPath = __DIR__ . '/csv/schools/';
if(!file_exists($targetPath)) {
    mkdir($targetPath, 0777, true);
}
foreach($days AS $day) {
    $theTime = strtotime($day);
    $theYear = date('Y', $theTime);
    $theDate = date('Ymd', $theTime);
    $htmlFile = $rawPath . '/' . $theDate . '.html';
    if(!file_exists($htmlFile)) {
        file_put_contents($htmlFile, file_get_contents(str_replace('20091020', $theDate, $baseUrl)));
    }
    $fh = fopen($targetPath . '/' . $theDate . '.csv', 'w');
    fputcsv($fh, $header);
    $html = mb_convert_encoding(file_get_contents($htmlFile), 'utf-8', 'big5');
    $lines = preg_split('/<tr[^\\>]*>/', $html);
    foreach($lines AS $line) {
        $cols = preg_split('/<td[^\\>]*>/', $line);
        foreach($cols AS $k => $col) {
            $cols[$k] = trim(strip_tags($col));
            if(false !== strpos($cols[$k], chr(13))) {
                $cols[$k] = explode(chr(13), $cols[$k])[0];
            }
        }
        if(count($cols) === 34) {
            fputcsv($fh, $cols);
        }
    }
}