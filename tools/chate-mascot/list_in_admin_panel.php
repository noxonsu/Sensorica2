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


    // Check if a specific post is being edited
    $editing_post_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

    // Handle form submission for edits
    if ('POST' === $_SERVER['REQUEST_METHOD'] && $editing_post_id > 0 && is_admin()) {
        $main_title = sanitize_text_field($_POST['NEXT_PUBLIC_MAIN_TITLE'] ?? '');
        $api_key = sanitize_text_field($_POST['OPENAI_API_KEY'] ?? '');
        $system_prompt = sanitize_textarea_field($_POST['NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT'] ?? '');
        $sensorica_openai_model = sanitize_text_field($_POST['sensorica_openai_model'] ?? 'gpt-3.5-turbo-0125');
        $sensorica_theme = sanitize_text_field($_POST['sensorica_theme'] ?? 'light');

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
            'sensorica_openai_model' => $sensorica_openai_model,
            'sensorica_theme' => $sensorica_theme
        ));

        echo '<div class="notice notice-success"><p>' . esc_html__('Shortcode updated successfully.', 'sensorica') . '</p></div>';
    }

    // Edit form for a specific post
    if ($editing_post_id > 0) {
        $saved_inputs = get_post_meta($editing_post_id, '_sensorica_chat_saved_inputs', true);
        //check if admin is editing the post
        if (!current_user_can('edit_post', $editing_post_id)) {
            wp_die(esc_html_e('You do not have sufficient permissions to access this page.', 'sensorica'));
        }
        ?>
        <div class="sensorica_wrapper">
            <main>
                <div class="sensorica_row">
                    <form method="post">
                        <?php wp_nonce_field('sensorica_edit_shortcode'); ?>
                        <input type="hidden" name="edit_id" value="<?php echo esc_attr($editing_post_id); ?>">
                        <div class="sensorica_form-section">
                            <label for="NEXT_PUBLIC_MAIN_TITLE">
                                <?php esc_html_e('Main Title:', 'sensorica'); ?>
                            </label>
                            <input class="sensorica_form-control" type="text" name="NEXT_PUBLIC_MAIN_TITLE"
                                id="NEXT_PUBLIC_MAIN_TITLE" value="<?php echo get_the_title($editing_post_id); ?>"><br>
                        </div>
                        <div class="sensorica_form-section">
                            <label for="OPENAI_API_KEY">
                                <?php esc_html_e('OpenAI API Key:', 'sensorica'); ?>
                            </label>
                            <input class="sensorica_form-control" type="text" name="OPENAI_API_KEY" id="OPENAI_API_KEY"
                                value="<?php echo esc_attr($saved_inputs['API_KEY'] ?? ''); ?>"><br>
                        </div>
                        <div class="sensorica_form-section">
                            <label for="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT">
                                <?php esc_html_e('Default System Prompt:', 'sensorica'); ?>
                            </label>
                            <textarea class="sensorica_prompt-area" name="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"
                                id="NEXT_PUBLIC_DEFAULT_SYSTEM_PROMPT"><?php echo esc_textarea($saved_inputs['SYSTEM_PROMPT'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="sensorica_form-section">
                            <label for="NEXT_PUBLIC_MODEL">
                                <?php esc_html_e('Model:', 'sensorica'); ?>
                            </label>
                            <select name="sensorica_openai_model" id="NEXT_PUBLIC_MODEL">
                                <option value="gpt-3.5-turbo-0125" <?php echo ($saved_inputs['sensorica_openai_model'] ?? '') === 'gpt-3.5-turbo-0125' ? 'selected' : ''; ?>>GPT-3.5 Turbo</option>
                                <option value="gpt-4-turbo-preview" <?php echo ($saved_inputs['sensorica_openai_model'] ?? '') === 'gpt-4-turbo-preview' ? 'selected' : ''; ?>>GPT-4 Turbo Preview</option>
                            </select>
                        </div>

                        <div class="sensorica_form-section">
                            <label for="NEXT_PUBLIC_THEME">
                                <?php esc_html_e('Theme:', 'sensorica'); ?>
                            </label>
                            <select name="sensorica_theme" id="NEXT_PUBLIC_THEME">
                                <option value="light" <?php echo ($saved_inputs['sensorica_theme'] ?? '') === 'light' ? 'selected' : ''; ?>>Light</option>
                                <option value="dark" <?php echo ($saved_inputs['sensorica_theme'] ?? '') === 'dark' ? 'selected' : ''; ?>>Dark</option>
                            </select>
                        </div>

                        <input type="submit" class="sensorica_btn" value="<?php esc_attr_e('Update', 'sensorica'); ?>">
                        <div class="sensorica_separator"><span>
                                <?php esc_html_e('Codes for embedding:', 'sensorica'); ?>
                            </span></div>
                        <?php sensorica_show_output_links_and_iframes($editing_post_id); ?>
                    </form>
                </div>
            </main>
        </div>

        <?php
    } else {
        echo '<div class="wrap"><br><br></div>';
        // List all posts with 'sensorica_chats' taxonomy
        $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
        $limit = 30;
        $offset = ($pagenum - 1) * $limit;
        global $wpdb;
        $total = $wpdb->get_var("SELECT COUNT(`ID`) FROM {$wpdb->posts} WHERE `post_type` = 'post' AND `post_status` = 'publish' AND `post_title` != ''");
        $num_of_pages = ceil($total / $limit);

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
            'posts_per_page' => $limit,
            'offset' => $offset,
        );

        $query = new WP_Query($args);
        ?>
        <table class='wp-list-table widefat fixed striped table-view-list'>
            <thead>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>
                            <?php esc_html_e('Title', 'sensorica'); ?>
                        </span>
                    </th>

                </tr>
            </thead>
            <tbody id="the-list">
                <?php
                //if no items show mase message
                if (!$query->have_posts()) {
                    echo '<tr><td colspan="2">' . esc_html__('No chat found.', 'sensorica') . '</td></tr>';
                } 
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
                            <td class="title column-title has-row-actions column-primary page-title"
                                data-colname="<?php esc_attr_e('Title', 'sensorica'); ?>">
                                <strong>
                                    <a class="row-title" href="<?php echo esc_url($post_edit_link); ?>"
                                        aria-label="<?php echo esc_attr_e('Edit', 'sensorica'); ?> “<?php echo esc_attr($post_title); ?>” (Edit)">
                                        <?php echo esc_html($post_title); ?>
                                    </a>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="<?php echo esc_url($post_edit_link); ?>"
                                            aria-label="<?php echo esc_attr_e('Edit', 'sensorica'); ?> “<?php echo esc_attr($post_title); ?>”">
                                            <?php esc_html_e('Edit', 'sensorica'); ?>
                                        </a> |
                                    </span>
                                    <span class="trash">
                                        <a href="<?php echo get_delete_post_link($post_id); ?>" class="submitdelete"
                                            aria-label="<?php echo esc_attr_e('Move', 'sensorica'); ?> “<?php echo esc_attr($post_title); ?>” <?php echo esc_attr_e('to the Trash', 'sensorica'); ?>"
                                            onclick="return confirm('<?php echo esc_js(esc_html_e('Are you sure you want to delete this shortcode?', 'sensorica')); ?>');">
                                            <?php esc_html_e('Trash', 'sensorica'); ?>
                                        </a>
                                    </span>
                                </div>
                            </td>

                        </tr>
                        <?php
                    }
                }

                wp_reset_postdata();
                ?>
            </tbody>
        </table>
        <?php
        //add pagination here

        $page_links = paginate_links(array(
            'base' => add_query_arg('pagenum', '%#%'),
            'format' => '',
            'prev_text' => __('&laquo;', 'text-domain'),
            'next_text' => __('&raquo;', 'text-domain'),
            'total' => $num_of_pages,
            'current' => $pagenum
        ));

        if ($page_links) {
            echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
        }
    ?>
    <?php
    }
}