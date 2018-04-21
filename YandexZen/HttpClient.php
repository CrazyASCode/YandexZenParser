<?php

class HttpClient
{
    public $response;
    public $cookies;
    public $headers = [];
	public $timeout;

    public function __construct($timeout = 5)
    {
		$this->timeout = $timeout;
    }

    public function Get($url, $params = [])
    {
        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url . '?' . http_build_query($params));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
			curl_setopt($curl, CURLOPT_COOKIE, $this->cookies);
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			$response = curl_exec($curl);
			
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$headers = explode("\r\n", substr($response, 0, $header_size));
			$body = substr($response, $header_size);
			
			$cookies = $this->ExplodeCookies($headers);
			$this->response = $body;
			
			$this->cookies = implode('; ', $cookies);
			
            curl_close($curl);
            return true;
        } else {
            return false;
        }
    }

    public function Post($url, $params)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_COOKIE, $this->cookies);
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			$response = curl_exec($curl);
			
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$headers = explode("\r\n", substr($response, 0, $header_size));
			$body = substr($response, $header_size);
			
			$cookies = $this->ExplodeCookies($headers);
			$this->response = $body;
			
			$this->cookies = implode('; ', $cookies);

            curl_close ($curl);

            return true;
        } else {
            return false;
        }
    }

	private function ExplodeCookies($headers)
	{
		$matches = preg_grep('/^Set-Cookie:/i', $headers);
		$cookies = [];
		foreach ($matches as $cookie) {
			$cookies[] = explode(': ', $cookie)[1];
		}
		
		return $cookies;
	}
	
    public function PostFile($url, $file)
    {
        if ($curl = curl_init()) {
            if (!file_exists($file)) {
                try {
                    $data = file_get_contents($file);
                } catch (Exception $e) {
                    throw new Exception('Caught exception: ',  $e->getMessage());
                }
            }
            $data = file_get_contents($file);

            curl_setopt($curl, CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);

            $this -> response = curl_exec($curl);

            curl_close ($curl);

            return true;
        } else {
            return false;
        }
    }
}