<?php
/*
Plugin Name: 翻译服务计算器
Plugin URI: https://www.wpzhiku.com/wordpress-shang-dian-fu-wu/
Description: 翻译服务计算器
Version: 0.2.5
Author: Amos Lee
Author URI: https://www.wpzhiku.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wprs-tsm
Domain Path: /languages
*/

// don't call the file directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WenpriseTranslatorManager class
 *
 * @class Base_Plugin The class that holds the entire Base_Plugin plugin
 */
final class WenpriseTranslatorManager
{

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the Base_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct()
    {

        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        add_action('plugins_loaded', [$this, 'init_plugin']);
        // add_action('init', [$this, 'register_content_types']);

        load_plugin_textdomain('wprs-tsm', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Initializes the Base_Plugin() class
     *
     * Checks for an existing Base_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new WenpriseTranslatorManager();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get($prop)
    {
        if (array_key_exists($prop, $this->container)) {
            return $this->container[$prop];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset($prop)
    {
        return isset($this->{$prop}) || isset($this->container[$prop]);
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('WENPRISE_TRANSLATOR_MANAGER_VERSION', $this->version);
        define('WENPRISE_TRANSLATOR_MANAGER_FILE', __FILE__);
        define('WENPRISE_TRANSLATOR_MANAGER_PATH', dirname(WENPRISE_TRANSLATOR_MANAGER_FILE));
        define('WENPRISE_TRANSLATOR_MANAGER_URL', plugins_url('', WENPRISE_TRANSLATOR_MANAGER_FILE));
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin()
    {
        require_once(WENPRISE_TRANSLATOR_MANAGER_PATH . '/vendor/autoload.php');

        $this->init_classes();
    }

    /**
     * Register content types
     */
    public function register_content_types()
    {
        wprs_types("store", __("Store", 'wprs-tsm'), ['title'], true, false, 'dashicons-store');
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate()
    {

        $installed = get_option('wprs-tsm_installed');

        if (!$installed) {
            update_option('wprs-tsm_installed', time());
        }

        update_option('wprs-tsm_version', WENPRISE_TRANSLATOR_MANAGER_VERSION);
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate()
    { }


    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes()
    {
        $this->container['assets'] = new WenpriseTranslatorManager\Assets();
        $this->container['admin'] = new WenpriseTranslatorManager\Admin();
        $this->container['frontend'] = new WenpriseTranslatorManager\Frontend();
    }

}

WenpriseTranslatorManager::init();