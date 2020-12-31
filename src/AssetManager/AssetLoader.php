<?php

namespace Oblak\AssetManager;

use Oblak\Assets\JsonManifest;

class AssetLoader
{

    private $namespace;

    private $version;

    private $assets;

    private JsonManifest $manifest;

    public function __construct(string $namespace, array $data)
    {

        $this->namespace = $namespace;
        $this->version   = $data['version'];
        $this->assets    = $data['assets'];

        foreach ($data as $var => $val)
            $this->$var = $val;

        $this->manifest = new JsonManifest(
            $data['dist_path'].'/assets.json',
            $data['dist_uri']);

        add_action(AssetManager::getHook(), [&$this, 'loadStyles'], $data['priority']);
        add_action(AssetManager::getHook(), [&$this, 'loadScripts'], $data['priority']);

    }

    public function loadStyles()
    {

        $load_styles = apply_filters("{$this->namespace}/load_styles", true);
        $context     = (is_admin()) ? 'admin' : 'front';

        if (!$load_styles)
            return;

        foreach ($this->assets[$context]['styles'] as $style) :

            $basename = basename($style);
            $handler  = $this->namespace . '/' . $basename;

            wp_register_style($handler, $this->manifest->getUri($style));

            wp_enqueue_style($handler);

        endforeach;

    }

    public function loadScripts()
    {

        $load_scripts = apply_filters("{$this->namespace}/load_scripts", true);
        $context     = (is_admin()) ? 'admin' : 'front';

        if (!$load_scripts)
            return;

        foreach ($this->assets[$context]['scripts'] as $script) :


            $basename = basename($script);
            $handler  = $this->namespace . '/' . $basename;

            wp_register_script($handler, $this->manifest->getUri($script), [], $this->version, true);

            do_action("{$this->namespace}/localize/$basename");

            wp_enqueue_script($handler);

        endforeach;

    }

    public function getUri(string $asset) : string
    {
        return $this->manifest->getUri($asset);
    }

}
