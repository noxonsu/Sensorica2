<?php 


//check we are in wordpress
if (!defined('ABSPATH')) {
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NEXT_PUBLIC_MAIN_TITLE'])) {

    
    // Validate and sanitize input data
    $main_title = sanitize_text_field($_POST['NEXT_PUBLIC_MAIN_TITLE'] ?? '');
    $api_key = sanitize_text_field($_POST['OPENAI_API_KEY'] ?? '');
    $system_prompt = sanitize_textarea_field($_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'] ?? '');
    $sensorica_theme = sanitize_text_field($_POST['sensorica_theme'] ?? '');
    $sensorica_openai_model = sanitize_text_field($_POST['sensorica_openai_model'] ?? '');
    // Insert a new post with the 'sensorica_chats' taxonomy
    $post_id = wp_insert_post(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_title' => $main_title,
        'tax_input' => array('sensorica_chats' => array('Sensorica Chat')),
    ));

    if ($post_id) {
        // Save the POST values as post meta
        update_post_meta($post_id, '_sensorica_chat_saved_inputs', array(
            'MAIN_TITLE' => $main_title,
            'API_KEY' => $api_key,
            'SYSTEM_PROMPT' => $system_prompt,
            'sensorica_theme' => $sensorica_theme,
            'sensorica_openai_model' => $sensorica_openai_model
        ));

        wp_set_object_terms($post_id, 'sensorica-chat', 'sensorica_chats');

        echo '<div class="sensorica_form-section">';
        esc_html_e("Chat created successfully. Copy and paste the following code into a page or post to display the chat form.","sensorica");
        echo '<br><br>';
        sensorica_show_output_links_and_iframes($post_id);
        $sensorica_hide_final_form = true;
        echo '</div>';
    }
}







?>