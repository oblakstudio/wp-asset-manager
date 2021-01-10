<?php

namespace Oblak\Assets;

/**
 * Class JsonManifest
 * 
 * @package Roots\Sage
 * @author QWp6t
 * @author Sibin Grasic <sibin.grasic@oblak.studio>
 */
class JsonManifest implements ManifestInterface
{
    /** @var array */
    public $manifest;

    /** @var string */
    public $dist;

    /** @var string */
    public $path;

    /**
     * JsonManifest constructor
     *
     * @param string $manifestPath Local filesystem path to JSON-encoded manifest
     * @param string $distUri      Remote URI to assets root
     * @param string $distPath     Local filepath to assets root
     */
    public function __construct(string $manifestPath, string $distUri, string $distPath)
    {
        $this->manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $this->dist = $distUri;
        $this->dist = $distPath;
    }

    public function get(string $asset) : string
    {
        return isset($this->manifest[$asset]) ? $this->manifest[$asset] : $asset;
    }

    public function getUri(string $asset) : string
    {
        return "{$this->dist}/{$this->get($asset)}";
    }

    public function getPath(string $asset): string
    {
        return "{$this->path}/{$this->get($asset)}";
    }

}
