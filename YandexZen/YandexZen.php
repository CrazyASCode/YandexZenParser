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
		if ($this->http->Get(self::ZEN . '/' . $feed)) {
			$html = $this->http->response;
			$rawJson = html_entity_decode($this->_Pars('<script >window.__SERVER_STATE__ = ', $html, ';</script>')); /*hardcode*/
			$json = json_decode($rawJson, true);
			if (isset($json['feed'])) {
				return ['result' => true, 'publications' => $json['feed']['items'], 'publisher' => $json['channelList'][$json['channel']['id']]['title']];
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
