<?php
use phpseclib\Crypt\RSA;
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
function sensorica_chat_shortcode($atts)
{
    // Get the 'id' attribute from the shortcode
    $atts = shortcode_atts(array(
        'id' => 'newshortcode',
        'title' => ''
    ), $atts);
    $envato_key = get_option('sensorica_envato_key', '');
    // Check if the user is an admin
    $sensorica_rest_url = site_url() . '/wp-json/sensorica2/v1/shortcode/' . $atts['id'];
    $sensorica_rest_url = base64_encode($sensorica_rest_url);
    $iframe_url = SENSORICA2_URL.'vendor_source/index.html?site=' . urlencode($envato_key) . '&chat='.$sensorica_rest_url;

    return '<iframe src="' . esc_url($iframe_url) . '" style="border: 0; width: 100%; height: 100%; min-height:700px; border-radius: 15px;" allowfullscreen></iframe>';
}


function sensorica2_shortcodes_page()
{
    echo '<h2>Sensorica2 Shortcodes</h2>';
    // Debugging: Check if the taxonomy has any terms and associated posts
    $terms = get_terms(array(
        'taxonomy' => 'sensorica_chats',
        'hide_empty' => false,
    ));
    
    

    // Check if a specific post is being edited
    $editing_post_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

    // Handle form submission for edits
    if ('POST' === $_SERVER['REQUEST_METHOD'] && $editing_post_id > 0) {
        check_admin_referer('sensorica2_edit_shortcode');

        $main_title = sanitize_text_field($_POST['NEXT_PUBLIC_MAIN_TITLE'] ?? '');
        $api_key = sanitize_text_field($_POST['OPENAI_API_KEY'] ?? '');
        $system_prompt = sanitize_textarea_field($_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'] ?? '');

        // Update the post title
        if ($editing_post_id > 0) {
            $post_data = array(
                'ID' => $editing_post_id,
                'post_title' => $main_title,
            );
            wp_update_post($post_data);
        }

        update_post_meta($editing_post_id, '_sensorica_chat_saved_inputs', array(
            'API_KEY' => $api_key,
            'SYSTEM_PROMPT' => $system_prompt,
        ));

        echo '<div class="notice notice-success"><p>Shortcode updated successfully.</p></div>';
    }

    // Edit form for a specific post
    if ($editing_post_id > 0) {
        $saved_inputs = get_post_meta($editing_post_id, '_sensorica_chat_saved_inputs', true);

        ?>
        <form method="post">
            <?php wp_nonce_field('sensorica2_edit_shortcode'); ?>
            <input type="hidden" name="edit_id" value="<?php echo esc_attr($editing_post_id); ?>">

            <label for="NEXT_PUBLIC_MAIN_TITLE">Main Title:</label>
            <input type="text" name="NEXT_PUBLIC_MAIN_TITLE" id="NEXT_PUBLIC_MAIN_TITLE"
                value="<?php echo esc_attr($saved_inputs['MAIN_TITLE'] ?? ''); ?>"><br>

            <label for="OPENAI_API_KEY">OpenAI API Key:</label>
            <input type="text" name="OPENAI_API_KEY" id="OPENAI_API_KEY"
                value="<?php echo esc_attr($saved_inputs['API_KEY'] ?? ''); ?>"><br>

            <label for="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT">Default System Prompt:</label>
            <textarea name="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"
                id="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"><?php echo esc_textarea($saved_inputs['SYSTEM_PROMPT'] ?? ''); ?></textarea><br>

            <input type="submit" value="Update">
        </form>
        <?php
    } else {
        // List all posts with 'sensorica_chats' taxonomy
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'sensorica_chats',
                    'field' => 'slug',
                    'terms' => 'sensorica-chat', // Replace with the correct term slug
                ),
            ),
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . admin_url('admin.php?page=sensorica2_shortcodes&edit=' . get_the_ID()) . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No posts found with the specified taxonomy.</p>';
        }

        wp_reset_postdata();
    }
}



function sensorica2_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'sensorica2-blocks',
        SENSORICA2_URL . 'tools/chate-mascot/blocks.js?rand=' . rand(1,22222),
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
        SENSORICA2_VERSION,
        true
    );

}


function sensorica2_get_chats() {
    $chats_query = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'sensorica_chats',
                'field' => 'term_id',
                'terms' => get_terms('sensorica_chats', array('fields' => 'ids')),
            ),
        ),
        'posts_per_page' => -1,
    ));

    $chats = array();
    if ($chats_query->have_posts()) {
        while ($chats_query->have_posts()) {
            $chats_query->the_post();
            $chats[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
            );
        }
    }
    return new WP_REST_Response($chats, 200);
}

function sensorica2_get_shortcode_data($data) {
    $shortcode_id = $data['id'];
    $saved_inputs = get_post_meta($shortcode_id, '_sensorica_chat_saved_inputs', true);

    unset($saved_inputs['API_KEY']);

    // Create a new RSA object
    $rsa = new RSA();

    // Extract the private key and public key
    extract($rsa->createKey());

    echo $publickey;
    // Encrypt the data using the public key
    $rsa->loadKey($publickey);
    $encrypted = $rsa->encrypt(json_encode($saved_inputs));

    echo "Encrypted: " . base64_encode($encrypted) . "\n";

    // Decrypt the data using the private key
    $rsa->loadKey($privatekey);
    $decrypted = $rsa->decrypt($encrypted);

    echo "Decrypted: " . $decrypted . "\n";

    if (!$saved_inputs) {
        return new WP_Error('no_data', 'No data found', array('status' => 404));
    }

    return new WP_REST_Response(base64_encode($encrypted), 200);
}

function sensorica_form_shortcode($atts) {

    wp_enqueue_script('jquery');
    wp_enqueue_style('sensorica2-style', SENSORICA2_URL . 'static/new.css', array(), SENSORICA2_VERSION, 'all');
  
    // Set the tool parameter
    $_GET['tool'] = 'chate-mascot';

    // Define the path to the file
    $file_path = SENSORICA2_PATH . 'index.php';

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

add_shortcode('sensorica_chat', 'sensorica_chat_shortcode');
add_action('init', 'register_sensorica_chats_taxonomy');

add_action('enqueue_block_editor_assets', 'sensorica2_enqueue_block_editor_assets');

add_shortcode('sensorica_form', 'sensorica_form_shortcode');
add_action('rest_api_init', function () {
    register_rest_route('sensorica2/v1', '/chats/', array(
        'methods' => 'GET',
        'callback' => 'sensorica2_get_chats',
        'permission_callback' => '__return_true'
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('sensorica2/v1', '/shortcode/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'sensorica2_get_shortcode_data',
        'permission_callback' => '__return_true',
    ));
});
