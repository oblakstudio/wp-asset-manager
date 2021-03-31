<?php
namespace Oblak\Asset;

interface LoaderInterface
{

    public function loadStyles();

    public function loadScripts();
    
    /**
     * Get the cache-busted URI
     *
     * If the manifest does not have an entry for $asset, then return URI for $asset
     *
     * @param string $asset The original name of the file before cache-busting
     * @return string
     */
    public function getUri(string $asset) : string;

    /**
     * Get the cache-busted path
     * 
     * If the manifest does not have an entry for $asset, then return URI for $asset
     *
     * @param  string $asset The original name of the file before cache-busting
     * @return string
     */
    public function getPath(string $asset) : string;

}