<?php
/**
 * Plugin Name: Sensorica2
 * Description: Advanced sensor technology integration and analysis tools.
 * Author: Aleksandr Noxon
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
//get SENSORICA2_URL plugin dirrectory using base site url
define('SENSORICA2_URL', plugin_dir_url(__FILE__));
define('SENSORICA2_PATH', plugin_dir_path(__FILE__));
define('SENSORICA2_BASENAME', plugin_basename(__FILE__));

function sensorica_default_slug()
{
    return 'sensorica';
}

function sensorica_page_slug()
{
    $slug = sensorica_default_slug();
    if (get_option('sensorica_slug')) {
        $slug = get_option('sensorica_slug');
    }
    return esc_html($slug);
}


// add menu to admin
function sensorica2_menu()
{
    // Add the top-level menu page.
    add_menu_page('Sensorica2', 'Sensorica2', 'manage_options', 'sensorica2', 'sensorica2_admin_page');

    // Add the submenus - New Run and Runs
    add_submenu_page('sensorica2', 'Sensorica2 Shortcodes', 'Shortcodes', 'manage_options', 'sensorica2_shortcodes', 'sensorica2_shortcodes_page');
    add_submenu_page('sensorica2', 'Sensorica2 Settings', 'Settings', 'manage_options', 'sensorica2_settings', 'sensorica2_settings_page');
    
    
    // WordPress will automatically create a submenu with the same slug as the main menu.
    // Rename this automatically created submenu to "Settings"
    global $submenu;
    if (isset($submenu['sensorica2'])) {
        $submenu['sensorica2'][0][0] = 'New Shortcode';
    }
}


add_action('admin_menu', 'sensorica2_menu');

include SENSORICA2_PATH . 'tools/chate-mascot/sensorica-shortcode.php';
// add admin page
function sensorica2_admin_page()
{
    include SENSORICA2_PATH . 'index.php';
}
function sensorica2_settings_page()
{
    //check admin permission
    if (!current_user_can('manage_options')) {
        return;
    }
    if (isset($_POST['sensorica_envato_key'])) {
        update_option('sensorica_envato_key', sanitize_text_field($_POST['sensorica_envato_key']));
        update_option("sensorica_openaiproxy", sanitize_text_field($_POST['sensorica_openaiproxy']));

        //check the envato license and save domain to whitelist
        $envato_key = sanitize_text_field($_POST['sensorica_envato_key']);
        $back = 'https://special-acorn-5xprqggx9fp4qr-3009.app.github.dev/envatocheckandsave';
        $url = $back . "?registeredurl=" . base64_encode(SENSORICA2_URL) . "&key=" . $envato_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $con = curl_exec($ch);
        curl_close($ch);

        ?>
        <div id="message" class="notice notice-success is-dismissible">
            <p>
                <?php

                echo $con;
                esc_html_e('Settings saved', 'sensorica');
                ?>
            </p>
        </div>
        <?php
    }
    include SENSORICA2_PATH . 'templates/settings.php';
}
// add settings link to plugin page
function sensorica2_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=sensorica2">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}


function getToolInfo($toolSlug)
{
  $tools = json_decode(file_get_contents(SENSORICA2_PATH.'tools.json'), true);
  foreach ($tools as $tool) {
    if ($tool['slug'] == $toolSlug) {
      $tool = json_decode(file_get_contents(SENSORICA2_PATH.'tools/' . $tool['slug'] . '/info.json'), true);
      break;
    }
  }
  return $tool;
}