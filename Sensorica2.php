<?php
/**
 * Plugin Name: Sensorica2
 * Description: Advanced sensor technology integration and analysis tools.
 * Author: [Your Name]
 * Version: 0.1.0
 * Requires PHP: 7.1
 * Text Domain: sensorica2
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Constants.
define('SENSORICA2_VERSION', '0.1.0');
define('SENSORICA2_URL', plugin_dir_url(__FILE__));
define('SENSORICA2_PATH', plugin_dir_path(__FILE__));
define('SENSORICA2_BASENAME', plugin_basename(__FILE__));

// Include dependencies and additional files.
//require_once SENSORICA2_PATH . 'includes/sensor-functions.php';
//require_once SENSORICA2_PATH . 'includes/sensor-post-type.php';
//require_once SENSORICA2_PATH . 'includes/sensor-shortcode.php';
//require_once SENSORICA2_PATH . 'includes/sensor-metabox.php';

// Enqueue s cripts and styles.
function sensorica2_enqueue_scripts() {
    wp_enqueue_script('sensorica2-script', SENSORICA2_URL . 'assets/js/sensorica2.js', array('jquery'), SENSORICA2_VERSION, true);
    wp_enqueue_style('sensorica2-style', SENSORICA2_URL . 'assets/css/sensorica2.css', array(), SENSORICA2_VERSION, 'all');
}
add_action('wp_enqueue_scripts', 'sensorica2_enqueue_scripts');

// Initialize the plugin.
function sensorica2_init() {
    // Initialization code here.
}
add_action('init', 'sensorica2_init');

// Register activation and deactivation hooks.
function sensorica2_activate() {
    // Activation code here here.
}
register_activation_hook(__FILE__, 'sensorica2_activate');

function sensorica2_deactivate() {
    // Deactivation code here.
}
register_deactivation_hook(__FILE__, 'sensorica2_deactivate');

// Additional hooks and functionalities can be added here.

// add menu to admin
function sensorica2_menu() {
    add_menu_page('Sensorica2', 'Sensorica2', 'manage_options', 'sensorica2', 'sensorica2_admin_page', 'dashicons-admin-generic');
}
add_action('admin_menu', 'sensorica2_menu');

// add admin page
function sensorica2_admin_page() {
    ?>
    <div class="wrap">
        <h1>Sensorica2</h1>
        <p>Advanced sensor technology integration and analysis tools.</p>
        <p>Version: <?php echo SENSORICA2_VERSION; ?></p>
    </div>
    <?php
}

// add settings link to plugin page
function sensorica2_settings_link($links) {
    $settings_link = '<a href="admin.php?page=sensorica2">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}