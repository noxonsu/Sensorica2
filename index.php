<?php
#load required inputs as array and find for slug 
$tools = json_decode(file_get_contents('tools.json'), true);
if ($_GET['tool']) {
  foreach ($tools as $tool) {

    if ($tool['slug'] == $_GET['tool']) {
      $tool = json_decode(file_get_contents('tools/' . $tool['slug'] . '/' . $tool['slug'] . '_info.json'), true);
      break;
    }
  }

}
?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>AI wizard</title>
  <link rel="stylesheet" href="./static/new.css" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="theme-color" content="#5f48b0" />
  <meta name="description" content="Deploy your own ChatGPT bot on Telegram" />
  <meta name="keywords"
    content="chatgpt bot, telegram bot, chatgpt telegram, ai bot, ai app, no-code telegram bot, earn on ai, onout" />
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

<body>
  <div class="wrapper">
    <!-- header -->
    <header>
      <div class="header-logo">
        <img src="./static/images/chat-gpt-logo.svg" alt="" />
      </div>
      <h2 class="deploymentPageTitle">
        <?php echo ($tool['title']) ? $tool['title'] : "AI tool creation wizard"; ?>
      </h2>
    </header>
    <!--/ header -->
    <main>
      <div class="row">
        <?php
        #hide if no tool selected
        if ($_GET['tool']) {
          ?>
          <div class="sidebar">
            <div class="title">Fill The Form Step By Step:</div>
            <ul class="nav">

              <?php

              foreach ($tool['inputs'] as $input => $input_data) {
                echo '<li><a href="javascript:void(0);" class="tab-title" data-title="title1"><svg class="icon"><use xlink:href="#'.$input_data['svg_icon_id'].'"></use></svg>' . $input_data['title'] . '</a></li>';
              }

              ?>
              <li>
                <a href="javascript:void(0);" class="tab-title" data-title="title8"><svg class="icon">
                    <use xlink:href="#ico-7"></use>
                  </svg>F.A.Q.</a>
              </li>
            </ul>

          </div>
        <?php } ?>
        <!-- content -->
        <div class="content">
          <?php
          if ($_GET['tool']) {

            ?>
            <form action="" method="post">
              <?php

              foreach ($tool['inputs'] as $input => $input_data) {
                if ($_POST[$input]) {
                  //save input to hidden input
                  echo '<input type="hidden" name="' . $input . '" value="' . $_POST[$input] . '" />';
                  continue;
                }


                ?>
                <div class="tab-content active" data-content="title3">
                  <div class="headline">
                    <span style="background: #fff"><svg class="icon" style="fill: #f6821f">
                        <use xlink:href="#ico-3"></use>
                      </svg></span><?php echo $input_data['title']; ?>
                  </div>
                  <div class="form-section">
                    <label for="<?php echo $input; ?>">
                      <?php echo $input_data['description']; ?>
                    </label>
                    <input type="text" class="form-control" name="<?php echo $input; ?>" id="<?php echo $input; ?>"
                      placeholder="<?php echo $input_data['placeholder']; ?>" />
                    <details>
                      <summary>
                        Instructions
                        <div class="chevron">
                          <svg class="icon">
                            <use xlink:href="#chevron"></use>
                          </svg>
                        </div>
                      </summary>
                      <div class="summary-content">
                        <ol>
                          <?php

                          foreach ($input_data['help_image'] as $image) {
                            echo '<li>' . $image . '</li>';
                          }

                          ?>
                        </ol>
                      </div>
                    </details>
                  </div>

                  <input type="submit" value="Apply & Next" class='btn' />
                </div>
                <?php
                //show only one setting per step
                break;
              } ?>
            </form>
            <?php
          }

          if (!$_GET['tool']) {
            ?>
            <form method="post" action="bot/deploy">
              <div class="tab-container">
                <!-- tab 5 -->
                <div class="tab-content active" data-content="title5">
                  <div class="headline" id="promptHeadline">
                    <span id="customIcon" style="background: #e00094"><svg class="icon">
                        <use xlink:href="#ico-1"></use>
                      </svg> </span>Select the tool to run
                  </div>
                  <div class="form-section" id="descriptionSection">
                    <p>
                      Select the tool template to deploy
                    </p>
                  </div>


                  <ul class="accordionOptionsList" id="listOfPrompts">
                    <?php
                    //load toola from tools.json and show them <li><button data-prompt="undefined" type="button"><img src="static/icons/business.png" alt="undefined"><span>Chat on a Website</span>Interactive mascot for websites</button></li>
                  
                    $tools = json_decode(file_get_contents('tools.json'), true);
                    foreach ($tools as $tool) {
                      echo '<li><button onclick="window.location=\'?tool=' . $tool['slug'] . '\'" data-prompt="' . $tool['slug'] . '" type="button"><img src="' . $tool['img'] . '" alt="' . $tool['slug'] . '"><span>' . $tool['title'] . '</span>' . $tool['description'] . '</button></li>';
                    }
                    ?>
                  </ul>

                  <ul class="accordionOptionsList" id="listOfPrompts"></ul>
                  <div class="more">
                    <a href="#">Get more templates</a>
                  </div>
                  <div class="button-wrap">
                    <button type="submit" class="btn btn-prompt" id="resetToOriginal">Reset to Original</button><button
                      type="button" class="btn" id="apply" data-action="save_prompt">Apply & Next step</button>
                  </div>
                </div>
                <!--/ tab 5 -->
              </div>
            </form>
          <?php } ?>
        </div>
        <!--/ content -->
      </div>
      <!-- footer -->
      <footer>
        <div class="supportWrapper">
          Support:
          <a href="mailto:support@onout.org" target="_blank" rel="noreferrer"><svg class="icon">
              <use xlink:href="#ico-eml"></use>
            </svg>Email
          </a>
          or
          <a href="https://t.me/onoutsupportbot" target="_blank" rel="noreferrer"><svg class="icon">
              <use xlink:href="#ico-tlg"></use>
            </svg>Telegram
          </a>
        </div>
      </footer>
      <!--/ footer -->
    </main>
  </div>
  <!-- import svg --> 
  <?php
  //import svg static/icons/icons.svg
  echo file_get_contents('static/icons/icons.svg');
  ?>
  
</body>

</html>