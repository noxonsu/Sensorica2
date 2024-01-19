<?php

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
add_action('init', 'register_sensorica_chats_taxonomy');
function sensorica_chat_shortcode($atts)
{
    // Get the 'id' attribute from the shortcode
    $atts = shortcode_atts(array(
        'id' => ''
    ), $atts);

    // Check if the user is an admin
    $current_user = wp_get_current_user();
    $is_admin = in_array('administrator', $current_user->roles);

    // Admin view: Display saved inputs
    if ($is_admin && !empty($atts['id'])) {
        $saved_inputs = get_post_meta($atts['id'], '_sensorica_chat_saved_inputs', true);

        if ($saved_inputs) {
            ob_start();
            echo '<pre>';
            print_r($saved_inputs);
            echo '</pre>';
            return ob_get_clean();
        }
    }



    // Non-admin user view: Display the form
    ob_start();
    ?>
    <form method="post">
        <label for="NEXT_PUBLIC_MAIN_TITLE">Main Title:</label>
        <input type="text" name="NEXT_PUBLIC_MAIN_TITLE" id="NEXT_PUBLIC_MAIN_TITLE" /><br>

        <label for="OPENAI_API_KEY">OpenAI API Key:</label>
        <input type="text" name="OPENAI_API_KEY" id="OPENAI_API_KEY" /><br>

        <label for="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT">Default System Prompt:</label>
        <textarea name="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT" id="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"></textarea><br>

        <input type="submit" value="Submit">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('sensorica_chat', 'sensorica_chat_shortcode');


function sensorica2_shortcodes_page()
{
    echo '<h2>Sensorica2 Shortcodes</h2>';
    // Debugging: Check if the taxonomy has any terms and associated posts
    $terms = get_terms(array(
        'taxonomy' => 'sensorica_chats',
        'hide_empty' => false,
    ));
    echo '<pre>';
    print_r($terms); // This will show all terms in the 'sensorica_chats' taxonomy
    echo '</pre>';

    // Check if a specific post is being edited
    $editing_post_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

    // Handle form submission for edits
    if ('POST' === $_SERVER['REQUEST_METHOD'] && $editing_post_id > 0) {
        check_admin_referer('sensorica2_edit_shortcode');

        $main_title = sanitize_text_field($_POST['NEXT_PUBLIC_MAIN_TITLE'] ?? '');
        $api_key = sanitize_text_field($_POST['OPENAI_API_KEY'] ?? '');
        $system_prompt = sanitize_textarea_field($_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'] ?? '');

        update_post_meta($editing_post_id, '_sensorica_chat_saved_inputs', array(
            'MAIN_TITLE' => $main_title,
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




