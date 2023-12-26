<div class="sidebar">
    <div class="title">Fill The Form Step By Step:</div>
    <ul class="nav">


        <li>
            <a href="javascript:void(0);" class="tab-title active" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-2"></use>
                </svg>Select tool</a>
        </li>
        <li>
            <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-3"></use>
                </svg>Specify the inputs</a>
        </li>
        <li>
            <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-7"></use>
                </svg>Fetch the result</a>
        </li>

        <li>
            <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-7"></use>
                </svg>F.A.Q.</a>
        </li>
    </ul>

</div>

<!-- content -->
<div class="content">
    <form method="post" action="bot/deploy">
        <div class="tab-container">
            <!-- tab 5 -->
            <div class="tab-title active filled" data-content="title5">
                <div class="headline" id="promptHeadline">
                    <span id="customIcon" style="background: #e00094"><svg class="icon">
                            <use xlink:href="#ico-1"></use>
                        </svg> </span>AI tools to increase user attraction and retention rate
                </div>
                <div class="form-section" id="descriptionSection">
                    <p>
                        This is AI tools suite . We recomnmend run all them one by one to increase user attraction and retention rate. Please analyse your website for AI understand your business and then run tools one by one.
                    </p>
                </div>


                <ul class="accordionOptionsList" id="listOfPrompts">
                    <?php
                    //load toola from tools.json and show them <li><button data-prompt="undefined" type="button"><img src="static/icons/business.png" alt="undefined"><span>Chat on a Website</span>Interactive mascot for websites</button></li>
                    
                    $tools = json_decode(file_get_contents('tools.json'), true);
                    foreach ($tools as $tool) {
                        if (!$tool['img'])
                            $tool['img'] = 'tools/' . $tool['slug'] . '/icon.png';
                        echo '<li><button onclick="window.location=\'?tool=' . $tool['slug'] . '\'" data-prompt="' . $tool['slug'] . '" type="button"><img src="' . $tool['img'] . '" /><span>' . $tool['title'] . '</span>' . $tool['description'] . '</button></li>';
                    }
                    ?>
                </ul>

                <ul class="accordionOptionsList" id="listOfPrompts"></ul>
                <div class="more">
                    <a href="https://onout.org/sponsor.md" target="_blank">Reuqest a tool</a>
                </div>
                <div class="button-wrap">
                    <button type="submit" class="btn btn-prompt" id="resetToOriginal">Reset to Original</button><button
                        type="button" class="btn" id="apply" data-action="save_prompt">Apply & Next step</button>
                </div>
            </div>
            <!--/ tab 5 -->
        </div>
    </form>
</div>
<!--/ content -->