<?php
/**
 * Frontend Assets files
 */

namespace WenpriseTranslatorManager;


class Assets
{

    /**
     * Register assets
     *
     * Assets constructor.
     */
    function __construct()
    {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'register'], 5);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'register'], 5);
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register()
    {
        $this->register_scripts($this->get_scripts());
        $this->register_styles($this->get_styles());
    }

    /**
     * Register scripts
     *
     * @param array $scripts
     *
     * @return void
     */
    private function register_scripts($scripts)
    {
        foreach ($scripts as $handle => $script) {
            $deps      = isset($script[ 'deps' ]) ? $script[ 'deps' ] : false;
            $in_footer = isset($script[ 'in_footer' ]) ? $script[ 'in_footer' ] : false;
            $version   = isset($script[ 'version' ]) ? $script[ 'version' ] : WENPRISE_TRANSLATOR_MANAGER_VERSION;

            wp_register_script($handle, $script[ 'src' ], $deps, $version, $in_footer);
        }
    }

    /**
     * Register styles
     *
     * @param array $styles
     *
     * @return void
     */
    public function register_styles($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps = isset($style[ 'deps' ]) ? $style[ 'deps' ] : false;

            wp_register_style($handle, $style[ 'src' ], $deps, WENPRISE_TRANSLATOR_MANAGER_VERSION);
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts()
    {
        $prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';
        $assets = $this->get_manifest('../dist/app');

        $scripts = [
            'wprs-tsm-runtime'  => [
                'src'       => $this->get_url($assets[ 'runtime.js' ]),
                'in_footer' => true,
            ],
            'wprs-tsm-frontend' => [
                'src'       => $this->get_url($assets[ 'frontend.js' ]),
                'in_footer' => true,
            ],
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles()
    {

        $assets = $this->get_manifest('../dist/app');

        $styles = [
            'wprs-tsm-frontend' => [
                'src' => $this->get_url($assets[ 'frontend.css' ]),
            ],
        ];

        return $styles;
    }

    /**
     * 转换路径为 URL
     *
     * @param $directory
     *
     * @return string
     */
    function dir_to_url($directory)
    {
        $url   = \trailingslashit($directory);
        $count = 0;

        # Sanitize directory separator on Windows
        $url = str_replace('\\', '/', $url);

        $possible_locations = [
            WP_PLUGIN_DIR  => \plugins_url(), # If installed as a plugin
            WP_CONTENT_DIR => \content_url(), # If anywhere in wp-content
            ABSPATH        => \site_url('/'), # If anywhere else within the WordPress installation
        ];

        foreach ($possible_locations as $test_dir => $test_url) {
            $test_dir_normalized = str_replace('\\', '/', $test_dir);
            $url                 = str_replace($test_dir_normalized, $test_url, $url, $count);

            if ($count > 0) {
                return \untrailingslashit($url);
            }
        }

        return '';
    }

    /**
     * 获取资源集文件
     *
     * @param $dir
     *
     * @return bool|mixed
     */
    function get_manifest($dir)
    {
        $filepath = realpath(__DIR__ . '/' . $dir . '/manifest.json');

        if (file_exists($filepath)) {
            $manifest = json_decode(file_get_contents($filepath), true);

            return $manifest;
        }

        return false;
    }

    /**
     * 获取资源 URL
     *
     * @param $assets
     *
     * @return string
     */
    function get_url($assets)
    {
        # 设置根目录 Url
        if ( ! defined('WENPRISE_FORM_URL')) {
            define('WENPRISE_FORM_URL', $this->dir_to_url(realpath(__DIR__ . '/../')));
        }

        return esc_url(WENPRISE_TRANSLATOR_MANAGER_URL . '/dist/' . $assets);
    }


}