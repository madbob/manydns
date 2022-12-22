ManyDNS
========

This package wraps the update API for many different dynamic DNS providers.

Currently supported providers:

* NoIP https://www.noip.com/
* ChangeIP http://www.changeip.com/
* Dynu https://www.dynu.com/
* DuckDNS https://www.duckdns.org/

# Installation

`composer require madbob/manydns`

# Usage

```php
require 'vendor/autoload.php';

use ManyDNS\ManyDNS;
use ManyDNS\FailedUpdateException;

/*
	To obtain a list of supported providers
*/
$providers = ManyDNS::getProviders();
foreach($providers as $provider) {
	echo $provider->getName() . "\n";
}

/*
	getProvider() accepts the name of a supported provider, and returns a
	ManyDNS\Client object (or NULL if none is found).
*/
$provider = ManyDNS::getProvider('NoIP');

/*
	To perform a new update of DNS addressing, just call the updateNow()
	function on the preferred client.
	The $ip parameter is optional: most providers accepts the current public IP
	as default, if not the package tries to retrieve the current public IP of
	the instance.
*/
try {
	$provider->updateNow($username, $password, $hostname, $ip);
}
catch (FailedUpdateException $e) {
	/*
		In case of error, FailedUpdateException provides both a human message
		and an error code defined as:
		ManyDNS::ERROR_INVALID_AUTH
		ManyDNS::ERROR_INVALID_HOST
		ManyDNS::ERROR_UNKNOWN
	*/
	echo $e->getMessage() . "\n";
	echo $e->getCode() . "\n";
}
```

# Special Behaviors

The DuckDNS authentication is based on a single token, to be used in place of the password when asking for an update.

```
$provider = ManyDNS::getProvider('DuckDNS');
$provider->updateNow(null, $token, $hostname, $ip);
```
