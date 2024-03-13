<?php
/**
 * Plugin Name: Sensorica
 * Description: simple AI chat for WordPress
 * Author: Aleksandr Noxon
 * Version: 0.1.0
 * Requires PHP: 7.1
 * Text Domain: sensorica
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Constants.
define('sensorica_VERSION', '0.1.0');
$plugin_url = plugins_url('', __FILE__);

// Check if the site is accessed via HTTPS and adjust the URL if necessary
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $plugin_url = str_replace('http://', 'https://', $plugin_url)."/";
}
//get sensorica_URL plugin dirrectory using base site url
define('sensorica_URL', $plugin_url);
define('sensorica_PATH', plugin_dir_path(__FILE__));
define('sensorica_BASENAME', plugin_basename(__FILE__));

include sensorica_PATH . 'tools/chate-mascot/sensorica-shortcode.php';
include sensorica_PATH . 'settings.php';
//include sensorica_PATH . 'promptbase_shortcode.php';
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
    add_menu_page('Sensorica', 'Sensorica', 'manage_options', 'sensorica_newchat', 'sensorica_admin_new_chat');

    // Add the submenus - New Run and Runs
    add_submenu_page('sensorica_newchat', 'Sensorica Shortcodes', 'All Chats', 'manage_options', 'sensorica_shortcodes', 'sensorica_shortcodes_page');
    add_submenu_page('sensorica_newchat', 'Sensorica Settings', 'Settings', 'manage_options', 'sensorica_settings', 'sensorica_settings_page');
    
    
    // WordPress will automatically create a submenu with the same slug as the main menu.
    // Rename this automatically created submenu to "Settings"
    global $submenu;
    if (isset($submenu['sensorica_newchat'])) {
        $submenu['sensorica_newchat'][0][0] = 'New AI Chat';
    }
}

function sensorica_show_footer() { ?>
  <footer class='sensorica'>
      <?php // Only for admin
      if (current_user_can('administrator')) :
      ?>
        <div class="sensorica_supportWrapper">
          <?php esc_html_e('Support:', 'sensorica'); ?>
          <a href="mailto:support@onout.org" target="_blank" rel="noreferrer">
            <svg class="icon">
              <use xlink:href="#ico-eml"></use>
            </svg>
            <?php esc_html_e('Email', 'sensorica'); ?>
          </a>
          <?php esc_html_e('or', 'sensorica'); ?>
          <a href="https://t.me/onoutsupportbot" target="_blank" rel="noreferrer">
            <svg class="icon">
              <use xlink:href="#ico-tlg"></use>
            </svg>
            <?php esc_html_e('Telegram', 'sensorica'); ?>
          </a>
        </div>
        <div class="sensorica_supportWrapper">
          <?php esc_html_e('Community:', 'sensorica'); ?>
          <a href="https://discord.com/channels/898545581591506975/1078964362783506442" target="_blank">
            <?php esc_html_e('Discord', 'sensorica'); ?>
          </a>
          <?php esc_html_e('or', 'sensorica'); ?>
          <a href="https://t.me/sensorica2" target="_blank">
            <?php esc_html_e('Telegram', 'sensorica'); ?>
          </a>
        </div>
      <?php endif; ?>
    </footer> <?php 
}
function sensorica_admin_new_chat()
{
    //check if get_option sensorica_envato_key is not set ask him to enter the key
    if (!get_option('sensorica_envato_key')) {
      $sensorica_settings_message = 'Please enter your Envato purchase code to activate the plugin. Go to Sensorica -> Settings';  
      if (get_option('sensorica_client_id') == '') {
        $sensorica_settings_message .= '. Error: Client ID is not set';
      }
      ?>
      <br><br>
      <div class=wrap>
      <div id="message" class="notice is-dismissible">
      <br>
        <?php esc_html_e( $sensorica_settings_message, 'sensorica' ); ?>
        <br> <br>
      </div>
      </div>
      <?
    } else {
        include sensorica_PATH . 'new_chat.php';
    }

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

function admin_body_class( $classes ) {
    $classes .= ' 4 '.get_option('sensorica_theme', 'light');

   return $classes;
}



function sensorica_promptbase($atts)
{   wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');

    global $_GET;
    //fetch data from promptbase https://raw.githubusercontent.com/linexjlin/GPTs/main/README.md
    if (isset($_GET['prompt'])) {
        $prompt = $_GET['prompt'];
        //https://raw.githubusercontent.com/linexjlin/GPTs/main/prompts/devrelguide.md
        $url = 'https://raw.githubusercontent.com/linexjlin/GPTs/main/prompts/' . esc_attr($prompt) . '.md';
       
        $response = get_transient('sensorica_promptbase_response'.md5($url));

        if (false === $response) {
            $response = wp_remote_get($url);
            set_transient('sensorica_promptbase_response'.md5($url), $response, 3600); // Cache for 1 hour
        }
        
        $body = wp_remote_retrieve_body($response);
        $main_title = sanitize_text_field($prompt." (auto)");
        $api_key = sanitize_text_field(get_option('OPENAI_API_KEY'));
        
        //title = body /\n(.*?)By/g
        
        preg_match_all('/```markdown\n(.*?)```/s', $body, $matches);
        
        
        $body = '<textarea disabled class="sensorica_prompt-area">'.$body.'</textarea>';
    } else {


        $url = 'https://raw.githubusercontent.com/linexjlin/GPTs/main/README.md';
        $response = wp_remote_get($url);
        //print_r($response);
        $body = wp_remote_retrieve_body($response);

        $page_link = get_permalink();
        //add argument (with correct url format) to the link
        
        
        //preg_match_all and return only - \[.*?\]\(\./prompts/(.*?)\.md\) (.*?)\n to a href link to the link where this shportcode is used with the same name.  $body = preg_replace('/\[.*?\]\(\.\/prompts\/(.*?)\.md\) (.*?)\n/', '<li><a href="'.$page_link.'">$1</a> $2 </li>', $body);

        preg_match_all('/\[.*?\]\(\.\/prompts\/(.*?)\.md\) (.*?)\n/', $body, $matches);

        $body = '<ul>';
        foreach ($matches[1] as $key => $value) {
            $page_link = add_query_arg('prompt', $value, $page_link);
       
            $body .= '<li><a href="' . $page_link . '">' . $value . '</a> ' . $matches[2][$key] . ' </li>';
        }
        $body .= '</ul>';
        //remove –
        $body = preg_replace('/&#8211;/', '', $body);

    }
    return $body;
}

function sensorica_show_confirmation_form($tool) {
    global $_POST;
    echo '<div class="sensorica_title">Confirm & finalize</div>';
            foreach ($tool['inputs'] as $input => $input_data) {
              // Check if $_POST[$input] is set to avoid undefined index notice
              
              $postValue = isset($_POST[$input]) ? stripslashes($_POST[$input]) : '';
  
              if ($input == 'NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT') {
                echo '<div class="sensorica_form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><textarea class="sensorica_prompt-area" disabled name="' . $input . '" id="' . $input . '">' . $postValue . '</textarea></div>';
              } else {
                echo '<div class="sensorica_form-section"><label for="' . $input . '">' . $input_data['description'] . '</label><input type="text" class="sensorica_form-control sensorica_userinput" name="' . $input . '" id="' . $input . '" value="' . $postValue . '" disabled /></div>';
              }
  
            }
            echo '<input type="submit" value="' . esc_attr__('Finalize', 'sensorica') . '" name="action" class="sensorica_btn" />';
          
  }

add_action('admin_menu', 'sensorica_menu');
add_filter('body_class', 'sensorica_body_class');
add_filter("admin_body_class", "admin_body_class", 9999); 

add_shortcode('sensorica_promptbase', 'sensorica_promptbase');

add_action("init", "sensorica_init");

function sensorica_init()
{
  wp_enqueue_script('jquery');
  wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');
}  

function sensorica_enqueue_scripts_prompt_textarea() {
  wp_enqueue_script(
    'prompt-textarea-script',
    sensorica_URL . '/static/js/prompt_textarea.js?r=' . rand(1, 22222),
    array('jquery'), // Make sure jQuery is properly loaded
    sensorica_VERSION,
    true
);

// JavaScript code to set the global variable
$inline_script = 'window.sensorica_plugin_url = "' . sensorica_URL . '";';

// Add the inline script to 'prompt-textarea-script'
wp_add_inline_script('prompt-textarea-script', $inline_script);
}



function sensorica_enqueue_scripts_magic_prompt() {
  // Enqueue the script first
  wp_enqueue_script(
      'magic-prompt-script', // Updated handle for the new script
      sensorica_URL . 'static/js/magic_prompt.js', 
      array('jquery'), // Dependencies remain the same
      sensorica_VERSION, // Version
      true // In footer
  );

  // JavaScript code to set the global variable remains the same
  $inline_script = 'window.sensorica_plugin_url = "' . sensorica_URL . '";';

  // Add the inline script to 'magic-prompt-script' (updated handle)
  wp_add_inline_script('magic-prompt-script', $inline_script);
}


add_action('wp_enqueue_scripts', 'sensorica_enqueue_scripts_magic_prompt');

add_action('admin_enqueue_scripts', 'sensorica_enqueue_scripts_magic_prompt');

if (isset($_GET['sensorica_tool']) && $_GET['sensorica_tool'] == 'chate-mascot') {
  add_action('admin_enqueue_scripts', 'sensorica_enqueue_scripts_prompt_textarea');
}


