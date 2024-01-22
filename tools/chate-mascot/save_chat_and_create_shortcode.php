<?php 


//check is admin can create shortcodes


// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NEXT_PUBLIC_MAIN_TITLE'])) {

    
    // Validate and sanitize input data
    $main_title = sanitize_text_field($_POST['NEXT_PUBLIC_MAIN_TITLE'] ?? '');
    $api_key = sanitize_text_field($_POST['OPENAI_API_KEY'] ?? '');
    $system_prompt = sanitize_textarea_field($_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'] ?? '');

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
        ));

        wp_set_object_terms($post_id, 'sensorica-chat', 'sensorica_chats');

        echo '<div class="sensorica_title">';
        esc_html_e("Shortcode created successfully. Copy and paste the following shortcode into a page or post to display the chat form.");
        
        // Return the shortcode with the new ID
        echo '<input class="sensorica_form-control"  value="[sensorica_chat id="' . $post_id . '"]" type="text"';

        echo '</div>';

        echo "This shorcote is available in the list of shortcodes in the admin panel.";
        
        $sensorica_hide_final_form = true;
    }
}







?>