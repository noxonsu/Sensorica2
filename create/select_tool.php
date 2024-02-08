<div class="<?php echo esc_attr('sensorica_sidebar'); ?>">
    <div class="<?php echo esc_attr('sensorica_title'); ?>">Fill The Form Step By Step:</div>
    <ul class="<?php echo esc_attr('sensorica_nav'); ?>">
        <li>
            <a href="javascript:void(0);" class="<?php echo esc_attr('sensorica_tab-title sensorica_active'); ?>" data-title="title8"><svg
                    class="<?php echo esc_attr('sensorica_icon'); ?>">
                    <use xlink:href="#ico-2"></use>
                </svg><?php echo esc_html('Select a tool'); ?></a>
        </li>
        <li>
            <a href="javascript:void(0);" class="<?php echo esc_attr('sensorica_tab-title'); ?>" data-title="title8"><svg class="<?php echo esc_attr('sensorica_icon'); ?>">
                    <use xlink:href="#ico-3"></use>
                </svg><?php echo esc_html('Specify the inputs'); ?></a>
        </li>
        <li>
            <a href="javascript:void(0);" class="<?php echo esc_attr('sensorica_tab-title'); ?>" data-title="title8"><svg class="<?php echo esc_attr('sensorica_icon'); ?>">
                    <use xlink:href="#ico-7"></use>
                </svg><?php echo esc_html('Fetch the result'); ?></a>
        </li>
        
    </ul>
</div>

<div class="<?php echo esc_attr('sensorica_content'); ?>">
    <form method="post" action="<?php echo esc_url('bot/deploy'); ?>">
        <div class="<?php echo esc_attr('sensorica_tab-container'); ?>">
            <!-- tab 5 -->
            <div class="<?php echo esc_attr('sensorica_active sensorica_filled'); ?>" data-content="title5">
                <div class="<?php echo esc_attr('sensorica_headline'); ?>" id="promptHeadline">
                    <span id="customIcon" style="background: #e00094"><svg class="<?php echo esc_attr('sensorica_icon'); ?>">
                            <use xlink:href="#ico-1"></use>
                        </svg> </span><?php echo esc_html('Select a platform where you want to deploy your bot'); ?>
                </div>
                

                <ul class="<?php echo esc_attr('sensorica_accordionOptionsList'); ?>" id="listOfPrompts">
                    <?php
                    $sensorica_platforms = json_decode(file_get_contents(sensorica_PATH . 'platforms.json'), true);

                    foreach ($sensorica_platforms as $sensorica_tool) {
                        
                        if (!isset($sensorica_tool['img'])) {
                            //echo sensorica_URL;
                            $sensorica_tool['img'] = sensorica_URL . 'tools/' . $sensorica_tool['slug'] . '/icon.png';
                           
                        }

                        //if optiion sensorica_dont_use_openaiproxy is set, skip skip tools with ['only_with_proxy'] set to true
                        if (get_option('sensorica_dont_use_openaiproxy') == 1 && isset($sensorica_tool['only_with_proxy']) && $sensorica_tool['only_with_proxy']) {
                            //show button but visibility 0.5 and disabled
                            echo '<li style="opacity: 0.5;"><button  onclick="alert(\'Please enable proxy api\')" data-prompt="' . esc_attr($sensorica_tool['slug']) . '" type="button"><img src="' . esc_url($sensorica_tool['img']) . '" /><span>' . esc_html($sensorica_tool['title']) . '</span>' . esc_html($sensorica_tool['description']) . '</button></li>';
                            continue;
                        }

                        // Use admin_url() to generate the correct admin URL with query parameters
                        $create_tool_url = esc_url(add_query_arg(array(
                            'page' => 'sensorica_newchat',
                            'sensorica_tool' => $sensorica_tool['slug'],
                        ), admin_url('admin.php')));

                        echo '<li><button onclick="window.location=\'' . $create_tool_url . '\'" data-prompt="' . $sensorica_tool['slug'] . '" type="button"><img src="' . esc_url($sensorica_tool['img']) . '" /><span>' . esc_html($sensorica_tool['title']) . '</span>' . esc_html($sensorica_tool['description']) . '</button></li>';
                    }
                    ?>
                </ul>


                <ul class="<?php echo esc_attr('sensorica_accordionOptionsList'); ?>" id="listOfPrompts"></ul>
                <div class="<?php echo esc_attr('sensorica_more'); ?>">
                    <a href="<?php echo esc_url('https://onout.org/sponsor.md'); ?>" target="_blank"><?php echo esc_html('Request a platforrm'); ?></a>
                </div>
                <div class="<?php echo esc_attr('sensorica_button-wrap'); ?>">
                    <button type="submit" class="<?php echo esc_attr('sensorica_btn sensorica_btn-prompt'); ?>" id="resetToOriginal"><?php echo esc_html('Reset to Original'); ?></button>
                    <button type="button" class="<?php echo esc_attr('sensorica_btn'); ?>" id="apply" data-action="save_prompt"><?php echo esc_html('Apply & Next step'); ?></button>
                </div>
            </div>
            <!--/ tab 5 -->
        </div>
    </form>
</div>
<!--/ content -->