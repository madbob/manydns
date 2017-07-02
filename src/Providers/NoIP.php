<?php

namespace ManyDNS\Providers;

use ManyDNS\ManyDNS;
use ManyDNS\Client;
use ManyDNS\FailedUpdateException;

/*
	https://www.noip.com/integrate/request
*/

class NoIP extends Client
{
	public function __construct()
	{
		$this->accepts_null_ip = true;
		$this->name = "NoIP";
	}
	
	public function doRealUpdate($username, $password, $hostname, $ip)
	{
		if ($ip == null)
			$url = sprintf('http://dynupdate.no-ip.com/nic/update?hostname=%s', $hostname);
		else
			$url = sprintf('http://dynupdate.no-ip.com/nic/update?hostname=%s&myip=%s', $hostname, $ip);
			
		$resp = $this->doGet($username, $password, $url);
		$resp = trim($resp);

		if (strpos($resp, 'good') !== 0 && strpos($resp, 'nochg') !== 0) {
			if ($resp == 'badauth')
				throw new FailedUpdateException('Invalid Authentication', ManyDNS::ERROR_INVALID_AUTH);
			else if ($resp == 'nohost')
				throw new FailedUpdateException('Invalid Host', ManyDNS::ERROR_INVALID_HOST);
			else
				throw new FailedUpdateException('Unknown Error', ManyDNS::ERROR_UNKNOWN);
		}
	}
}

