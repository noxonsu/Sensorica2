<?php




add_shortcode('sensorica_promptbase', 'sensorica_promptbase');

use erusev\Parsedown;

function sensorica_promptbase($atts)
{   wp_enqueue_style('sensorica-style', sensorica_URL . 'static/new.css', array(), sensorica_VERSION, 'all');

    global $_GET;
    //fetch data from promptbase https://raw.githubusercontent.com/linexjlin/GPTs/main/README.md
    if (isset($_GET['prompt'])) {
        $prompt = $_GET['prompt'];
        //https://raw.githubusercontent.com/linexjlin/GPTs/main/prompts/devrelguide.md
        $url = 'https://raw.githubusercontent.com/linexjlin/GPTs/main/prompts/' . esc_attr($prompt) . '.md';

        $response = wp_remote_get($url);
        
        $body = wp_remote_retrieve_body($response);
        $main_title = sanitize_text_field($prompt." (auto)");
        $api_key = sanitize_text_field(get_option('OPENAI_API_KEY'));
        
        //title = body /\n(.*?)By/g
        
        preg_match_all('/```markdown\n(.*?)```/s', $body, $matches);
        
        
        // Insert a new post with the 'sensorica_chats' taxonomy
        $post_exists = get_page_by_title($main_title, OBJECT, 'post');

        if (!$post_exists) {
            $post_id = wp_insert_post(array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'post_title' => $main_title,
                'tax_input' => array('sensorica_chats' => $body),
            ));
        } else {
            $post_id = $post_exists->ID;
        }
        
        if ($post_id) {
            // Save the POST values as post meta
            ob_start();
            update_post_meta($post_id, '_sensorica_chat_saved_inputs', array(
                
                'API_KEY' => $api_key,
                'SYSTEM_PROMPT' => $body,
            ));
    
            wp_set_object_terms($post_id, 'sensorica-chat', 'sensorica_chats');
            
            echo sensorica_chat_shortcode(array("id"=>$post_id));
            $chat .= ob_get_clean();
        }
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