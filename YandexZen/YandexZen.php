<?php
class YandexZen
{
	const ZEN = 'https://zen.yandex.ru';
	
	private $http;
	
	public function __construct()
	{
		$this->http = new HttpClient();
	}
	
	public function getFeed($feed)
	{
		if ($this->http->Get(self::ZEN . '/media/' . $feed)) {
			$html = $this->http->response;
			$rawJson = html_entity_decode($this->_Pars('<textarea id="init_data" style="display: none" hidden>', $html, '</')); /*hardcode*/
			$json = json_decode($rawJson, true);
			if (isset($json['publisher'])) {
				return ['result' => true, 'publications' => $json['publications'], 'publisher' => $json['publisher']];
			} else {
				return ['result' => false, 'error' => 'Feed not found'];
			}
		} else {
			return ['result' => false, 'error' => 'Http request failed'];
		}
	}
	
	private function _Pars($start, $source, $end) 
	{ 
		$result = ""; 
		$a = strpos($source, $start); 
		$a = $a + strlen($start); 
		$source = substr($source, $a, strlen($source) - ($a)); 
		$b = strpos($source, $end); 
		$result = substr($source, 0, ($b)); 
		return $result; 
	} 
}