<div class="sensorica_sidebar">
    <div class="sensorica_title">Fill The Form Step By Step:</div>
    <ul class="sensorica_nav">
        <li>
            <a href="javascript:void(0);" class="sensorica_tab-title sensorica_active" data-title="title8"><svg
                    class="sensorica_icon">
                    <use xlink:href="#ico-2"></use>
                </svg>Select tool</a>
        </li>
        <li>
            <a href="javascript:void(0);" class="sensorica_tab-title" data-title="title8"><svg class="sensorica_icon">
                    <use xlink:href="#ico-3"></use>
                </svg>Specify the inputs</a>
        </li>
        <li>
            <a href="javascript:void(0);" class="sensorica_tab-title" data-title="title8"><svg class="sensorica_icon">
                    <use xlink:href="#ico-7"></use>
                </svg>Fetch the result</a>
        </li>
        <li>
            <a href="javascript:void(0);" class="sensorica_tab-title" data-title="title8"><svg class="sensorica_icon">
                    <use xlink:href="#ico-7"></use>
                </svg>F.A.Q.</a>
        </li>
    </ul>
</div>

<div class="sensorica_content">
    <form method="post" action="bot/deploy">
        <div class="sensorica_tab-container">
            <!-- tab 5 -->
            <div class="sensorica_tab-title sensorica_active sensorica_filled" data-content="title5">
                <div class="sensorica_headline" id="promptHeadline">
                    <span id="customIcon" style="background: #e00094"><svg class="sensorica_icon">
                            <use xlink:href="#ico-1"></use>
                        </svg> </span>AI tools to increase user attraction and retention rate
                </div>
                <div class="sensorica_form-section" id="descriptionSection">
                    <p>
                        This is AI tools suite. We recommend running all of them one by one to increase user attraction
                        and retention rate. Please analyze your website for AI to understand your business and then run
                        tools one by one.
                    </p>
                </div>


                <ul class="sensorica_accordionOptionsList" id="listOfPrompts">
                    <?php
                    // Load tools from tools.json and show them
                    $tools = json_decode(file_get_contents(SENSORICA2_PATH . 'tools.json'), true);

                    foreach ($tools as $tool) {
                        if (!isset($tool['img'])) {
                            $tool['img'] = SENSORICA2_URL . 'tools/' . $tool['slug'] . '/icon.png';
                        }
                        // Use admin_url() to generate the correct admin URL with query parameters
                        $tool_url = esc_url(add_query_arg(array(
                            'page' => 'sensorica2',
                            'tool' => $tool['slug'],
                        ), admin_url('admin.php')));

                        echo '<li><button onclick="window.location=\'' . $tool_url . '\'" data-prompt="' . $tool['slug'] . '" type="button"><img src="' . esc_url(plugin_dir_url(__FILE__) . 'tools/' . $tool['slug'] . '/icon.png') . '" /><span>' . esc_html($tool['title']) . '</span>' . esc_html($tool['description']) . '</button></li>';
                    }
                    ?>
                </ul>


                <ul class="sensorica_accordionOptionsList" id="listOfPrompts"></ul>
                <div class="sensorica_more">
                    <a href="https://onout.org/sponsor.md" target="_blank">Request a tool</a>
                </div>
                <div class="sensorica_button-wrap">
                    <button type="submit" class="sensorica_btn sensorica_btn-prompt" id="resetToOriginal">Reset to
                        Original</button>
                    <button type="button" class="sensorica_btn" id="apply" data-action="save_prompt">Apply & Next
                        step</button>
                </div>
            </div>
            <!--/ tab 5 -->
        </div>
    </form>
</div>
<!--/ content -->