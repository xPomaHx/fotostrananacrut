<?php

$url = $_GET['url'];
$query = http_build_query([
	'autologinlink' => $_GET['autologinlink'],
	'proxynow' => $_GET['proxynow'],
	'likeid' => $_GET['likeid'],
]);
echo getData("http://188.134.2.171:9999" . "/?" . $query);
function getData($url) {
	$ch = curl_init();
	$timeout = 50;
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
