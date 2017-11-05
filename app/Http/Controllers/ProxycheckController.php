<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ProxycheckController extends Controller {
	//
	public function getwaytest() {

		$output = '';

		foreach ($_SERVER as $key => $value) {
			if (!empty($value)) {
				$output .= $key . '--' . $value . '---';
			}
		}

		$output = substr($output, 0, -3);

		die($output);
		//return view('test');

	}
	public function gatewayResults($url, $proxy) {
		$types = array(
			'http',
			'socks4',
			'socks5',
		);

		$url = curl_init($url);

		curl_setopt($url, CURLOPT_PROXY, $proxy);

		foreach ($types as $type) {
			switch ($type) {
			case 'http':
				curl_setopt($url, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				break;
			case 'socks4':
				curl_setopt($url, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
				break;
			case 'socks5':
				curl_setopt($url, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				break;
			}

			curl_setopt($url, CURLOPT_TIMEOUT, 10);
			curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);

			$resultsQuery = explode('---', curl_exec($url));

			if (!empty($resultsQuery)) {
				break;
			}
		}

		$results = array();

		foreach ($resultsQuery as $result) {
			if (!empty($result)) {
				$split = explode('--', $result);

				if (!empty($split[1])) {
					$results[$split[0]] = $split[1];
				}
			}
		}

		curl_close($url);
		unset($url);

		return $results;
	}

	public function checkAnonymity($server = array()) {
		$realIp = $_SERVER['REMOTE_ADDR'];

		$level = 'transparent';

		if (!in_array($realIp, $server)) {
			$level = 'anonymous';

			$proxyDetection = array(
				'HTTP_X_REAL_IP',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_X_PROXY_ID',
				'HTTP_VIA',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_FORWARDED_FOR',
				'HTTP_X_FORWARDED',
				'HTTP_FORWARDED',
				'HTTP_CLIENT_IP',
				'HTTP_FORWARDED_FOR_IP',
				'VIA',
				'X_FORWARDED_FOR',
				'FORWARDED_FOR',
				'X_FORWARDED FORWARDED',
				'CLIENT_IP',
				'FORWARDED_FOR_IP',
				'HTTP_PROXY_CONNECTION',
				'HTTP_XROXY_CONNECTION',
			);

			if (!array_intersect(array_keys($server), $proxyDetection)) {
				$level = 'elite';
			}
		}

		return $level;
	}
	public function index(Request $request) {
		$proxy = "";
		$proxy = $request->proxy;
		if ($proxy == "") {
			return "Недостаточно параметров";
		} else {
			$url = url('getwaytest');
			$gatewayResults = $this->gatewayResults($url, $proxy);
			$checkAnonymity = $this->checkAnonymity($gatewayResults);
			return $checkAnonymity;
		}
	}
}
