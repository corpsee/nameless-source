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
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;

/**
 * AssetCollection class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AssetsCollection
{
	/**
	 * @var array
	 */
	protected $assets;

	/**
	 * @var string
	 */
	protected $meta_type = NULL;

	/**
	 * @param array $assets
	 */
	public function __construct (array $assets)
	{
		$this->assets = $assets;
	}

	/**
	 * @param Asset $asset
	 */
	public function addAsset (Asset $asset)
	{
		$this->assets[] = $asset;
	}

	/**
	 * @return array
	 */
	public function getAssets ()
	{
		return $this->assets;
	}

	/**
	 * @return string
	 */
	public function getMetaType ()
	{
		if (!is_null($this->meta_type))
		{
			return $this->meta_type;
		}
		$this->meta_type = empty($this->assets) ? 'js' : $this->assets[0]->getMetaType();
		return $this->meta_type;
	}

	/**
	 * @param string $assets_dir
	 *
	 * @return string
	 */
	public function dump ($assets_dir)
	{
		$assets        = array();
		$assets_pathes = array();

		foreach ($this->assets as $asset)
		{
			$assets[]       = $asset->getFileAsset($assets_dir);
			$asset_pathes[] = $asset->getPath();
		}

		$collection      = new AssetCollection($assets, array());
		$collection_dump = $collection->dump();

		foreach ($assets_pathes as $asset_path)
		{
			unlink($asset_path);
		}
		return $collection_dump;
	}

	/**
	 * @param string $assets_dir
	 * @param string $compressor_path
	 * @param string $java_path
	 *
	 * @return string
	 */
	public function dumpCompress ($assets_dir, $compressor_path, $java_path)
	{
		$filters = array();
		if ($this->assets[0]->getType() === 'js')
		{
			$filters[] = new JsCompressorFilter($compressor_path, $java_path);
		}
		else
		{
			$filters[] = new CssCompressorFilter($compressor_path, $java_path);
		}

		$assets        = array();
		$assets_pathes = array();

		foreach ($this->assets as $asset)
		{
			$assets[]       = $asset->getFileAsset($assets_dir);
			$asset_pathes[] = $asset->getPath();
		}

		$collection      = new AssetCollection($assets, $filters);
		$collection_dump = $collection->dump();

		foreach ($assets_pathes as $asset_path)
		{
			unlink($asset_path);
		}
		return $collection_dump;
	}

	/**
	 * @return integer
	 *
	 * @throws \RuntimeException
	 */
	public function getLastModified ()
	{
		$last_modified = 0;
		foreach ($this->assets as $asset)
		{
			if (!file_exists($asset->getPath()))
			{
				throw new \RuntimeException(sprintf('The source file "%s" doesn`t exists: ', $asset->getURL()));
			}

			$asset_mtime = filemtime($asset->getPath());
			if ($asset_mtime > $last_modified)
			{
				$last_modified = $asset_mtime;
			}
		}
		return $last_modified;
	}
}