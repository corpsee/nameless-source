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

use Assetic\Asset\AssetCollection;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\PackerFilter;

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
    protected $meta_type = null;

    /**
     * @param array $assets
     */
    public function __construct(array $assets)
    {
        $this->assets = $assets;
    }

    /**
     * @param Asset $asset
     */
    public function addAsset(Asset $asset)
    {
        $this->assets[] = $asset;
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @return string
     */
    public function getMetaType()
    {
        if (!is_null($this->meta_type)) {
            return $this->meta_type;
        }
        $this->meta_type = empty($this->assets) ? 'js' : $this->assets[0]->getMetaType();
        return $this->meta_type;
    }

    /**
     * @param string $assets_dir
     * @param array $filters
     *
     * @return string
     */
    public function dump($assets_dir, array $filters = [])
    {
        $assets        = [];
        $assets_pathes = [];

        foreach ($this->assets as $asset) {
            $assets[]        = $asset->getFileAsset($assets_dir);
            $assets_pathes[] = $asset->getTempPath();
        }

        $collection = new AssetCollection($assets, $filters);
        $collection_dump = $collection->dump();

        foreach ($assets_pathes as $asset_path) {
            @unlink($asset_path);
        }
        return $collection_dump;
    }

    /**
     * @param string $assets_dir
     *
     * @return string
     */
    public function dumpCompress($assets_dir)
    {
        $filters = [];
        if ($this->assets[0]->getType() === 'js') {
            $filters[] = new PackerFilter();
        } else {
            $filters[] = new CssMinFilter();
        }

        return $this->dump($assets_dir, $filters);
    }

    /**
     * @return integer
     *
     * @throws \RuntimeException
     */
    public function getLastModified()
    {
        $last_modified = 0;
        foreach ($this->assets as $asset) {
            if (!file_exists($asset->getPath())) {
                throw new \RuntimeException(sprintf('The source file "%s" doesn`t exists: ', $asset->getURL()));
            }

            $asset_last_modified = filemtime($asset->getPath());
            if ($asset_last_modified > $last_modified) {
                $last_modified = $asset_last_modified;
            }
        }
        return $last_modified;
    }
}