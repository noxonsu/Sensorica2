<?php
/**
 * Plugin Name: Sensorica
 * Description: Run own AI wrapper.
 * Author: Aleksandr Noxon
 * Version: 0.1.2
 * Requires PHP: 7.1
 * Text Domain: sensorica
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Constants.
define('sensorica_VERSION', '0.1.2');
$plugin_url = plugins_url('', __FILE__);

// Check if the site is accessed via HTTPS and adjust the URL if necessary
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $plugin_url = str_replace('http://', 'https://', $plugin_url)."/";
}
//get sensorica_URL plugin dirrectory using base site url
define('sensorica_URL', $plugin_url);
define('sensorica_PATH', plugin_dir_path(__FILE__));
define('sensorica_BASENAME', plugin_basename(__FILE__));

require_once sensorica_PATH . '/vendor/autoload.php';
include sensorica_PATH . 'tools/chate-mascot/sensorica-shortcode.php';
include sensorica_PATH . 'settings.php';
include sensorica_PATH . 'promptbase_shortcode.php';
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


function sensorica_menu()
{
    // Add the top-level menu page.
    add_menu_page('Sensorica', 'Sensorica', 'manage_options', 'sensorica', 'sensorica_admin_page');

    // Add the submenus - New Run and Runs
    add_submenu_page('sensorica', 'Sensorica Shortcodes', 'All Prompts', 'manage_options', 'sensorica_shortcodes', 'sensorica_shortcodes_page');
    add_submenu_page('sensorica', 'Sensorica Settings', 'Settings', 'manage_options', 'sensorica_settings', 'sensorica_settings_page');
    
    
    // WordPress will automatically create a submenu with the same slug as the main menu.
    // Rename this automatically created submenu to "Settings"
    global $submenu;
    if (isset($submenu['sensorica'])) {
        $submenu['sensorica'][0][0] = 'New AI Prompt';
    }
}


function sensorica_admin_page()
{
    include sensorica_PATH . 'index.php';
}

// add settings link to plugin page
function sensorica_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=sensorica">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}


function getToolInfo($toolSlug)
{
  $tools = json_decode(file_get_contents(sensorica_PATH.'platforms.json'), true);
  foreach ($tools as $tool) {
    if ($tool['slug'] == $toolSlug) {
      $tool = json_decode(file_get_contents(sensorica_PATH.'tools/' . $tool['slug'] . '/info.json'), true);
      break;
    }
  }
  return $tool;
}

function sensorica_body_class($classes)
{ 
  $sensorica_theme = get_option('sensorica_theme', 'light');
  $classes[] = $sensorica_theme;
  return $classes;
}


add_action('admin_menu', 'sensorica_menu');



add_filter('body_class', 'sensorica_body_class');

function admin_body_class( $classes ) {
     $classes .= ' 4 '.get_option('sensorica_theme', 'light');

    return $classes;
}

add_filter("admin_body_class", "admin_body_class", 9999); 

