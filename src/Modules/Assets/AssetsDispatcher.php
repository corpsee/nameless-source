<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Assets;

use Nameless\Core\Container;
use Nameless\Utilities\PathHelper;

/**
 * AssetsDispatcher class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
//TODO: don`t compress .min. files
class AssetsDispatcher
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $templates = [
        'css'  => '<link href="%s" rel="stylesheet" type="text/css" />',
        'less' => '<link href="%s" rel="stylesheet/less" type="text/css" />',
        'js'   => '<script src="%s" type="text/javascript"></script>',
    ];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $assets
     *
     * @return array
     */
    protected function createAssets(array $assets)
    {
        foreach ($assets as &$asset) {
            $asset = new Asset($asset);
        }
        unset($asset);
        return $assets;
    }

    /**
     * @param string $name
     * @param array $assets
     * @param bool $debug
     * @param bool $compress
     *
     * @return string
     * @throws \RuntimeException
     */
    //TODO: check amount of files
    public function getAssets($name, array $assets, $debug = false, $compress = true)
    {
        $assets = $this->createAssets($assets);
        $assets_collection = new AssetsCollection($assets);

        $compress_postfix = $compress ? 'min.' : '';
        $version = $assets_collection->getLastModified();

        $config = $this->container['assets'];
        $compiled_path = $config['path'] . $name . '.' . $compress_postfix . $assets_collection->getMetaType();

        if
        (
            $this->container['environment'] === 'debug' ||
            $debug ||
            (
                !file_exists($compiled_path) &&
                $this->container['environment'] === 'production'
            )
        ) {
            return $this->generateAssetsDebug($assets_collection);
        } elseif ($this->container['environment'] === 'test') {
            $this->generateAssetsTest($assets_collection, $compiled_path, $compress);
        }
        return sprintf(
            $this->templates[$assets_collection->getMetaType()],
            PathHelper::toURL($compiled_path, PUBLIC_PATH) . '?v=' . $version
        );
    }

    protected function generateAssetsDebug(AssetsCollection $assets_collection)
    {
        $config = $this->container['assets'];
        $result_assets = '';
        if ($assets_collection->getMetaType() === 'js' && $config['less']) {
            $assets_collection->addAsset(new Asset($config['lessjs_url']));
        }

        $assets = $assets_collection->getAssets();
        foreach ($assets as $asset) {
            if (file_exists($asset->getPath())) {
                $result_assets .= sprintf($this->templates[$asset->getType()], $asset->getURL());
            }
        }
        return $result_assets;
    }

    /**
     * @param AssetsCollection $assets_collection
     * @param string $compiled_path
     * @param bool $compress
     *
     * @return string
     * @throws \RuntimeException
     */
    //TODO: Remove every time generation, check changes
    protected function generateAssetsTest(AssetsCollection $assets_collection, $compiled_path, $compress = true)
    {
        $config = $this->container['assets'];
        if ($compress) {
            $dump = $assets_collection->dumpCompress($config['path']);
        } else {
            $dump = $assets_collection->dump($config['path']);
        }

        if (false === @file_put_contents($compiled_path, $dump)) {
            throw new \RuntimeException('Unable to write file ' . $compiled_path);
        }
        return PathHelper::toURL($compiled_path, PUBLIC_PATH);
    }
}
