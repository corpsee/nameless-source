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

	protected $hash_path;

	protected $templates = array
	(
		'css'  => '<link href="%s" rel="stylesheet" type="text/css" />',
		'less' => '<link href="%s" rel="stylesheet/less" type="text/css" />',
		'js'   => '<script src="%s" type="text/javascript"></script>',
	);

	public function __construct(\Pimple $container)
	{
		$this->container = $container;
	}

	protected function generateAssetsDebug (array $assets, $type = 'css')
	{
		$result_assets = '';
		if ($type === 'js')
		{
			$assets[] = $this->container['assets']['lessjs_url'];
		}

		foreach ($assets as $asset)
		{
			$result_assets .= sprintf($this->templates[$type], $asset);
		}
		return $result_assets;
	}

	protected function getHash (array $assets)
	{
		$hash = '';
		foreach ($assets as $asset)
		{
			$hash .= md5_file(URLToPath($asset));
		}

		return $hash;
	}

	protected function getCanonicalHash ($hash_path)
	{
		$canonical_hash = '';
		if (file_exists($hash_path))
		{
			$canonical_hash = trim(file_get_contents($hash_path));
		}
		return $canonical_hash;
	}

	protected function generateAssets (array $assets, $compiled_path, $type = 'css')
	{
		$file_filters     = array();
		if ($type = 'less')
		{
			$file_filters[] = new LessphpFilter();
		}

		$assets_instances = array();
		foreach ($assets as $asset)
		{
			$assets_instances[] = new FileAsset(URLToPath($asset), $file_filters);
		}

		$filters = array();
		if ($type === 'js')
		{
			$filters[] = new JsCompressorFilter($this->container['assets']['yuicompressor_path'], $this->container['assets']['java_path']);
		}
		else
		{
			$filters[] = new CssCompressorFilter($this->container['assets']['yuicompressor_path'], $this->container['assets']['java_path']);
		}

		$collection = new AssetCollection($assets_instances, $filters);

		file_put_contents($compiled_path, $collection->dump());
	}

	public function getAssets ($name, array $assets, $type = 'css')
	{
		if ($type)
		{

		}

		$compiled_path = $this->container['assets']['path'] . $name . '.' . $type;

		// debug
		if ($this->container['environment'] === 'debug')
		{
			return $this->generateAssetsDebug($assets, $type);
		}

		// production
		elseif ($this->container['environment'] === 'production')
		{
			if (file_exists($compiled_path))
			{
				return pathToURL($compiled_path);
			}

			return $this->generateAssetsDebug($assets, $type);
		}

		$hash_path      = $this->container['cache_path'] . $name . '-' . $type;
		$hash           = $this->getHash($assets);
		$canonical_hash = $this->getCanonicalHash($hash_path);

		if ($canonical_hash !== $hash || !file_exists($compiled_path))
		{
			$this->generateAssets($assets, $compiled_path, $hash_path, $hash, $type);
			file_put_contents($hash_path, $hash);
		}

		return sprintf($this->templates[$type], pathToURL($compiled_path));
	}
}