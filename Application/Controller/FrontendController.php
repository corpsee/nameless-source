<?php

namespace Application\Controller;

use Framework\Controller;
use Assetic\Asset\AssetCollection;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Asset\FileAsset;

class FrontendController extends Controller
{
	protected function setAsset ($name, array $assets, $type = 'css')
	{
		if ($this->container->debug === TRUE)
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
				$result_path = ROOT_PATH . 'Public/Files/j/' . $name . '.' . $type;
				break;
			case 'css':
			default:
				$result_path = ROOT_PATH . 'Public/Files/s/' . $name . '.' . $type;
		}
		$hash_path = ROOT_PATH . 'Cache/' . $name . '-' . $type;

		$canonical_hash = '';
		if (file_exists($hash_path))
		{
			$canonical_hash = trim(file_get_contents($hash_path));
		}

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
					$filter = new JsCompressorFilter(ROOT_PATH . 'yuicompressor-2.4.7.jar', 'C:\Program Files\Java\jre6\bin\java.exe');
					break;
				case 'css':
				default:
					$filter = new CssCompressorFilter(ROOT_PATH . 'yuicompressor-2.4.7.jar', 'C:\Program Files\Java\jre6\bin\java.exe');
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
		//exit;
	}
}