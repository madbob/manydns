<?php

namespace ManyDNS;

abstract class Client
{
	protected $accepts_null_ip = false;
	protected $name = '';

	protected function doGet($username, $password, $url)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'ManyDNS PHP Package'
		));

		if ($username != null && $password != null) {
			curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
		}

		$resp = curl_exec($curl);
		curl_close($curl);
		return $resp;
	}

	protected function guessIP()
	{
		$ret = $this->doGet('http://ipecho.net/plain');
		return trim($ret);
	}

	protected function acceptsNullIP()
	{
		return $this->accepts_null_ip;
	}

	public function getName()
	{
		return $this->name;
	}

	public function updateNow($username, $password, $host, $ip = null)
	{
		if ($ip == null && $this->acceptsNullIP() === false) {
			$ip = $this->guessIP();
		}

		$this->doRealUpdate($username, $password, $host, $ip);
		return true;
	}

	protected abstract function doRealUpdate($username, $password, $host, $ip);
}
