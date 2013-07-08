<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
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

	/**
	 * @param string $asset_url
	 *
	 * @return string
	 * @throws \LogicException
	 */
	protected function getAssetType ($asset_url)
	{
		$type = 'css';
		if (stripos($asset_url, '.js') !== FALSE)
		{
			$type = 'js';
		}
		elseif (stripos($asset_url, '.less') !== FALSE)
		{
			$type = 'less';
		}

		if (!in_array($type, array('css', 'less', 'js')))
		{
			throw new \LogicException('Invalid asset type: ' . $type);
		}
		return $type;
	}

	/**
	 * @param string $asset_type
	 *
	 * @return string
	 */
	protected function getAssetGlobalType ($asset_type)
	{
		if ($asset_type === 'less')
		{
			$asset_type = 'css';
		}
		return $asset_type;
	}

	/**
	 * @param array $assets
	 *
	 * @return array
	 */
	protected function assetsNormalize (array $assets)
	{
		foreach ($assets as &$asset)
		{
			$asset = array
			(
				'asset_url'  => $asset,
				'asset_path' => URLToPath($asset),
				'type'       => $this->getAssetType($asset),
			);
		}
		unset($asset);
		return $assets;
	}

	/**
	 * @param string $name
	 * @param array  $assets
	 * @param bool   $compress
	 * @param string $assets_path
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	public function getAssets ($name, array $assets, $compress = TRUE, $assets_path = NULL)
	{
		$assets = $this->assetsNormalize($assets);

		if (is_null($assets_path))
		{
			$assets_path = $this->container['assets.path'];
		}

		$last_modify    = $this->getLastModified($assets);
		$type           = $this->getAssetGlobalType($assets[0]['type']);
		$compiled_path  = $assets_path . $name . '.' . ($last_modify - 1370019600) . '.' . $type;

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
			if (!file_exists($compiled_path))
			{
				$dump = $this->generateAssets($assets, $compress);

				if (FALSE === @file_put_contents($compiled_path, $dump))
				{
					throw new \RuntimeException('Unable to write file ' . $compiled_path);
				}
			}
			$result_assets = sprintf($this->templates[$type], pathToURL($compiled_path));
		}
		return $result_assets;
	}

	/**
	 * @param array $assets
	 *
	 * @return string
	 */
	protected function generateAssetsDebug (array $assets)
	{
		$result_assets = '';
		if ($this->getAssetGlobalType($assets[0]['type']) === 'js' && $this->container['assets.less'])
		{
			$assets[] = array
			(
				'asset_url' => $this->container['assets.lessjs_url'],
				'asset_path' => URLToPath($this->container['assets.lessjs_url']),
				'type'      => 'js',
			);
		}

		foreach ($assets as $asset)
		{
			if (file_exists($asset['asset_path']))
			{
				$result_assets .= sprintf($this->templates[$asset['type']], $asset['asset_url']);
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
	protected function generateAssets (array $assets, $compress = TRUE)
	{
		$assets_instances = array();
		foreach ($assets as $asset)
		{
			$file_filters = array();
			if ($asset['type'] === 'less')
			{
				$file_filters[] = new LessphpFilter();
			}
			$assets_instances[] = new FileAsset($asset['asset_path'], $file_filters);
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
	 * @param array $assets
	 *
	 * @return int
	 * @throws \RuntimeException
	 */
	protected function getLastModified (array $assets)
	{
		$mtime = 0;
		foreach ($assets as $asset)
		{
			if (!file_exists($asset['asset_path']))
			{
				throw new \RuntimeException(sprintf('The source file "%s" does not exist.', $asset['asset_path']));
			}

			$asset_mtime = filemtime($asset['asset_path']);
			if ($asset_mtime > $mtime)
			{
				$mtime = $asset_mtime;
			}
		}

		return $mtime;
	}

	/**
	 * @param string $hash_path
	 *
	 * @return string
	 */
	protected function getLastModifiedResult ($hash_path)
	{
		if (file_exists($hash_path))
		{
			return trim(file_get_contents($hash_path));
		}
		return 0;
	}
}