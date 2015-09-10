<?php

class Bot {

	private $maxDomains = 400;

	private $postData = [
		'format' => 'Csv',
		'show_topical_trust_flow' => '1',
		'request_name' => 'BulkBacklinks',
		'index_data_source' => 'Fresh',
		'SortBy' => '-1'
	];

	private $outputFormattingMap = [
		'domain' => 0,
		'cf' => 13,
		'tf' => 14,
		'rd' => 6,
		'csubnets' => 8,
		'ips' => 7,
		'niche' => 15,
		'niche_tf' => 16,
	];

	public function __construct($cookie,  $proxy = false) {
		
		$this->curl = new Curl\Curl;

		$this->curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
		$this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
		$this->curl->setOpt(CURLOPT_TIMEOUT, 52);

		$this->curl->setHeader('Cookie', $cookie);

		if($proxy) {
			$this->proxy = explode('@',$proxy);
			$this->proxyAuth = false;
			
			if(count($this->proxy)===2){
				$this->proxyAuth = $this->proxy[1];
				$this->curl->setOpt(CURLOPT_PROXYUSERPWD, $this->proxyAuth);
			}

			$this->proxy = $this->proxy[0];
			$this->curl->setOpt(CURLOPT_PROXY, $this->proxy);

			echo 'Using proxy: '.$this->proxy.PHP_EOL;
		}
	}

	public function fetch() {
		$data = $this->postData;
		$key = 0;

		foreach ($this->Vdomains as $domain) {
			$data['item'.($key++)] = $domain;
		}
		
		$data['items'] = count($this->Vdomains);
		$this->csv = $this->curl->post('https://majestic.com/data-output',$data);
	}

	public function parse(){
		$data = explode(PHP_EOL, $this->csv);

		foreach ($data as $key => $row) {
			$data[$key] = explode(',',str_replace("\"",'',preg_replace('/[\n\r]+/', '', $row)));
			$data[$key][$this->outputFormattingMap['niche']] = str_replace('/',' / ',$data[$key][$this->outputFormattingMap['niche']]);
			if(!is_array($data[$key]) OR count($data[$key])!=35) {
				unset($data[$key]);
			}

		}

		$this->parsedData = $data;
	}

	public function verifyParsedResult() {
		if(is_array($this->parsedData) AND count($this->parsedData) > 1) {
			$header = $this->parsedData[0];
			// var_dump($header);
			if( 
				is_array($header) and count($header)==35 AND
				isset($header[0]) AND $header[0] == '﻿Item' AND
				isset($header[1]) AND $header[1] == 'ItemType' AND
				isset($header[2]) AND $header[2] == 'ReportCode' AND
				isset($header[3]) AND $header[3] == 'Status' AND
				isset($header[4]) AND $header[4] == 'ACRank' AND
				isset($header[5]) AND $header[5] == 'ExtBackLinks' AND
				isset($header[6]) AND $header[6] == 'RefDomains' AND
				isset($header[7]) AND $header[7] == 'RefIPs' AND
				isset($header[8]) AND $header[8] == 'RefSubNets' AND
				isset($header[9]) AND $header[9] == 'ExtBackLinksEDU' AND
				isset($header[10]) AND $header[10] == 'ExtBackLinksGOV' AND
				isset($header[11]) AND $header[11] == 'RefDomainsEDU' AND
				isset($header[12]) AND $header[12] == 'RefDomainsGOV' AND
				isset($header[13]) AND $header[13] == 'CitationFlow' AND
				isset($header[14]) AND $header[14] == 'TrustFlow' AND
				isset($header[15]) AND $header[15] == 'TopicalTrustFlow_Topic_0' AND
				isset($header[16]) AND $header[16] == 'TopicalTrustFlow_Value_0' AND
				isset($header[17]) AND $header[17] == 'TopicalTrustFlow_Topic_1' AND
				isset($header[18]) AND $header[18] == 'TopicalTrustFlow_Value_1' AND
				isset($header[19]) AND $header[19] == 'TopicalTrustFlow_Topic_2' AND
				isset($header[20]) AND $header[20] == 'TopicalTrustFlow_Value_2' AND
				isset($header[21]) AND $header[21] == 'TopicalTrustFlow_Topic_3' AND
				isset($header[22]) AND $header[22] == 'TopicalTrustFlow_Value_3' AND
				isset($header[23]) AND $header[23] == 'TopicalTrustFlow_Topic_4' AND
				isset($header[24]) AND $header[24] == 'TopicalTrustFlow_Value_4' AND
				isset($header[25]) AND $header[25] == 'TopicalTrustFlow_Topic_5' AND
				isset($header[26]) AND $header[26] == 'TopicalTrustFlow_Value_5' AND
				isset($header[27]) AND $header[27] == 'TopicalTrustFlow_Topic_6' AND
				isset($header[28]) AND $header[28] == 'TopicalTrustFlow_Value_6' AND
				isset($header[29]) AND $header[29] == 'TopicalTrustFlow_Topic_7' AND
				isset($header[30]) AND $header[30] == 'TopicalTrustFlow_Value_7' AND
				isset($header[31]) AND $header[31] == 'TopicalTrustFlow_Topic_8' AND
				isset($header[32]) AND $header[32] == 'TopicalTrustFlow_Value_8' AND
				isset($header[33]) AND $header[33] == 'TopicalTrustFlow_Topic_9' AND
				isset($header[34]) AND $header[34] == 'TopicalTrustFlow_Value_9'
			){
				return true;
			} 
		} else {
			die('Invalid parsed data format');
		}
		die('Invalid format returned from majestic!');
	}

