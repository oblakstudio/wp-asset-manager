<?php

namespace Oblak\Asset;

class Loader implements LoaderInterface
{

    private static ?string $hook = null;

    private static ?string $context = null;

    private string $namespace;

    private string $version;

    private array $assets;

    private Manifest $manifest;

    public function __construct(string $namespace, array $data)
    {

        $this->namespace = $namespace;
        $this->version   = $data['version'];
        $this->assets    = $data['assets'];
        $this->priority  = $data['priority'];

        $this->manifest = new Manifest(
            $data['dist_path'].'/assets.json',
            $data['dist_uri'],
            $data['dist_path'],
        );

        if ( is_null(self::$hook) ) :
            self::$hook = (!is_admin())  ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';
            self::$context = ( !is_admin() ) ? 'front' : 'admin';
        endif;

       

    }

    public function run()
    {
        add_action(self::$hook, [&$this, 'loadStyles'], $this->priority);
        add_action(self::$hook, [&$this, 'loadScripts'], $this->priority);
    }

    public function loadStyles()
    {

        $load_styles = apply_filters("{$this->namespace}/load_styles", true);

        if (!$load_styles)
            return;

        foreach ($this->assets[self::$context]['styles'] as $style) :

            $basename = basename($style);
            $handler  = "{$this->namespace}/{$basename}";

            wp_register_style($handler, $this->manifest->getUri($style));
            wp_enqueue_style($handler);

        endforeach;

    }

    public function loadScripts()
    {

        $load_scripts = apply_filters("{$this->namespace}/load_scripts", true);

        if (!$load_scripts)
            return;

        foreach ($this->assets[self::$context]['scripts'] as $script) :

            $basename = basename($script);
            $handler  = "{$this->namespace}/{$basename}";

            wp_register_script($handler, $this->manifest->getUri($script), [], $this->version, true);
            do_action("{$this->namespace}/localize/$basename");
            wp_enqueue_script($handler);

        endforeach;

    }

    public function getUri(string $asset) : string
    {
        return $this->manifest->getUri($asset);
    }

    public function getPath(string $asset) : string
    {
        return $this->manifest->getPath($asset);
    }

}
