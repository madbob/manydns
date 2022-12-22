<?php

namespace ManyDNS\Providers;

use ManyDNS\ManyDNS;
use ManyDNS\Client;
use ManyDNS\FailedUpdateException;

/*
	https://www.duckdns.org/spec.jsp
*/

class DuckDNS extends Client
{
	public function __construct()
	{
		$this->accepts_null_ip = true;
		$this->name = "DuckDNS";
	}

	public function doRealUpdate($username, $password, $hostname, $ip)
	{
		if ($ip == null) {
			$url = sprintf('https://www.duckdns.org/update?domains=%s&token=%s', $hostname, $password);
		}
		else {
			$url = sprintf('https://www.duckdns.org/update?domains=%s&token=%s&ip=%s', $hostname, $password, $ip);
		}

		$resp = $this->doGet(null, null, $url);
		$resp = trim($resp);

		if ($resp != 'OK') {
			throw new FailedUpdateException('Unknown Error', ManyDNS::ERROR_UNKNOWN);
		}
	}
}
