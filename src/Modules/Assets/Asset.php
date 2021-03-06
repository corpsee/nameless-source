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

use Assetic\Filter\LessphpFilter;
use Assetic\Asset\FileAsset;
use Nameless\Utilities\PathHelper;
use Nameless\Utilities\UrlHelper;

/**
 * Asset class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Asset
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $path = null;

    /**
     * @var string
     */
    protected $temp_path = null;

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $meta_type = null;

    /**
     * @var FileAsset
     */
    protected $file_asset = null;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if (!is_null($this->path)) {
            return $this->path;
        }

        $this->path = UrlHelper::toPath($this->url, PUBLIC_PATH);
        return $this->path;
    }

    /**
     * @return string
     */
    public function getTempPath()
    {
        return $this->temp_path;
    }

    /**
     * @return string
     *
     * @throws \LogicException
     */
    public function getType()
    {
        if (!is_null($this->type)) {
            return $this->type;
        }

        $type = pathinfo($this->url, PATHINFO_EXTENSION);

        switch ($type) {
            case 'css':
            case 'less':
            case 'js':
                $this->type = $type;
                break;
            default:
                throw new \LogicException("Invalid asset type: '$type'");
        }
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMetaType()
    {
        if (!is_null($this->meta_type)) {
            return $this->meta_type;
        }

        $this->getType();
        if ($this->type === 'less') {
            $this->meta_type = 'css';
        } else {
            $this->meta_type = $this->type;
        }
        return $this->meta_type;
    }

    /**
     * @param string $assets_dir
     *
     * @return FileAsset
     */
    public function getFileAsset($assets_dir)
    {
        if (!is_null($this->file_asset)) {
            return $this->file_asset;
        }

        $filters = [];
        if ($this->getType() === 'js') {
            $this->file_asset = new FileAsset($this->getPath(), $filters);
            return $this->file_asset;
        }

        $asset_path_temp = $this->replaceRelativeLinks($assets_dir);

        if ($this->getType() === 'less') {
            $filters[] = new LessphpFilter();
        }
        $this->file_asset = new FileAsset($asset_path_temp, $filters);
        return $this->file_asset;
    }

    /**
     * @param string $assets_dir
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function replaceRelativeLinks($assets_dir)
    {
        $file = fopen($this->getPath(), 'rb');
        $asset_text = fread($file, filesize($this->getPath()));
        fclose($file);

        chdir(dirname($this->getPath()));

        $urls_old = [];
        $urls_new = [];

        preg_match_all('#url\(([\'"]?[^/\'"][^\'"]*[\'"]?)\)#imU', $asset_text, $urls_old);
        $urls = array_unique($urls_old[1]);

        foreach ($urls as $url) {
            $urls_new[] = "'" . PathHelper::toURL(realpath(trim($url, '"\'')), PUBLIC_PATH) . "'";
        }

        $asset_text = str_replace($urls, $urls_new, $asset_text);

        $this->temp_path = $assets_dir . basename($this->getPath());

        if (false === @file_put_contents($this->temp_path, $asset_text)) {
            throw new \RuntimeException('Unable to write file ' . $this->temp_path);
        }
        return $this->temp_path;
    }
}
