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
        ?>
        <table class='wp-list-table widefat fixed striped table-view-list'>
            <thead>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Title</span>
                    </th>
                    <th scope="col" id="shortcode" class="manage-column column-shortcode column-primary sortable desc">
                        <span>Shortcode</span>
                    </th>
                    <th scope="col" id="shortcode" class="manage-column column-shortcode column-primary sortable desc">
                        <span>Embed</span>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $post_id = get_the_ID();
                        $post_title = get_the_title();
                        $post_permalink = get_the_permalink();
                        $post_edit_link = admin_url('admin.php?page=sensorica_shortcodes&edit=' . get_the_ID());
                        $post_shortcode = '[sensorica_chat id="' . $post_id . '"]';
                        $post_embed = '<iframe src="' . $post_permalink . '" width="100%" height="500px"></iframe>';
                        ?>
                        <tr>
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                                <strong>
                                    <a class="row-title" href="<?php echo esc_url($post_edit_link); ?>"
                                        aria-label="“<?php echo esc_attr($post_title); ?>” (Edit)">
                                        <?php echo esc_html($post_title); ?>
                                    </a>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="<?php echo esc_url($post_edit_link); ?>"
                                            aria-label="Edit “<?php echo esc_attr($post_title); ?>”">Edit</a> |
                                    </span>
                                    <span class="trash">
                                        <a href="<?php echo get_delete_post_link($post_id); ?>" class="submitdelete"
                                            aria-label="Move “<?php echo esc_attr($post_title); ?>” to the Trash"
                                            onclick="return confirm('Are you sure you want to delete this shortcode?');">Trash</a>
                                    </span>
                                </div>
                            </td>
                            <td class="shortcode column-shortcode" data-colname="Shortcode">
                                <input type="text" readonly="readonly" class="large-text">
                                <button class="button button-secondary"
                                    onclick="copyToClipboard('<?php echo esc_attr($post_shortcode); ?>')">Copy</button>
                            </td>
                            <td class="shortcode column-shortcode" data-colname="Embed">
                                <input type="text" readonly="readonly" class="large-text">
                                <button class="button button-secondary"
                                    onclick="copyToClipboard('<?php echo esc_attr($post_embed); ?>')">Copy</button>
                            </td>
                        </tr>
                        <?php
                    }
                }


                wp_reset_postdata();
    }
}