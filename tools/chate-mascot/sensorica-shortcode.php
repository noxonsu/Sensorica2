<?php
use phpseclib\Crypt\RSA;

include sensorica_PATH.'tools/chate-mascot/admin.php';


function register_sensorica_chats_taxonomy()
{
    $labels = array(
        'name' => 'Sensorica Chats',
        'singular_name' => 'Sensorica Chat',
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'sensorica_chats'),
    );
    register_taxonomy('sensorica_chats', 'post', $args);
}

function sensorica_get_iframe_url($post_id)
{
    $iframe_url = sensorica_URL . 'tools/chate-mascot/vendor_source/Chat.php?post_id=' . $post_id;
    return $iframe_url;
}
function sensorica_chat_shortcode($atts)
{
    // Get the 'id' attribute from the shortcode
   

    //iframre rest api call 'sensorica/v1', '/chat/(?P<id>\d+)'
    $iframe_url = sensorica_get_iframe_url($atts['id']);


    return '<iframe src="' . esc_url($iframe_url) . '" style="border: 0; width: 100%; height: 100%; min-height:700px; border-radius: 15px;" allowfullscreen></iframe><br><a style="color:gray" href="https://onout.org/embedGPT">embed chatgpt</a>';
}



function sensorica_get_shortcode_data($data) {
    $shortcode_id = $data['id'];
    $saved_inputs = get_post_meta($shortcode_id, '_sensorica_chat_saved_inputs', true);

    $rsapublic = get_option('sensorica_backend_rsa_openkey_base64', '');
    // Create a new RSA object
    $rsa = new RSA();

    $rsa->loadKey(base64_decode($rsapublic));
    $encrypted = $rsa->encrypt(json_encode($saved_inputs));

    
    // Decrypt the data using the private key

    //$decrypted = $rsa->decrypt($encrypted);

    

    if (!$saved_inputs) {
        return new WP_Error('no_data', 'No data found', array('status' => 404));
    }
    $json_res['data'] = $saved_inputs;
    $json_res['encrypted'] = base64_encode($encrypted);
    return new WP_REST_Response($json_res, 200);
}

function sensorica_form_shortcode($atts) {

    wp_enqueue_script('jquery');
    wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION."_".rand(1,44), 'all');
  
    // Set the tool parameter
    $_GET['tool'] = 'chate-mascot';

    // Define the path to the file
    $file_path = sensorica_PATH . 'index.php';

    // Check if the file exists
    if (file_exists($file_path)) {
        ob_start();
        include $file_path;
        $output = ob_get_clean();
        return $output;
    } else {
        return '<p>Error: File not found.</p>';
    }
}

function sensorica_show_output_links_and_iframes($editing_post_id) { ?>
    <hr>
    
    Shortcode: <code>[sensorica_chat id="<?php echo esc_attr($editing_post_id); ?>"]</code>
    <hr>
    <div class="sensorica_form-section">
        <label>HTML widget:</label>
        <textarea rows="3" cols="50" readonly><?php 
            $shortcode_html = sensorica_chat_shortcode(array(
                'id' => $editing_post_id,
            ));
            echo esc_textarea($shortcode_html);
            ?>
        </textarea>
    </div>
    <hr>
    <div class="sensorica_form-section">
        <label>Direct url to this chat iframe:</label>
        <input class="sensorica_form-control" type="text"
               value="<?php echo esc_url(sensorica_get_iframe_url($editing_post_id)); ?>" readonly>
    </div>
    <?php
    }
    



add_shortcode('sensorica_chat', 'sensorica_chat_shortcode');
add_action('init', 'register_sensorica_chats_taxonomy');

add_action('enqueue_block_editor_assets', 'sensorica_enqueue_block_editor_assets');

add_shortcode('sensorica_form', 'sensorica_form_shortcode');
add_action('rest_api_init', function () {
    register_rest_route('sensorica/v1', '/chats/', array(
        'methods' => 'GET',
        'callback' => 'sensorica_get_chats',
        'permission_callback' => '__return_true'
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('sensorica/v1', '/shortcode/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'sensorica_get_shortcode_data',
        'permission_callback' => '__return_true',
    ));
});


