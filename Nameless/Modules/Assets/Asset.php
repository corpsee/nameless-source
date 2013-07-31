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

use Assetic\Asset\FileAsset;

/**
 * Asset class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Asset
{
	protected $url;
	protected $path       = NULL;
	protected $type       = NULL;
	protected $meta_type  = NULL;
	protected $file_asset = NULL;

	public function __construct ($url)
	{
		$this->url = $url;
	}

	public function getURL ()
	{
		return $this->url;
	}

	public function getPath ()
	{
		if (!is_null($this->path))
		{
			return $this->path;
		}

		$this->path = URLToPath($this->url);
		return $this->path;
	}

	public function getType ()
	{
		if (!is_null($this->type))
		{
			return $this->type;
		}

		$type = pathinfo($this->url, PATHINFO_EXTENSION);

		switch ($type)
		{
			case 'css': case 'less': case 'js':
				$this->type = $type;
				break;
			default:
				throw new \LogicException("Invalid asset type: $type");
		}
		return $this->type;
	}

	public function getMetaType ()
	{
		if (!is_null($this->meta_type))
		{
			return $this->meta_type;
		}

		$this->getType();
		if ($this->type === 'less')
		{
			$this->meta_type = 'css';
		}
		else
		{
			$this->meta_type = $this->type;
		}
		return $this->meta_type;
	}

	public function getFileAsset(array $filters = array())
	{
		if (!is_null($this->file_asset))
		{
			return $this->file_asset;
		}

		$this->file_asset = new FileAsset($this->getPath(), $filters);
		return $this->file_asset;
	}

	public function replaceURLs ()
	{
		$asset_text = file_get_contents($this->getPath());

		chdir(dirname($this->getPath()));

		$urls_old = array();
		$urls_new = array();

		preg_match_all('#url\((.*)\)#im', $asset_text, $urls_old);

		foreach ($urls_old[1] as $url)
		{
			$urls_new[] = '\'' . pathToURL(realpath(trim($url, '"\''))) . '\'';
		}

		$asset_text = str_replace($urls_old[1], $urls_new, $asset_text);
		$asset_path = $this->container['assets.path'] . basename($this->getPath());

		//TODO: exception for wrong rights
		file_put_contents($asset_path, $asset_text);
		return $asset_path;
	}

	//TODO: addFilter method for FileAsset
}