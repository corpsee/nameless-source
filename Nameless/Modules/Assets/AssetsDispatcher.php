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
use Assetic\Factory\AssetFactory;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Filter\LessphpFilter;
use Assetic\Asset\FileAsset;

/**
 * AssetsDispatcher class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AssetsDispatcher
{
	/**
	 * @var \Pimple
	 */
	protected $container;

	/**
	 * @var string
	 */
	protected $hash_path;

	/**
	 * @var array
	 */
	protected $templates = array
	(
		'css'  => '<link href="%s" rel="stylesheet" type="text/css" />',
		'less' => '<link href="%s" rel="stylesheet/less" type="text/css" />',
		'js'   => '<script src="%s" type="text/javascript"></script>',
	);

	/**
	 * @param \Pimple $container
	 */
	public function __construct(\Pimple $container)
	{
		$this->container = $container;
	}

	protected function getAssetType($asset)
	{
		$type = 'css';
		if (stripos($asset, 'js') === FALSE)
		{
			$type = 'js';
		}
		elseif (stripos($asset, 'less') === FALSE)
		{
			$type = 'less';
		}

		if (!in_array($type, array('css', 'less', 'js')))
		{
			throw new \LogicException('Invalid asset type: ' . $type);
		}
		return $type;
	}

	protected function getAssetsCollectionType(array $assets)
	{
		$type = 'css';
		if (stripos($assets[0], 'js') === FALSE)
		{
			$type = 'js';
		}

		if (!in_array($type, array('css', 'js')))
		{
			throw new \LogicException('Invalid asset type: ' . $type);
		}
		return $type;
	}

	protected function assetsNormalize (array $assets)
	{
		foreach ($assets as &$asset)
		{
			$asset = array
			(
				'asset' => $asset,
				'type'  => $this->getAssetType($asset),
			);
		}
		unset($asset);
		return $assets;
	}

	public function getAssets ($name, array $assets, $compress = TRUE, $assets_path = NULL)
	{
		if (is_null($assets_path))
		{
			$assets_path = $this->container['assets.path'];
		}

		// css + less
		if (is_array($assets[0]))
		{
			$type           = $this->getAssetsCollectionType($assets[0]);
			$compiled_path  = $assets_path . $name . '.css';
			$result_assets  = '';
			$hash           = '';
			$hash_path      = $this->container['cache_path'] . $name . '-' . $type;
			$canonical_hash = $this->getCanonicalHash($hash_path);
			foreach ($assets as &$assets_collection)
			{
				$assets_collection = $this->assetsNormalize($assets_collection);
			}
			unset($assets_collection);

			if ($this->container['environment'] === 'production' && file_exists($compiled_path))
			{
				$result_assets = sprintf($this->templates[$type], pathToURL($compiled_path));
			}
			elseif ($this->container['environment'] === 'debug' || $this->container['environment'] === 'production')
			{
				foreach ($assets as $assets_collection)
				{
					$result_assets .= $this->generateAssetsDebug($assets);
				}
			}
			else
			{
				foreach ($assets as $assets_collection)
				{
					$hash .= $this->getHash($assets_collection);
				}
				if ($canonical_hash !== $hash || !file_exists($compiled_path))
				{
					$collection_dump = $this->generateAssetsCollections($assets, $compress);
					file_put_contents($compiled_path, $collection_dump);
					file_put_contents($hash_path, $hash);
				}
				$result_assets = sprintf($this->templates[$type], pathToURL($compiled_path));
			}
			return $result_assets;
		}
		else
		{
			$type           = $this->getAssetsCollectionType($assets);
			$assets         = $this->assetsNormalize($assets);
			$compiled_path  = $assets_path . $name . '.' . $type;
			$hash_path      = $this->container['cache_path'] . $name . '-' . $type;
			$hash           = $this->getHash($assets);
			$canonical_hash = $this->getCanonicalHash($hash_path);

			if ($this->container['environment'] === 'production' && file_exists($compiled_path))
			{
				$result_assets = sprintf($this->templates[$type], pathToURL($compiled_path));
			}
			elseif ($this->container['environment'] === 'debug' || $this->container['environment'] === 'production')
			{
				$result_assets = $this->generateAssetsDebug($assets);
			}
			else
			{
				if ($canonical_hash !== $hash || !file_exists($compiled_path))
				{
					$collection_dump = $this->generateAssetsCollection($assets, $compress);
					file_put_contents($compiled_path, $collection_dump);
					file_put_contents($hash_path, $hash);
				}
				$result_assets = sprintf($this->templates[$type], pathToURL($compiled_path));
			}
			return $result_assets;
		}
	}

	/**
	 * @param array $assets
	 *
	 * @return string
	 */
	protected function generateAssetsDebug (array $assets)
	{
		$result_assets = '';
		if ($assets[0]['type'] === 'js' && $this->container['assets.less'])
		{
			$assets[] = array
			(
				'asset' => $this->container['assets.lessjs_url'],
				'type'  => 'js',
			);
		}

		foreach ($assets as $asset)
		{
			if (file_exists(URLToPath($asset['asset'])))
			{
				$result_assets .= sprintf($this->templates[$asset['type']], $asset['asset']);
			}
		}
		return $result_assets;
	}

	/**
	 * @param array   $assets
	 * @param boolean $compress
	 *
	 * @return string
	 */
	protected function generateAssetsCollection (array $assets, $compress = TRUE)
	{
		$assets_instances = array();
		foreach ($assets as $asset)
		{
			$file_filters = array();
			if ($asset['type'] === 'less')
			{
				$file_filters[] = new LessphpFilter();
			}
			$assets_instances[] = new FileAsset(URLToPath($asset['asset']), $file_filters);
		}

		$collection_filters = array();
		if ($compress)
		{
			if ($assets[0]['type'] === 'js')
			{
				$collection_filters[] = new JsCompressorFilter($this->container['assets.yuicompressor_path'], $this->container['assets.java_path']);
			}
			else
			{
				$collection_filters[] = new CssCompressorFilter($this->container['assets.yuicompressor_path'], $this->container['assets.java_path']);
			}
		}
		$collection = new AssetCollection($assets_instances, $collection_filters);
		return $collection->dump();
	}

	/**
	 * @param array   $assets
	 * @param boolean $compress
	 *
	 * @return string
	 */
	protected function generateAssetsCollections (array $assets, $compress = TRUE)
	{
		$assets_instances = array();
		foreach ($assets as $assets_collection)
		{
			foreach ($assets_collection as $asset)
			{
				$file_filters = array();
				if ($asset['type'] === 'less')
				{
					$file_filters[] = new LessphpFilter();
				}
				$assets_instances[] = new FileAsset(URLToPath($asset['asset']), $file_filters);
			}
		}

		$collection_filters = array();
		if ($compress)
		{
			$collection_filters[] = new CssCompressorFilter($this->container['assets.yuicompressor_path'], $this->container['assets.java_path']);
		}
		$collection = new AssetCollection($assets_instances, $collection_filters);
		return $collection->dump();
	}

	/**
	 * @param array $assets
	 *
	 * @return string
	 */
	protected function getHash (array $assets)
	{
		$hash = '';
		foreach ($assets as $asset)
		{
			if (file_exists(URLToPath($asset['asset'])))
			{
				$hash .= md5_file(URLToPath($asset['asset']));
			}
		}
	}

	/**
	 * @param string $hash_path
	 *
	 * @return string
	 */
	protected function getCanonicalHash ($hash_path)
	{
		if (file_exists($hash_path))
		{
			return trim(file_get_contents($hash_path));
		}
		return NULL;
	}
}