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
	protected function getAssetMetaType ($asset_type)
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

	protected function createAssets (array $assets)
	{
		foreach ($assets as &$asset)
		{
			$asset = new Asset($asset);
		}
		unset($asset);
		return $assets;
	}

	/**
	 * @param string $name
	 * @param array  $assets
	 * @param bool   $debug
	 * @param bool   $compress
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	public function getAssets ($name, array $assets, $debug = FALSE, $compress = TRUE)
	{
		$assets = $this->createAssets($assets);

		$last_modify    = $this->getLastModified($assets);
		$type           = empty($assets) ? 'js' : $assets[0]->getMetaType();

		$compress_postfix = $compress ? 'min.' : '';
		$compiled_path    = $this->container['assets.path'] . $name . '.' . $last_modify . '.' . $compress_postfix . $type;

		if
		(
			$this->container['environment'] === 'debug' ||
			$debug ||
			(
				!file_exists($compiled_path) &&
				$this->container['environment'] === 'production'
			)
		)
		{
			$result_assets = $this->generateAssetsDebug($assets);
		}
		elseif ($this->container['environment'] === 'production')
		{
			$result_assets = sprintf($this->templates[$type], pathToURL($compiled_path));
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
		if ($assets[0]->getMetaType() === 'js' && $this->container['assets.less'])
		{
			$assets[] = new Asset($this->container['assets.lessjs_url']);
		}

		foreach ($assets as $asset)
		{
			if (file_exists($asset->getPath()))
			{
				$result_assets .= sprintf($this->templates[$asset->getType()], $asset->getURL());
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
		$assets_pathes    = array();

		foreach ($assets as $key => $asset)
		{
			$file_filters = array();
			if ($asset['type'] === 'js')
			{
				$assets_instances[] = new FileAsset($asset['asset_path'], $file_filters);
				continue;
			}

			$assets_pathes[$key] = $this->replaceURLs($asset);

			if ($asset['type'] === 'less')
			{
				$file_filters[] = new LessphpFilter();
			}
			$assets_instances[] = new FileAsset($assets_pathes[$key], $file_filters);
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
		$collection_dump = $collection->dump();

		foreach ($assets_pathes as $asset_path)
		{
			unlink($asset_path);
		}

		return $collection_dump;
	}

	protected function replaceURLs (Asset $asset)
	{
		$asset_text = file_get_contents($asset->getPath());

		chdir(dirname($asset->getPath()));

		$urls_old = array();
		$urls_new = array();

		preg_match_all('#url\((.*)\)#im', $asset_text, $urls_old);

		foreach ($urls_old[1] as $url)
		{
			$urls_new[] = '\'' . pathToURL(realpath(trim($url, '"\''))) . '\'';
		}

		$asset_text = str_replace($urls_old[1], $urls_new, $asset_text);
		$asset_path = $this->container['assets.path'] . basename($asset->getPath());

		file_put_contents($asset_path, $asset_text);
		return $asset_path;
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
			if (!file_exists($asset->getPath()))
			{
				throw new \RuntimeException(sprintf('The source file "%s" doesn`t exists: ', $asset->getURL()));
			}

			$asset_mtime = filemtime($asset->getPath());
			if ($asset_mtime > $mtime)
			{
				$mtime = $asset_mtime;
			}
		}
		return $mtime;
	}
}