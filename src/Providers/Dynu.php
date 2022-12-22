<?php

namespace ManyDNS\Providers;

use ManyDNS\ManyDNS;
use ManyDNS\Client;
use ManyDNS\FailedUpdateException;

/*
	https://www.dynu.com/en-US/DynamicDNS/IP-Update-Protocol
*/

class Dynu extends Client
{
	public function __construct()
	{
		$this->accepts_null_ip = true;
		$this->name = "Dynu";
	}

	public function doRealUpdate($username, $password, $hostname, $ip)
	{
		if ($ip == null) {
			$url = sprintf('http://api.dynu.com/nic/update?username=%s&password=%s&hostname=%s', $username, md5($password), $hostname);
		}
		else {
			$url = sprintf('http://api.dynu.com/nic/update?username=%s&password=%s&hostname=%s&myip=%s', $username, md5($password), $hostname, $ip);
		}

		$resp = $this->doGet(null, null, $url);
		$resp = trim($resp);

		if (strpos($resp, 'good') !== 0) {
			if ($resp == 'badauth') {
				throw new FailedUpdateException('Invalid Authentication', ManyDNS::ERROR_INVALID_AUTH);
			}
			else {
				throw new FailedUpdateException('Unknown Error', ManyDNS::ERROR_UNKNOWN);
			}
		}
	}
}
