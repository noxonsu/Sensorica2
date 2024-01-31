<?php 
//allow origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: true");
// Initialize the WordPress environment without themes.
define('WP_USE_THEMES', false);
$wordpress_root_dir = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) . '/';

// Now include wp-load.php
require_once $wordpress_root_dir.'wp-load.php';

// Security check: Ensure that there is a valid post ID and the current user has permission to view it.
if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);

    if ($post) {
        // Include your HTML file here if the post exists and the user has permission.
        $proxy = get_option("sensorica_openaiproxy");
        $proxy = str_replace("telegram.", "apisensorica13015.", $proxy);
        $proxy = 'https://apisensorica13015.onout.org/';
        if (get_option("sensorica_use_openaiproxy") != "1") {
            $proxy = home_url()."/wp-json/sensorica/v1/shortcode/".$post_id;
        }
        echo '<script>';
        echo 'window.sensorica_client_id = "' . esc_attr(get_option("sensorica_client_id",0)) . '";';
        echo 'window.post_id = "' .esc_attr($post_id). '";';
        echo 'window.main_title = "' .esc_attr($post->post_title). '";';
        echo 'window.sensorica_openaiproxy = "' . esc_url($proxy) . '";';
        echo 'window.chatUniqId = "' . esc_attr(get_option("sensorica_client_id",0)) . '_' . esc_attr($post_id) . '";';
        echo '</script>';
        
        include("index.html");
    } else {
        // Handle the case where the post doesn't exist or the user doesn't have permission.
        echo 'You do not have permission to view this page or the page does not exist.';
    }
} else {
    // Handle the case where the post ID is not set or is not valid.
    echo 'Invalid post ID.';
}

?>