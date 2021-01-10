<?php

namespace Oblak\Assets;

/**
 * Interface ManifestInterface
 * @package Roots\Sage
 * @author QWp6t
 */
interface ManifestInterface
{
    /**
     * Get the cache-busted filename
     *
     * If the manifest does not have an entry for $asset, then return $asset
     *
     * @param  string $asset The original name of the file before cache-busting
     * @return string        Cache busted filename
     */
    public function get(string $asset) : string;

    /**
     * Get the cache-busted URI
     *
     * If the manifest does not have an entry for $asset, then return URI for $asset
     *
     * @param  string $asset The original name of the file before cache-busting
     * @return string        Cache busted URI
     */
    public function getUri(string $asset) : string;

    /**
     * Get the cache-busted filepath
     * 
     * If the manifest does not have an entry for $asset, then return filepath for $asset
     * 
     * @param  string $asset The original name of the file before cache-busting
     * @return string        Cache busted filepath
     */
    public function getPath(string $asset) : string;
}
