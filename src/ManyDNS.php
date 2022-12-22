<?php

namespace ManyDNS;

class ManyDNS
{
	const ERROR_INVALID_AUTH = 0;
	const ERROR_INVALID_HOST = 1;
	const ERROR_UNKNOWN = 99;

	public static function getProviders()
	{
		$providers = [];
		$folder = __DIR__ . '/Providers';

		foreach(scandir($folder) as $file) {
			if ($file == '.' || $file == '..')
				continue;
			$name = 'ManyDNS\\Providers\\' . str_replace('.php', '', $file);
			$providers[] = new $name();
		}

		return $providers;
	}

	public static function getProvider($name)
	{
		$name = strtolower($name);

		$providers = self::getProviders();
		foreach($providers as $provider) {
			$pname = strtolower($provider->getName());
			if ($pname == $name) {
				return $provider;
			}
		}

		return null;
	}
}
