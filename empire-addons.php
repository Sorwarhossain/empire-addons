<?php

/**
 * Plugin Name: Empire Addons
 * Description: Two custom elementor addons to show webinar start timers.
 * Plugin URI:  https://bootstrappedempire.com/
 * Version:     1.0.0
 * Author:      Saroar Hossain
 * Author URI:  https://devsgram.com
 * Text Domain: empire-addons
 *
 * Elementor tested up to: 3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


// Defining Constants
define('EMPIRE_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));
define('EMPIRE_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));

if (
    !did_action('elementor/loaded')
) {
    add_action('admin_notices', 'empire_admin_notice_missing_main_plugin');
    return;
}
function empire_admin_notice_missing_main_plugin() {
    if (isset($_GET['activate'])) unset($_GET['activate']);
    $message = sprintf(
        esc_html__('"%1$s" requires "%2$s" to be installed and activated', 'empire-addons'),
        '<strong>' . esc_html__('Empire Addons', 'empire-addons') . '</strong>',
        '<strong>' . esc_html__('Elementor', 'empire-addons') . '</strong>'
    );

    printf('<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message);
}

add_action('wp_enqueue_scripts', 'empire_register_scripts_styles');
function empire_register_scripts_styles() {
    // wp_register_style('myew-owl-carousel', EMPIRE_PLUGIN_URL . 'assets/vendor/owl-carousel/css/owl.carousel.min.css', [], rand(), 'all');
    // wp_register_style('myew-owl-carousel-theme', EMPIRE_PLUGIN_URL . 'assets/vendor/owl-carousel/css/owl.theme.default.min.css', [], rand(), 'all');
    // wp_register_script('myew-owl-carousel', EMPIRE_PLUGIN_URL . 'assets/vendor/owl-carousel/js/owl.carousel.min.js', ['jquery'], rand(), true);

    // wp_register_style('myew-style', EMPIRE_PLUGIN_URL . 'assets/dist/css/public.min.css', [], rand(), 'all');
    // wp_register_script('myew-script', EMPIRE_PLUGIN_URL . 'assets/dist/js/public.min.js', ['jquery'], rand(), true);

    // wp_enqueue_style('myew-owl-carousel');
    // wp_enqueue_style('myew-owl-carousel-theme');

    // wp_enqueue_script('myew-owl-carousel');
    // wp_enqueue_style('myew-style');
    // wp_enqueue_script('myew-script');

    wp_register_style('empire_styles', EMPIRE_PLUGIN_URL . 'assets/css/empire_scripts.css', [], rand(), 'all');

    wp_register_script('empire_simple_timer', EMPIRE_PLUGIN_URL . 'assets/js/empire-simple-counter.js', ['jquery'], '', true);
    wp_register_script('empire_scripts', EMPIRE_PLUGIN_URL . 'assets/js/empire_scripts.js', ['jquery'], '', true);

    wp_enqueue_style('empire_styles');

    wp_enqueue_script('empire_simple_timer');
    wp_enqueue_script('empire_scripts');
}



/**
 * Register Empire Widgets
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function empire_register_all_widgets($widgets_manager) {

    require_once(__DIR__ . '/widgets/webinar-start.php');

    $widgets_manager->register(new \Webinar_Start());
}
add_action('elementor/widgets/register', 'empire_register_all_widgets');




function empire_elementor_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'empire-addons',
        [
            'title' => esc_html__('Empire Addons', 'empire-addons'),
            'icon' => 'fa fa-angry',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'empire_elementor_widget_categories');
