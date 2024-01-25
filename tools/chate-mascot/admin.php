<?php




function sensorica_enqueue_block_editor_assets()
{
    wp_enqueue_script(
        'sensorica-blocks',
        sensorica_URL . 'tools/chate-mascot/blocks.js?rand=' . rand(1, 22222),
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
        sensorica_VERSION,
        true
    );

}


function sensorica_get_chats()
{
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

function sensorica_shortcodes_page()
{
    wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');

    echo '<h2>Sensorica Prompts</h2>';
    // Debugging: Check if the taxonomy has any terms and associated posts
    $terms = get_terms(array(
        'taxonomy' => 'sensorica_chats',
        'hide_empty' => false,
    ));



    // Check if a specific post is being edited
    $editing_post_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

    // Handle form submission for edits
    if ('POST' === $_SERVER['REQUEST_METHOD'] && $editing_post_id > 0) {
        check_admin_referer('sensorica_edit_shortcode');

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
        <div class="sensorica_wrapper">

            <main>
                <div class="sensorica_row">
                    <form method="post">
                        <?php wp_nonce_field('sensorica_edit_shortcode'); ?>
                        <input type="hidden" name="edit_id" value="<?php echo esc_attr($editing_post_id); ?>">
                        <div class="sensorica_form-section">

                            <label for="NEXT_PUBLIC_MAIN_TITLE">Main Title:</label>
                            <input class="sensorica_form-control" type="text" name="NEXT_PUBLIC_MAIN_TITLE"
                                id="NEXT_PUBLIC_MAIN_TITLE" value="<?php echo get_the_title($editing_post_id); ?>"><br>

                        </div>
                        <div class="sensorica_form-section">

                            <label for="OPENAI_API_KEY">OpenAI API Key:</label>
                            <input class="sensorica_form-control" type="text" name="OPENAI_API_KEY" id="OPENAI_API_KEY"
                                value="<?php echo esc_attr($saved_inputs['API_KEY'] ?? ''); ?>"><br>
                        </div>
                        <div class="sensorica_form-section">
                            

                                <label for="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT">Default System Prompt:</label>
                                <textarea class="sensorica_prompt-area" name="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"
                                    id="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"><?php echo esc_textarea($saved_inputs['SYSTEM_PROMPT'] ?? ''); ?></textarea>
                                <a style='color:black' href="?tool=gpt-crawler">Attach a database</a>
                            
                        </div>
                        <input type="submit" class="sensorica_btn" value="Update">
                        
                        <div class="sensorica_separator"><span>Codes for embeding: </span></div>
                        <?php
                        sensorica_show_output_links_and_iframes($editing_post_id);

                        ?>
                        </div>
                    </form>
                </div>
            </main>
        </div>
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
                echo '<li><a href="' . admin_url('admin.php?page=sensorica_shortcodes&edit=' . get_the_ID()) . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No posts found with the specified taxonomy.</p>';
        }

        wp_reset_postdata();
    }
}