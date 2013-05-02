<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Assets;

use Assetic\Asset\AssetCollection;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Asset\FileAsset;

class AssetsDispatcher
{
	protected $container;

	//TODO: нужен ModuleProvider ($container->assets_path)
	public function __construct(\Pimple $container)
	{
		$this->container = $container;
	}

	//TODO: сделать обработку LESS
	protected function generateAssets ($name, array $assets, $type = 'css')
	{
		switch ($type)
		{
			case 'js':
				//TODO: вынести пути в настройки приложения
				//$result_path = SCRIPT_PATH . $name . '.' . $type;
				$result_path = $this->container->assets_path . $name . '.' . $type;
				break;
			case 'css':
			default:
				//$result_path = STYLE_PATH . $name . '.' . $type;
				$result_path = $this->container->assets_path . $name . '.' . $type;
		}

		// debug
		if ($this->container->environment === 'debug')
		{
			return $assets;
		}
		// production
		elseif ($this->container->environment === 'production')
		{
			if (file_exists($result_path))
			{
				return pathToURL($result_path);
			}

			return $assets;
		}

		// $this->container->environment === test
		$hash_path = $this->container->cache_path . $name . '-' . $type;

		$hash = '';
		//echo '<pre>'; print_r($assets); exit;
		foreach ($assets as $asset)
		{
			$hash .= md5_file(URLToPath($asset));
		}

		$canonical_hash = '';
		if (file_exists($hash_path))
		{
			$canonical_hash = trim(file_get_contents($hash_path));
		}

		if ($canonical_hash !== $hash || !file_exists($result_path))
		{
			$assets_array = array();
			foreach ($assets as $asset)
			{
				$assets_array[] = new FileAsset(URLToPath($asset));
			}

			switch ($type)
			{
				case 'js':
					$filter = new JsCompressorFilter($this->container->yuicompressor_path, $this->container->java_path);
					break;
				case 'css':
				default:
					$filter = new CssCompressorFilter($this->container->yuicompressor_path, $this->container->java_path);
			}

			$collection = new AssetCollection
			(
				$assets_array,
				array($filter)
			);

			file_put_contents($result_path, $collection->dump());
			file_put_contents($hash_path, $hash);
		}

		return array(pathToURL($result_path));
	}
}