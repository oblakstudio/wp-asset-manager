<?php

namespace Oblak\AssetManager;

class AssetManager
{

    private array $namespaces;

    /**
     * @var AssetLoader[]
     */
    private array $loaders;

    private static ?AssetManager $instance = null;

    private static string $hook = '';

    private function __construct()
    {

        $this->namespaces = [];
        $this->loaders    = [];

        self::$hook = (!is_admin())  ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';

        add_action(self::$hook, [&$this, 'sortNamespaces'], -2);
        add_action(self::$hook, [&$this, 'enqueueNamespaceAssets'], -1);
    }

    public static function getInstance() : AssetManager
    {
        return (is_null(self::$instance))
            ? self::$instance = new static
            : self::$instance;
    }

    public static function getHook()
    {
        return self::$hook;
    }

    public function sortNamespaces()
    {
        uasort($this->namespaces, function($a, $b){

            if ($a['priority'] == $b['priority']) :
                return 0;
            endif;

            return ($a['priority'] < $b['priority']) ? -1 : 1;

        });
    }

    public function registerNamespace(string $name, array $args)
    {

        $this->namespaces[$name] = $args;

    }

    public function enqueueNamespaceAssets()
    {

        foreach ($this->namespaces as $namespace => $data) :

            $this->loaders[$namespace] = new AssetLoader($namespace, $data);

        endforeach;

    }

    public function getUri(string $namespace, string $asset) : string
    {
        return $this->loaders[$namespace]->getUri($asset);
    }

    public function getPath(string $namespace, string $asset) : string
    {
        return $this->loaders[$namespace]->getPath($asset);
    }

}
