<?php

namespace ManyDNS\Providers;

use ManyDNS\ManyDNS;
use ManyDNS\Client;
use ManyDNS\FailedUpdateException;

/*
	http://www.changeip.com/accounts/knowledgebase.php?action=displayarticle&id=34
*/

class ChangeIP extends Client
{
	public function __construct()
	{
		$this->accepts_null_ip = true;
		$this->name = "ChangeIP";
	}
	
	public function doRealUpdate($username, $password, $hostname, $ip)
	{
		if ($ip == null)
			$url = sprintf('https://nic.changeip.com/nic/update?hostname=%s', $hostname);
		else
			$url = sprintf('https://nic.changeip.com/nic/update?hostname=%s&ip=%s', $hostname, $ip);
			
		$resp = $this->doGet($username, $password, $url);
		$resp = trim($resp);

		if ($resp != '200 Successful Update') {
			if (strpos($resp, '401') === 0)
				throw new FailedUpdateException('Invalid Authentication', ManyDNS::ERROR_INVALID_AUTH);
			else if (strpos($resp, '422') === 0)
				throw new FailedUpdateException('Invalid Host', ManyDNS::ERROR_INVALID_HOST);
			else
				throw new FailedUpdateException('Unknown Error', ManyDNS::ERROR_UNKNOWN);
		}
	}
}

