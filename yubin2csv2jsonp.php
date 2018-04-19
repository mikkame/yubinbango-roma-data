<?php
$zip = file_get_contents('http://www.post.japanpost.jp/zipcode/dl/roman/ken_all_rome.zip');
//file_put_contents('ken_all.zip', $zip);
//exec('unzip ken_all.zip');

$fp = fopen('php://temp', 'w');

fwrite($fp, mb_convert_encoding(file_get_contents('KEN_ALL_ROME.CSV'), 'utf-8', 'sjis'));
rewind($fp);

$regions =  [
      null, 'HOKKAIDO','AOMORI KEN','IWATE KEN','MIYAGI KEN','AKITA KEN','YAMAGATA KEN','FUKUSHIMA KEN','IBARAKI KEN','TOCHIGI KEN','GUMMA KEN','SAITAMA KEN','CHIBA KEN','TOKYO TO','KANAGAWA KEN','NIIGATA KEN','TOYAMA KEN','ISHIKAWA KEN','FUKUI KEN','YAMANASHI KEN','NAGANO KEN','GIFU KEN','SHIZUOKA KEN','AICHI KEN','MIE KEN','SHIGA KEN','KYOTO FU','OSAKA FU','HYOGO KEN','NARA KEN','WAKAYAMA KEN','TOTTORI KEN','SHIMANE KEN','OKAYAMA KEN','HIROSHIMA KEN','YAMAGUCHI KEN','TOKUSHIMA KEN','KAGAWA KEN','EHIME KEN','KOCHI KEN','FUKUOKA KEN','SAGA KEN','NAGASAKI KEN','KUMAMOTO KEN','OITA KEN','MIYAZAKI KEN','KAGOSHIMA KEN','OKINAWA KEN'
    ];
$data = [];

while($row = fgetcsv($fp)) {
	$zipcode = $row[0];
	$pref = $row[4];
	$addr1 = $row[5];
	$addr2 = $row[6];
	$zip3 = substr($zipcode,0,3);
	$zip4 = substr($zipcode,3, 4);

	$data[$zip3][$zipcode] = [
		array_search($pref, $regions),
		$addr1,
		$addr2 == 'IKANIKEISAIGANAIBAAI' ? '' : $addr2
	];
}
exec('rm -rf data');
mkdir('data');

foreach ($data as $zip3 => $data) {
	file_put_contents('data/'.$zip3.'.js', '$yubin('.json_encode($data).');');
}