	public function formatParsedData() {
		$data = $this->parsedData;
		$outputFormattingMap = $this->outputFormattingMap;
		
		unset($outputFormattingMap['domain']);
		unset($data[0]);

		$results = [];
		foreach ($data as $domainData) {
			// var_dump($domainData);
			$results[rtrim($domainData[$this->outputFormattingMap['domain']],'/')] = [];
			foreach ($outputFormattingMap as $metric => $parsedIndex) {
				$results[rtrim($domainData[$this->outputFormattingMap['domain']],'/')][$metric] = $domainData[$parsedIndex];
			}
		}
		
		$this->results = $results;
	}


	public function mergeVariations() {
		foreach ($this->domains as $domain) {

			$this->results[$domain]['cf'] = max($this->results[$domain]['cf'],$this->results['http://'.$domain]['cf'],$this->results['www.'.$domain]['cf'],$this->results['http://www.'.$domain]['cf']);
			$this->results[$domain]['tf'] = max($this->results[$domain]['tf'],$this->results['http://'.$domain]['tf'],$this->results['www.'.$domain]['tf'],$this->results['http://www.'.$domain]['tf']);
			$this->results[$domain]['rd'] = max($this->results[$domain]['rd'],$this->results['http://'.$domain]['rd'],$this->results['www.'.$domain]['rd'],$this->results['http://www.'.$domain]['rd']);
			$this->results[$domain]['csubnets'] = max($this->results[$domain]['csubnets'],$this->results['http://'.$domain]['csubnets'],$this->results['www.'.$domain]['csubnets'],$this->results['http://www.'.$domain]['csubnets']);
			$this->results[$domain]['ips'] = max($this->results[$domain]['ips'],$this->results['http://'.$domain]['ips'],$this->results['www.'.$domain]['ips'],$this->results['http://www.'.$domain]['ips']);
			$this->results[$domain]['niche_tf'] = max($this->results[$domain]['niche_tf'],$this->results['http://'.$domain]['niche_tf'],$this->results['www.'.$domain]['niche_tf'],$this->results['http://www.'.$domain]['niche_tf']);
		
			switch ($this->results[$domain]['niche_tf']) {
				case $this->results['http://'.$domain]['niche_tf']: {
					$this->results[$domain]['niche'] = $this->results['http://'.$domain]['niche'];
					break;
				}
				case $this->results['www.'.$domain]['niche_tf']: {
					$this->results[$domain]['niche'] = $this->results['www.'.$domain]['niche'];
					break;
				}
				case $this->results['http://www.'.$domain]['niche_tf']: {
					$this->results[$domain]['niche'] = $this->results['http://www.'.$domain]['niche'];
					break;
				}
				default: {
					// niche is already set
				}
			}

			unset($this->results['http://'.$domain]);
			unset($this->results['www.'.$domain]);
			unset($this->results['http://www.'.$domain]);
		}
	}

	public function check($domains) {
		
		$this->domains = array_slice($domains,0,intval($this->maxDomains/4));
		$this->Vdomains = [];

		foreach ($this->domains as $domain) {
			$this->Vdomains[] = $domain;
			$this->Vdomains[] = 'http://'.$domain;
			$this->Vdomains[] = 'www.'.$domain;
			$this->Vdomains[] = 'http://www.'.$domain;
		}
		
		echo 'Fetching data for '.count($this->domains).' domains (x4 variations not included)'.PHP_EOL;
		
		$this->fetch();

		if($this->curl->error) {
			die($this->curl->error_message);
		}
		
		
		$this->parse();
		$this->verifyParsedResult();
		$this->formatParsedData();
		$this->mergeVariations();

		return $this->results;	

	}
}
		
?>