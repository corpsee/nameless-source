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
	 * @param array $assets
	 *
	 * @return array
	 */
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
		$assets_collection = new AssetsCollection($assets);

		$compress_postfix = $compress ? 'min.' : '';
		$compiled_path    = $this->container['assets.path'] . $name . '.' . $assets_collection->getLastModified() . '.' . $compress_postfix . $assets_collection->getMetaType();

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
			return $this->generateAssetsDebug($assets_collection);
		}
		elseif ($this->container['environment'] === 'production')
		{
			return $this->generateAssets($assets_collection, $compiled_path);
		}
		else
		{
			return $this->generateAssetsTest($assets_collection, $compiled_path, $compress);
		}
	}

	protected function generateAssetsDebug (AssetsCollection $assets_collection)
	{
		$result_assets = '';
		if ($assets_collection->getMetaType() === 'js' && $this->container['assets.less'])
		{
			$assets_collection->addAsset(new Asset($this->container['assets.lessjs_url']));
		}

		$assets = $assets_collection->getAssets();
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
	 * @param AssetsCollection $assets_collection
	 * @param string           $compiled_path
	 * @param bool             $compress
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	protected function generateAssetsTest (AssetsCollection $assets_collection, $compiled_path, $compress = TRUE)
	{
		if (!file_exists($compiled_path))
		{
			if ($compress)
			{
				$dump = $assets_collection->dumpCompress($this->container['assets.path'], $this->container['assets.yuicompressor_path'], $this->container['assets.java_path']);
			}
			else
			{
				$dump = $assets_collection->dump($this->container['assets.path']);
			}

			if (FALSE === @file_put_contents($compiled_path, $dump))
			{
				throw new \RuntimeException('Unable to write file ' . $compiled_path);
			}
		}
		return sprintf($this->templates[$assets_collection->getMetaType()], pathToURL($compiled_path));
	}

	/**
	 * @param AssetsCollection $assets_collection
	 * @param string           $compiled_path
	 *
	 * @return string
	 */
	protected function generateAssets (AssetsCollection $assets_collection, $compiled_path)
	{
		return sprintf($this->templates[$assets_collection->getMetaType()], pathToURL($compiled_path));
	}
}