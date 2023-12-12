<form method="post" action="bot/deploy">
    <div class="tab-container">
        <!-- tab 5 -->
        <div class="tab-title active filled" data-content="title5">
            <div class="headline" id="promptHeadline">
                <span id="customIcon" style="background: #e00094"><svg class="icon">
                        <use xlink:href="#ico-1"></use>
                    </svg> </span>Select a tool to run
            </div>
            <div class="form-section" id="descriptionSection">
                <p>
                    After selecting a tool, you will be able to customize it and run
                </p>
            </div>


            <ul class="accordionOptionsList" id="listOfPrompts">
                <?php
                //load toola from tools.json and show them <li><button data-prompt="undefined" type="button"><img src="static/icons/business.png" alt="undefined"><span>Chat on a Website</span>Interactive mascot for websites</button></li>
                
                $tools = json_decode(file_get_contents('tools.json'), true);
                foreach ($tools as $tool) {
                    if (!$tool['img']) $tool['img'] = 'tools/' . $tool['slug'] . '/icon.png';
                    echo '<li><button onclick="window.location=\'?tool=' . $tool['slug'] . '\'" data-prompt="' . $tool['slug'] . '" type="button"><div class="image-container"><img src="'.$tool['img'].'" /></div><span>' . $tool['title'] . '</span>' . $tool['description'] . '</button></li>';
                }
                ?>
            </ul>
            <style>
                .image-container {
                    width: 104px;
                    height: 104px;
                    overflow: hidden;
                    border-radius: 20%;
                    margin: 0 auto 30px auto;
                }

                .accordionOptionsList li button .image-container img {
                    width: 208px;
                    height: 208px;
                    max-width: none;
                }
            </style>
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