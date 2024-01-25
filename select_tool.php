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
                <div class="<?php echo esc_attr('sensorica_form-section'); ?>" id="descriptionSection">
                    <p>
                        <?php echo esc_html('This is AI tools suite.'); ?>
                    </p>
                </div>


                <ul class="<?php echo esc_attr('sensorica_accordionOptionsList'); ?>" id="listOfPrompts">
                    <?php
                    $tools = json_decode(file_get_contents(sensorica_PATH . 'platforms.json'), true);

                    foreach ($tools as $tool) {
                        if (!isset($tool['img'])) {
                            $tool['img'] = sensorica_URL . 'tools/' . $tool['slug'] . '/icon.png';
                        }
                        // Use admin_url() to generate the correct admin URL with query parameters
                        $tool_url = esc_url(add_query_arg(array(
                            'page' => 'sensorica',
                            'tool' => $tool['slug'],
                        ), admin_url('admin.php')));

                        echo '<li><button onclick="window.location=\'' . $tool_url . '\'" data-prompt="' . $tool['slug'] . '" type="button"><img src="' . esc_url(plugin_dir_url(__FILE__) . 'tools/' . $tool['slug'] . '/icon.png') . '" /><span>' . esc_html($tool['title']) . '</span>' . esc_html($tool['description']) . '</button></li>';
                    }
                    ?>
                </ul>


                <ul class="<?php echo esc_attr('sensorica_accordionOptionsList'); ?>" id="listOfPrompts"></ul>
                <div class="<?php echo esc_attr('sensorica_more'); ?>">
                    <a href="<?php echo esc_url('https://onout.org/sponsor.md'); ?>" target="_blank"><?php echo esc_html('Request a tool'); ?></a>
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