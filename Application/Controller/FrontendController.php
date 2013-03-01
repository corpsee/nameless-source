<?php

namespace Application\Controller;

use Framework\Controller;
use Assetic\Asset\AssetCollection;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Asset\FileAsset;

class FrontendController extends Controller
{
	protected function generateAssets ($name, array $assets, $type = 'css')
	{
		if ($this->container->minify_assets === FALSE)
		{
		 	return $assets;
		}

		$hash = '';
		//echo '<pre>'; print_r($assets); exit;
		foreach ($assets as $asset)
		{
			$hash = $hash . md5_file(URLToPath($asset));
		}

		switch ($type)
		{
			case 'js':
				$result_path = SCRIPT_PATH . $name . '.' . $type;
				break;
			case 'css':
			default:
				$result_path = STYLE_PATH . $name . '.' . $type;
		}
		$hash_path = $this->container->cache_path . $name . '-' . $type;

		$canonical_hash = '';
		if (file_exists($hash_path))
		{
			$canonical_hash = trim(file_get_contents($hash_path));
		}

		// —жатие и минификаци€ новых общих файлов
		if ($canonical_hash !== $hash)
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